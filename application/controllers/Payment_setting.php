<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payment_setting extends CI_Controller
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
     * Display Payment Settings page
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['user'] = $this->General_model->getOne('users', ['id' => $user_id]);
        $data['settings'] = $this->General_model->getPaymentSettings();

        $this->load->view('templates/header', $data);
        $this->load->view('payment_settings_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Process Payment Settings Update
     */
    public function update()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/payment_settings');
        }

        $upi_id              = trim($this->input->post('payment_upi_id', TRUE) ?: '');
        $upi_name            = trim($this->input->post('payment_upi_name', TRUE) ?: '');
        $bank_name           = trim($this->input->post('payment_bank_name', TRUE) ?: '');
        $account_holder_name = trim($this->input->post('payment_account_holder_name', TRUE) ?: '');
        $account_number      = trim($this->input->post('payment_account_number', TRUE) ?: '');
        $ifsc_code           = strtoupper(trim($this->input->post('payment_ifsc_code', TRUE) ?: ''));
        $account_type        = trim($this->input->post('payment_account_type', TRUE) ?: 'Current');
        $branch_name         = trim($this->input->post('payment_branch_name', TRUE) ?: '');
        $instructions        = trim($this->input->post('payment_instructions', TRUE) ?: '');
        $min_deposit_amount  = $this->input->post('min_deposit_amount', TRUE);
        $remove_qr           = $this->input->post('remove_qr', TRUE);

        // Update text settings
        $this->General_model->setSetting('payment_upi_id', $upi_id);
        $this->General_model->setSetting('payment_upi_name', $upi_name);
        $this->General_model->setSetting('payment_bank_name', $bank_name);
        $this->General_model->setSetting('payment_account_holder_name', $account_holder_name);
        $this->General_model->setSetting('payment_account_number', $account_number);
        $this->General_model->setSetting('payment_ifsc_code', $ifsc_code);
        $this->General_model->setSetting('payment_account_type', $account_type);
        $this->General_model->setSetting('payment_branch_name', $branch_name);
        $this->General_model->setSetting('payment_instructions', $instructions);

        if ($min_deposit_amount !== null && is_numeric($min_deposit_amount) && (float)$min_deposit_amount >= 1) {
            $this->General_model->setSetting('min_deposit_amount', number_format((float)$min_deposit_amount, 2, '.', ''));
        }

        // Handle QR Code Removal
        if (!empty($remove_qr)) {
            $existing_qr = $this->General_model->getSetting('payment_qr_code', '');
            if (!empty($existing_qr) && file_exists(FCPATH . $existing_qr)) {
                @unlink(FCPATH . $existing_qr);
            }
            $this->General_model->setSetting('payment_qr_code', '');
        }

        // Handle QR Code Upload
        if (!empty($_FILES['payment_qr_code']['name'])) {
            $upload_path = './uploads/payment_settings/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size']      = 5120; // 5MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('payment_qr_code')) {
                // Delete old QR image if present
                $old_qr = $this->General_model->getSetting('payment_qr_code', '');
                if (!empty($old_qr) && file_exists(FCPATH . $old_qr)) {
                    @unlink(FCPATH . $old_qr);
                }

                $upload_data = $this->upload->data();
                $qr_path = 'uploads/payment_settings/' . $upload_data['file_name'];
                $this->General_model->setSetting('payment_qr_code', $qr_path);
            } else {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Settings saved, but QR Code upload failed: ' . $error);
                redirect('admin/payment_settings');
                return;
            }
        }

        $this->session->set_flashdata('success', 'Payment settings updated successfully.');
        redirect('admin/payment_settings');
    }
}
