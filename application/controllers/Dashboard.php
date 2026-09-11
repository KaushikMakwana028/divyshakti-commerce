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
            
            $data['placed_orders'] = $this->db->where_in('status', ['placed', 'pending'])->count_all_results('orders');
            $data['pending_orders'] = $data['placed_orders'];
            $data['confirmed_orders'] = $this->db->where('status', 'confirmed')->count_all_results('orders');
            $data['packed_orders'] = $this->db->where('status', 'packed')->count_all_results('orders');
            $data['out_for_delivery_orders'] = $this->db->where('status', 'out_for_delivery')->count_all_results('orders');
            $data['delivered_orders'] = $this->db->where_in('status', ['delivered', 'completed'])->count_all_results('orders');
            $data['completed_orders'] = $this->db->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])->count_all_results('orders');
            $data['cancelled_orders'] = $this->db->where('status', 'cancelled')->count_all_results('orders');
            
            // Sum metrics
            $sales_sum = $this->db->select_sum('amount')->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])->get('orders')->row();
            $data['total_sales'] = (float)($sales_sum->amount ?? 0.00);

            $admin_comm_sum = $this->db->select_sum('amount')->where('source', 'admin_commission')->get('wallet_transactions')->row();
            $data['total_admin_comm'] = (float)($admin_comm_sum->amount ?? 0.00);

            $ref_comm_sum = $this->db->select_sum('amount')->where('source', 'referral_commission')->get('wallet_transactions')->row();
            $data['total_ref_comm'] = (float)($ref_comm_sum->amount ?? 0.00);

            // Pending deposit requests
            $pending_deposits = $this->db->select('COUNT(*) as count, SUM(amount) as total')->where('status', 'pending')->get('wallet_deposit_requests')->row();
            $data['pending_deposits_count'] = (int)($pending_deposits->count ?? 0);
            $data['pending_deposits_amount'] = (float)($pending_deposits->total ?? 0.00);

            // Charts: 1. Monthly Sales Trend (Last 6 Months)
            $data['sales_chart'] = $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_val, DATE_FORMAT(created_at, '%b %Y') as month_label, SUM(amount) as amount")
                ->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->group_by(["DATE_FORMAT(created_at, '%Y-%m')", "DATE_FORMAT(created_at, '%b %Y')"])
                ->order_by("month_val", "ASC")
                ->limit(6)
                ->get('orders')->result_array();

            // Charts: 2. Order Status distribution
            $data['status_chart'] = $this->db->select("status, COUNT(*) as count")
                ->group_by("status")
                ->get('orders')->result_array();

            // Charts: 3. Top Selling Products
            $data['top_products_chart'] = $this->db->select("products.name as product_name, SUM(orders.quantity) as qty, SUM(orders.amount) as amount")
                ->from('orders')
                ->join('products', 'products.id = orders.product_id', 'inner')
                ->where_in('orders.status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->group_by(["orders.product_id", "products.name"])
                ->order_by("amount", "DESC")
                ->limit(5)
                ->get()->result_array();

            // Charts: 4. User registration trend
            $data['reg_chart'] = $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_val, DATE_FORMAT(created_at, '%b %Y') as month_label, COUNT(*) as count")
                ->where('role', 0)
                ->group_by(["DATE_FORMAT(created_at, '%Y-%m')", "DATE_FORMAT(created_at, '%b %Y')"])
                ->order_by("month_val", "ASC")
                ->limit(6)
                ->get('users')->result_array();

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
            $data['my_placed_orders'] = $this->db->where('user_id', $user_id)->where_in('status', ['placed', 'pending'])->count_all_results('orders');
            $data['my_pending_orders'] = $data['my_placed_orders'];
            $data['my_confirmed_orders'] = $this->db->where('user_id', $user_id)->where('status', 'confirmed')->count_all_results('orders');
            $data['my_packed_orders'] = $this->db->where('user_id', $user_id)->where('status', 'packed')->count_all_results('orders');
            $data['my_out_for_delivery_orders'] = $this->db->where('user_id', $user_id)->where('status', 'out_for_delivery')->count_all_results('orders');
            $data['my_delivered_orders'] = $this->db->where('user_id', $user_id)->where_in('status', ['delivered', 'completed'])->count_all_results('orders');
            $data['my_completed_orders'] = $this->db->where('user_id', $user_id)->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])->count_all_results('orders');
            $data['my_cancelled_orders'] = $this->db->where('user_id', $user_id)->where('status', 'cancelled')->count_all_results('orders');

            // Total spent on purchases and total referral earnings
            $purchase_sum = $this->db->select_sum('amount')->where(['user_id' => $user_id, 'type' => 'debit', 'source' => 'purchase'])->get('wallet_transactions')->row();
            $data['my_total_spent'] = (float)($purchase_sum->amount ?? 0.00);

            $ref_earned = $this->db->select_sum('amount')->where(['user_id' => $user_id, 'type' => 'credit', 'source' => 'referral_commission'])->get('wallet_transactions')->row();
            $data['my_total_referral_earnings'] = (float)($ref_earned->amount ?? 0.00);

            // My pending deposit requests
            $my_pending_deposits = $this->db->select('COUNT(*) as count, SUM(amount) as total')->where(['user_id' => $user_id, 'status' => 'pending'])->get('wallet_deposit_requests')->row();
            $data['my_pending_deposits_count'] = (int)($my_pending_deposits->count ?? 0);
            $data['my_pending_deposits_amount'] = (float)($my_pending_deposits->total ?? 0.00);

            // Charts: Spent vs Referral Earnings vs Other Credits
            $my_spent = $this->db->select_sum('amount')->where(['user_id' => $user_id, 'type' => 'debit'])->get('wallet_transactions')->row()->amount ?? 0.00;
            $my_ref_earn = $this->db->select_sum('amount')->where(['user_id' => $user_id, 'type' => 'credit', 'source' => 'referral_commission'])->get('wallet_transactions')->row()->amount ?? 0.00;
            $my_other_credit = $this->db->select_sum('amount')->where(['user_id' => $user_id, 'type' => 'credit'])->where_in('source', ['admin_credit'])->get('wallet_transactions')->row()->amount ?? 0.00;
            
            $data['my_pie_chart'] = [
                'spent' => (float)$my_spent,
                'referral_earnings' => (float)$my_ref_earn,
                'other_credits' => (float)$my_other_credit
            ];

            // Charts: Monthly wallet activity trend
            $data['my_monthly_activity_chart'] = $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_val, DATE_FORMAT(created_at, '%b %Y') as month_label,
                SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) as credit,
                SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as debit")
                ->where('user_id', $user_id)
                ->group_by(["DATE_FORMAT(created_at, '%Y-%m')", "DATE_FORMAT(created_at, '%b %Y')"])
                ->order_by("month_val", "ASC")
                ->limit(6)
                ->get('wallet_transactions')->result_array();

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
