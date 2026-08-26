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
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('status', (int)$status);
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

        $this->db->select('id, name, email, referral_code');
        $this->db->from('users');
        $this->db->where('role', 0); // only members
        $this->db->group_start();
        $this->db->like('name', $query);
        $this->db->or_like('email', $query);
        $this->db->or_like('referral_code', $query);
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
}
