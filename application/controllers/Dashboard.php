<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        $this->load->helper('url');
        
        // Enforce authentication on all dashboard routes
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * Renders the user dashboard home page
     */
    public function index()
    {
        // Refresh user data from database to ensure fresh balance and status
        $user_id = $this->session->userdata('user_id');
        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user || (int)$user->status === 0) {
            $this->session->sess_destroy();
            redirect('admin/login');
        }

        $data['user'] = $user;

        // If admin role = 1, fetch admin panel statistics
        if ((int)$user->role === 1) {
            // Count metrics
            $data['total_members'] = $this->db->where('role', 0)->count_all_results('users');
            $data['total_products'] = $this->db->count_all_results('products');
            $data['total_orders'] = $this->db->count_all_results('orders');
            
            $data['pending_orders'] = $this->db->where('status', 'pending')->count_all_results('orders');
            $data['completed_orders'] = $this->db->where('status', 'completed')->count_all_results('orders');
            $data['cancelled_orders'] = $this->db->where('status', 'cancelled')->count_all_results('orders');
            
            // Sum metrics
            $sales_sum = $this->db->select_sum('amount')->where('status', 'completed')->get('orders')->row();
            $data['total_sales'] = (float)($sales_sum->amount ?? 0.00);

            $admin_comm_sum = $this->db->select_sum('amount')->where('source', 'admin_commission')->get('wallet_transactions')->row();
            $data['total_admin_comm'] = (float)($admin_comm_sum->amount ?? 0.00);

            // Recent 5 Orders
            $this->db->select('orders.*, users.name as buyer_name, products.name as product_name');
            $this->db->from('orders');
            $this->db->join('users', 'users.id = orders.user_id', 'inner');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->order_by('orders.id', 'DESC');
            $this->db->limit(5);
            $data['recent_orders'] = $this->db->get()->result();

            // Recent 5 Registered Members
            $this->db->where('role', 0);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(5);
            $data['recent_members'] = $this->db->get('users')->result();
        } 
        // If member role = 0, fetch member console statistics
        else {
            $data['direct_referrals_count'] = $this->db->where('parent_id', $user_id)->count_all_results('users');
            
            // Total orders bought by this user
            $data['my_total_orders'] = $this->db->where('user_id', $user_id)->count_all_results('orders');
            
            // Recent 5 orders bought by this user
            $this->db->select('orders.*, products.name as product_name');
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->where('orders.user_id', $user_id);
            $this->db->order_by('orders.id', 'DESC');
            $this->db->limit(5);
            $data['my_recent_orders'] = $this->db->get()->result();

            // Recent 5 wallet logs for this user
            $this->db->where('user_id', $user_id);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(5);
            $data['my_recent_txns'] = $this->db->get('wallet_transactions')->result();
        }

        $this->load->view('templates/header', $data);
        $this->load->view('dashboard_view', $data);
        $this->load->view('templates/footer');
    }
}
