<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Renders the premium brand landing page (root route)
     */
    public function index()
    {
        $this->load->view('landing_view');
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
}
