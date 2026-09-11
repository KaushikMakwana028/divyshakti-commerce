<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commission extends CI_Controller
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
     * Lists commission settings for levels 1 to 12
     */
    public function index()
    {
        $data['settings'] = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('commission_settings_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Saves updated money amounts for levels 1 to 12
     */
    public function update()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/commissions');
        }

        // Support 'amounts' (fixed money ₹) or fallback to 'percentages'
        $amounts = $this->input->post('amounts', TRUE);
        if (!is_array($amounts)) {
            $amounts = $this->input->post('percentages', TRUE);
        }

        if (!is_array($amounts)) {
            $this->session->set_flashdata('error', 'Invalid input format.');
            redirect('admin/commissions');
        }

        $total_amount = 0.00;
        // Basic pre-validation: check non-negative numbers
        foreach ($amounts as $level => $val) {
            if (!is_numeric($val) || (float)$val < 0) {
                $this->session->set_flashdata('error', "Commission amount for Level {$level} must be a non-negative number.");
                redirect('admin/commissions');
            }
            $total_amount += (float)$val;
        }

        $this->db->trans_begin();

        foreach ($amounts as $level => $val) {
            $amt = round((float)$val, 2);
            $this->db->update(
                'commission_settings',
                [
                    'amount'     => $amt,
                    'percentage' => 0.00
                ],
                ['level' => (int)$level]
            );
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to update commission settings due to a transaction database error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Commission settings updated successfully. Total level payout is ₹" . number_format($total_amount, 2) . ".");
        }

        redirect('admin/commissions');
    }
}
