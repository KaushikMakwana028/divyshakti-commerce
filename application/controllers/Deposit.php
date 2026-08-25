<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Deposit extends CI_Controller
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
     * Lists all deposit requests with filters and pagination
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        $this->db->from('wallet_deposit_requests');
        $this->db->join('users', 'users.id = wallet_deposit_requests.user_id', 'inner');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('users.name', $search);
            $this->db->or_like('users.email', $search);
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('wallet_deposit_requests.status', $status);
        }

        // Count query
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->select('wallet_deposit_requests.*, users.name as user_name, users.email as user_email, users.phone as user_phone');
        $this->db->order_by('wallet_deposit_requests.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['requests'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('deposit_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Approves a deposit request and credits user's wallet
     */
    public function approve($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/deposits');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Request ID.');
            redirect('admin/deposits');
        }

        $request = $this->General_model->getOne('wallet_deposit_requests', ['id' => (int)$id]);
        if (!$request) {
            $this->session->set_flashdata('error', 'Deposit request not found.');
            redirect('admin/deposits');
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'This request has already been processed.');
            redirect('admin/deposits');
        }

        $user = $this->General_model->getOne('users', ['id' => $request->user_id]);
        if (!$user) {
            $this->session->set_flashdata('error', 'User account not found.');
            redirect('admin/deposits');
        }

        $admin_id = $this->session->userdata('user_id');

        $this->db->trans_begin();

        // 1. Update request status to approved
        $this->db->update('wallet_deposit_requests', [
            'status'     => 'approved',
            'action_by'  => $admin_id,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $request->id]);

        // 2. Increment user's wallet balance
        $new_balance = (float)$user->wallet_balance + (float)$request->amount;
        $this->db->update('users', ['wallet_balance' => $new_balance], ['id' => $user->id]);

        // 3. Log credit wallet transaction
        $remark = "Approved wallet deposit request ID: #{$id}";
        if ($request->remark) {
            $remark .= " (" . $request->remark . ")";
        }
        $this->db->insert('wallet_transactions', [
            'user_id'      => $user->id,
            'type'         => 'credit',
            'amount'       => (float)$request->amount,
            'source'       => 'admin_credit',
            'remark'       => $remark,
            'reference_id' => $request->id,
            'added_by'     => $admin_id,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to approve request due to database transaction error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Deposit request #{$id} approved. ₹" . number_format($request->amount, 2) . " credited to user wallet.");
        }

        redirect('admin/deposits');
    }

    /**
     * Rejects a deposit request
     */
    public function reject($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/deposits');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Request ID.');
            redirect('admin/deposits');
        }

        $request = $this->General_model->getOne('wallet_deposit_requests', ['id' => (int)$id]);
        if (!$request) {
            $this->session->set_flashdata('error', 'Deposit request not found.');
            redirect('admin/deposits');
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'This request has already been processed.');
            redirect('admin/deposits');
        }

        $admin_id = $this->session->userdata('user_id');

        // Update status to rejected
        $update_data = [
            'status'     => 'rejected',
            'action_by'  => $admin_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->db->update('wallet_deposit_requests', $update_data, ['id' => $request->id])) {
            $this->session->set_flashdata('success', "Deposit request #{$id} has been rejected.");
        } else {
            $this->session->set_flashdata('error', 'Database update failed.');
        }

        redirect('admin/deposits');
    }
}
