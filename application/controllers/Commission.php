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
     * Saves updated percentages for levels 1 to 12
     */
    public function update()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/commissions');
        }

        $percentages = $this->input->post('percentages', TRUE);
        if (!is_array($percentages)) {
            $this->session->set_flashdata('error', 'Invalid input format.');
            redirect('admin/commissions');
        }

        $total_percentage = 0.00;
        // Basic pre-validation: check numbers
        foreach ($percentages as $level => $val) {
            if (!is_numeric($val) || (float)$val < 0) {
                $this->session->set_flashdata('error', "Percentage for Level {$level} must be a non-negative number.");
                redirect('admin/commissions');
            }
            $total_percentage += (float)$val;
        }

        // Commission settings must sum to less than 100%
        if ($total_percentage >= 100.00) {
            $this->session->set_flashdata('error', "Total level commission allocation ({$total_percentage}%) must be strictly less than 100.00% to reserve a base cut for the admin.");
            redirect('admin/commissions');
        }

        $this->db->trans_begin();

        foreach ($percentages as $level => $val) {
            $this->db->update(
                'commission_settings',
                ['percentage' => (float)$val],
                ['level' => (int)$level]
            );
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to update commission settings due to a transaction database error.');
        } else {
            $this->db->trans_commit();
            $admin_cut = 100.00 - $total_percentage;
            $this->session->set_flashdata('success', "Commission settings updated successfully. Admin base cut is set to " . number_format($admin_cut, 2) . "%.");
        }

        redirect('admin/commissions');
    }
}
