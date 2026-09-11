<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        // Ensure default JSON responses don't output html headers on errors
        $this->load->helper('url');
    }

    /**
     * Helper to output JSON responses and terminate
     */
    private function response($status, $message, $data = null, $status_code = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode([
                'status' => (bool)$status,
                'message' => $message,
                'data' => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * Helper to authenticate JWT token and ensure it's not blacklisted
     */
    private function check_auth()
    {
        // Extract Authorization header
        $auth_header = $this->input->get_request_header('Authorization', TRUE);
        if (!$auth_header) {
            // Fallback for Apache CGI/FastCGI environments where header is stripped
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }

        if (!$auth_header) {
            // Support session authentication fallback for admin web portal requests
            if ($this->session->userdata('logged_in') && $this->session->userdata('user_id')) {
                $session_user_id = (int)$this->session->userdata('user_id');
                $session_user = $this->General_model->getOne('users', ['id' => $session_user_id]);
                if ($session_user) {
                    $decoded = (object)[
                        'user_id' => (int)$session_user->id,
                        'role'    => (int)$session_user->role,
                        'email'   => $session_user->email
                    ];
                    return [
                        'decoded' => $decoded,
                        'token'   => null
                    ];
                }
            }
            $this->response(false, 'Authorization header missing', null, 401);
        }

        if (!preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
            $this->response(false, 'Invalid Authorization header format', null, 401);
        }

        $token = $matches[1];

        // Check if token exists in blacklist
        $blacklisted = $this->General_model->getOne('token_blacklist', ['token' => $token]);
        if ($blacklisted) {
            $this->response(false, 'Token has been blacklisted', null, 401);
        }

        // Verify and decode JWT
        try {
            $jwt_secret = $this->config->item('jwt_secret') ?: 'DivyShaktiSecretJWTKey2026SuperSecureAndLongKey';
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($jwt_secret, 'HS256'));

            if (isset($decoded->exp) && $decoded->exp < time()) {
                $this->response(false, 'Token has expired', null, 401);
            }

            return [
                'decoded' => $decoded,
                'token' => $token
            ];
        } catch (\Exception $e) {
            $this->response(false, 'Unauthorized: ' . $e->getMessage(), null, 401);
        }
    }

    /**
     * Generate unique 8-character alphanumeric referral code
     */
    private function generate_unique_referral_code()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exists = $this->General_model->getOne('users', ['referral_code' => $code]);
        } while ($exists);
        return $code;
    }

    /**
     * Alias for response to match reference code conventions
     */
    private function send_response($status, $message, $data = null, $status_code = 200)
    {
        $this->response($status, $message, $data, $status_code);
    }

    /**
     * Enforce HTTP method or abort
     */
    private function require_method($method = 'POST')
    {
        if (strtoupper($this->input->method(TRUE)) !== strtoupper($method)) {
            $this->response(false, 'Method Not Allowed', null, 405);
        }
    }

    /**
     * Helper to read value from JSON body, POST, or GET
     */
    private function input_value($key, $default = null)
    {
        static $json_payload = null;
        if ($json_payload === null) {
            $content_type = $this->input->server('CONTENT_TYPE');
            if ($content_type && (strpos($content_type, 'application/json') !== false)) {
                $raw = file_get_contents('php://input');
                $decoded = json_decode($raw, true);
                $json_payload = is_array($decoded) ? $decoded : [];
                // Merge into $_POST for form_validation compatibility
                $_POST = array_merge($_POST, $json_payload);
            } else {
                $json_payload = [];
            }
        }

        if (isset($json_payload[$key])) {
            return $json_payload[$key];
        }

        $val = $this->input->post($key, TRUE);
        if ($val !== null && $val !== false) {
            return $val;
        }

        $get_val = $this->input->get($key, TRUE);
        if ($get_val !== null && $get_val !== false) {
            return $get_val;
        }

        return $default;
    }

    /**
     * Helper to get full user image URL
     */
    private function get_user_image_url($image = '')
    {
        if (!empty($image)) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }
            return base_url($image);
        }
        return null;
    }

    /**
     * Generate JWT token for an authenticated user
     */
    private function generate_token($user)
    {
        $issued_at       = time();
        $expiration_time = $issued_at + (24 * 60 * 60);
        $jwt_secret      = $this->config->item('jwt_secret') ?: 'DivyShaktiSecretJWTKey2026SuperSecureAndLongKey';

        $payload = [
            'iss'     => base_url(),
            'aud'     => base_url(),
            'iat'     => $issued_at,
            'exp'     => $expiration_time,
            'user_id' => (int)$user->id,
            'phone'   => $user->phone ?? '',
            'email'   => $user->email ?? '',
            'role'    => (int)($user->role ?? 0)
        ];

        return \Firebase\JWT\JWT::encode($payload, $jwt_secret, 'HS256');
    }

    /**
     * Calculate profile completion stats
     * 13 required fields for 100% completion:
     * - Personal: name, phone, address
     * - Aadhar: aadhar_number, aadhar_image
     * - PAN: pan_number, pan_image
     * - Bank: account_holder_name, bank_name, account_number, ifsc_code, account_type, branch_name
     */
    private function calculate_profile_completion($user)
    {
        $required_fields = [
            'name'                => 'Name',
            'phone'               => 'Phone',
            'gender'              => 'Gender',
            'address'             => 'Address',
            'aadhar_number'       => 'Aadhar Number',
            'aadhar_image'        => 'Aadhar Image',
            'pan_number'          => 'PAN Number',
            'pan_image'           => 'PAN Image',
            'account_holder_name' => 'Account Holder Name',
            'bank_name'           => 'Bank Name',
            'account_number'      => 'Account Number',
            'ifsc_code'           => 'IFSC Code',
            'account_type'        => 'Account Type',
            'branch_name'         => 'Branch Name',
        ];

        $completed_count = 0;
        $missing_fields = [];

        foreach ($required_fields as $field => $label) {
            $val = isset($user->$field) ? trim((string)$user->$field) : '';
            if ($val !== '') {
                $completed_count++;
            } else {
                $missing_fields[] = $field;
            }
        }

        $total_fields = count($required_fields);
        $percentage = (int) round(($completed_count / $total_fields) * 100);
        if ($percentage > 100) {
            $percentage = 100;
        }

        $is_completed = ($completed_count === $total_fields);

        return [
            'total_fields'    => $total_fields,
            'completed_count' => $completed_count,
            'percentage'      => $percentage,
            'is_completed'    => $is_completed,
            'missing_fields'  => $missing_fields
        ];
    }

    /**
     * Check if user is eligible to add to cart or place an order
     * Requires:
     * 1. 100% Profile completion
     * 2. Profile activation by Admin (is_profile_active == 1)
     */
    private function check_profile_eligibility($user_id, &$error_msg = null, &$error_data = null)
    {
        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user) {
            $error_msg = 'User not found.';
            $error_data = null;
            return false;
        }

        if ((int)$user->status === 0) {
            $error_msg = 'Your account has been blocked. Please contact admin.';
            $error_data = null;
            return false;
        }

        $completion = $this->calculate_profile_completion($user);

        if (!$completion['is_completed']) {
            $error_msg = 'Please complete 100% of your profile (Aadhar, PAN, and Bank details) before adding items to cart or purchasing products.';
            $error_data = [
                'profile_completion_percentage' => $completion['percentage'],
                'is_profile_completed'          => false,
                'is_profile_active'             => (bool)($user->is_profile_active ?? 0),
                'missing_fields'                => $completion['missing_fields']
            ];
            return false;
        }

        if (empty($user->is_profile_active) || (int)$user->is_profile_active !== 1) {
            $error_msg = 'Your profile is 100% complete and submitted for review. Please wait for admin to activate your profile before adding items to cart or purchasing products.';
            $error_data = [
                'profile_completion_percentage' => 100,
                'is_profile_completed'          => true,
                'is_profile_active'             => false
            ];
            return false;
        }

        return true;
    }

    /**
     * Get detailed profile progression bar and KYC metrics
     */
    private function get_profile_progression_bar($user)
    {
        $completion = $this->calculate_profile_completion($user);
        $percentage = (int)$completion['percentage'];

        // Determine progression color and tier
        $progress_color = '#ef4444'; // Red for < 35%
        $progress_level = 'low';
        if ($percentage >= 100) {
            $progress_color = '#10b981'; // Green for 100%
            $progress_level = 'complete';
        } elseif ($percentage >= 70) {
            $progress_color = '#3b82f6'; // Blue for >= 70%
            $progress_level = 'good';
        } elseif ($percentage >= 35) {
            $progress_color = '#f59e0b'; // Amber for >= 35%
            $progress_level = 'medium';
        }

        $is_profile_active = (bool)($user->is_profile_active ?? 0);
        $status_label = 'Incomplete';
        if ($completion['is_completed']) {
            $status_label = $is_profile_active ? 'Verified & Active' : 'Pending Admin Approval';
        }

        $field_definitions = [
            'name'                => ['label' => 'Full Name', 'category' => 'personal'],
            'phone'               => ['label' => 'Phone Number', 'category' => 'personal'],
            'address'             => ['label' => 'Postal Address', 'category' => 'personal'],
            'aadhar_number'       => ['label' => 'Aadhar Number', 'category' => 'kyc'],
            'aadhar_image'        => ['label' => 'Aadhar Card Document', 'category' => 'kyc'],
            'pan_number'          => ['label' => 'PAN Number', 'category' => 'kyc'],
            'pan_image'           => ['label' => 'PAN Card Document', 'category' => 'kyc'],
            'account_holder_name' => ['label' => 'Account Holder Name', 'category' => 'bank'],
            'bank_name'           => ['label' => 'Bank Name', 'category' => 'bank'],
            'account_number'      => ['label' => 'Account Number', 'category' => 'bank'],
            'ifsc_code'           => ['label' => 'IFSC Code', 'category' => 'bank'],
            'account_type'        => ['label' => 'Account Type', 'category' => 'bank'],
            'branch_name'         => ['label' => 'Branch Name', 'category' => 'bank']
        ];

        $checklist = [];
        $completed_fields = [];
        $missing_fields_details = [];

        foreach ($field_definitions as $field => $meta) {
            $val = isset($user->$field) ? trim((string)$user->$field) : '';
            $is_done = ($val !== '');
            $item = [
                'field'    => $field,
                'label'    => $meta['label'],
                'category' => $meta['category'],
                'is_done'  => $is_done
            ];
            $checklist[] = $item;
            if ($is_done) {
                $completed_fields[] = $field;
            } else {
                $missing_fields_details[] = $item;
            }
        }

        $message = "Your profile is {$percentage}% complete. Please complete remaining KYC and Bank details to activate shopping and rewards.";
        if ($completion['is_completed']) {
            $message = $is_profile_active
                ? 'Your profile is 100% complete and verified.'
                : 'Your profile is 100% complete and submitted for review. Please wait for admin approval.';
        }

        return [
            'percentage'              => $percentage,
            'completed_count'         => (int)$completion['completed_count'],
            'total_fields'            => (int)$completion['total_fields'],
            'is_profile_completed'    => (bool)$completion['is_completed'],
            'is_profile_active'       => $is_profile_active,
            'can_purchase'            => ($completion['is_completed'] && $is_profile_active),
            'can_add_to_cart'         => ($completion['is_completed'] && $is_profile_active),
            'status_label'            => $status_label,
            'progress_color'          => $progress_color,
            'progress_level'          => $progress_level,
            'message'                 => $message,
            'missing_fields'          => $completion['missing_fields'],
            'missing_fields_details'  => $missing_fields_details,
            'completed_fields'        => $completed_fields,
            'checklist'               => $checklist
        ];
    }

    /**
     * Format user data array consistently
     */
    private function format_user_data($user)
    {
        $completion = $this->calculate_profile_completion($user);
        $progression_bar = $this->get_profile_progression_bar($user);

        $sponsor = null;
        if (!empty($user->parent_id)) {
            $sponsor = $this->General_model->getOne('users', ['id' => $user->parent_id]);
        }

        return [
            'id'                            => (int)$user->id,
            'unique_id'                     => $user->custom_id ?? null,
            'custom_id'                     => $user->custom_id ?? null,
            'name'                          => $user->name,
            'phone'                         => $user->phone,
            'mobile'                        => $user->phone,
            'email'                         => $user->email,
            'gender'                        => $user->gender ?? null,
            'profile_image'                 => $this->get_user_image_url($user->profile_image ?? ''),
            'image'                         => $this->get_user_image_url($user->profile_image ?? ''),
            'address'                       => $user->address ?? null,
            'aadhar_number'                 => $user->aadhar_number ?? null,
            'aadhar_image'                  => $this->get_user_image_url($user->aadhar_image ?? ''),
            'pan_number'                    => $user->pan_number ?? null,
            'pan_image'                     => $this->get_user_image_url($user->pan_image ?? ''),
            'account_holder_name'           => $user->account_holder_name ?? null,
            'bank_name'                     => $user->bank_name ?? null,
            'account_number'                => $user->account_number ?? null,
            'ifsc_code'                     => $user->ifsc_code ?? null,
            'account_type'                  => $user->account_type ?? null,
            'branch_name'                   => $user->branch_name ?? null,
            'profile_completion_percentage' => $completion['percentage'],
            'is_profile_completed'          => (bool)$completion['is_completed'],
            'is_profile_active'             => (bool)($user->is_profile_active ?? 0),
            'missing_fields'                => $completion['missing_fields'],
            'can_purchase'                  => ($completion['is_completed'] && (int)($user->is_profile_active ?? 0) === 1),
            'can_add_to_cart'               => ($completion['is_completed'] && (int)($user->is_profile_active ?? 0) === 1),
            'profile_progression_bar'       => $progression_bar,
            'role'                          => (int)($user->role ?? 0),
            'referral_code'                 => $user->referral_code,
            'parent_id'                     => !empty($user->parent_id) ? (int)$user->parent_id : null,
            'sponsor_name'                  => $sponsor ? $sponsor->name : null,
            'sponsor_referral_code'         => $sponsor ? $sponsor->referral_code : null,
            'sponsor_phone'                 => $sponsor ? $sponsor->phone : null,
            'wallet_balance'                => (float)($user->wallet_balance ?? 0.00),
            'status'                        => (int)($user->status ?? 1),
            'created_at'                    => $user->created_at,
            'updated_at'                    => $user->updated_at
        ];
    }

    /**
     * Send OTP via SMS (Dove-SMS Gateway)
     */
    private function send_otp_via_sms(string $mobileNo, string $otp): bool
    {
        $message = "Hi $mobileNo\n\nYour Verification OTP is $otp Do not share this OTP with anyone for security reasons.\n\nRegards\nOMKARENT";

        $params = [
            'user'     => 'Fitcketsp',
            'key'      => '81a6b2f99cXX',
            'mobile'   => '91' . $mobileNo,
            'message'  => $message,
            'senderid' => 'OENTER',
            'accusage' => '1',
            'entityid' => '1401487200000053882',
            'tempid'   => '1407168611506367587',
        ];

        $url = 'http://mobicomm.dove-sms.com/submitsms.jsp?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            log_message('error', 'OTP SMS cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        log_message('info', "OTP sent to $mobileNo. Response: $response");

        return true;
    }

    /**
     * POST api/register
     * Replaced direct registration with send_register_otp
     */
    public function register()
    {
        $this->send_register_otp();
    }

    /**
     * SEND OTP (For Registration)
     * POST /api/send_register_otp
     * Body: { "name", "phone" (or "mobile"), "referral_code" (optional) }
     */
    public function send_register_otp()
    {
        $this->require_method('POST');

        $name          = trim((string)$this->input_value('name'));
        $mobile        = $this->input_value('phone') ?: $this->input_value('mobile');
        $referral_code = trim((string)$this->input_value('referral_code'));

        if (empty($name)) {
            $this->send_response(false, 'The Name field is required.', null, 400);
        }

        $mobile = preg_replace('/[^0-9]/', '', (string)$mobile);
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }

        if (empty($mobile) || strlen($mobile) !== 10) {
            $this->send_response(false, 'Please enter a valid 10-digit mobile number.', null, 400);
        }

        // Check if phone already registered
        $existing = $this->db->get_where('users', ['phone' => $mobile])->row();
        if ($existing) {
            $this->send_response(false, 'This phone number is already registered. Please login.', null, 400);
        }

        // Check referral code
        if (!empty($referral_code)) {
            $referrer = $this->db->get_where('users', ['referral_code' => $referral_code])->row();
            if (!$referrer) {
                $this->send_response(false, 'Invalid referral code. Referrer not found.', null, 400);
            }
        }

        // Default OTP for development as requested:
        $otp = '123456';
        // When going live, uncomment live random OTP generation and SMS sending:
        // $otp = (string) random_int(100000, 999999);
        // $this->send_otp_via_sms($mobile, $otp);

        $user_data = json_encode([
            'name'          => $name,
            'phone'         => $mobile,
            'referral_code' => $referral_code,
        ]);

        // Clear old registration OTPs for this number
        $this->db->where('mobile', $mobile)->delete('user_registration_otps');

        $this->db->insert('user_registration_otps', [
            'mobile'     => $mobile,
            'otp'        => $otp,
            'user_data'  => $user_data,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->send_response(true, 'Registration OTP sent successfully to your mobile number.', [
            'phone'         => $mobile,
            'masked_mobile' => '*******' . substr($mobile, -4),
            'otp'           => $otp,
            'expires_in'    => '5 minutes',
        ]);
    }

    /**
     * VERIFY OTP (For Registration)
     * POST /api/register_verify_otp
     * Body: { "phone" (or "mobile"), "otp" }
     */
    public function register_verify_otp()
    {
        $this->require_method('POST');

        $mobile      = $this->input_value('phone') ?: $this->input_value('mobile');
        $entered_otp = trim((string)$this->input_value('otp'));

        // Validate
        if (empty($mobile) || empty($entered_otp)) {
            $this->send_response(false, 'Both mobile number and OTP are required.', null, 400);
        }

        // Sanitise mobile
        $mobile = preg_replace('/[^0-9]/', '', (string)$mobile);
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }

        // Find OTP
        $otp_row = $this->db
            ->where('mobile', $mobile)
            ->where('otp', $entered_otp)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('user_registration_otps')
            ->row();

        if (!$otp_row) {
            $this->send_response(false, 'OTP is incorrect or has expired. Please request a new OTP.', null, 400);
        }

        // Decode user data
        $user_data = json_decode($otp_row->user_data, true);

        // Check if phone already registered in the meantime
        $existing = $this->db->get_where('users', ['phone' => $mobile])->row();
        if ($existing) {
            $this->db->where('id', $otp_row->id)->delete('user_registration_otps');
            $this->send_response(false, 'This phone number is already registered. Please login.', null, 400);
        }

        $parent_id = null;
        if (!empty($user_data['referral_code'])) {
            $referrer = $this->General_model->getOne('users', ['referral_code' => $user_data['referral_code']]);
            if ($referrer) {
                $parent_id = (int)$referrer->id;
            }
        }

        $new_referral_code = $this->generate_unique_referral_code();

        // Create user
        $this->db->insert('users', [
            'custom_id'      => $this->General_model->generateUniqueCustomId(),
            'name'           => $user_data['name'] ?? '',
            'email'          => null,
            'phone'          => $mobile,
            'password'       => null,
            'profile_image'  => null,
            'address'        => null,
            'role'           => 0,
            'referral_code'  => $new_referral_code,
            'parent_id'      => $parent_id,
            'wallet_balance' => 0.00,
            'status'         => 1,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $new_user_id = $this->db->insert_id();

        if (!$new_user_id) {
            $this->send_response(false, 'Registration failed. Please try again.', null, 500);
        }

        // Cleanup used OTP
        $this->db->where('id', $otp_row->id)->delete('user_registration_otps');

        // Get full user object for token
        $user = $this->db->get_where('users', ['id' => $new_user_id])->row();

        // Generate JWT
        $token = $this->generate_token($user);

        $this->send_response(true, 'Registration successful. You are now logged in.', [
            'token' => $token,
            'user'  => $this->format_user_data($user)
        ], 201);
    }

    /*-----------------------------------------------------------------------
    | SEND OTP (For Login)
    | POST /api/send_otp
    | Body: { "mobile" } or { "phone" }
    |-----------------------------------------------------------------------*/
    public function send_otp()
    {
        $this->require_method('POST');

        // Read input (JSON body or POST form-data)
        $mobile = $this->input_value('phone') ?: $this->input_value('mobile');

        // Validate
        if (empty($mobile)) {
            $this->send_response(false, 'Mobile number is required.', null, 400);
        }

        // Sanitise mobile
        $mobile = preg_replace('/[^0-9]/', '', (string)$mobile);
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }

        if (strlen($mobile) !== 10) {
            $this->send_response(false, 'Please enter a valid 10-digit mobile number.', null, 400);
        }

        // Check user exists
        $user = $this->db->get_where('users', ['phone' => $mobile])->row();

        if (!$user) {
            $this->send_response(false, 'This mobile number is not registered. Please register first.', null, 404);
        }

        if ((int) $user->status !== 1) {
            $this->send_response(false, 'Your account is inactive or blocked. Please contact support.', null, 403);
        }

        // Generate OTP
        // Default OTP for development as requested:
        $otp = '123456';
        // When going live, uncomment live random OTP generation and SMS sending:
        // $otp = (string) random_int(100000, 999999);
        // $this->send_otp_via_sms($mobile, $otp);

        // Clear old OTPs for this user_id
        $this->db->where('user_id', (int) $user->id)->delete('user_login_otps');

        // Insert new OTP against user_id (not mobile)
        $this->db->insert('user_login_otps', [
            'user_id'    => (int) $user->id,
            'phone'      => $mobile,
            'otp'        => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->send_response(true, 'OTP sent successfully to your mobile number.', [
            'phone'         => $mobile,
            'masked_mobile' => '*******' . substr($mobile, -4),
            'otp'           => $otp,
            'expires_in'    => '5 minutes',
        ]);
    }

    /*-----------------------------------------------------------------------
    | VERIFY OTP (Login)
    | POST /api/verify_otp
    | Body: { "mobile", "otp" } or { "phone", "otp" }
    |-----------------------------------------------------------------------*/
    public function verify_otp()
    {
        $this->require_method('POST');

        // Read input
        $mobile      = $this->input_value('phone') ?: $this->input_value('mobile');
        $entered_otp = trim((string)$this->input_value('otp'));

        // Validate
        if (empty($mobile) || empty($entered_otp)) {
            $this->send_response(false, 'Both mobile number and OTP are required.', null, 400);
        }

        // Sanitise mobile
        $mobile = preg_replace('/[^0-9]/', '', (string)$mobile);
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }

        // Find user by phone first
        $user = $this->db->get_where('users', [
            'phone'  => $mobile,
            'status' => 1,
        ])->row();

        if (!$user) {
            // Check if user is completing registration via verify_otp
            $pending_reg_otp = $this->db
                ->where('mobile', $mobile)
                ->where('otp', $entered_otp)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->order_by('id', 'DESC')
                ->get('user_registration_otps')
                ->row();

            if ($pending_reg_otp) {
                $this->register_verify_otp();
                return;
            }

            $inactive_user = $this->db->get_where('users', ['phone' => $mobile])->row();
            if ($inactive_user) {
                $this->send_response(false, 'Your account is blocked. Please contact support.', null, 403);
            }
            $this->send_response(false, 'User not found or account is inactive. Please register first.', null, 404);
        }

        // Look up OTP by user_id (not mobile)
        $otp_row = $this->db
            ->where('user_id', (int) $user->id)
            ->where('otp', $entered_otp)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('user_login_otps')
            ->row();

        if (!$otp_row) {
            $this->send_response(false, 'OTP is incorrect or has expired. Please request a new OTP.', null, 400);
        }

        // Generate JWT
        $token = $this->generate_token($user);

        // Cleanup used OTP row
        $this->db->where('id', $otp_row->id)->delete('user_login_otps');

        $this->send_response(true, 'OTP verified. Login successful.', [
            'token' => $token,
            'user'  => $this->format_user_data($user)
        ]);
    }

    /**
     * POST api/login
     * Replaced password login with verify_otp
     */
    public function login()
    {
        $this->verify_otp();
    }


    /**
     * POST api/logout
     */
    public function logout()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // This will authenticate token and reject if expired/invalid/blacklisted
        $auth = $this->check_auth();

        $token   = $auth['token'];
        $decoded = $auth['decoded'];

        // Add to token blacklist table
        $blacklist_data = [
            'token'      => $token,
            'user_id'    => $decoded->user_id,
            'expires_at' => date('Y-m-d H:i:s', $decoded->exp),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->General_model->insert('token_blacklist', $blacklist_data);

        $this->response(true, 'Logout successful', null, 200);
    }

    /**
     * GET/POST api/dashboard
     * Authenticated endpoint to fetch comprehensive dashboard data for the logged-in user:
     * - User Profile & KYC completion status
     * - Wallet balance and revenue breakdown (total commission, today, this month, spent)
     * - Downline team stats & 5 most recent members registered under user
     * - Order summary stats & 5 most recent orders
     * - Active cart count and subtotal
     * - Referral link and share text
     */
    public function dashboard()
    {
        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user) {
            $this->response(false, 'User not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        // 1. User Profile Data
        $user_data = $this->format_user_data($user);

        // 2. Financial Metrics (Wallet & Revenue)
        $total_commission = $this->db->select_sum('amount', 'total')
            ->where('receiver_id', $user_id)
            ->get('order_commissions')
            ->row()->total ?? 0.00;

        $today_commission = $this->db->select_sum('amount', 'total')
            ->where('receiver_id', $user_id)
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get('order_commissions')
            ->row()->total ?? 0.00;

        $month_commission = $this->db->select_sum('amount', 'total')
            ->where('receiver_id', $user_id)
            ->where('MONTH(created_at)', date('m'))
            ->where('YEAR(created_at)', date('Y'))
            ->get('order_commissions')
            ->row()->total ?? 0.00;

        $total_spent = $this->db->select_sum('amount', 'total')
            ->where('user_id', $user_id)
            ->where('type', 'debit')
            ->where('source', 'purchase')
            ->get('wallet_transactions')
            ->row()->total ?? 0.00;

        $wallet_metrics = [
            'wallet_balance'     => (float)($user->wallet_balance ?? 0.00),
            'total_revenue'      => (float)$total_commission,
            'today_revenue'      => (float)$today_commission,
            'this_month_revenue' => (float)$month_commission,
            'total_spent'        => (float)$total_spent
        ];

        // 3. Team / Referrals Metrics
        $total_members_count = $this->db->where('parent_id', $user_id)->count_all_results('users');
        $active_members_count = $this->db->where('parent_id', $user_id)->where('is_profile_active', 1)->count_all_results('users');
        $pending_members_count = max(0, $total_members_count - $active_members_count);

        // Recent 5 members registered under this user
        $this->db->select('id, custom_id, name, phone, email, profile_image, is_profile_completed, is_profile_active, profile_completion_percentage, created_at');
        $this->db->where('parent_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(5);
        $recent_members_raw = $this->db->get('users')->result();

        $recent_members = [];
        foreach ($recent_members_raw as $m) {
            $recent_members[] = [
                'id'                            => (int)$m->id,
                'user_id'                       => $m->custom_id ?? null,
                'name'                          => $m->name,
                'phone'                         => $m->phone,
                'email'                         => $m->email,
                'profile_image'                 => $this->get_user_image_url($m->profile_image ?? ''),
                'is_profile_completed'          => (bool)$m->is_profile_completed,
                'is_profile_active'             => (bool)$m->is_profile_active,
                'profile_completion_percentage' => (int)($m->profile_completion_percentage ?? 0),
                'registered_at'                 => $m->created_at
            ];
        }

        $team_metrics = [
            'total_members'   => (int)$total_members_count,
            'active_members'  => (int)$active_members_count,
            'pending_members' => (int)$pending_members_count,
            'recent_members'  => $recent_members
        ];

        // 4. Orders Metrics
        $total_orders            = $this->db->where('user_id', $user_id)->where('status !=', 'pending')->count_all_results('orders');
        $placed_orders           = $this->db->where('user_id', $user_id)->where('status', 'placed')->count_all_results('orders');
        $pending_orders          = $this->db->where('user_id', $user_id)->where('status', 'pending')->count_all_results('orders');
        $confirmed_orders        = $this->db->where('user_id', $user_id)->where('status', 'confirmed')->count_all_results('orders');
        $packed_orders           = $this->db->where('user_id', $user_id)->where('status', 'packed')->count_all_results('orders');
        $out_for_delivery_orders = $this->db->where('user_id', $user_id)->where('status', 'out_for_delivery')->count_all_results('orders');
        $delivered_orders        = $this->db->where('user_id', $user_id)->where_in('status', ['delivered', 'completed'])->count_all_results('orders');
        $completed_orders        = $this->db->where('user_id', $user_id)->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])->count_all_results('orders');
        $cancelled_orders        = $this->db->where('user_id', $user_id)->where('status', 'cancelled')->count_all_results('orders');

        // Recent 5 orders placed by this user (exclude abandoned draft pending orders)
        $this->db->select('orders.*, products.name as product_name, products.image as product_image, products.price as product_price');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id', 'left');
        $this->db->where('orders.user_id', $user_id);
        $this->db->where('orders.status !=', 'pending');
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit(5);
        $recent_orders_raw = $this->db->get()->result();

        $recent_orders = [];
        foreach ($recent_orders_raw as $ord) {
            $recent_orders[] = [
                'order_id'      => (int)$ord->id,
                'product_id'    => (int)$ord->product_id,
                'product_name'  => $ord->product_name ?? 'Product',
                'product_slug'  => null,
                'product_image' => !empty($ord->product_image) ? base_url($ord->product_image) : null,
                'product_price' => (float)($ord->product_price ?? 0),
                'quantity'      => (int)$ord->quantity,
                'total_amount'  => (float)$ord->amount,
                'status'        => $ord->status,
                'status_label'  => ($ord->status === 'placed' || $ord->status === 'pending') ? 'Placed' : ucwords(str_replace('_', ' ', $ord->status)),
                'created_at'    => $ord->created_at
            ];
        }

        $order_metrics = [
            'total_orders'            => (int)$total_orders,
            'placed_orders'           => (int)$placed_orders,
            'pending_orders'          => (int)$pending_orders,
            'confirmed_orders'        => (int)$confirmed_orders,
            'packed_orders'           => (int)$packed_orders,
            'out_for_delivery_orders' => (int)$out_for_delivery_orders,
            'delivered_orders'        => (int)$delivered_orders,
            'completed_orders'        => (int)$completed_orders,
            'cancelled_orders'        => (int)$cancelled_orders,
            'status_breakdown'        => [
                'placed'           => (int)$placed_orders,
                'pending'          => (int)$pending_orders,
                'confirmed'        => (int)$confirmed_orders,
                'packed'           => (int)$packed_orders,
                'out_for_delivery' => (int)$out_for_delivery_orders,
                'delivered'        => (int)$delivered_orders,
                'cancelled'        => (int)$cancelled_orders
            ],
            'recent_orders'           => $recent_orders
        ];

        // 5. Active Cart Metrics
        $this->db->select('cart.quantity, products.price');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('products.status', 1);
        $cart_rows = $this->db->get()->result();

        $cart_total_items = 0;
        $cart_subtotal = 0.00;
        foreach ($cart_rows as $c) {
            $cart_total_items += (int)$c->quantity;
            $cart_subtotal += (float)$c->price * (int)$c->quantity;
        }

        $cart_metrics = [
            'total_items' => (int)$cart_total_items,
            'subtotal'    => (float)$cart_subtotal
        ];

        // 6. Referral & Sharing Info
        $referral_code = $user->referral_code ?? '';
        $referral_info = [
            'referral_code' => $referral_code,
            'referral_link' => base_url('register?ref=' . urlencode($referral_code)),
            'share_message' => "Join Divy Shakti and start your wellness & earning journey! Use my referral code: {$referral_code}"
        ];

        // 7. Profile Progression Bar metrics
        $progression_bar = $this->get_profile_progression_bar($user);

        $dashboard_data = [
            'user'                    => $user_data,
            'profile_progression_bar' => $progression_bar,
            'profile_progress'        => $progression_bar,
            'wallet'                  => $wallet_metrics,
            'team'                    => $team_metrics,
            'orders'                  => $order_metrics,
            'cart'                    => $cart_metrics,
            'referral'                => $referral_info
        ];

        $this->response(true, 'Dashboard data retrieved successfully', $dashboard_data, 200);
    }

    /**
     * GET/POST api/get_profile
     * Authenticated endpoint to fetch the user's profile details
     */
    public function get_profile()
    {
        $auth = $this->check_auth();
        $user_id = $auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user) {
            $this->response(false, 'User not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        $user_data = $this->format_user_data($user);

        $this->response(true, 'Profile retrieved successfully', $user_data, 200);
    }

    /**
     * POST api/update_profile
     * Authenticated endpoint to update the user's profile, KYC documents, and bank details
     */
    public function update_profile()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = $auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user) {
            $this->response(false, 'User not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $update_data = [];

        // Support partial updates: check if field is explicitly passed
        if (isset($_POST['name'])) {
            $name = trim($this->input->post('name', TRUE));
            if (empty($name)) {
                $this->response(false, 'Name field cannot be empty.', null, 400);
            }
            $update_data['name'] = $name;
        }

        if (isset($_POST['email'])) {
            $email = trim($this->input->post('email', TRUE));
            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->response(false, 'Invalid email format.', null, 400);
                }
                // Check uniqueness excluding current user
                $email_check = $this->General_model->getOne('users', ['email' => $email, 'id !=' => $user_id]);
                if ($email_check) {
                    $this->response(false, 'This email is already registered by another user.', null, 400);
                }
                $update_data['email'] = $email;
            } else {
                $update_data['email'] = null;
            }
        }

        if (isset($_POST['phone'])) {
            $phone = trim($this->input->post('phone', TRUE));
            if (empty($phone)) {
                $this->response(false, 'Phone field cannot be empty.', null, 400);
            }
            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $this->response(false, 'The phone number must be between 10 and 15 digits.', null, 400);
            }
            $update_data['phone'] = $phone;
        }

        if (isset($_POST['gender'])) {
            $gender = strtolower(trim((string)$this->input->post('gender', TRUE)));
            if ($gender === '' || in_array($gender, ['male', 'female', 'other'])) {
                $update_data['gender'] = ($gender !== '') ? $gender : null;
            } else {
                $this->response(false, 'Gender must be either male, female, or other.', null, 400);
            }
        }

        if (isset($_POST['address'])) {
            $address = $this->input->post('address', TRUE);
            $update_data['address'] = ($address !== NULL && trim($address) !== '') ? trim($address) : null;
        }

        // Aadhar & PAN details
        if (isset($_POST['aadhar_number'])) {
            $update_data['aadhar_number'] = trim((string)$this->input->post('aadhar_number', TRUE)) ?: null;
        }

        if (isset($_POST['pan_number'])) {
            $update_data['pan_number'] = strtoupper(trim((string)$this->input->post('pan_number', TRUE))) ?: null;
        }

        // Bank Account Information
        if (isset($_POST['account_holder_name'])) {
            $update_data['account_holder_name'] = trim((string)$this->input->post('account_holder_name', TRUE)) ?: null;
        }

        if (isset($_POST['bank_name'])) {
            $update_data['bank_name'] = trim((string)$this->input->post('bank_name', TRUE)) ?: null;
        }

        if (isset($_POST['account_number'])) {
            $update_data['account_number'] = trim((string)$this->input->post('account_number', TRUE)) ?: null;
        }

        if (isset($_POST['ifsc_code'])) {
            $update_data['ifsc_code'] = strtoupper(trim((string)$this->input->post('ifsc_code', TRUE))) ?: null;
        }

        if (isset($_POST['account_type'])) {
            $update_data['account_type'] = trim((string)$this->input->post('account_type', TRUE)) ?: null;
        }

        if (isset($_POST['branch_name'])) {
            $update_data['branch_name'] = trim((string)$this->input->post('branch_name', TRUE)) ?: null;
        }

        // Handle profile image upload (max 2MB, images only)
        if (!empty($_FILES['profile_image']['name'])) {
            $upload_path = './uploads/profile_images/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_image')) {
                $this->response(false, 'Profile image upload failed: ' . $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();

                if (!empty($user->profile_image) && file_exists('./' . $user->profile_image)) {
                    @unlink('./' . $user->profile_image);
                }

                $update_data['profile_image'] = 'uploads/profile_images/' . $upload_data['file_name'];
            }
        }

        $kyc_upload_path = './uploads/kyc_documents/';
        if (!is_dir($kyc_upload_path)) {
            mkdir($kyc_upload_path, 0777, true);
        }

        // Handle Aadhar image upload (all file types allowed, max 2MB)
        if (!empty($_FILES['aadhar_image']['name'])) {
            $config_aadhar['upload_path']   = $kyc_upload_path;
            $config_aadhar['allowed_types'] = '*';
            $config_aadhar['max_size']      = 2048; // 2MB
            $config_aadhar['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config_aadhar);

            if (!$this->upload->do_upload('aadhar_image')) {
                $this->response(false, 'Aadhar document upload failed: ' . $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();

                if (!empty($user->aadhar_image) && file_exists('./' . $user->aadhar_image)) {
                    @unlink('./' . $user->aadhar_image);
                }

                $update_data['aadhar_image'] = 'uploads/kyc_documents/' . $upload_data['file_name'];
            }
        }

        // Handle PAN image upload (all file types allowed, max 2MB)
        if (!empty($_FILES['pan_image']['name'])) {
            $config_pan['upload_path']   = $kyc_upload_path;
            $config_pan['allowed_types'] = '*';
            $config_pan['max_size']      = 2048; // 2MB
            $config_pan['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config_pan);

            if (!$this->upload->do_upload('pan_image')) {
                $this->response(false, 'PAN card document upload failed: ' . $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();

                if (!empty($user->pan_image) && file_exists('./' . $user->pan_image)) {
                    @unlink('./' . $user->pan_image);
                }

                $update_data['pan_image'] = 'uploads/kyc_documents/' . $upload_data['file_name'];
            }
        }

        // Merge updated fields into current user object to evaluate new completion stats
        $merged_user = clone $user;
        foreach ($update_data as $k => $v) {
            $merged_user->$k = $v;
        }
        $completion = $this->calculate_profile_completion($merged_user);
        $update_data['is_profile_completed']          = $completion['is_completed'] ? 1 : 0;
        $update_data['profile_completion_percentage'] = $completion['percentage'];

        // Only update if there are changes submitted
        if (!empty($update_data)) {
            $update_data['updated_at'] = date('Y-m-d H:i:s');
            $this->General_model->update('users', ['id' => $user_id], $update_data);
        }

        // Return the fresh updated profile details
        $updated_user = $this->General_model->getOne('users', ['id' => $user_id]);
        $user_data = $this->format_user_data($updated_user);

        $this->response(true, 'Profile updated successfully', $user_data, 200);
    }

    /**
     * GET/POST api/get_category_list
     * User-facing API endpoint to retrieve all active categories
     */
    public function get_category_list()
    {
        $categories = $this->General_model->getAll('categories', ['status' => 1]);

        $list = [];
        foreach ($categories as $cat) {
            $list[] = [
                'id'         => (int)$cat->id,
                'name'       => $cat->name,
                'slug'       => null,
                'image'      => $cat->image ? base_url($cat->image) : null,
                'status'     => (int)$cat->status,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at
            ];
        }

        $this->response(true, 'Categories retrieved successfully', $list, 200);
    }

    /**
     * GET/POST api/get_category_detail
     * User-facing API endpoint to retrieve details for a specific active category
     */
    public function get_category_detail()
    {
        $id = $this->input->post('id', TRUE) ?: $this->input->get('id', TRUE);
        $slug = $this->input->post('slug', TRUE) ?: $this->input->get('slug', TRUE);

        if (empty($id) && empty($slug)) {
            $this->response(false, 'Category id is required.', null, 400);
        }

        $where = [];
        if (!empty($id)) {
            $where['id'] = $id;
        } elseif (!empty($slug)) {
            $where['slug'] = $slug;
        }
        $where['status'] = 1; // view-only active categories

        $cat = $this->General_model->getOne('categories', $where);
        if (!$cat) {
            $this->response(false, 'Category not found or inactive.', null, 404);
        }

        $detail = [
            'id'         => (int)$cat->id,
            'name'       => $cat->name,
            'slug'       => null,
            'image'      => $cat->image ? base_url($cat->image) : null,
            'status'     => (int)$cat->status,
            'created_at' => $cat->created_at,
            'updated_at' => $cat->updated_at
        ];

        $this->response(true, 'Category detail retrieved successfully', $detail, 200);
    }

    /**
     * GET api/products
     * User-facing API endpoint to retrieve list of active products with search, sorting, filtering, and pagination
     */
    public function get_product_list()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $search = $this->input->get('search', TRUE);
        $category_id = $this->input->get('category_id', TRUE);
        $min_price = $this->input->get('min_price', TRUE);
        $max_price = $this->input->get('max_price', TRUE);
        $sort_by = $this->input->get('sort_by', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Build total count query parameters
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.status', 1);

        if (!empty($search)) {
            $this->db->like('products.name', $search);
        }
        if (!empty($category_id)) {
            $this->db->where('products.category_id', (int)$category_id);
        }
        if ($min_price !== NULL && $min_price !== '') {
            $this->db->where('products.price >=', (float)$min_price);
        }
        if ($max_price !== NULL && $max_price !== '') {
            $this->db->where('products.price <=', (float)$max_price);
        }

        // Keep parameter conditions for next call
        $total = $this->db->count_all_results('', FALSE);

        // Apply sorting
        if ($sort_by === 'price_low') {
            $this->db->order_by('products.price', 'ASC');
        } elseif ($sort_by === 'price_high') {
            $this->db->order_by('products.price', 'DESC');
        } elseif ($sort_by === 'newest') {
            $this->db->order_by('products.id', 'DESC');
        } else {
            $this->db->order_by('products.id', 'DESC');
        }

        // Apply pagination
        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        $this->db->select('products.*, categories.name as category_name');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $products_raw = $query->result();

        $products = [];
        foreach ($products_raw as $prod) {
            $products[] = [
                'id'             => (int)$prod->id,
                'category_id'    => (int)$prod->category_id,
                'category_name'  => $prod->category_name,
                'name'           => $prod->name,
                'slug'           => null,
                'description'    => $prod->description,
                'price'          => (float)$prod->price,
                'image'          => $prod->image ? base_url($prod->image) : null,
                'stock'          => (int)$prod->stock,
                'status'         => (int)$prod->status,
                'created_at'     => $prod->created_at,
                'updated_at'     => $prod->updated_at
            ];
        }

        $response_data = [
            'products' => $products,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ];

        $this->response(true, 'Products retrieved successfully', $response_data, 200);
    }

    /**
     * GET api/products/category/(:num)
     * User-facing API endpoint to retrieve products in a specific category with sorting, filtering, and pagination
     */
    public function get_products_by_category($category_id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $category_id = $category_id ?: ($this->input->get('category_id') ?: $this->input->post('category_id'));

        if (empty($category_id) || !is_numeric($category_id)) {
            $this->response(false, 'Invalid category id', null, 400);
        }

        // Validate that category exists and is active
        $category = $this->General_model->getOne('categories', ['id' => $category_id, 'status' => 1]);
        if (!$category) {
            $this->response(false, 'Category not found or inactive', null, 404);
        }

        $search = $this->input->get('search', TRUE);
        $min_price = $this->input->get('min_price', TRUE);
        $max_price = $this->input->get('max_price', TRUE);
        $sort_by = $this->input->get('sort_by', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Count query
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.status', 1);
        $this->db->where('products.category_id', (int)$category_id);

        if (!empty($search)) {
            $this->db->like('products.name', $search);
        }
        if ($min_price !== NULL && $min_price !== '') {
            $this->db->where('products.price >=', (float)$min_price);
        }
        if ($max_price !== NULL && $max_price !== '') {
            $this->db->where('products.price <=', (float)$max_price);
        }

        $total = $this->db->count_all_results('', FALSE);

        // Sorting
        if ($sort_by === 'price_low') {
            $this->db->order_by('products.price', 'ASC');
        } elseif ($sort_by === 'price_high') {
            $this->db->order_by('products.price', 'DESC');
        } elseif ($sort_by === 'newest') {
            $this->db->order_by('products.id', 'DESC');
        } else {
            $this->db->order_by('products.id', 'DESC');
        }

        // Pagination
        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        $this->db->select('products.*, categories.name as category_name');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $products_raw = $query->result();

        $products = [];
        foreach ($products_raw as $prod) {
            $products[] = [
                'id'             => (int)$prod->id,
                'category_id'    => (int)$prod->category_id,
                'category_name'  => $prod->category_name,
                'name'           => $prod->name,
                'slug'           => null,
                'description'    => $prod->description,
                'price'          => (float)$prod->price,
                'image'          => $prod->image ? base_url($prod->image) : null,
                'stock'          => (int)$prod->stock,
                'status'         => (int)$prod->status,
                'created_at'     => $prod->created_at,
                'updated_at'     => $prod->updated_at
            ];
        }

        $response_data = [
            'products' => $products,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ];

        $this->response(true, 'Products retrieved successfully', $response_data, 200);
    }

    /**
     * GET api/products/(:num)
     * User-facing API endpoint to retrieve specific active product details
     */
    public function get_product_detail($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $id = $id ?: ($this->input->get('id') ?: $this->input->post('id'));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid product id', null, 400);
        }

        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', (int)$id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $prod = $query->row();

        if (!$prod) {
            $this->response(false, 'Product not found', null, 404);
        }

        $product_data = [
            'id'             => (int)$prod->id,
            'category_id'    => (int)$prod->category_id,
            'category_name'  => $prod->category_name,
            'name'           => $prod->name,
            'slug'           => null,
            'description'    => $prod->description,
            'price'          => (float)$prod->price,
            'image'          => $prod->image ? base_url($prod->image) : null,
            'stock'          => (int)$prod->stock,
            'status'         => (int)$prod->status,
            'created_at'     => $prod->created_at,
            'updated_at'     => $prod->updated_at
        ];

        $this->response(true, 'Product details retrieved successfully', $product_data, 200);
    }

    /**
     * POST api/members/(:num)/wallet
     * Admin-only endpoint to directly credit/add money to a user's wallet
     */
    public function add_wallet_money($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        // Enforce bearer token authentication
        $auth = $this->check_auth();
        $admin_id = (int)$auth['decoded']->user_id;
        $admin_role = (int)$auth['decoded']->role;

        // Admin role check (role = 1)
        if ($admin_role !== 1) {
            $this->response(false, 'Forbidden: Admin access only', null, 403);
        }

        $id = $id ?: ($this->input->post('user_id') ?: $this->input->get('user_id'));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing user ID', null, 400);
        }

        $user = $this->General_model->getOne('users', ['id' => (int)$id]);
        if (!$user) {
            $this->response(false, 'Target user not found', null, 404);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('remark', 'Remark', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $amount = (float)$this->input->post('amount');
        $remark = $this->input->post('remark', TRUE) ?: null;

        // Perform inside a database transaction block
        $this->db->trans_begin();

        // 1. Record credit transaction
        $txn_data = [
            'user_id'    => (int)$id,
            'type'       => 'credit',
            'amount'     => $amount,
            'source'     => 'admin_credit',
            'remark'     => $remark,
            'added_by'   => $admin_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('wallet_transactions', $txn_data);

        // 2. Increment wallet balance
        $new_balance = (float)$user->wallet_balance + $amount;
        $this->db->update('users', ['wallet_balance' => $new_balance], ['id' => (int)$id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to add money due to a database transaction error', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Wallet money credited successfully', [
                'user_id'     => (int)$id,
                'amount'      => $amount,
                'new_balance' => $new_balance
            ], 200);
        }
    }

    /**
     * GET api/wallet/balance
     * Authenticated endpoint to fetch the user's current wallet balance
     */
    public function get_wallet_balance()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user) {
            $this->response(false, 'User account not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        $this->response(true, 'Wallet balance retrieved successfully', [
            'wallet_balance' => (float)$user->wallet_balance
        ], 200);
    }

    /**
     * GET api/wallet/transactions
     * Authenticated endpoint to retrieve current user's wallet transactions, paginated and sorted newest first
     */
    public function get_wallet_transactions()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        // Build count query
        $this->db->from('wallet_transactions');
        $this->db->where('user_id', $user_id);
        $total = $this->db->count_all_results('', FALSE);

        // Fetch paginated transactions sorted by newest first
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $txns = $query->result();

        $list = [];
        foreach ($txns as $t) {
            $list[] = [
                'id'           => (int)$t->id,
                'user_id'      => (int)$t->user_id,
                'type'         => $t->type,
                'amount'       => (float)$t->amount,
                'source'       => $t->source,
                'reference_id' => $t->reference_id ? (int)$t->reference_id : null,
                'remark'       => $t->remark,
                'added_by'     => $t->added_by ? (int)$t->added_by : null,
                'created_at'   => $t->created_at
            ];
        }

        $this->response(true, 'Transactions retrieved successfully', [
            'transactions' => $list,
            'total'        => (int)$total,
            'page'         => (int)$page,
            'limit'         => (int)$limit
        ], 200);
    }

    /**
     * POST api/cart/add
     * Authenticated endpoint to add an item to the shopping cart
     */
    public function add_to_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Check 100% profile completion and admin activation
        $error_msg = null;
        $error_data = null;
        if (!$this->check_profile_eligibility($user_id, $error_msg, $error_data)) {
            $this->response(false, $error_msg, $error_data, 403);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $product_id = (int)$this->input->post('product_id');
        $quantity = (int)$this->input->post('quantity');

        // Check if product exists, is active and has sufficient stock
        $product = $this->General_model->getOne('products', ['id' => $product_id, 'status' => 1]);
        if (!$product) {
            $this->response(false, 'Product not found or inactive', null, 404);
        }

        if ((int)$product->stock < $quantity) {
            $this->response(false, 'Insufficient stock available. Only ' . $product->stock . ' units left.', null, 400);
        }

        // Check if already in user's cart
        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => $product_id]);

        if ($existing) {
            $new_quantity = (int)$existing->quantity + $quantity;
            if ($new_quantity > (int)$product->stock) {
                $this->response(false, 'Cannot add more. Total quantity in your cart (' . $new_quantity . ') exceeds product stock (' . $product->stock . ').', null, 400);
            }

            $this->db->update('cart', ['quantity' => $new_quantity, 'updated_at' => date('Y-m-d H:i:s')], ['id' => (int)$existing->id]);
            $cart_row_id = (int)$existing->id;
            $msg = 'Cart quantity updated successfully';
            $status_code = 200;
        } else {
            $insert_data = [
                'user_id'    => $user_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $cart_id = $this->General_model->insert('cart', $insert_data);
            $cart_row_id = (int)$cart_id;
            $msg = 'Product added to cart successfully';
            $status_code = 201;
        }

        // Fetch full details of the cart row
        $this->db->select('cart.id, cart.product_id, cart.quantity, products.name as product_name, products.price as product_price, products.image as product_image');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.id', $cart_row_id);
        $full_cart_item = $this->db->get()->row();

        if ($full_cart_item) {
            $full_cart_item->id = (int)$full_cart_item->id;
            $full_cart_item->product_id = (int)$full_cart_item->product_id;
            $full_cart_item->quantity = (int)$full_cart_item->quantity;
            $full_cart_item->product_price = (float)$full_cart_item->product_price;
            $full_cart_item->product_image = $full_cart_item->product_image ? base_url($full_cart_item->product_image) : null;
            $full_cart_item->subtotal = (float)($full_cart_item->product_price * $full_cart_item->quantity);
        }

        $this->response(true, $msg, $full_cart_item, $status_code);
    }

    /**
     * GET api/cart
     * Authenticated endpoint to retrieve all items in the user's cart
     */
    public function get_cart()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->select('cart.id as cart_id, cart.quantity, products.id as product_id, products.name as product_name, products.price, products.image, products.stock as product_stock');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $rows = $query->result();

        $cart_items = [];
        foreach ($rows as $row) {
            $cart_items[] = [
                'cart_id'       => (int)$row->cart_id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => null,
                'price'         => (float)$row->price,
                'quantity'      => (int)$row->quantity,
                'product_stock' => (int)$row->product_stock,
                'image'         => $row->image ? base_url($row->image) : null,
                'total_price'   => (float)$row->price * (int)$row->quantity
            ];
        }

        $this->response(true, 'Cart items retrieved successfully', $cart_items, 200);
    }

    /**
     * POST api/cart/update
     * Authenticated endpoint to update absolute quantity of an item in the cart
     */
    public function update_cart_quantity()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Check 100% profile completion and admin activation
        $error_msg = null;
        $error_data = null;
        if (!$this->check_profile_eligibility($user_id, $error_msg, $error_data)) {
            $this->response(false, $error_msg, $error_data, 403);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $product_id = (int)$this->input->post('product_id');
        $quantity = (int)$this->input->post('quantity');

        // Check product stock
        $product = $this->General_model->getOne('products', ['id' => $product_id, 'status' => 1]);
        if (!$product) {
            $this->response(false, 'Product not found or inactive', null, 404);
        }

        if ((int)$product->stock < $quantity) {
            $this->response(false, 'Insufficient stock. Only ' . $product->stock . ' units available.', null, 400);
        }

        // Check if cart item exists
        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => $product_id]);
        if (!$existing) {
            $this->response(false, 'Product not found in your cart', null, 404);
        }

        $this->db->update('cart', ['quantity' => $quantity, 'updated_at' => date('Y-m-d H:i:s')], ['id' => (int)$existing->id]);

        // Fetch full details of the cart row
        $this->db->select('cart.id, cart.product_id, cart.quantity, products.name as product_name, products.price as product_price, products.image as product_image');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.id', (int)$existing->id);
        $full_cart_item = $this->db->get()->row();

        if ($full_cart_item) {
            $full_cart_item->id = (int)$full_cart_item->id;
            $full_cart_item->product_id = (int)$full_cart_item->product_id;
            $full_cart_item->quantity = (int)$full_cart_item->quantity;
            $full_cart_item->product_price = (float)$full_cart_item->product_price;
            $full_cart_item->product_image = $full_cart_item->product_image ? base_url($full_cart_item->product_image) : null;
            $full_cart_item->subtotal = (float)($full_cart_item->product_price * $full_cart_item->quantity);
        }

        $this->response(true, 'Cart quantity updated successfully', $full_cart_item, 200);
    }

    /**
     * POST api/cart/remove
     * Authenticated endpoint to delete a product from the user's cart
     */
    public function remove_from_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $product_id = $this->input->post('product_id', TRUE);
        if (empty($product_id) || !is_numeric($product_id)) {
            $this->response(false, 'Invalid or missing product ID', null, 400);
        }

        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => (int)$product_id]);
        if (!$existing) {
            $this->response(false, 'Product not found in your cart', null, 404);
        }

        $this->db->delete('cart', ['user_id' => $user_id, 'product_id' => (int)$product_id]);
        $this->response(true, 'Product removed from cart successfully', null, 200);
    }

    /**
     * POST api/cart/clear
     * Authenticated endpoint to clear the entire cart database table for current user
     */
    public function clear_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->delete('cart', ['user_id' => $user_id]);
        $this->response(true, 'Cart cleared successfully', null, 200);
    }

    /**
     * GET api/cart/row
     * Authenticated endpoint to retrieve cart details for an individual product
     */
    public function get_cart_row()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $product_id = $this->input->get('product_id', TRUE);
        if (empty($product_id) || !is_numeric($product_id)) {
            $this->response(false, 'Invalid or missing product ID', null, 400);
        }

        $this->db->select('cart.id as cart_id, cart.quantity, products.id as product_id, products.name as product_name, products.price, products.image, products.stock as product_stock');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('cart.product_id', (int)$product_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $row = $query->row();

        if (!$row) {
            $this->response(true, 'Product not in cart', null, 200);
        } else {
            $item = [
                'cart_id'       => (int)$row->cart_id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => null,
                'price'         => (float)$row->price,
                'quantity'      => (int)$row->quantity,
                'product_stock' => (int)$row->product_stock,
                'image'         => $row->image ? base_url($row->image) : null,
                'total_price'   => (float)$row->price * (int)$row->quantity
            ];
            $this->response(true, 'Cart row retrieved successfully', $item, 200);
        }
    }

    /**
     * GET api/cart/summary
     * Authenticated endpoint to fetch cart total quantity count and subtotal amount sum
     */
    public function get_cart_summary()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->select('cart.quantity, products.price');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $rows = $query->result();

        $total_items = 0;
        $subtotal = 0.00;

        foreach ($rows as $row) {
            $total_items += (int)$row->quantity;
            $subtotal += (float)$row->price * (int)$row->quantity;
        }

        $this->response(true, 'Cart summary retrieved successfully', [
            'total_items' => $total_items,
            'subtotal'    => $subtotal
        ], 200);
    }

    /**
     * POST api/place_order
     * Authenticated endpoint to place orders or preview checkout.
     * Supports:
     * - preview = 1: Calculates totals, addresses, line items, wallet status WITHOUT inserting database rows.
     * - Standard / Wallet Placement:
     *   1. Requires a valid shipping address.
     *   2. Automatically cancels any previous abandoned/uncompleted 'pending' orders for this user
     *      to prevent duplicate "Awaiting Payment" entries in the order table.
     *   3. Immediately executes wallet deduction, stock deduction from products table,
     *      MLM commission distribution, cart cleanup, and creates order with status = 'placed'.
     */
    public function place_order()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Check profile eligibility
        $error_msg = null;
        $error_data = null;
        if (!$this->check_profile_eligibility($user_id, $error_msg, $error_data)) {
            $this->response(false, $error_msg, $error_data, 403);
        }

        $buyer = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$buyer) {
            $this->response(false, 'Buyer user not found', null, 404);
        }

        $product_id       = $this->input->post('product_id');
        $quantity         = $this->input->post('quantity');
        $input_address_id = $this->input->post('address_id');
        $is_preview       = !empty($this->input->post('preview')) || !empty($this->input->get('preview'));

        // Resolve shipping address
        $selected_address_row = null;
        if (!empty($input_address_id)) {
            $selected_address_row = $this->General_model->getOne('user_addresses', [
                'id'      => (int)$input_address_id,
                'user_id' => $user_id
            ]);
            if (!$selected_address_row && !$is_preview) {
                $this->response(false, 'Invalid Address ID. Shipping address not found or unauthorized.', null, 400);
            }
        } else {
            // Find default address
            $selected_address_row = $this->General_model->getOne('user_addresses', [
                'user_id'    => $user_id,
                'is_default' => 1
            ]);
            // Fallback to any user address
            if (!$selected_address_row) {
                $selected_address_row = $this->General_model->getOne('user_addresses', ['user_id' => $user_id]);
            }
            if (!$selected_address_row && !$is_preview) {
                $this->response(false, 'Please add a shipping address before placing an order.', null, 400);
            }
        }
        $resolved_address_id = $selected_address_row ? (int)$selected_address_row->id : null;

        // Fetch all saved addresses for user
        $all_addresses = $this->General_model->getAll('user_addresses', ['user_id' => $user_id]);
        $formatted_addresses = [];
        $selected_address = null;

        foreach ($all_addresses as $addr) {
            $addr_data = [
                'id'            => (int)$addr->id,
                'full_name'     => $addr->full_name,
                'mobile'        => $addr->mobile,
                'address_line1' => $addr->address_line1,
                'address_line2' => $addr->address_line2,
                'landmark'      => $addr->landmark,
                'city'          => $addr->city,
                'state'         => $addr->state,
                'pincode'       => $addr->pincode,
                'country'       => $addr->country,
                'is_default'    => (int)$addr->is_default
            ];
            $formatted_addresses[] = $addr_data;
            if ($resolved_address_id && (int)$addr->id === $resolved_address_id) {
                $selected_address = $addr_data;
            }
        }

        // Determine items to order (Flow A: Buy Now single product vs Flow B: Full-Cart checkout)
        $items_to_order = [];
        if (!empty($product_id)) {
            $qty = (!empty($quantity) && is_numeric($quantity) && (int)$quantity > 0) ? (int)$quantity : 1;
            $prod = $this->General_model->getOne('products', ['id' => (int)$product_id, 'status' => 1]);
            if (!$prod) {
                $this->response(false, 'Product not found or inactive.', null, 404);
            }
            if ((int)$prod->stock < $qty) {
                $this->response(false, 'Insufficient stock available for ' . htmlspecialchars($prod->name) . '. Only ' . $prod->stock . ' units left.', null, 400);
            }
            $items_to_order[] = [
                'product'  => $prod,
                'quantity' => $qty
            ];
        } else {
            // Full-Cart Checkout
            $cart_rows = $this->General_model->getAll('cart', ['user_id' => $user_id]);
            if (empty($cart_rows)) {
                $this->response(false, 'Your cart is empty. Please add items to your cart before proceeding to checkout.', null, 400);
            }
            foreach ($cart_rows as $row) {
                $prod = $this->General_model->getOne('products', ['id' => (int)$row->product_id, 'status' => 1]);
                if (!$prod) {
                    $this->response(false, 'One or more products in your cart are no longer active or available.', null, 400);
                }
                $qty = (int)$row->quantity;
                if ((int)$prod->stock < $qty) {
                    $this->response(false, 'Insufficient stock available for ' . htmlspecialchars($prod->name) . '. Only ' . $prod->stock . ' units left.', null, 400);
                }
                $items_to_order[] = [
                    'product'  => $prod,
                    'quantity' => $qty
                ];
            }
        }

        // Calculate totals and line items
        $subtotal = 0.00;
        $total_items = count($items_to_order);
        $total_quantity = 0;

        foreach ($items_to_order as $entry) {
            $prod = $entry['product'];
            $qty = $entry['quantity'];
            $line_total = round((float)$prod->price * $qty, 2);
            $subtotal += $line_total;
            $total_quantity += $qty;
        }

        $delivery_charge = 0.00;
        $discount = 0.00;
        $total_payable = max(0, $subtotal + $delivery_charge - $discount);

        $current_wallet_balance = (float)($buyer->wallet_balance ?? 0.00);
        $is_wallet_sufficient = ($current_wallet_balance >= $total_payable);
        $balance_after_payment = $is_wallet_sufficient ? round($current_wallet_balance - $total_payable, 2) : 0.00;
        $wallet_deficit = $is_wallet_sufficient ? 0.00 : round($total_payable - $current_wallet_balance, 2);

        // ─────────────────────────────────────────────────────────────
        // PREVIEW MODE: Return review calculations without creating any database rows
        // ─────────────────────────────────────────────────────────────
        if ($is_preview) {
            $preview_line_items = [];
            foreach ($items_to_order as $entry) {
                $prod = $entry['product'];
                $qty = $entry['quantity'];
                $unit_price = (float)$prod->price;
                $line_total = round($unit_price * $qty, 2);
                $preview_line_items[] = [
                    'order_id'        => null,
                    'product_id'      => (int)$prod->id,
                    'product_name'    => $prod->name,
                    'product_slug'    => null,
                    'product_image'   => !empty($prod->image) ? base_url($prod->image) : null,
                    'unit_price'      => $unit_price,
                    'quantity'        => $qty,
                    'line_total'      => $line_total,
                    'available_stock' => (int)$prod->stock,
                    'is_in_stock'     => ((int)$prod->stock >= $qty)
                ];
            }

            $this->response(true, 'Checkout review data retrieved successfully.', [
                'is_preview'         => true,
                'order_ids'          => [],
                'order_id'           => null,
                'line_items'         => $preview_line_items,
                'orders'             => [],
                'order_summary'      => [
                    'total_items'          => (int)$total_items,
                    'total_quantity'       => (int)$total_quantity,
                    'subtotal'             => (float)$subtotal,
                    'delivery_charge'      => (float)$delivery_charge,
                    'discount'             => (float)$discount,
                    'total_payable_amount' => (float)$total_payable
                ],
                'shipping_addresses' => $formatted_addresses,
                'selected_address'   => $selected_address,
                'wallet_info'        => [
                    'current_wallet_balance' => (float)$current_wallet_balance,
                    'order_total'            => (float)$total_payable,
                    'is_wallet_sufficient'   => (bool)$is_wallet_sufficient,
                    'balance_after_payment'  => (float)$balance_after_payment,
                    'wallet_deficit'         => (float)$wallet_deficit,
                    'payment_method'         => 'wallet',
                    'can_proceed'            => (bool)($is_wallet_sufficient && !empty($selected_address)),
                    'message'                => $is_wallet_sufficient
                        ? 'Wallet balance is sufficient. Confirming will deduct ₹' . number_format($total_payable, 2) . ' from your wallet.'
                        : 'Insufficient wallet balance. You need ₹' . number_format($wallet_deficit, 2) . ' more. Please add funds to your wallet.'
                ]
            ], 200);
            return;
        }

        // ─────────────────────────────────────────────────────────────
        // ACTUAL ORDER PLACEMENT:
        // 1. Enforce required address
        // 2. Enforce wallet balance
        // 3. Clean up previous abandoned 'pending' orders for this user
        // 4. Atomically deduct stock, deduct wallet, distribute MLM commissions, and create 'placed' order
        // ─────────────────────────────────────────────────────────────
        if (empty($resolved_address_id)) {
            $this->response(false, 'Please select or add a valid shipping address before placing your order.', null, 400);
        }

        if (!$is_wallet_sufficient) {
            $this->response(false, 'Insufficient wallet balance. Total amount: ₹' . number_format($total_payable, 2) . ', current balance: ₹' . number_format($current_wallet_balance, 2) . '. Please add ₹' . number_format($wallet_deficit, 2) . ' more to proceed.', [
                'required_amount' => (float)$total_payable,
                'wallet_balance'  => (float)$current_wallet_balance,
                'deficit'         => (float)$wallet_deficit
            ], 400);
        }

        // Clean up any stale uncompleted 'pending' orders for this user so they don't linger as duplicate "Awaiting Payment" rows
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'pending');
        $this->db->update('orders', [
            'status'     => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_begin();

        // 1. Deduct total payable from buyer's wallet
        $buyer_new_balance = round($current_wallet_balance - $total_payable, 2);
        $this->db->update('users', ['wallet_balance' => $buyer_new_balance], ['id' => $user_id]);

        // 2. Fetch commission settings (Fixed money amount ₹)
        $levels_amount = [];
        $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        foreach ($settings as $setting) {
            $levels_amount[(int)$setting->level] = (float)($setting->amount ?? $setting->percentage ?? 0);
        }

        // 3. Find lowest-ID admin for remainder cut
        $this->db->where('role', 1);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        $admin = $this->db->get('users')->row();

        $order_ids = [];
        $created_orders = [];
        $line_items = [];

        foreach ($items_to_order as $entry) {
            $prod = $entry['product'];
            $qty = $entry['quantity'];
            $unit_price = (float)$prod->price;
            $line_total = round($unit_price * $qty, 2);

            // Create placed order row directly
            $order_data = [
                'user_id'    => $user_id,
                'product_id' => (int)$prod->id,
                'quantity'   => $qty,
                'amount'     => $line_total,
                'status'     => 'placed',
                'address_id' => $resolved_address_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $order_id = $this->General_model->insert('orders', $order_data);
            $order_ids[] = (int)$order_id;

            // Debit transaction log for buyer
            $this->db->insert('wallet_transactions', [
                'user_id'      => $user_id,
                'type'         => 'debit',
                'amount'       => $line_total,
                'source'       => 'purchase',
                'reference_id' => (int)$order_id,
                'remark'       => "Debited for product order purchase (Order ID: #{$order_id})",
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // DEDUCT PRODUCT STOCK
            $current_prod = $this->General_model->getOne('products', ['id' => (int)$prod->id]);
            $new_stock = max(0, (int)($current_prod->stock ?? $prod->stock) - $qty);
            $this->db->update('products', ['stock' => $new_stock], ['id' => (int)$prod->id]);

            // Walk MLM referral commission chain (up to 12 levels)
            $total_allocated_commission_sum = 0.00;
            $ancestor_id = ((int)$buyer->role !== 1) ? $buyer->parent_id : null;

            if (!empty($ancestor_id)) {
                for ($level = 1; $level <= 12; $level++) {
                    if (empty($ancestor_id)) {
                        break;
                    }
                    $fixed_amount = isset($levels_amount[$level]) ? $levels_amount[$level] : 0.00;
                    $ancestor = $this->General_model->getOne('users', ['id' => (int)$ancestor_id]);
                    if (!$ancestor) {
                        break;
                    }

                    if ((int)$ancestor->status === 0 || (int)$ancestor->role === 1) {
                        $ancestor_id = $ancestor->parent_id;
                        continue;
                    }

                    $level_comm = round($fixed_amount * $qty, 2);

                    $this->db->set('wallet_balance', 'wallet_balance + ' . $level_comm, FALSE);
                    $this->db->where('id', (int)$ancestor->id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$ancestor->id,
                        'type'         => 'credit',
                        'amount'       => $level_comm,
                        'source'       => 'referral_commission',
                        'reference_id' => (int)$order_id,
                        'remark'       => "Referral commission (₹" . number_format($fixed_amount, 2) . ($qty > 1 ? " x {$qty}" : "") . ") from level {$level} purchase (Order ID: #{$order_id})",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);

                    $this->db->insert('order_commissions', [
                        'order_id'    => (int)$order_id,
                        'buyer_id'    => $user_id,
                        'receiver_id' => (int)$ancestor->id,
                        'level'       => $level,
                        'amount'      => $level_comm,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

                    $total_allocated_commission_sum += $level_comm;
                    $ancestor_id = $ancestor->parent_id;
                }
            }

            // Admin remainder cut: balance revenue after referral commission payouts
            $admin_commission = max(0, round($line_total - $total_allocated_commission_sum, 2));

            if ($admin && $admin_commission > 0) {
                $this->db->set('wallet_balance', 'wallet_balance + ' . $admin_commission, FALSE);
                $this->db->where('id', (int)$admin->id);
                $this->db->update('users');

                $this->db->insert('wallet_transactions', [
                    'user_id'      => (int)$admin->id,
                    'type'         => 'credit',
                    'amount'       => $admin_commission,
                    'source'       => 'admin_commission',
                    'reference_id' => (int)$order_id,
                    'remark'       => "Admin commission remainder cut for Order ID: #{$order_id}",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // Clear purchased item from cart
            $this->db->delete('cart', [
                'user_id'    => $user_id,
                'product_id' => (int)$prod->id
            ]);

            $line_items[] = [
                'order_id'        => (int)$order_id,
                'product_id'      => (int)$prod->id,
                'product_name'    => $prod->name,
                'product_slug'    => null,
                'product_image'   => !empty($prod->image) ? base_url($prod->image) : null,
                'unit_price'      => $unit_price,
                'quantity'        => $qty,
                'line_total'      => $line_total,
                'available_stock' => (int)$new_stock,
                'is_in_stock'     => ((int)$new_stock >= $qty)
            ];

            $created_orders[] = [
                'id'               => (int)$order_id,
                'buyer_id'         => $user_id,
                'product_id'       => (int)$prod->id,
                'product_name'     => $prod->name,
                'product_slug'     => null,
                'product_price'    => $unit_price,
                'product_image'    => !empty($prod->image) ? base_url($prod->image) : null,
                'quantity'         => $qty,
                'amount'           => $line_total,
                'status'           => 'placed',
                'status_label'     => 'Placed',
                'is_paid'          => true,
                'address_id'       => $resolved_address_id,
                'shipping_address' => $selected_address,
                'created_at'       => date('Y-m-d H:i:s'),
                'updated_at'       => date('Y-m-d H:i:s')
            ];
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to place order due to a database error.', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Order placed successfully! Payment confirmed from wallet.', [
                'order_ids'             => $order_ids,
                'order_id'              => !empty($order_ids) ? $order_ids[0] : null,
                'line_items'            => $line_items,
                'orders'                => $created_orders,
                'order'                 => count($created_orders) === 1 ? $created_orders[0] : null,
                'is_paid'               => true,
                'order_status'          => 'placed',
                'status'                => 'placed',
                'status_label'          => 'Placed',
                'total_amount_paid'     => (float)$total_payable,
                'buyer_updated_balance' => (float)$buyer_new_balance,
                'order_summary'         => [
                    'total_items'          => (int)$total_items,
                    'total_quantity'       => (int)$total_quantity,
                    'subtotal'             => (float)$subtotal,
                    'delivery_charge'      => (float)$delivery_charge,
                    'discount'             => (float)$discount,
                    'total_payable_amount' => (float)$total_payable
                ],
                'shipping_addresses'    => $formatted_addresses,
                'selected_address'      => $selected_address,
                'wallet_info'           => [
                    'current_wallet_balance' => (float)$buyer_new_balance,
                    'order_total'            => (float)$total_payable,
                    'is_wallet_sufficient'   => true,
                    'balance_after_payment'  => (float)$buyer_new_balance,
                    'wallet_deficit'         => 0.00,
                    'payment_method'         => 'wallet',
                    'can_proceed'            => true,
                    'message'                => 'Payment of ₹' . number_format($total_payable, 2) . ' debited from your wallet successfully.'
                ]
            ], 201);
        }
    }

    /**
     * POST api/verify_order_payment
     * Authenticated endpoint to execute actual wallet payment, deduct stock,
     * distribute MLM level referral commissions, admin remainder cut, and clear cart.
     * This is the ONLY place money moves in the ordering flow.
     */
    public function verify_order_payment()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Check profile eligibility
        $error_msg = null;
        $error_data = null;
        if (!$this->check_profile_eligibility($user_id, $error_msg, $error_data)) {
            $this->response(false, $error_msg, $error_data, 403);
        }

        // Parse order_ids (supports array, csv, or single order_id)
        $input_ids = $this->input->post('order_ids');
        if (empty($input_ids)) {
            $input_ids = $this->input->post('order_id');
        }
        if (is_string($input_ids)) {
            $decoded_json = json_decode($input_ids, true);
            if (is_array($decoded_json)) {
                $input_ids = $decoded_json;
            } else {
                $input_ids = explode(',', $input_ids);
            }
        }
        if (!is_array($input_ids)) {
            $input_ids = [$input_ids];
        }

        $order_ids = [];
        foreach ($input_ids as $val) {
            $val = trim((string)$val);
            if (is_numeric($val) && (int)$val > 0) {
                $order_ids[] = (int)$val;
            }
        }
        $order_ids = array_values(array_unique($order_ids));

        if (empty($order_ids)) {
            $this->response(false, 'Invalid or missing order IDs.', null, 400);
        }

        $buyer = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$buyer) {
            $this->response(false, 'Buyer user not found.', null, 404);
        }

        // Fetch all orders in the batch
        $this->db->from('orders');
        $this->db->where_in('id', $order_ids);
        $orders = $this->db->get()->result();

        if (count($orders) !== count($order_ids)) {
            $this->response(false, 'One or more specified orders could not be found.', null, 404);
        }

        // Pre-validation 1: Verify ownership, status is strictly 'pending', or already paid
        $already_paid_count = 0;
        foreach ($orders as $ord) {
            if ((int)$ord->user_id !== $user_id) {
                $this->response(false, "Unauthorized: Order #{$ord->id} does not belong to your account.", null, 403);
            }
            $is_already_paid = (bool)$this->General_model->getOne('wallet_transactions', [
                'reference_id' => (int)$ord->id,
                'source'       => 'purchase'
            ]);
            if ($is_already_paid || $ord->status === 'placed') {
                $already_paid_count++;
            } elseif ($ord->status !== 'pending') {
                $this->response(false, "Order #{$ord->id} cannot be paid. Current status: '{$ord->status}' (only pending orders can be paid).", null, 400);
            }
        }

        // If all specified orders are already paid / placed, return success idempotently
        if ($already_paid_count === count($orders)) {
            $this->db->select('orders.*, products.name as product_name, products.image as product_image, products.price as product_price, categories.name as category_name');
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->join('categories', 'categories.id = products.category_id', 'left');
            $this->db->where_in('orders.id', $order_ids);
            $existing_rows = $this->db->get()->result();

            $verified_orders = [];
            $total_paid_calc = 0.00;
            foreach ($existing_rows as $row) {
                $total_paid_calc += (float)$row->amount;
                $shipping_address = null;
                if (!empty($row->address_id)) {
                    $addr = $this->General_model->getOne('user_addresses', ['id' => $row->address_id]);
                    if ($addr) {
                        $shipping_address = [
                            'id'            => (int)$addr->id,
                            'full_name'     => $addr->full_name,
                            'mobile'        => $addr->mobile,
                            'address_line1' => $addr->address_line1,
                            'address_line2' => $addr->address_line2,
                            'landmark'      => $addr->landmark,
                            'city'          => $addr->city,
                            'state'         => $addr->state,
                            'pincode'       => $addr->pincode,
                            'country'       => $addr->country,
                            'is_default'    => (int)$addr->is_default
                        ];
                    }
                }

                $verified_orders[] = [
                    'id'               => (int)$row->id,
                    'buyer_id'         => (int)$row->user_id,
                    'product_id'       => (int)$row->product_id,
                    'product_name'     => $row->product_name,
                    'product_slug'     => null,
                    'product_price'    => (float)$row->product_price,
                    'product_image'    => !empty($row->product_image) ? base_url($row->product_image) : null,
                    'category_name'    => $row->category_name ?: 'Uncategorized',
                    'quantity'         => (int)$row->quantity,
                    'amount'           => (float)$row->amount,
                    'status'           => 'placed',
                    'status_label'     => 'Placed',
                    'is_paid'          => true,
                    'address_id'       => $row->address_id ? (int)$row->address_id : null,
                    'shipping_address' => $shipping_address,
                    'created_at'       => $row->created_at,
                    'updated_at'       => $row->updated_at
                ];
            }

            $this->response(true, 'Payment completed successfully for these orders.', [
                'orders'                => $verified_orders,
                'order'                 => count($verified_orders) === 1 ? $verified_orders[0] : null,
                'total_amount_paid'     => (float)$total_paid_calc,
                'buyer_updated_balance' => (float)$buyer->wallet_balance
            ], 200);
            return;
        }

        // Pre-validation 2: Aggregate stock requirement per product across the batch
        $required_qty = [];
        $total_batch_amount = 0.00;
        foreach ($orders as $ord) {
            $pid = (int)$ord->product_id;
            $required_qty[$pid] = ($required_qty[$pid] ?? 0) + (int)$ord->quantity;
            $total_batch_amount += (float)$ord->amount;
        }

        foreach ($required_qty as $pid => $req_units) {
            $prod = $this->General_model->getOne('products', ['id' => $pid, 'status' => 1]);
            if (!$prod) {
                $this->response(false, "A product in this order batch (ID #{$pid}) is no longer active or available.", null, 400);
            }
            if ((int)$prod->stock < $req_units) {
                $this->response(false, "Insufficient stock for " . htmlspecialchars($prod->name) . ". Required: {$req_units}, Available: {$prod->stock}.", null, 400);
            }
        }

        // Pre-validation 3: Verify buyer wallet balance covers the full batch
        $current_wallet_balance = (float)$buyer->wallet_balance;
        if ($current_wallet_balance < $total_batch_amount) {
            $this->response(false, 'Insufficient wallet balance. Total amount: ₹' . number_format($total_batch_amount, 2) . ', current balance: ₹' . number_format($current_wallet_balance, 2), [
                'required_amount' => (float)$total_batch_amount,
                'wallet_balance'  => (float)$current_wallet_balance,
                'deficit'         => (float)round($total_batch_amount - $current_wallet_balance, 2)
            ], 400);
        }

        // ALL PRE-VALIDATIONS PASSED - BEGIN SINGLE ATOMIC DB TRANSACTION
        $this->db->trans_begin();

        // 1. Deduct total batch amount from buyer's wallet
        $buyer_new_balance = round($current_wallet_balance - $total_batch_amount, 2);
        $this->db->update('users', ['wallet_balance' => $buyer_new_balance], ['id' => $user_id]);

        // 2. Fetch commission settings (Fixed money amount ₹)
        $levels_amount = [];
        $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        foreach ($settings as $setting) {
            $levels_amount[(int)$setting->level] = (float)($setting->amount ?? $setting->percentage ?? 0);
        }

        // 3. Find lowest-ID admin for remainder cut
        $this->db->where('role', 1);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        $admin = $this->db->get('users')->row();

        // 4. Process each order in batch
        foreach ($orders as $ord) {
            $order_id = (int)$ord->id;
            $order_amount = (float)$ord->amount;
            $qty = (int)$ord->quantity;
            $product_id = (int)$ord->product_id;

            // Debit transaction log for buyer
            $this->db->insert('wallet_transactions', [
                'user_id'      => $user_id,
                'type'         => 'debit',
                'amount'       => $order_amount,
                'source'       => 'purchase',
                'reference_id' => $order_id,
                'remark'       => "Debited for product order purchase (Order ID: #{$order_id})",
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // Deduct stock safely
            $current_prod = $this->General_model->getOne('products', ['id' => $product_id]);
            $new_stock = max(0, (int)($current_prod->stock ?? 0) - $qty);
            $this->db->update('products', ['stock' => $new_stock], ['id' => $product_id]);

            // Set order status to placed
            $this->db->update('orders', [
                'status'     => 'placed',
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $order_id]);

            // Walk MLM chain up to 12 levels
            $total_allocated_commission_sum = 0.00;
            $ancestor_id = ((int)$buyer->role !== 1) ? $buyer->parent_id : null;

            if (!empty($ancestor_id)) {
                for ($level = 1; $level <= 12; $level++) {
                    if (empty($ancestor_id)) {
                        break;
                    }
                    $fixed_amount = isset($levels_amount[$level]) ? $levels_amount[$level] : 0.00;
                    $ancestor = $this->General_model->getOne('users', ['id' => (int)$ancestor_id]);
                    if (!$ancestor) {
                        break;
                    }

                    // Skip blocked ancestor (status == 0) - percentage rolls up to admin
                    if ((int)$ancestor->status === 0) {
                        $ancestor_id = $ancestor->parent_id;
                        continue;
                    }

                    // Skip admin ancestor (role == 1) - percentage rolls up to admin
                    if ((int)$ancestor->role === 1) {
                        $ancestor_id = $ancestor->parent_id;
                        continue;
                    }

                    // Active non-admin member: calculate commission
                    $level_comm = round($fixed_amount * $qty, 2);

                    // Credit ancestor wallet
                    $this->db->set('wallet_balance', 'wallet_balance + ' . $level_comm, FALSE);
                    $this->db->where('id', (int)$ancestor->id);
                    $this->db->update('users');

                    // Wallet transaction log
                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$ancestor->id,
                        'type'         => 'credit',
                        'amount'       => $level_comm,
                        'source'       => 'referral_commission',
                        'reference_id' => $order_id,
                        'remark'       => "Referral commission (₹" . number_format($fixed_amount, 2) . ($qty > 1 ? " x {$qty}" : "") . ") from level {$level} purchase (Order ID: #{$order_id})",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);

                    // Order commissions row
                    $this->db->insert('order_commissions', [
                        'order_id'    => $order_id,
                        'buyer_id'    => $user_id,
                        'receiver_id' => (int)$ancestor->id,
                        'level'       => $level,
                        'amount'      => $level_comm,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

                    $total_allocated_commission_sum += $level_comm;
                    $ancestor_id = $ancestor->parent_id;
                }
            }

            // Admin remainder cut: balance revenue after referral commission payouts
            $admin_commission = max(0, round($order_amount - $total_allocated_commission_sum, 2));

            if ($admin && $admin_commission > 0) {
                $this->db->set('wallet_balance', 'wallet_balance + ' . $admin_commission, FALSE);
                $this->db->where('id', (int)$admin->id);
                $this->db->update('users');

                $this->db->insert('wallet_transactions', [
                    'user_id'      => (int)$admin->id,
                    'type'         => 'credit',
                    'amount'       => $admin_commission,
                    'source'       => 'admin_commission',
                    'reference_id' => $order_id,
                    'remark'       => "Admin commission remainder cut for Order ID: #{$order_id}",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // Clear purchased item from cart
            $this->db->delete('cart', [
                'user_id'    => $user_id,
                'product_id' => $product_id
            ]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Transaction failed during order payment verification. All changes rolled back.', null, 500);
        } else {
            $this->db->trans_commit();

            // Fetch verified orders
            $this->db->select('orders.*, products.name as product_name, products.image as product_image, products.price as product_price, categories.name as category_name');
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->join('categories', 'categories.id = products.category_id', 'left');
            $this->db->where_in('orders.id', $order_ids);
            $updated_rows = $this->db->get()->result();

            $verified_orders = [];
            foreach ($updated_rows as $row) {
                $shipping_address = null;
                if (!empty($row->address_id)) {
                    $addr = $this->General_model->getOne('user_addresses', ['id' => $row->address_id]);
                    if ($addr) {
                        $shipping_address = [
                            'id'            => (int)$addr->id,
                            'full_name'     => $addr->full_name,
                            'mobile'        => $addr->mobile,
                            'address_line1' => $addr->address_line1,
                            'address_line2' => $addr->address_line2,
                            'landmark'      => $addr->landmark,
                            'city'          => $addr->city,
                            'state'         => $addr->state,
                            'pincode'       => $addr->pincode,
                            'country'       => $addr->country,
                            'is_default'    => (int)$addr->is_default
                        ];
                    }
                }

                $verified_orders[] = [
                    'id'               => (int)$row->id,
                    'buyer_id'         => (int)$row->user_id,
                    'product_id'       => (int)$row->product_id,
                    'product_name'     => $row->product_name,
                    'product_slug'     => null,
                    'product_price'    => (float)$row->product_price,
                    'product_image'    => !empty($row->product_image) ? base_url($row->product_image) : null,
                    'category_name'    => $row->category_name ?: 'Uncategorized',
                    'quantity'         => (int)$row->quantity,
                    'amount'           => (float)$row->amount,
                    'status'           => 'placed',
                    'status_label'     => 'Placed',
                    'is_paid'          => true,
                    'address_id'       => $row->address_id ? (int)$row->address_id : null,
                    'shipping_address' => $shipping_address,
                    'created_at'       => $row->created_at,
                    'updated_at'       => $row->updated_at
                ];
            }

            $this->response(true, 'Payment completed successfully from wallet. Orders placed and awaiting merchant confirmation.', [
                'orders'                => $verified_orders,
                'order'                 => count($verified_orders) === 1 ? $verified_orders[0] : null,
                'total_amount_paid'     => (float)$total_batch_amount,
                'buyer_updated_balance' => (float)$buyer_new_balance
            ], 200);
        }
    }

    /**
     * GET api/orders
     * Authenticated endpoint to fetch the user's paginated order history list
     */
    public function get_orders()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        $status = $this->input->get('status', TRUE);

        // Build count query
        $this->db->from('orders');
        $this->db->where('orders.user_id', $user_id);
        if ($status !== '' && $status !== null) {
            $this->db->where('orders.status', $status);
        } else {
            // Default user order history: exclude uncompleted/abandoned pending orders
            // Only show legitimate orders: placed, confirmed, packed, out_for_delivery, delivered, cancelled
            $this->db->where('orders.status !=', 'pending');
        }
        $total = (int)$this->db->count_all_results();

        // Fetch paginated order rows joining with products
        $this->db->select('orders.*, products.name as product_name, products.image as product_image');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');
        $this->db->where('orders.user_id', $user_id);
        if ($status !== '' && $status !== null) {
            $this->db->where('orders.status', $status);
        } else {
            $this->db->where('orders.status !=', 'pending');
        }
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows = $query->result();

        $orders = [];
        foreach ($rows as $row) {
            $is_paid = (bool)$this->General_model->getOne('wallet_transactions', [
                'reference_id' => (int)$row->id,
                'source'       => 'purchase'
            ]);

            $status_label = ucwords(str_replace('_', ' ', $row->status));
            if ($row->status === 'pending') {
                $status_label = 'Awaiting Payment';
            } elseif ($row->status === 'placed') {
                $status_label = 'Placed';
            }

            $orders[] = [
                'id'            => (int)$row->id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => null,
                'product_image' => !empty($row->product_image) ? base_url($row->product_image) : null,
                'quantity'      => (int)$row->quantity,
                'amount'        => (float)$row->amount,
                'status'        => $row->status,
                'status_label'  => $status_label,
                'is_paid'       => $is_paid,
                'created_at'    => $row->created_at,
                'updated_at'    => $row->updated_at
            ];
        }

        $this->response(true, 'Orders history retrieved successfully', [
            'orders' => $orders,
            'total'  => (int)$total,
            'page'   => (int)$page,
            'limit'  => (int)$limit
        ], 200);
    }

    /**
     * GET api/orders/(:num)
     * Authenticated endpoint to fetch specific details of an individual order,
     * including full MLM commission audit trail (level, receiver, allocation %, payout amount, action type).
     */
    public function get_order_details($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        $id = $id ?: ($this->input->get('order_id') ?: ($this->input->get('id') ?: ($this->input->post('order_id') ?: $this->input->post('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        $this->db->select('orders.*, products.name as product_name, products.image as product_image, products.price as product_price, categories.name as category_name, users.name as buyer_name, users.email as buyer_email, users.phone as buyer_phone, users.referral_code as buyer_ref');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->join('users', 'users.id = orders.user_id', 'inner');
        $this->db->where('orders.id', (int)$id);
        $query = $this->db->get();
        $order = $query->row();

        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        // Auth restriction: user must be the buyer, OR an admin
        if ($role !== 1 && (int)$order->user_id !== $user_id) {
            $this->response(false, 'Unauthorized access to this order details', null, 403);
        }

        $shipping_address = null;
        if (!empty($order->address_id)) {
            $addr = $this->General_model->getOne('user_addresses', ['id' => $order->address_id]);
            if ($addr) {
                $shipping_address = [
                    'id'            => (int)$addr->id,
                    'full_name'     => $addr->full_name,
                    'mobile'        => $addr->mobile,
                    'address_line1' => $addr->address_line1,
                    'address_line2' => $addr->address_line2,
                    'landmark'      => $addr->landmark,
                    'city'          => $addr->city,
                    'state'         => $addr->state,
                    'pincode'       => $addr->pincode,
                    'country'       => $addr->country,
                    'is_default'    => (int)$addr->is_default
                ];
            }
        }

        $is_paid = (bool)$this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$order->id,
            'source'       => 'purchase'
        ]);

        $status_label = ucwords(str_replace('_', ' ', $order->status));
        if ($order->status === 'pending') {
            $status_label = 'Awaiting Payment';
        } elseif ($order->status === 'placed') {
            $status_label = 'Placed';
        }

        // Fetch MLM referral commissions audit trail
        $this->db->select('order_commissions.*, users.name as receiver_name, users.email as receiver_email');
        $this->db->from('order_commissions');
        $this->db->join('users', 'users.id = order_commissions.receiver_id', 'inner');
        $this->db->where('order_commissions.order_id', (int)$id);
        $this->db->order_by('order_commissions.level', 'ASC');
        $comm_rows = $this->db->get()->result();

        $commissions = [];
        $total_referral_amt = 0.00;

        foreach ($comm_rows as $comm) {
            $setting_item = $this->General_model->getOne('commission_settings', ['level' => (int)$comm->level]);
            $fixed_amt = $setting_item ? (float)($setting_item->amount ?? $setting_item->percentage ?? 0) : 0.00;
            $level_pct = $setting_item ? (float)($setting_item->percentage ?? 0) : 0.00;
            $total_referral_amt += (float)$comm->amount;

            $commissions[] = [
                'level'                 => (int)$comm->level,
                'level_label'           => 'Level ' . $comm->level,
                'receiver_id'           => (int)$comm->receiver_id,
                'receiver_name'         => $comm->receiver_name,
                'receiver_email'        => $comm->receiver_email,
                'fixed_amount'          => $fixed_amt,
                'commission_rule'       => '₹' . number_format($fixed_amt, 2) . ' Fixed Money',
                'allocation_percentage' => $level_pct,
                'payout_amount'         => (float)$comm->amount,
                'action_type'           => 'referral_commission',
                'action_label'          => 'Referral Commission'
            ];
        }

        // Check for admin remainder commission cut
        $admin_txn = $this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'admin_commission'
        ]);

        if ($admin_txn) {
            $admin_user = $this->General_model->getOne('users', ['id' => (int)$admin_txn->user_id]);

            $commissions[] = [
                'level'                 => 'R',
                'level_label'           => 'Admin Remainder',
                'receiver_id'           => (int)$admin_txn->user_id,
                'receiver_name'         => $admin_user ? $admin_user->name : 'Main Admin',
                'receiver_email'        => $admin_user ? $admin_user->email : '',
                'fixed_amount'          => (float)$admin_txn->amount,
                'commission_rule'       => 'Order Remainder Cut',
                'allocation_percentage' => 0.00,
                'payout_amount'         => (float)$admin_txn->amount,
                'action_type'           => 'admin_commission',
                'action_label'          => 'Admin Commission'
            ];
        }

        $order_detail = [
            'id'               => (int)$order->id,
            'buyer_id'         => (int)$order->user_id,
            'buyer_name'       => $order->buyer_name,
            'buyer_email'      => $order->buyer_email,
            'buyer_phone'      => $order->buyer_phone,
            'buyer_ref'        => $order->buyer_ref,
            'product_id'       => (int)$order->product_id,
            'product_name'     => $order->product_name,
            'product_slug'     => null,
            'product_price'    => (float)$order->product_price,
            'product_image'    => !empty($order->product_image) ? base_url($order->product_image) : null,
            'category_name'    => $order->category_name ?: 'Uncategorized',
            'quantity'         => (int)$order->quantity,
            'amount'           => (float)$order->amount,
            'status'           => $order->status,
            'status_label'     => $status_label,
            'is_paid'          => $is_paid,
            'address_id'       => $order->address_id ? (int)$order->address_id : null,
            'shipping_address' => $shipping_address,
            'commissions'      => $commissions,
            'created_at'       => $order->created_at,
            'updated_at'       => $order->updated_at
        ];

        $this->response(true, 'Order details retrieved successfully', $order_detail, 200);
    }

    /**
     * POST api/cancel_order
     * POST api/cancel_order/(:num)
     * Authenticated endpoint (buyer or admin) to cancel an order.
     * Allowed in statuses: pending, placed, confirmed (blocks packed, out_for_delivery, delivered).
     * If already paid: full reversal (refund buyer with admin_credit, restore product stock,
     * reverse referral & admin commissions with admin_debit, delete order_commissions).
     * If unpaid (pending): plain status change to cancelled.
     */
    public function cancel_order($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        $id = $id ?: ($this->input->post('order_id') ?: ($this->input->post('id') ?: ($this->input->get('order_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        $order = $this->General_model->getOne('orders', ['id' => (int)$id]);
        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        // Restrict cancellation to buyer OR admin
        if ($role !== 1 && (int)$order->user_id !== $user_id) {
            $this->response(false, 'Unauthorized to cancel this order', null, 403);
        }

        // Check if already cancelled
        if ($order->status === 'cancelled') {
            $this->response(false, 'Order #' . $id . ' is already cancelled.', null, 400);
        }

        // Validate allowed cancellation statuses
        $allowed_cancel_statuses = ['pending', 'placed', 'confirmed'];
        if (!in_array($order->status, $allowed_cancel_statuses)) {
            $this->response(false, 'Orders that are packed, out for delivery, or delivered cannot be cancelled. Current status: ' . $order->status, null, 400);
        }

        $is_already_paid = (bool)$this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'purchase'
        ]);

        $this->db->trans_begin();

        if ($is_already_paid) {
            $buyer = $this->General_model->getOne('users', ['id' => (int)$order->user_id]);
            $order_amount = (float)$order->amount;

            // 1. Refund buyer balance
            if ($buyer) {
                $this->db->set('wallet_balance', 'wallet_balance + ' . $order_amount, FALSE);
                $this->db->where('id', (int)$buyer->id);
                $this->db->update('users');

                // Log refund wallet transaction
                $this->db->insert('wallet_transactions', [
                    'user_id'      => (int)$buyer->id,
                    'type'         => 'credit',
                    'amount'       => $order_amount,
                    'source'       => 'admin_credit',
                    'reference_id' => (int)$id,
                    'remark'       => "Refund for cancelled Order #{$id}",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // 2. Restore product stock
            $current_prod = $this->General_model->getOne('products', ['id' => (int)$order->product_id]);
            if ($current_prod) {
                $new_stock = (int)$current_prod->stock + (int)$order->quantity;
                $this->db->update('products', ['stock' => $new_stock], ['id' => (int)$order->product_id]);
            }

            // 3. Reverse MLM level commissions
            $commissions = $this->db->get_where('order_commissions', ['order_id' => (int)$id])->result();
            foreach ($commissions as $comm) {
                $receiver = $this->General_model->getOne('users', ['id' => (int)$comm->receiver_id]);
                if ($receiver) {
                    $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$comm->amount, FALSE);
                    $this->db->where('id', (int)$receiver->id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$receiver->id,
                        'type'         => 'debit',
                        'amount'       => (float)$comm->amount,
                        'source'       => 'admin_debit',
                        'reference_id' => (int)$id,
                        'remark'       => "Reversal of Level {$comm->level} referral commission for cancelled Order #{$id}",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // 4. Reverse Admin commission cut
            $admin_txn = $this->General_model->getOne('wallet_transactions', [
                'reference_id' => (int)$id,
                'source'       => 'admin_commission'
            ]);

            if ($admin_txn) {
                $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$admin_txn->amount, FALSE);
                $this->db->where('id', (int)$admin_txn->user_id);
                $this->db->update('users');

                $this->db->insert('wallet_transactions', [
                    'user_id'      => (int)$admin_txn->user_id,
                    'type'         => 'debit',
                    'amount'       => (float)$admin_txn->amount,
                    'source'       => 'admin_debit',
                    'reference_id' => (int)$id,
                    'remark'       => "Reversal of Admin commission cut for cancelled Order #{$id}",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }

            // 5. Delete order commissions records
            $this->db->delete('order_commissions', ['order_id' => (int)$id]);
        }

        // Update status to cancelled
        $this->db->update('orders', [
            'status'     => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => (int)$order->id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to cancel order due to a database error.', null, 500);
        } else {
            $this->db->trans_commit();
            $resp_msg = $is_already_paid
                ? "Order #{$id} cancelled successfully. Paid amount of ₹" . number_format($order->amount, 2) . " has been refunded to wallet and commissions reversed."
                : "Order #{$id} cancelled successfully.";

            $this->response(true, $resp_msg, [
                'order_id'         => (int)$order->id,
                'status'           => 'cancelled',
                'status_label'     => 'Cancelled',
                'was_paid'         => $is_already_paid,
                'refund_issued'    => $is_already_paid,
                'refund_amount'    => $is_already_paid ? (float)$order->amount : 0.00
            ], 200);
        }
    }

    /**
     * POST api/update_order_status
     * POST api/update_order_status/(:num)
     * Admin authenticated endpoint to update order status through lifecycle:
     * placed -> confirmed -> packed -> out_for_delivery -> delivered, or -> cancelled from any non-terminal state.
     * Orders at pending (never paid) cannot be moved forward — only cancelled.
     * Moving to cancelled reverses everything verify_order_payment did (if paid).
     */
    public function update_order_status($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $role = (int)$auth['decoded']->role;

        // Only Admin can update order status
        if ($role !== 1) {
            $this->response(false, 'Unauthorized. Only administrators can update order status.', null, 403);
        }

        $id = $id ?: ($this->input->post('order_id') ?: ($this->input->post('id') ?: ($this->input->get('order_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        $new_status = strtolower(trim((string)$this->input->post('status')));
        $valid_all_statuses = ['pending', 'placed', 'confirmed', 'packed', 'out_for_delivery', 'delivered', 'cancelled'];
        if (!in_array($new_status, $valid_all_statuses)) {
            $this->response(false, 'Invalid status selected: ' . htmlspecialchars($new_status), null, 400);
        }

        $order = $this->General_model->getOne('orders', ['id' => (int)$id]);
        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        if ($order->status === $new_status) {
            $this->response(false, "Order #{$id} already has status: '{$new_status}'", null, 400);
        }

        // Terminal states cannot transition
        if ($order->status === 'delivered') {
            $this->response(false, "Order #{$id} is already delivered (terminal state) and cannot be modified.", null, 400);
        }
        if ($order->status === 'cancelled') {
            $this->response(false, "Order #{$id} is already cancelled (terminal state) and cannot be modified.", null, 400);
        }

        // Payment gating: pending orders cannot move forward
        if ($order->status === 'pending') {
            if ($new_status !== 'cancelled') {
                $this->response(false, "Order #{$id} is awaiting buyer payment (pending) and cannot be moved forward. Only paid orders (placed) can proceed in fulfillment.", null, 400);
            }
        }

        // Enforce strict progression among non-terminal states:
        // placed -> confirmed or cancelled
        // confirmed -> packed or cancelled
        // packed -> out_for_delivery or cancelled
        // out_for_delivery -> delivered or cancelled
        $allowed_transitions = [
            'pending'          => ['cancelled'],
            'placed'           => ['confirmed', 'cancelled'],
            'confirmed'        => ['packed', 'cancelled'],
            'packed'           => ['out_for_delivery', 'cancelled'],
            'out_for_delivery' => ['delivered', 'cancelled']
        ];

        if (!isset($allowed_transitions[$order->status]) || !in_array($new_status, $allowed_transitions[$order->status])) {
            $this->response(false, "Invalid status transition from '{$order->status}' to '{$new_status}'.", null, 400);
        }

        $is_already_paid = (bool)$this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'purchase'
        ]);

        $this->db->trans_begin();

        if ($new_status === 'cancelled') {
            if ($is_already_paid) {
                $buyer = $this->General_model->getOne('users', ['id' => (int)$order->user_id]);
                $order_amount = (float)$order->amount;

                // 1. Refund buyer balance
                if ($buyer) {
                    $this->db->set('wallet_balance', 'wallet_balance + ' . $order_amount, FALSE);
                    $this->db->where('id', (int)$buyer->id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$buyer->id,
                        'type'         => 'credit',
                        'amount'       => $order_amount,
                        'source'       => 'admin_credit',
                        'reference_id' => (int)$id,
                        'remark'       => "Refund for cancelled Order #{$id} by Admin",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }

                // 2. Restore product stock
                $current_prod = $this->General_model->getOne('products', ['id' => (int)$order->product_id]);
                if ($current_prod) {
                    $new_stock = (int)$current_prod->stock + (int)$order->quantity;
                    $this->db->update('products', ['stock' => $new_stock], ['id' => (int)$order->product_id]);
                }

                // 3. Reverse MLM level referral commissions
                $commissions = $this->db->get_where('order_commissions', ['order_id' => (int)$id])->result();
                foreach ($commissions as $comm) {
                    $receiver = $this->General_model->getOne('users', ['id' => (int)$comm->receiver_id]);
                    if ($receiver) {
                        $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$comm->amount, FALSE);
                        $this->db->where('id', (int)$receiver->id);
                        $this->db->update('users');

                        $this->db->insert('wallet_transactions', [
                            'user_id'      => (int)$receiver->id,
                            'type'         => 'debit',
                            'amount'       => (float)$comm->amount,
                            'source'       => 'admin_debit',
                            'reference_id' => (int)$id,
                            'remark'       => "Reversal of Level {$comm->level} referral commission for cancelled Order #{$id}",
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                // 4. Reverse Admin commission cut
                $admin_txn = $this->General_model->getOne('wallet_transactions', [
                    'reference_id' => (int)$id,
                    'source'       => 'admin_commission'
                ]);

                if ($admin_txn) {
                    $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$admin_txn->amount, FALSE);
                    $this->db->where('id', (int)$admin_txn->user_id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$admin_txn->user_id,
                        'type'         => 'debit',
                        'amount'       => (float)$admin_txn->amount,
                        'source'       => 'admin_debit',
                        'reference_id' => (int)$id,
                        'remark'       => "Reversal of Admin commission cut for cancelled Order #{$id}",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }

                // 5. Delete order commissions records
                $this->db->delete('order_commissions', ['order_id' => (int)$id]);
            }

            $this->db->update('orders', [
                'status'     => 'cancelled',
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => (int)$order->id]);
        } else {
            // Forward transition
            $this->db->update('orders', [
                'status'     => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => (int)$order->id]);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to update order status due to a database transaction error.', null, 500);
        } else {
            $this->db->trans_commit();

            $status_label = ucwords(str_replace('_', ' ', $new_status));
            if ($new_status === 'pending') {
                $status_label = 'Awaiting Payment';
            } elseif ($new_status === 'placed') {
                $status_label = 'Placed';
            }

            $success_msg = ($new_status === 'cancelled')
                ? "Order #{$id} has been cancelled" . ($is_already_paid ? ' and paid amount refunded to buyer.' : '.')
                : "Order status successfully updated to {$status_label}.";

            $this->response(true, $success_msg, [
                'order_id'         => (int)$order->id,
                'status'           => $new_status,
                'status_label'     => $status_label,
                'was_paid'         => $is_already_paid,
                'refund_issued'    => ($new_status === 'cancelled' && $is_already_paid),
                'updated_at'       => date('Y-m-d H:i:s')
            ], 200);
        }
    }

    /**
     * POST api/wallet/deposit
     * Authenticated endpoint to submit a wallet deposit request (cash/online with proof)
     */
    public function request_wallet_deposit()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[cash,online]');
        $this->form_validation->set_rules('remark', 'Remark', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $amount = (float)$this->input->post('amount');
        $payment_method = $this->input->post('payment_method', TRUE);
        $remark = $this->input->post('remark', TRUE) ?: null;
        $proof_file_path = null;

        // If online payment method, require screenshot or PDF proof file
        if ($payment_method === 'online') {
            if (empty($_FILES['proof_file']['name'])) {
                $this->response(false, 'Proof receipt file is required for online deposit requests.', null, 400);
            }

            $upload_path = './uploads/deposit_proofs/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('proof_file')) {
                $this->response(false, $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();
                $proof_file_path = 'uploads/deposit_proofs/' . $upload_data['file_name'];
            }
        }

        $insert_data = [
            'user_id'        => $user_id,
            'amount'         => $amount,
            'payment_method' => $payment_method,
            'proof_file'     => $proof_file_path,
            'remark'         => $remark,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $request_id = $this->General_model->insert('wallet_deposit_requests', $insert_data);

        if ($request_id) {
            $this->response(true, 'Deposit request submitted successfully. Pending admin approval.', [
                'request_id'     => (int)$request_id,
                'amount'         => $amount,
                'payment_method' => $payment_method,
                'status'         => 'pending'
            ], 201);
        } else {
            $this->response(false, 'Failed to insert request into database', null, 500);
        }
    }

    /**
     * GET api/wallet/deposits
     * GET api/get_deposit_requests
     * GET api/get_deposit_requests/(:num)
     * Authenticated endpoint to retrieve deposit requests or individual request details
     */
    public function get_deposit_requests($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        // If an individual request ID is passed via URL parameter or query parameter, return its full details
        $id = $id ?: ($this->input->get('id', TRUE) ?: ($this->input->get('request_id', TRUE) ?: ($this->input->post('id', TRUE) ?: $this->input->post('request_id', TRUE))));
        if (!empty($id)) {
            return $this->get_deposit_request_details($id);
        }

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;
        $status = $this->input->get('status', TRUE);
        $search = $this->input->get('search', TRUE);

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        // Base query setup
        $this->db->from('wallet_deposit_requests');
        $this->db->join('users', 'users.id = wallet_deposit_requests.user_id', 'inner');

        // Permission check: Admin can view all or filter by user_id; regular user sees only their own requests
        if ($role === 1) {
            $filter_user_id = $this->input->get('user_id', TRUE);
            if (!empty($filter_user_id) && is_numeric($filter_user_id)) {
                $this->db->where('wallet_deposit_requests.user_id', (int)$filter_user_id);
            }
        } else {
            $this->db->where('wallet_deposit_requests.user_id', $user_id);
        }

        if ($status !== '' && $status !== null) {
            $this->db->where('wallet_deposit_requests.status', $status);
        }

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('users.name', $search);
            $this->db->or_like('users.email', $search);
            $this->db->group_end();
        }

        // Count query
        $total = $this->db->count_all_results('', FALSE);

        // Fetch query with joined user and moderator info
        $this->db->select('wallet_deposit_requests.*, 
            users.name as user_name, users.email as user_email, users.phone as user_phone,
            admin_users.name as action_by_name');
        $this->db->join('users as admin_users', 'admin_users.id = wallet_deposit_requests.action_by', 'left');
        $this->db->order_by('wallet_deposit_requests.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows = $query->result();

        $requests = [];
        foreach ($rows as $row) {
            $proof_url = !empty($row->proof_file) ? base_url($row->proof_file) : null;
            $ext = !empty($row->proof_file) ? strtolower(pathinfo($row->proof_file, PATHINFO_EXTENSION)) : null;
            $is_image = !empty($row->proof_file) && in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg']);
            $is_pdf = !empty($row->proof_file) && ($ext === 'pdf');

            $requests[] = [
                'id'                   => (int)$row->id,
                'user_id'              => (int)$row->user_id,
                'user_name'            => $row->user_name,
                'user_email'           => $row->user_email,
                'user_phone'           => $row->user_phone,
                'amount'               => (float)$row->amount,
                'formatted_amount'     => '₹' . number_format((float)$row->amount, 2),
                'payment_method'       => $row->payment_method,
                'payment_method_label' => ($row->payment_method === 'online') ? 'Online Transfer' : 'Cash',
                'proof_file'           => $proof_url,
                'proof_file_url'       => $proof_url,
                'proof_image'          => $is_image ? $proof_url : null,
                'file_name'            => !empty($row->proof_file) ? basename($row->proof_file) : null,
                'file_type'            => $ext,
                'is_image'             => $is_image,
                'is_pdf'               => $is_pdf,
                'remark'               => $row->remark ?: '',
                'status'               => $row->status,
                'status_label'         => ucfirst($row->status),
                'action_by'            => $row->action_by ? (int)$row->action_by : null,
                'action_by_name'       => $row->action_by_name,
                'detail_url'           => base_url('api/get_deposit_requests/' . $row->id),
                'created_at'           => $row->created_at,
                'formatted_created_at' => date('M d, Y h:i A', strtotime($row->created_at)),
                'updated_at'           => $row->updated_at,
                'formatted_updated_at' => $row->updated_at ? date('M d, Y h:i A', strtotime($row->updated_at)) : null
            ];
        }

        $this->response(true, 'Deposit requests retrieved successfully', [
            'requests' => $requests,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ], 200);
    }

    /**
     * GET api/get_deposit_request_details/(:num)
     * Authenticated endpoint to fetch complete details of an individual deposit request,
     * including user details, uploaded proof image/file URL, status, and transaction details.
     */
    public function get_deposit_request_details($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        $id = $id ?: ($this->input->get('id', TRUE) ?: ($this->input->get('request_id', TRUE) ?: ($this->input->post('id', TRUE) ?: $this->input->post('request_id', TRUE))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing deposit request ID', null, 400);
        }

        $this->db->select('wallet_deposit_requests.*, 
            users.name as user_name, users.email as user_email, users.phone as user_phone, users.referral_code as user_referral_code, users.wallet_balance as user_wallet_balance,
            admin_users.name as action_by_name, admin_users.email as action_by_email');
        $this->db->from('wallet_deposit_requests');
        $this->db->join('users', 'users.id = wallet_deposit_requests.user_id', 'inner');
        $this->db->join('users as admin_users', 'admin_users.id = wallet_deposit_requests.action_by', 'left');
        $this->db->where('wallet_deposit_requests.id', (int)$id);
        $row = $this->db->get()->row();

        if (!$row) {
            $this->response(false, 'Deposit request not found', null, 404);
        }

        // Auth restriction: user must be the request owner, OR an admin
        if ($role !== 1 && (int)$row->user_id !== $user_id) {
            $this->response(false, 'Unauthorized access to this deposit request', null, 403);
        }

        $proof_url = !empty($row->proof_file) ? base_url($row->proof_file) : null;
        $ext = !empty($row->proof_file) ? strtolower(pathinfo($row->proof_file, PATHINFO_EXTENSION)) : null;
        $is_image = !empty($row->proof_file) && in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp', 'svg']);
        $is_pdf = !empty($row->proof_file) && ($ext === 'pdf');

        // Fetch linked wallet transaction if approved
        $transaction = null;
        if ($row->status === 'approved') {
            $txn = $this->General_model->getOne('wallet_transactions', [
                'reference_id' => (int)$row->id,
                'source'       => 'admin_credit'
            ]);
            if ($txn) {
                $transaction = [
                    'id'         => (int)$txn->id,
                    'type'       => $txn->type,
                    'amount'     => (float)$txn->amount,
                    'remark'     => $txn->remark,
                    'created_at' => $txn->created_at
                ];
            }
        }

        $request_data = [
            'id'                   => (int)$row->id,
            'user_id'              => (int)$row->user_id,
            'user_name'            => $row->user_name,
            'user_email'           => $row->user_email,
            'user_phone'           => $row->user_phone,
            'user_referral_code'   => $row->user_referral_code,
            'user_wallet_balance'  => (float)$row->user_wallet_balance,
            'amount'               => (float)$row->amount,
            'formatted_amount'     => '₹' . number_format((float)$row->amount, 2),
            'payment_method'       => $row->payment_method,
            'payment_method_label' => ($row->payment_method === 'online') ? 'Online Transfer' : 'Cash',
            'proof_file'           => $proof_url,
            'proof_file_url'       => $proof_url,
            'proof_image'          => $is_image ? $proof_url : null,
            'file_name'            => !empty($row->proof_file) ? basename($row->proof_file) : null,
            'file_type'            => $ext,
            'is_image'             => $is_image,
            'is_pdf'               => $is_pdf,
            'remark'               => $row->remark ?: '',
            'status'               => $row->status,
            'status_label'         => ucfirst($row->status),
            'action_by'            => $row->action_by ? (int)$row->action_by : null,
            'action_by_name'       => $row->action_by_name,
            'action_by_email'      => $row->action_by_email,
            'detail_url'           => base_url('api/get_deposit_requests/' . $row->id),
            'transaction'          => $transaction,
            'created_at'           => $row->created_at,
            'formatted_created_at' => date('M d, Y h:i A', strtotime($row->created_at)),
            'updated_at'           => $row->updated_at,
            'formatted_updated_at' => $row->updated_at ? date('M d, Y h:i A', strtotime($row->updated_at)) : null
        ];

        $this->response(true, 'Deposit request details retrieved successfully', [
            'request' => $request_data
        ], 200);
    }

    /**
     * GET api/addresses
     * Authenticated endpoint to retrieve all shipping addresses for the logged-in user
     */
    public function get_addresses()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->where('user_id', $user_id);
        $this->db->order_by('is_default', 'DESC');
        $this->db->order_by('id', 'DESC');
        $addresses = $this->db->get('user_addresses')->result();

        $this->response(true, 'Addresses retrieved successfully', [
            'addresses' => $addresses
        ], 200);
    }

    /**
     * POST api/addresses/save
     * Authenticated endpoint to save a new shipping address
     */
    public function save_address()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON body
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|max_length[15]');
        $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('address_line2', 'Address Line 2', 'trim|max_length[255]');
        $this->form_validation->set_rules('landmark', 'Landmark', 'trim|max_length[150]');
        $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('country', 'Country', 'trim|max_length[100]');
        $this->form_validation->set_rules('is_default', 'Is Default', 'integer|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        // Check if this is the user's first address
        $existing_count = $this->db->where('user_id', $user_id)->count_all_results('user_addresses');
        $is_default = (int)$this->input->post('is_default');

        if ($existing_count === 0) {
            $is_default = 1; // Force first address to be default
        }

        // If setting this address to default, set all others to 0
        if ($is_default === 1 && $existing_count > 0) {
            $this->db->update('user_addresses', ['is_default' => 0], ['user_id' => $user_id]);
        }

        $insert_data = [
            'user_id'       => $user_id,
            'full_name'     => $this->input->post('full_name', TRUE),
            'mobile'        => $this->input->post('mobile', TRUE),
            'address_line1' => $this->input->post('address_line1', TRUE),
            'address_line2' => $this->input->post('address_line2', TRUE) ?: null,
            'landmark'      => $this->input->post('landmark', TRUE) ?: null,
            'city'          => $this->input->post('city', TRUE),
            'state'         => $this->input->post('state', TRUE),
            'pincode'       => $this->input->post('pincode', TRUE),
            'country'       => $this->input->post('country', TRUE) ?: 'India',
            'is_default'    => $is_default,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $address_id = $this->General_model->insert('user_addresses', $insert_data);

        if ($address_id) {
            $full_address = $this->General_model->getOne('user_addresses', ['id' => $address_id]);
            if ($full_address) {
                $full_address->id = (int)$full_address->id;
                $full_address->user_id = (int)$full_address->user_id;
                $full_address->is_default = (int)$full_address->is_default;
            }
            $this->response(true, 'Address saved successfully', $full_address, 201);
        } else {
            $this->response(false, 'Failed to save address', null, 500);
        }
    }

    /**
     * PUT/POST api/addresses/update/(:num)
     * Authenticated endpoint to update an existing shipping address
     */
    public function update_address($id = null)
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'PUT' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse PUT/POST request body
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        } else if ($method === 'PUT') {
            parse_str(file_get_contents('php://input'), $put_data);
            if (is_array($put_data)) {
                $_POST = array_merge($_POST, $put_data);
            }
        }

        $id = $id ?: ($this->input->post('address_id') ?: ($this->input->post('id') ?: ($this->input->get('address_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid Address ID', null, 400);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $address = $this->General_model->getOne('user_addresses', ['id' => (int)$id]);
        if (!$address || (int)$address->user_id !== $user_id) {
            $this->response(false, 'Address not found or unauthorized access.', null, 404);
        }

        $update_data = [];

        if (isset($_POST['full_name'])) {
            $full_name = trim($this->input->post('full_name', TRUE));
            if (empty($full_name)) {
                $this->response(false, 'Full name cannot be empty.', null, 400);
            }
            $update_data['full_name'] = $full_name;
        }

        if (isset($_POST['mobile'])) {
            $mobile = trim($this->input->post('mobile', TRUE));
            if (empty($mobile)) {
                $this->response(false, 'Mobile cannot be empty.', null, 400);
            }
            $update_data['mobile'] = $mobile;
        }

        if (isset($_POST['address_line1'])) {
            $address_line1 = trim($this->input->post('address_line1', TRUE));
            if (empty($address_line1)) {
                $this->response(false, 'Address Line 1 cannot be empty.', null, 400);
            }
            $update_data['address_line1'] = $address_line1;
        }

        if (isset($_POST['address_line2'])) {
            $update_data['address_line2'] = trim($this->input->post('address_line2', TRUE)) ?: null;
        }

        if (isset($_POST['landmark'])) {
            $update_data['landmark'] = trim($this->input->post('landmark', TRUE)) ?: null;
        }

        if (isset($_POST['city'])) {
            $city = trim($this->input->post('city', TRUE));
            if (empty($city)) {
                $this->response(false, 'City cannot be empty.', null, 400);
            }
            $update_data['city'] = $city;
        }

        if (isset($_POST['state'])) {
            $state = trim($this->input->post('state', TRUE));
            if (empty($state)) {
                $this->response(false, 'State cannot be empty.', null, 400);
            }
            $update_data['state'] = $state;
        }

        if (isset($_POST['pincode'])) {
            $pincode = trim($this->input->post('pincode', TRUE));
            if (empty($pincode)) {
                $this->response(false, 'Pincode cannot be empty.', null, 400);
            }
            $update_data['pincode'] = $pincode;
        }

        if (isset($_POST['country'])) {
            $update_data['country'] = trim($this->input->post('country', TRUE)) ?: 'India';
        }

        $existing_count = $this->db->where('user_id', $user_id)->count_all_results('user_addresses');

        $is_default = (int)$address->is_default;
        if (isset($_POST['is_default'])) {
            $is_default = (int)$this->input->post('is_default');
        }

        if ($existing_count === 1) {
            $is_default = 1; // Force default if it's the user's only address
        }

        // If setting this to default, reset others
        if ($is_default === 1) {
            $this->db->update('user_addresses', ['is_default' => 0], ['user_id' => $user_id]);
        }

        $update_data['is_default'] = $is_default;
        $update_data['updated_at'] = date('Y-m-d H:i:s');

        if ($this->db->update('user_addresses', $update_data, ['id' => $address->id])) {
            $full_address = $this->General_model->getOne('user_addresses', ['id' => $address->id]);
            if ($full_address) {
                $full_address->id = (int)$full_address->id;
                $full_address->user_id = (int)$full_address->user_id;
                $full_address->is_default = (int)$full_address->is_default;
            }
            $this->response(true, 'Address updated successfully', $full_address, 200);
        } else {
            $this->response(false, 'Failed to update address', null, 500);
        }
    }

    /**
     * DELETE/POST api/addresses/delete/(:num)
     * Authenticated endpoint to delete a shipping address
     */
    public function delete_address($id = null)
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'DELETE' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $id = $id ?: ($this->input->post('address_id') ?: ($this->input->post('id') ?: ($this->input->get('address_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid Address ID', null, 400);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $address = $this->General_model->getOne('user_addresses', ['id' => (int)$id]);
        if (!$address || (int)$address->user_id !== $user_id) {
            $this->response(false, 'Address not found or unauthorized access.', null, 404);
        }

        $was_default = (int)$address->is_default;

        $this->db->trans_begin();

        // 1. Delete the address
        $this->db->delete('user_addresses', ['id' => $address->id]);

        // 2. If it was the default address, promote another one
        if ($was_default === 1) {
            $next_address = $this->db->where('user_id', $user_id)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get('user_addresses')
                ->row();
            if ($next_address) {
                $this->db->update('user_addresses', ['is_default' => 1], ['id' => $next_address->id]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to delete address due to database transaction error', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Address deleted successfully', null, 200);
        }
    }

    /**
     * GET api/get_referrals
     * Authenticated endpoint to fetch the user's direct referrals list
     */
    public function get_referrals()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Fetch users who registered using the current user's referral code (parent_id = current user's ID)
        $this->db->select('id, custom_id, name, email, phone, gender, profile_image, referral_code, wallet_balance, is_profile_active, is_profile_completed, status, created_at');
        $this->db->from('users');
        $this->db->where('parent_id', $user_id);
        $this->db->where('role', 0);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $rows = $query->result();

        $referrals = [];
        $active_count = 0;

        foreach ($rows as $row) {
            $is_active = ((int)$row->status === 1 && !empty($row->is_profile_active));
            if ($is_active) {
                $active_count++;
            }

            // Order metrics for this referral
            $ord_stat = $this->db->select('COUNT(*) as cnt, SUM(amount) as total')
                ->where('user_id', (int)$row->id)
                ->where_in('status', ['placed', 'confirmed', 'packed', 'out_for_delivery', 'delivered'])
                ->get('orders')
                ->row();

            $referrals[] = [
                'id'                   => (int)$row->id,
                'custom_id'            => $row->custom_id ?: str_pad($row->id, 7, '0', STR_PAD_LEFT),
                'name'                 => $row->name,
                'email'                => $row->email ?: '',
                'phone'                => $row->phone ?: '',
                'gender'               => $row->gender ?: null,
                'profile_image'        => $this->get_user_image_url($row->profile_image ?? ''),
                'referral_code'        => $row->referral_code,
                'wallet_balance'       => (float)($row->wallet_balance ?? 0.00),
                'is_profile_active'    => (bool)($row->is_profile_active ?? 0),
                'is_profile_completed' => (bool)($row->is_profile_completed ?? 0),
                'status'               => (int)$row->status,
                'status_label'         => ((int)$row->status === 1) ? ($is_active ? 'Active' : 'Pending KYC') : 'Blocked',
                'total_orders'         => (int)($ord_stat->cnt ?? 0),
                'total_spent'          => (float)($ord_stat->total ?? 0.00),
                'created_at'           => $row->created_at,
                'joined_formatted'     => date('M d, Y', strtotime($row->created_at))
            ];
        }

        $total = count($referrals);

        $this->response(true, 'Direct referrals retrieved successfully', [
            'referrals'        => $referrals,
            'total'            => $total,
            'total_referrals'  => $total,
            'active_referrals' => $active_count,
            'levels'           => 1,
        ], 200);
    }

    /**
     * GET api/privacy_policy
     * Returns Privacy Policy text (Custom Admin content or rich default fallback)
     */
    public function privacy_policy()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $page = cms_get_page('privacy_policy');

        // Clean plain text version: formats headings, bullets, paragraphs cleanly for native mobile apps
        $clean_text = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/i', "\n\n$1\n", $page['content']);
        $clean_text = preg_replace('/<li[^>]*>(.*?)<\/li>/i', "• $1\n", $clean_text);
        $clean_text = preg_replace('/<p[^>]*>(.*?)<\/p>/i', "$1\n\n", $clean_text);
        $clean_text = preg_replace('/<br\s*\/?>/i', "\n", $clean_text);
        $clean_text = html_entity_decode(strip_tags($clean_text), ENT_QUOTES, 'UTF-8');
        $clean_text = preg_replace("/\n{3,}/", "\n\n", trim($clean_text));

        $this->response(true, 'Privacy Policy retrieved successfully', [
            'title'        => $page['title'],
            'content'      => $page['content'],
            'plain_text'   => $clean_text,
            'content_html' => $page['content'],
            'is_custom'    => $page['is_custom'],
            'updated_at'   => $page['updated_at']
        ], 200);
    }

    /**
     * GET api/terms_conditions
     * Returns Terms & Conditions text (Custom Admin content or rich default fallback)
     */
    public function terms_conditions()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $page = cms_get_page('terms_conditions');

        // Clean plain text version: formats headings, bullets, paragraphs cleanly for native mobile apps
        $clean_text = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/i', "\n\n$1\n", $page['content']);
        $clean_text = preg_replace('/<li[^>]*>(.*?)<\/li>/i', "• $1\n", $clean_text);
        $clean_text = preg_replace('/<p[^>]*>(.*?)<\/p>/i', "$1\n\n", $clean_text);
        $clean_text = preg_replace('/<br\s*\/?>/i', "\n", $clean_text);
        $clean_text = html_entity_decode(strip_tags($clean_text), ENT_QUOTES, 'UTF-8');
        $clean_text = preg_replace("/\n{3,}/", "\n\n", trim($clean_text));

        $this->response(true, 'Terms & Conditions retrieved successfully', [
            'title'        => $page['title'],
            'content'      => $page['content'],
            'plain_text'   => $clean_text,
            'content_html' => $page['content'],
            'is_custom'    => $page['is_custom'],
            'updated_at'   => $page['updated_at']
        ], 200);
    }

    /**
     * GET/POST/DELETE api/delete_account
     * - GET: Returns steps to delete the account.
     * - POST/DELETE: Performs authenticated account deletion.
     */
    public function delete_account()
    {
        $method = $this->input->method(TRUE);

        if ($method === 'GET') {
            $steps = [
                "steps" => [
                    "Step 1: Go to the 'Profile' or 'Settings' tab in your app.",
                    "Step 2: Scroll down and tap on the 'Delete Account' button.",
                    "Step 3: Review the warning message carefully.",
                    "Step 4: Confirm the action on the confirmation dialog.",
                    "Step 5: All of your personal profile data, addresses, active cart, wallet balance, and transaction history will be permanently deleted.",
                    "Step 6: Your referral position in the network tree will be removed, and any downline members will be reassigned per platform policy."
                ],
                "what_gets_deleted" => [
                    "Profile information (name, email, phone, address)",
                    "Profile image / avatar",
                    "Wallet balance and full transaction history",
                    "Saved shipping addresses",
                    "Active and past cart data",
                    "Referral code and network position"
                ],
                "what_is_retained" => [
                    "Order invoices and statutory tax transaction logs are retained strictly in compliance with applicable law."
                ],
                "warning" => "WARNING: This action is permanent and irreversible. Your wallet balance and referral position will be permanently lost and cannot be recovered."
            ];
            $this->response(true, 'Steps to delete account retrieved successfully', $steps, 200);
        }

        if ($method !== 'DELETE' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Authenticate the user token
        $auth = $this->check_auth();
        $user_id = (int) $auth['decoded']->user_id;

        // Retrieve user to check profile image
        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user) {
            $this->response(false, 'User not found.', null, 404);
        }

        // Prevent admin / non-member accounts from deleting account
        if ((int)($user->role ?? 0) !== 0 || $user_id === 1) {
            $this->response(false, 'Administrative master accounts cannot be deleted through this endpoint. Platform administrators are permanently protected.', null, 403);
        }

        // Delete profile image file if exists
        if (!empty($user->profile_image) && file_exists('./' . $user->profile_image)) {
            @unlink('./' . $user->profile_image);
        }

        $this->db->trans_begin();

        // 1. Delete associated OTP records
        if (!empty($user->phone)) {
            $this->db->delete('user_login_otps', ['phone' => $user->phone]);
            $this->db->delete('user_registration_otps', ['mobile' => $user->phone]);
        }

        // 2. Delete user (foreign keys ON DELETE CASCADE handle user_addresses, cart, wallet_transactions, orders, etc.)
        $this->db->delete('users', ['id' => $user_id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to delete account due to a database transaction error.', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Your account has been deleted successfully.', [
                'message_detail' => 'All associated profile information, wallet balance, addresses, and network position have been permanently deleted.'
            ], 200);
        }
    }

    /**
     * GET/POST api/migrate_db_slug
     * Utility endpoint to modify categories.slug and products.slug columns to NULL
     * and update existing rows to NULL in the database.
     */
    public function migrate_db_slug()
    {
        $results = [];

        // 1. Alter categories table
        try {
            $this->db->query("ALTER TABLE `categories` MODIFY `slug` VARCHAR(255) NULL DEFAULT NULL");
            $this->db->query("UPDATE `categories` SET `slug` = NULL");
            $results['categories'] = 'Successfully modified categories.slug to NULL and updated rows to NULL.';
        } catch (Throwable $e) {
            $results['categories_error'] = $e->getMessage();
        }

        // 2. Alter products table
        try {
            $this->db->query("ALTER TABLE `products` MODIFY `slug` VARCHAR(255) NULL DEFAULT NULL");
            $this->db->query("UPDATE `products` SET `slug` = NULL");
            $results['products'] = 'Successfully modified products.slug to NULL and updated rows to NULL.';
        } catch (Throwable $e) {
            $results['products_error'] = $e->getMessage();
        }

        $this->response(true, 'Database slug migration executed successfully.', $results, 200);
    }

    /**
     * GET/POST api/migrate_gender_and_commission
     * Utility endpoint to add gender column to users table and amount column to commission_settings table.
     */
    public function migrate_gender_and_commission()
    {
        $results = [];

        // 1. Add gender column to users
        try {
            $check_gender = $this->db->query("SHOW COLUMNS FROM `users` LIKE 'gender'")->row();
            if (!$check_gender) {
                $this->db->query("ALTER TABLE `users` ADD COLUMN `gender` ENUM('male', 'female', 'other') NULL DEFAULT NULL AFTER `email`");
                $results['gender_column'] = "Added 'gender' column to users table.";
            } else {
                $results['gender_column'] = "'gender' column already exists in users table.";
            }
        } catch (Throwable $e) {
            $results['gender_error'] = $e->getMessage();
        }

        // 2. Add amount column to commission_settings
        try {
            $check_amount = $this->db->query("SHOW COLUMNS FROM `commission_settings` LIKE 'amount'")->row();
            if (!$check_amount) {
                $this->db->query("ALTER TABLE `commission_settings` ADD COLUMN `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `level`");
                $results['amount_column'] = "Added 'amount' column to commission_settings table.";
            } else {
                $results['amount_column'] = "'amount' column already exists in commission_settings table.";
            }

            // Relax percentage column
            $this->db->query("ALTER TABLE `commission_settings` MODIFY COLUMN `percentage` DECIMAL(5,2) NULL DEFAULT 0.00");

            // Seed default amounts if 0
            $defaults = [
                1 => 50.00, 2 => 35.00, 3 => 25.00, 4 => 20.00,
                5 => 15.00, 6 => 12.00, 7 => 10.00, 8 => 8.00,
                9 => 6.00,  10 => 4.00, 11 => 3.00, 12 => 2.00
            ];
            foreach ($defaults as $lvl => $amt) {
                $this->db->query("UPDATE `commission_settings` SET `amount` = {$amt} WHERE `level` = {$lvl} AND (`amount` = 0.00 OR `amount` IS NULL)");
            }
            $results['seeded_amounts'] = "Seeded default amounts for levels 1 to 12.";
        } catch (Throwable $e) {
            $results['commission_error'] = $e->getMessage();
        }

        $this->response(true, 'Database gender and commission migration executed successfully.', $results, 200);
    }

    /**
     * GET/POST api/migrate_db_indexes
     * Utility endpoint to add high-performance database indexes across all tables.
     */
    public function migrate_db_indexes()
    {
        $results = [];

        $indexes = [
            'orders'                   => [
                'idx_orders_status'          => ['status'],
                'idx_orders_created_at'      => ['created_at'],
                'idx_orders_user_status'     => ['user_id', 'status'],
            ],
            'users'                    => [
                'idx_users_role'             => ['role'],
                'idx_users_status'           => ['status'],
                'idx_users_role_status'      => ['role', 'status'],
                'idx_users_profile_active'   => ['is_profile_active'],
            ],
            'wallet_transactions'      => [
                'idx_wt_source'              => ['source'],
                'idx_wt_reference'           => ['reference_id'],
                'idx_wt_created_at'          => ['created_at'],
                'idx_wt_user_created'        => ['user_id', 'created_at'],
            ],
            'products'                 => [
                'idx_products_status'        => ['status'],
                'idx_products_price'         => ['price'],
            ],
            'categories'               => [
                'idx_categories_status'      => ['status'],
            ],
            'wallet_deposit_requests'  => [
                'idx_wdr_user_id'            => ['user_id'],
                'idx_wdr_status'             => ['status'],
                'idx_wdr_created_at'         => ['created_at'],
            ],
            'order_commissions'        => [
                'idx_oc_level'               => ['level'],
                'idx_oc_order_level'         => ['order_id', 'level'],
            ],
        ];

        foreach ($indexes as $table => $tbl_indexes) {
            foreach ($tbl_indexes as $idx_name => $cols) {
                try {
                    $check = $this->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name = '{$idx_name}'")->row();
                    if (!$check) {
                        $col_str = implode(', ', array_map(function($c) { return "`$c`"; }, $cols));
                        $this->db->query("ALTER TABLE `{$table}` ADD INDEX `{$idx_name}` ({$col_str})");
                        $results[] = "Added index {$idx_name} on {$table}";
                    } else {
                        $results[] = "Index {$idx_name} on {$table} already exists";
                    }
                } catch (Throwable $e) {
                    $results[] = "Error on {$idx_name} ({$table}): " . $e->getMessage();
                }
            }
        }

        $this->response(true, 'Database indexing completed successfully.', $results, 200);
    }
}
