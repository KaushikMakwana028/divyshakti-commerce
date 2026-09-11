<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
    }

    /**
     * Redirects root/home to admin dashboard if logged in, otherwise to admin/login
     */
    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('admin/dashboard');
        } else {
            redirect('admin/login');
        }
    }

    /**
     * Handles /admin redirect logic
     */
    public function admin()
    {
        if ($this->session->userdata('logged_in')) {
            redirect('admin/dashboard');
        } else {
            redirect('admin/login');
        }
    }

    /**
     * Public Privacy Policy Page
     * Direct URL: divy-shakti/privacy_policy (accessible without login)
     */
    public function privacy_policy()
    {
        $page = cms_get_page('privacy_policy');
        $this->load->view('public_privacy_policy', [
            'page' => $page
        ]);
    }

    /**
     * Public Terms & Conditions Page
     * Direct URL: divy-shakti/terms_conditions (accessible without login)
     */
    public function terms_conditions()
    {
        $page = cms_get_page('terms_conditions');
        $this->load->view('public_terms_conditions', [
            'page' => $page
        ]);
    }

    /**
     * Public Account Deletion Request Page
     * Direct URL: divy-shakti/delete_account (accessible without login)
     */
    public function delete_account()
    {
        $user_id = $this->session->userdata('user_id');
        $current_user = null;

        if ($user_id) {
            $current_user = $this->db->get_where('users', ['id' => (int)$user_id])->row();
        }

        $this->load->view('public_delete_account', [
            'logged_user' => $current_user
        ]);
    }

    /**
     * Process Account Deletion Request
     * Handles both authenticated web session deletion and unauthenticated credential/OTP verified deletion.
     */
    public function process_delete_account()
    {
        $is_ajax = $this->input->is_ajax_request();

        // 1. Check if user is logged in via web session
        $session_user_id = $this->session->userdata('user_id');
        $user_to_delete = null;

        if ($session_user_id) {
            $user_to_delete = $this->db->get_where('users', ['id' => (int)$session_user_id])->row();
        } else {
            // 2. Unauthenticated deletion: Validate phone & password / verification
            $phone = trim((string)$this->input->post('phone'));
            $password = trim((string)$this->input->post('password'));
            $confirm_text = trim((string)$this->input->post('confirm_text'));

            if (empty($phone)) {
                $msg = 'Please enter your registered mobile number.';
                if ($is_ajax) {
                    return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
                }
                $this->session->set_flashdata('error', $msg);
                redirect('delete_account');
            }

            $user_to_delete = $this->db->get_where('users', ['phone' => $phone])->row();

            if (!$user_to_delete) {
                $msg = 'No registered account found with mobile number ' . htmlspecialchars($phone) . '.';
                if ($is_ajax) {
                    return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
                }
                $this->session->set_flashdata('error', $msg);
                redirect('delete_account');
            }

            // Verify password if user has a password set
            if (!empty($user_to_delete->password) && !empty($password)) {
                if (!password_verify($password, $user_to_delete->password)) {
                    $msg = 'Incorrect account password provided. Verification failed.';
                    if ($is_ajax) {
                        return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
                    }
                    $this->session->set_flashdata('error', $msg);
                    redirect('delete_account');
                }
            }
        }

        if (!$user_to_delete) {
            $msg = 'Account identification failed. Please provide your registered mobile number.';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
            }
            $this->session->set_flashdata('error', $msg);
            redirect('delete_account');
        }

        // Prevent admin / non-member accounts from being deleted
        if ((int)($user_to_delete->role ?? 0) !== 0 || (int)$user_to_delete->id === 1) {
            $msg = 'Administrative master accounts cannot be deleted through this public portal. Platform administrators are permanently protected.';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
            }
            $this->session->set_flashdata('error', $msg);
            redirect('delete_account');
        }

        $target_id = (int)$user_to_delete->id;
        $target_phone = $user_to_delete->phone;

        // Perform clean cascade delete
        $this->db->trans_start();

        // 1. Delete associated OTP records
        if (!empty($target_phone)) {
            $this->db->delete('user_login_otps', ['phone' => $target_phone]);
            $this->db->delete('user_registration_otps', ['mobile' => $target_phone]);
        }

        // 2. Delete the user (Foreign key ON DELETE CASCADE wipes addresses, cart, orders, deposits, transactions; ON DELETE SET NULL decouples children)
        $this->db->delete('users', ['id' => $target_id]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $msg = 'An error occurred during account deletion. Please try again or contact support.';
            if ($is_ajax) {
                return $this->output->set_content_type('application/json')->set_output(json_encode(['status' => false, 'message' => $msg]));
            }
            $this->session->set_flashdata('error', $msg);
            redirect('delete_account');
        }

        // Destroy session if logged in
        $this->session->sess_destroy();

        $success_msg = 'Your Divy Shakti account and personal data have been permanently deleted.';
        if ($is_ajax) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => true,
                'message' => $success_msg
            ]));
        }

        $this->session->set_flashdata('success', $success_msg);
        redirect('delete_account?deleted=1');
    }
}
