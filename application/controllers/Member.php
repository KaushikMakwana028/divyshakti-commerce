<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Member extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);

        // Enforce admin authentication
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user || (int)$user->role !== 1) {
            $this->session->set_flashdata('error', 'Unauthorized access.');
            redirect('admin/login');
        }
    }

    /**
     * Lists all system members (role = 0) with search, status filtering, and pagination
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        $this->db->where('role', 0);
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('email', $search);
            $this->db->or_like('phone', $search);
            $this->db->or_like('custom_id', $search);
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            if ($status === '1' || $status === 'active') {
                // Active means account is unblocked AND profile is verified
                $this->db->where('status', 1);
                $this->db->where('is_profile_active', 1);
            } elseif ($status === 'incomplete' || $status === 'pending') {
                // Incomplete or pending approval
                $this->db->where('status', 1);
                $this->db->group_start();
                $this->db->where('is_profile_active', 0);
                $this->db->or_where('is_profile_completed', 0);
                $this->db->group_end();
            } elseif ($status === '0' || $status === 'blocked') {
                $this->db->where('status', 0);
            }
        }

        // Count query
        $this->db->from('users');
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['members'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('member_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Inspects a member's detail metrics
     */
    public function view($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            show_404();
        }

        $member = $this->General_model->getOne('users', ['id' => $id, 'role' => 0]);
        if (!$member) {
            show_404();
        }

        $data['member'] = $member;
        $data['direct_referrals_count'] = $this->db->where('parent_id', $id)->count_all_results('users');
        $data['referrer'] = $member->parent_id ? $this->General_model->getOne('users', ['id' => $member->parent_id]) : null;

        // Fetch last 10 wallet transactions
        $this->db->where('user_id', $id);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(10);
        $data['transactions'] = $this->db->get('wallet_transactions')->result();

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        if ($this->input->is_ajax_request()) {
            $data['is_ajax'] = true;
            $this->load->view('member_detail', $data);
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('member_detail', $data);
            $this->load->view('templates/footer');
        }
    }

    /**
     * Handles adding direct wallet money to user (Admin action)
     */
    public function add_wallet($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/members');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid User ID.');
            redirect('admin/members');
        }

        $user = $this->General_model->getOne('users', ['id' => (int)$id, 'role' => 0]);
        if (!$user) {
            $this->session->set_flashdata('error', 'Member not found.');
            redirect('admin/members');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('remark', 'Remark', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect($this->input->server('HTTP_REFERER') ?: 'admin/members');
        }

        $amount = (float)$this->input->post('amount');
        $remark = $this->input->post('remark', TRUE) ?: 'Credited by admin';
        $admin_id = $this->session->userdata('user_id');

        $this->db->trans_begin();

        // 1. Insert credit transaction row
        $this->db->insert('wallet_transactions', [
            'user_id'    => (int)$id,
            'type'       => 'credit',
            'amount'     => $amount,
            'source'     => 'admin_credit',
            'remark'     => $remark,
            'added_by'   => $admin_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Increment user wallet balance
        $new_balance = (float)$user->wallet_balance + $amount;
        $this->db->update('users', ['wallet_balance' => $new_balance], ['id' => (int)$id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to load funds due to transaction error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Wallet credited with ₹" . number_format($amount, 2) . " successfully.");
        }

        redirect($this->input->server('HTTP_REFERER') ?: 'admin/members');
    }

    /**
     * Toggles/activates member profile KYC status (Admin action)
     */
    public function activate_profile($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid User ID.');
            redirect('admin/members');
        }

        $user = $this->General_model->getOne('users', ['id' => (int)$id, 'role' => 0]);
        if (!$user) {
            $this->session->set_flashdata('error', 'Member not found.');
            redirect('admin/members');
        }

        $new_status = ((int)($user->is_profile_active ?? 0) === 1) ? 0 : 1;
        $this->General_model->update('users', ['id' => (int)$id], [
            'is_profile_active' => $new_status,
            'updated_at'        => date('Y-m-d H:i:s')
        ]);

        if ($new_status === 1) {
            $this->session->set_flashdata('success', "Member profile for " . htmlspecialchars($user->name ?? 'Member') . " has been successfully activated and approved.");
        } else {
            $this->session->set_flashdata('info', "Member profile for " . htmlspecialchars($user->name ?? 'Member') . " has been deactivated.");
        }

        redirect($this->input->server('HTTP_REFERER') ?: 'admin/members/view/' . $id);
    }

    /**
     * Renders the referral network tree visualization page
     */
    public function network()
    {
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);
        $data['initial_user_id'] = $this->input->get('user_id', TRUE) ?: null;

        $this->load->view('templates/header', $data);
        $this->load->view('member_network_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * AJAX endpoint to query node and child data for the referral tree chart
     */
    public function getReferralTree($id = null)
    {
        // Helper to fetch user list with fields and children count subquery
        $fetch_users = function ($where_conds) {
            $this->db->select("u.id, u.parent_id, u.name, u.email, u.phone, u.profile_image, u.referral_code, u.wallet_balance, u.status, u.created_at, 
                (SELECT COUNT(*) FROM users WHERE parent_id = u.id AND role = 0) as children_count");
            $this->db->from('users u');
            $this->db->where($where_conds);
            $query = $this->db->get();
            $rows = $query->result();

            $list = [];
            foreach ($rows as $row) {
                $list[] = [
                    'id'             => (int)$row->id,
                    'parent_id'      => $row->parent_id !== null ? (int)$row->parent_id : null,
                    'name'           => $row->name,
                    'email'          => $row->email,
                    'phone'          => $row->phone,
                    'profile_image'  => (!empty($row->profile_image) && file_exists(FCPATH . ltrim($row->profile_image, '/'))) ? base_url(ltrim($row->profile_image, '/')) : null,
                    'referral_code'  => $row->referral_code,
                    'wallet_balance' => (float)$row->wallet_balance,
                    'status'         => (int)$row->status,
                    'created_at'     => $row->created_at,
                    'has_children'   => (int)$row->children_count > 0
                ];
            }
            return $list;
        };

        if ($id === null) {
            // Return root members (parent_id IS NULL and role = 0)
            $children = $fetch_users(['u.parent_id' => null, 'u.role' => 0]);
            $response = [
                'status' => true,
                'user' => null,
                'children' => $children
            ];
        } else {
            // Validate user exists and return their own profile + direct children (parent_id = $id and role = 0)
            $user_list = $fetch_users(['u.id' => (int)$id, 'u.role' => 0]);
            if (empty($user_list)) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode([
                        'status' => false,
                        'message' => 'Member not found.'
                    ]));
            }

            $user = $user_list[0];
            $children = $fetch_users(['u.parent_id' => (int)$id, 'u.role' => 0]);

            // Fetch all ancestors of the user up to the root (parent_id is null)
            $ancestors = [];
            $current_parent_id = $user['parent_id'];
            while ($current_parent_id !== null) {
                $parent_list = $fetch_users(['u.id' => $current_parent_id, 'u.role' => 0]);
                if (empty($parent_list)) {
                    break;
                }
                $parent = $parent_list[0];
                $ancestors[] = $parent;
                $current_parent_id = $parent['parent_id'];
            }

            $response = [
                'status' => true,
                'user' => $user,
                'children' => $children,
                'ancestors' => $ancestors
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /**
     * AJAX endpoint to search active members for autocomplete dropdown
     */
    public function search_autocomplete()
    {
        $query = $this->input->get('query', TRUE);
        if (empty($query)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => false, 'results' => []]));
        }

        $this->db->select('id, name, email, referral_code, custom_id');
        $this->db->from('users');
        $this->db->where('role', 0); // only members
        $this->db->group_start();
        $this->db->like('name', $query);
        $this->db->or_like('email', $query);
        $this->db->or_like('referral_code', $query);
        $this->db->or_like('custom_id', $query);
        $this->db->group_end();
        $this->db->limit(10);
        $results = $this->db->get()->result();

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'results' => $results
            ]));
    }

    public function transactions($member_id)
    {
        if (empty($member_id) || !is_numeric($member_id)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid member ID']));
        }

        $page     = (int) ($this->input->get('page') ?: 1);
        $per_page = (int) ($this->input->get('per_page') ?: 5);
        $page     = $page < 1 ? 1 : $page;
        $offset   = ($page - 1) * $per_page;

        $this->db->where('user_id', (int) $member_id);
        $this->db->from('wallet_transactions');
        $total = $this->db->count_all_results();

        $rows = $this->db->where('user_id', (int) $member_id)
            ->order_by('id', 'DESC')
            ->limit($per_page, $offset)
            ->get('wallet_transactions')
            ->result();

        $out = [];
        foreach ($rows as $i => $t) {
            $out[] = [
                'row_no'     => $offset + $i + 1,
                'type'       => $t->type,
                'amount'     => $t->amount,
                'source'     => $t->source,
                'remark'     => $t->remark,
                'created_at' => $t->created_at,
            ];
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'transactions' => $out,
                'current_page' => $page,
                'total_pages'  => max(1, (int) ceil($total / $per_page)),
                'total'        => $total,
            ]));
    }

    /**
     * Checks if $possible_descendant_id is in $ancestor_id's downline tree
     */
    private function is_descendant($possible_descendant_id, $ancestor_id)
    {
        $current_id = $possible_descendant_id;
        $visited = [];
        while (!empty($current_id)) {
            if ((int)$current_id === (int)$ancestor_id) {
                return true;
            }
            if (in_array($current_id, $visited)) {
                break;
            }
            $visited[] = $current_id;
            $row = $this->db->select('parent_id')->get_where('users', ['id' => $current_id])->row();
            $current_id = $row ? $row->parent_id : null;
        }
        return false;
    }

    /**
     * AJAX Sponsor Check for Member Edit
     */
    public function ajax_check_sponsor()
    {
        $query = trim($this->input->get('query', TRUE) ?: '');
        $member_id = (int)$this->input->get('member_id', TRUE);

        if (empty($query)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['valid' => true, 'message' => 'No sponsor (Root member)']));
        }

        $sponsor = $this->db->group_start()
            ->where('referral_code', $query)
            ->or_where('custom_id', $query)
            ->or_where('phone', $query)
            ->or_where('id', $query)
            ->group_end()
            ->where('role', 0)
            ->get('users')
            ->row();

        if (!$sponsor) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['valid' => false, 'message' => 'No member found matching this referral code, custom ID, or phone number.']));
        }

        if ($sponsor->id == $member_id) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['valid' => false, 'message' => 'A member cannot be their own sponsor.']));
        }

        if ($this->is_descendant($sponsor->id, $member_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['valid' => false, 'message' => 'Cannot set sponsor to #' . $sponsor->id . ' (' . $sponsor->name . ') because they are in this member\'s downline.']));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'valid'   => true,
                'sponsor' => [
                    'id'            => (int)$sponsor->id,
                    'name'          => $sponsor->name,
                    'referral_code' => $sponsor->referral_code,
                    'custom_id'     => $sponsor->custom_id,
                    'phone'         => $sponsor->phone
                ]
            ]));
    }

    /**
     * Edit member complete profile, identification, referral codes, and documents
     */
    public function edit($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Member ID.');
            redirect('admin/members');
        }

        $member = $this->General_model->getOne('users', ['id' => (int)$id, 'role' => 0]);
        if (!$member) {
            $this->session->set_flashdata('error', 'Member not found.');
            redirect('admin/members');
        }

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
            $this->form_validation->set_rules('phone', 'Phone Number', 'required|trim|regex_match[/^[0-9]{10,15}$/]');
            $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|in_list[male,female,other]');
            $this->form_validation->set_rules('custom_id', 'Custom Member ID', 'trim');
            $this->form_validation->set_rules('referral_code', 'Referral Code', 'required|trim');
            $this->form_validation->set_rules('parent_sponsor', 'Sponsor / Parent Referral Code', 'trim');
            $this->form_validation->set_rules('status', 'Account Status', 'required|in_list[0,1]');
            $this->form_validation->set_rules('is_profile_active', 'KYC Active Status', 'required|in_list[0,1]');
            $this->form_validation->set_rules('password', 'Password', 'trim|min_length[6]');

            // Check phone uniqueness excluding current user
            $phone = $this->input->post('phone', TRUE);
            $phone_check = $this->General_model->getOne('users', ['phone' => $phone, 'id !=' => $member->id]);
            if ($phone_check) {
                $this->session->set_flashdata('error', 'The phone number is already registered by another member.');
                redirect('admin/members/edit/' . $member->id);
            }

            // Check email uniqueness excluding current user
            $email = $this->input->post('email', TRUE) ?: null;
            if (!empty($email)) {
                $email_check = $this->General_model->getOne('users', ['email' => $email, 'id !=' => $member->id]);
                if ($email_check) {
                    $this->session->set_flashdata('error', 'The email address is already in use by another member.');
                    redirect('admin/members/edit/' . $member->id);
                }
            }

            // Check custom_id uniqueness excluding current user
            $custom_id = $this->input->post('custom_id', TRUE) ?: null;
            if (!empty($custom_id)) {
                $custom_id_check = $this->General_model->getOne('users', ['custom_id' => $custom_id, 'id !=' => $member->id]);
                if ($custom_id_check) {
                    $this->session->set_flashdata('error', 'The Custom ID is already assigned to another member.');
                    redirect('admin/members/edit/' . $member->id);
                }
            }

            // Check referral_code uniqueness excluding current user
            $referral_code = strtoupper(trim($this->input->post('referral_code', TRUE)));
            $ref_check = $this->General_model->getOne('users', ['referral_code' => $referral_code, 'id !=' => $member->id]);
            if ($ref_check) {
                $this->session->set_flashdata('error', 'The referral code is already taken by another user.');
                redirect('admin/members/edit/' . $member->id);
            }

            // Validate parent sponsor (ADD / EDIT Referral Sponsor)
            $parent_sponsor_input = trim($this->input->post('parent_sponsor', TRUE));
            $new_parent_id = null;
            if (!empty($parent_sponsor_input)) {
                $sponsor = $this->db->group_start()
                    ->where('referral_code', $parent_sponsor_input)
                    ->or_where('custom_id', $parent_sponsor_input)
                    ->or_where('phone', $parent_sponsor_input)
                    ->or_where('id', $parent_sponsor_input)
                    ->group_end()
                    ->where('role', 0)
                    ->get('users')
                    ->row();

                if (!$sponsor) {
                    $this->session->set_flashdata('error', 'Parent Sponsor not found with code/phone/ID: ' . htmlspecialchars($parent_sponsor_input));
                    redirect('admin/members/edit/' . $member->id);
                }

                if ($sponsor->id == $member->id) {
                    $this->session->set_flashdata('error', 'A member cannot be their own sponsor.');
                    redirect('admin/members/edit/' . $member->id);
                }

                if ($this->is_descendant($sponsor->id, $member->id)) {
                    $this->session->set_flashdata('error', 'Cannot assign member #' . $sponsor->id . ' (' . htmlspecialchars($sponsor->name) . ') as sponsor because they are in this member\'s downline network (circular loop).');
                    redirect('admin/members/edit/' . $member->id);
                }

                $new_parent_id = $sponsor->id;
            }

            if ($this->form_validation->run() === FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
                redirect('admin/members/edit/' . $member->id);
            }

            // File uploads config
            $this->load->library('upload');
            $upload_err = '';

            // 1. Profile Image
            $profile_image_path = $member->profile_image;
            if (!empty($_FILES['profile_image']['name'])) {
                $upload_dir = './uploads/profile_images/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $config = [
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'jpg|jpeg|png|webp',
                    'max_size'      => 4096,
                    'encrypt_name'  => TRUE
                ];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('profile_image')) {
                    $data = $this->upload->data();
                    if (!empty($member->profile_image) && file_exists('./' . $member->profile_image)) {
                        @unlink('./' . $member->profile_image);
                    }
                    $profile_image_path = 'uploads/profile_images/' . $data['file_name'];
                } else {
                    $upload_err .= 'Profile image: ' . $this->upload->display_errors('', ' ') . ' ';
                }
            }

            // 2. Aadhaar Document
            $aadhar_image_path = $member->aadhar_image;
            if (!empty($_FILES['aadhar_image']['name'])) {
                $upload_dir = './uploads/kyc_documents/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $config = [
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'jpg|jpeg|png|pdf',
                    'max_size'      => 5120,
                    'encrypt_name'  => TRUE
                ];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('aadhar_image')) {
                    $data = $this->upload->data();
                    if (!empty($member->aadhar_image) && file_exists('./' . $member->aadhar_image)) {
                        @unlink('./' . $member->aadhar_image);
                    }
                    $aadhar_image_path = 'uploads/kyc_documents/' . $data['file_name'];
                } else {
                    $upload_err .= 'Aadhaar document: ' . $this->upload->display_errors('', ' ') . ' ';
                }
            }

            // 3. PAN Document
            $pan_image_path = $member->pan_image;
            if (!empty($_FILES['pan_image']['name'])) {
                $upload_dir = './uploads/kyc_documents/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $config = [
                    'upload_path'   => $upload_dir,
                    'allowed_types' => 'jpg|jpeg|png|pdf',
                    'max_size'      => 5120,
                    'encrypt_name'  => TRUE
                ];
                $this->upload->initialize($config);
                if ($this->upload->do_upload('pan_image')) {
                    $data = $this->upload->data();
                    if (!empty($member->pan_image) && file_exists('./' . $member->pan_image)) {
                        @unlink('./' . $member->pan_image);
                    }
                    $pan_image_path = 'uploads/kyc_documents/' . $data['file_name'];
                } else {
                    $upload_err .= 'PAN document: ' . $this->upload->display_errors('', ' ') . ' ';
                }
            }

            if (!empty($upload_err)) {
                $this->session->set_flashdata('error', $upload_err);
            }

            // Build update array
            $update_data = [
                'name'                => $this->input->post('name', TRUE),
                'email'               => $email,
                'phone'               => $phone,
                'gender'              => $this->input->post('gender', TRUE) ?: null,
                'custom_id'           => $custom_id,
                'referral_code'       => $referral_code,
                'parent_id'           => $new_parent_id,
                'status'              => (int)$this->input->post('status', TRUE),
                'is_profile_active'   => (int)$this->input->post('is_profile_active', TRUE),
                'address'             => $this->input->post('address', TRUE) ?: null,
                'profile_image'       => $profile_image_path,
                'aadhar_number'       => $this->input->post('aadhar_number', TRUE) ?: null,
                'aadhar_image'        => $aadhar_image_path,
                'pan_number'          => $this->input->post('pan_number', TRUE) ?: null,
                'pan_image'           => $pan_image_path,
                'account_holder_name' => $this->input->post('account_holder_name', TRUE) ?: null,
                'bank_name'           => $this->input->post('bank_name', TRUE) ?: null,
                'account_number'      => $this->input->post('account_number', TRUE) ?: null,
                'ifsc_code'           => $this->input->post('ifsc_code', TRUE) ?: null,
                'account_type'        => $this->input->post('account_type', TRUE) ?: null,
                'branch_name'         => $this->input->post('branch_name', TRUE) ?: null,
                'updated_at'          => date('Y-m-d H:i:s')
            ];

            // Password update if provided
            $new_password = $this->input->post('password', TRUE);
            if (!empty($new_password)) {
                $update_data['password'] = password_hash($new_password, PASSWORD_BCRYPT);
            }

            $this->General_model->update('users', ['id' => $member->id], $update_data);

            // Recalculate profile completion metrics
            $updated_member = $this->General_model->getOne('users', ['id' => $member->id]);
            $completion = $this->General_model->calculateProfileCompletion($updated_member);
            $this->General_model->update('users', ['id' => $member->id], [
                'profile_completion_percentage' => $completion['percentage'],
                'is_profile_completed'          => $completion['is_completed'] ? 1 : 0
            ]);

            $this->session->set_flashdata('success', 'Member #' . $member->id . ' (' . htmlspecialchars($update_data['name']) . ') details updated successfully.');
            redirect('admin/members/view/' . $member->id);
        }

        // GET Request: render form
        $data['member']   = $member;
        $data['referrer'] = $member->parent_id ? $this->General_model->getOne('users', ['id' => $member->parent_id]) : null;
        $data['user']     = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('member_edit', $data);
        $this->load->view('templates/footer');
    }
}

