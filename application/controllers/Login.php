<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    /**
     * Helper to redirect to dashboard if already logged in
     */
    private function check_logged_in()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('admin/dashboard');
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
     * Web Login Page & Handler
     */
    public function index()
    {
        $this->check_logged_in();

        // If accessed at root (empty URI) or 'login', redirect to 'admin/login' so direct /admin/login is shown in the URL
        $uri = trim($this->uri->uri_string(), '/');
        if ($uri === '' || strtolower($uri) === 'login') {
            redirect('admin/login');
        }

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() === TRUE) {
                $email = $this->input->post('email', TRUE);
                $password = $this->input->post('password');

                $user = $this->General_model->getOne('users', ['email' => $email]);

                if ($user && password_verify($password, $user->password)) {
                    if ((int)$user->status === 0) {
                        $this->session->set_flashdata('error', 'Your account has been blocked. Please contact admin.');
                    } else {
                        // Set session data
                        $session_data = [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'profile_image' => $user->profile_image,
                            'address' => $user->address,
                            'role' => $user->role,
                            'referral_code' => $user->referral_code,
                            'parent_id' => $user->parent_id,
                            'wallet_balance' => $user->wallet_balance,
                            'logged_in' => TRUE
                        ];
                        $this->session->set_userdata($session_data);
                        redirect('admin/dashboard');
                    }
                } else {
                    $this->session->set_flashdata('error', 'Invalid email or password.');
                }
            }
        }

        $this->load->view('login_view');
    }

    /**
     * Web Register Page & Handler
     */
    public function register()
    {
        $this->check_logged_in();

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('name', 'Name', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
                'is_unique' => 'This email is already registered.'
            ]);
            $this->form_validation->set_rules('phone', 'Phone', 'required|trim|regex_match[/^[0-9]{10,15}$/]', [
                'regex_match' => 'The phone number must be between 10 and 15 digits.'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]', [
                'matches' => 'The Confirm Password field does not match the Password field.'
            ]);

            if ($this->form_validation->run() === TRUE) {
                // Check referral code if provided
                $parent_id = null;
                $referral_code = $this->input->post('referral_code', TRUE);
                $referral_valid = TRUE;

                if (!empty($referral_code)) {
                    $referrer = $this->General_model->getOne('users', ['referral_code' => $referral_code]);
                    $ref_err = null;
                    if (!$this->General_model->isReferrerEligible($referrer, $ref_err)) {
                        $this->session->set_flashdata('error', $ref_err);
                        $referral_valid = FALSE;
                    } else {
                        $parent_id = $referrer->id;
                    }
                }

                if ($referral_valid) {
                    $new_referral_code = $this->generate_unique_referral_code();

                    $insert_data = [
                        'custom_id'      => $this->General_model->generateUniqueCustomId(),
                        'name'           => $this->input->post('name', TRUE),
                        'email'          => $this->input->post('email', TRUE),
                        'phone'          => $this->input->post('phone', TRUE),
                        'password'       => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                        'profile_image'  => null,
                        'address'        => null,
                        'role'           => 0,
                        'referral_code'  => $new_referral_code,
                        'parent_id'      => $parent_id,
                        'wallet_balance' => 0.00,
                        'status'         => 1,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ];

                    $insert_id = $this->General_model->insert('users', $insert_data);

                    if ($insert_id) {
                        $this->session->set_flashdata('success', 'Registration successful! Please login.');
                        redirect('admin/login');
                    } else {
                        $this->session->set_flashdata('error', 'Database error. Failed to register.');
                    }
                }
            }
        }

        $this->load->view('register_view');
    }



    /**
     * Web Logout Handler
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('admin/login');
    }
}
