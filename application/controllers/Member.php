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

        $this->load->view('templates/header', $data);
        $this->load->view('member_detail', $data);
        $this->load->view('templates/footer');
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
}
