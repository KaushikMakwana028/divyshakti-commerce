<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Withdraw extends CI_Controller
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
     * Lists all withdrawal requests with search, filters, pagination and stats
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        // Base query setup
        $this->db->from('wallet_withdraw_requests');
        $this->db->join('users', 'users.id = wallet_withdraw_requests.user_id', 'inner');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('users.name', $search);
            $this->db->or_like('users.email', $search);
            $this->db->or_like('users.phone', $search);
            $this->db->or_like('users.custom_id', $search);
            $this->db->or_like('wallet_withdraw_requests.account_number', $search);
            $this->db->or_like('wallet_withdraw_requests.bank_name', $search);
            $this->db->group_end();
        }

        if ($status !== '' && $status !== null) {
            $this->db->where('wallet_withdraw_requests.status', $status);
        }

        // Count query
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query with joined user and moderator info
        $this->db->select('wallet_withdraw_requests.*, 
            users.custom_id as user_custom_id,
            users.name as user_name, 
            users.email as user_email, 
            users.phone as user_phone,
            users.wallet_balance as user_wallet_balance,
            admin_users.name as action_by_name');
        $this->db->join('users as admin_users', 'admin_users.id = wallet_withdraw_requests.action_by', 'left');
        $this->db->order_by('wallet_withdraw_requests.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['requests'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        // Stats summary for admin dashboard widgets
        $stats_pending = $this->db->select('COUNT(*) as count, COALESCE(SUM(amount), 0) as total')
            ->where('status', 'pending')->get('wallet_withdraw_requests')->row();
        $stats_approved = $this->db->select('COUNT(*) as count, COALESCE(SUM(amount), 0) as total')
            ->where('status', 'approved')->get('wallet_withdraw_requests')->row();
        $stats_rejected = $this->db->select('COUNT(*) as count, COALESCE(SUM(amount), 0) as total')
            ->where('status', 'rejected')->get('wallet_withdraw_requests')->row();

        $data['stats'] = [
            'pending_count'       => (int)($stats_pending->count ?? 0),
            'pending_amount'      => (float)($stats_pending->total ?? 0),
            'approved_count'      => (int)($stats_approved->count ?? 0),
            'approved_amount'     => (float)($stats_approved->total ?? 0),
            'rejected_count'      => (int)($stats_rejected->count ?? 0),
            'rejected_amount'     => (float)($stats_rejected->total ?? 0),
            'total_count'         => $total_rows,
        ];

        // Minimum withdrawal threshold
        $data['min_withdraw_amount'] = $this->General_model->getMinWithdrawAmount();

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('withdraw_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Approves a withdrawal request and cuts the requested amount from member's wallet
     */
    public function approve($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/withdrawals');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Request ID.');
            redirect('admin/withdrawals');
        }

        $request = $this->General_model->getOne('wallet_withdraw_requests', ['id' => (int)$id]);
        if (!$request) {
            $this->session->set_flashdata('error', 'Withdrawal request not found.');
            redirect('admin/withdrawals');
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'This withdrawal request has already been processed.');
            redirect('admin/withdrawals');
        }

        $user = $this->General_model->getOne('users', ['id' => $request->user_id]);
        if (!$user) {
            $this->session->set_flashdata('error', 'Member account not found.');
            redirect('admin/withdrawals');
        }

        $withdraw_amount = (float)$request->amount;
        $current_balance = (float)$user->wallet_balance;

        // Enforce balance sufficiency at approval time
        if ($current_balance < $withdraw_amount) {
            $this->session->set_flashdata(
                'error',
                "Cannot approve request #{$id}: Member's current wallet balance (₹" . number_format($current_balance, 2) . ") is lower than requested withdrawal amount (₹" . number_format($withdraw_amount, 2) . ")."
            );
            redirect('admin/withdrawals');
        }

        $admin_id = $this->session->userdata('user_id');
        $admin_remark = trim($this->input->post('admin_remark', TRUE) ?: '');

        $this->db->trans_begin();

        // 1. Mark request as approved
        $this->db->update('wallet_withdraw_requests', [
            'status'       => 'approved',
            'admin_remark' => $admin_remark ?: 'Approved by admin',
            'action_by'    => $admin_id,
            'processed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s')
        ], ['id' => $request->id]);

        // 2. Cut amount from member's wallet
        $new_balance = $current_balance - $withdraw_amount;
        $this->db->update('users', [
            'wallet_balance' => $new_balance,
            'updated_at'     => date('Y-m-d H:i:s')
        ], ['id' => $user->id]);

        // 3. Log debit transaction in wallet_transactions
        $txn_remark = "Withdrawal request #{$id} approved.";
        if (!empty($request->bank_name)) {
            $txn_remark .= " [Bank: {$request->bank_name}, A/C: {$request->account_number}]";
        }
        if (!empty($admin_remark)) {
            $txn_remark .= " Note: {$admin_remark}";
        }

        $this->db->insert('wallet_transactions', [
            'user_id'      => $user->id,
            'type'         => 'debit',
            'amount'       => $withdraw_amount,
            'source'       => 'withdrawal',
            'reference_id' => $request->id,
            'remark'       => $txn_remark,
            'added_by'     => $admin_id,
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to approve withdrawal request due to database transaction error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Withdrawal request #{$id} approved successfully. ₹" . number_format($withdraw_amount, 2) . " has been deducted from member wallet.");
        }

        redirect('admin/withdrawals');
    }

    /**
     * Rejects a withdrawal request (wallet is NOT cut)
     */
    public function reject($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/withdrawals');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Request ID.');
            redirect('admin/withdrawals');
        }

        $request = $this->General_model->getOne('wallet_withdraw_requests', ['id' => (int)$id]);
        if (!$request) {
            $this->session->set_flashdata('error', 'Withdrawal request not found.');
            redirect('admin/withdrawals');
        }

        if ($request->status !== 'pending') {
            $this->session->set_flashdata('error', 'This withdrawal request has already been processed.');
            redirect('admin/withdrawals');
        }

        $admin_id = $this->session->userdata('user_id');
        $admin_remark = trim($this->input->post('admin_remark', TRUE) ?: '');

        $update_data = [
            'status'       => 'rejected',
            'admin_remark' => $admin_remark ?: 'Rejected by administrator',
            'action_by'    => $admin_id,
            'processed_at' => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        if ($this->db->update('wallet_withdraw_requests', $update_data, ['id' => $request->id])) {
            $this->session->set_flashdata('success', "Withdrawal request #{$id} has been rejected. No amount was deducted from member's wallet.");
        } else {
            $this->session->set_flashdata('error', 'Failed to reject withdrawal request due to database error.');
        }

        redirect('admin/withdrawals');
    }

    /**
     * Set Minimum Withdrawal Amount
     */
    public function set_min_amount()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/withdrawals');
        }

        $min_amount = $this->input->post('min_withdraw_amount', TRUE);
        if ($min_amount === null || $min_amount === '' || !is_numeric($min_amount) || (float)$min_amount < 1) {
            $this->session->set_flashdata('error', 'Please enter a valid minimum withdrawal amount (minimum ₹1.00).');
            redirect('admin/withdrawals');
        }

        $amount = (float)$min_amount;
        if ($this->General_model->setMinWithdrawAmount($amount)) {
            $this->session->set_flashdata('success', 'Minimum withdrawal amount successfully updated to ₹' . number_format($amount, 2) . '.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update minimum withdrawal amount.');
        }

        redirect('admin/withdrawals');
    }
}
