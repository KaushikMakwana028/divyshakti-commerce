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
            // Month & Year Filter (default: current month & current year)
            $month_input = $this->input->get('month', TRUE);
            $year_input  = $this->input->get('year', TRUE);

            $current_month = (int)date('m');
            $current_year  = (int)date('Y');

            $is_all_months = ($month_input === 'all');
            
            if ($is_all_months) {
                $selected_month = 'all';
                $month_label = 'All Months';
            } elseif (!empty($month_input) && is_numeric($month_input) && (int)$month_input >= 1 && (int)$month_input <= 12) {
                $selected_month = (int)$month_input;
                $month_label = date('F', mktime(0, 0, 0, $selected_month, 10));
            } else {
                $selected_month = $current_month;
                $month_label = date('F', mktime(0, 0, 0, $selected_month, 10));
            }

            if (!empty($year_input) && is_numeric($year_input) && (int)$year_input >= 2020 && (int)$year_input <= 2035) {
                $selected_year = (int)$year_input;
            } else {
                $selected_year = $current_year;
            }

            $data['selected_month'] = $selected_month;
            $data['selected_year']  = $selected_year;
            $data['month_label']    = $month_label;
            $data['is_all_months']  = $is_all_months;

            // Compute Date Range for queries
            if ($is_all_months) {
                $start_date = sprintf('%04d-01-01 00:00:00', $selected_year);
                $end_date   = sprintf('%04d-12-31 23:59:59', $selected_year);
            } else {
                $start_date = sprintf('%04d-%02d-01 00:00:00', $selected_year, (int)$selected_month);
                $end_date   = date('Y-m-t 23:59:59', strtotime(sprintf('%04d-%02d-01', $selected_year, (int)$selected_month)));
            }

            $data['start_date'] = $start_date;
            $data['end_date']   = $end_date;

            // Global totals (catalog & all-time directory)
            $data['total_all_members'] = $this->db->where('role', 0)->count_all_results('users');
            $data['total_products']    = $this->db->count_all_results('products');

            // Month-specific member signups
            $data['month_new_members'] = $this->db
                ->where('role', 0)
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('users');
            $data['total_members']     = $data['month_new_members'];

            // Filtered Orders Counts
            $data['total_orders'] = $this->db
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');
            
            $data['placed_orders'] = $this->db
                ->where_in('status', ['placed', 'pending'])
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');
            $data['pending_orders'] = $data['placed_orders'];

            $data['confirmed_orders'] = $this->db
                ->where('status', 'confirmed')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');

            $data['packed_orders'] = $this->db
                ->where('status', 'packed')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');

            $data['out_for_delivery_orders'] = $this->db
                ->where('status', 'out_for_delivery')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');

            $data['delivered_orders'] = $this->db
                ->where_in('status', ['delivered', 'completed'])
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');

            $data['completed_orders'] = $this->db
                ->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');

            $data['cancelled_orders'] = $this->db
                ->where('status', 'cancelled')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->count_all_results('orders');
            
            // Filtered Sum metrics
            $sales_sum = $this->db->select_sum('amount')
                ->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->get('orders')->row();
            $data['total_sales'] = (float)($sales_sum->amount ?? 0.00);

            $admin_comm_sum = $this->db->select_sum('amount')
                ->where('source', 'admin_commission')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->get('wallet_transactions')->row();
            $data['total_admin_comm'] = (float)($admin_comm_sum->amount ?? 0.00);

            $ref_comm_sum = $this->db->select_sum('amount')
                ->where('source', 'referral_commission')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->get('wallet_transactions')->row();
            $data['total_ref_comm'] = (float)($ref_comm_sum->amount ?? 0.00);

            // Filtered pending deposit requests
            $pending_deposits = $this->db->select('COUNT(*) as count, SUM(amount) as total')
                ->where('status', 'pending')
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->get('wallet_deposit_requests')->row();
            $data['pending_deposits_count'] = (int)($pending_deposits->count ?? 0);
            $data['pending_deposits_amount'] = (float)($pending_deposits->total ?? 0.00);

            // Charts: 1. Monthly Sales Trend (6 Months window anchor)
            $anchor_ym = $is_all_months ? sprintf('%04d-12-01', $selected_year) : sprintf('%04d-%02d-01', $selected_year, (int)$selected_month);
            $six_months_start = date('Y-m-01 00:00:00', strtotime("$anchor_ym -5 months"));
            $six_months_end   = date('Y-m-t 23:59:59', strtotime($anchor_ym));

            $data['sales_chart'] = $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_val, DATE_FORMAT(created_at, '%b %Y') as month_label, SUM(amount) as amount")
                ->where_in('status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->where('created_at >=', $six_months_start)
                ->where('created_at <=', $six_months_end)
                ->group_by(["DATE_FORMAT(created_at, '%Y-%m')", "DATE_FORMAT(created_at, '%b %Y')"])
                ->order_by("month_val", "ASC")
                ->get('orders')->result_array();

            // Charts: 2. Order Status distribution for the selected period
            $data['status_chart'] = $this->db->select("status, COUNT(*) as count")
                ->where('created_at >=', $start_date)
                ->where('created_at <=', $end_date)
                ->group_by("status")
                ->get('orders')->result_array();

            // Charts: 3. Top Selling Products for the selected period
            $data['top_products_chart'] = $this->db->select("products.name as product_name, SUM(orders.quantity) as qty, SUM(orders.amount) as amount")
                ->from('orders')
                ->join('products', 'products.id = orders.product_id', 'inner')
                ->where_in('orders.status', ['confirmed', 'packed', 'out_for_delivery', 'delivered', 'completed'])
                ->where('orders.created_at >=', $start_date)
                ->where('orders.created_at <=', $end_date)
                ->group_by(["orders.product_id", "products.name"])
                ->order_by("amount", "DESC")
                ->limit(5)
                ->get()->result_array();

            // Charts: 4. User registration trend (6 Months window anchor)
            $data['reg_chart'] = $this->db->select("DATE_FORMAT(created_at, '%Y-%m') as month_val, DATE_FORMAT(created_at, '%b %Y') as month_label, COUNT(*) as count")
                ->where('role', 0)
                ->where('created_at >=', $six_months_start)
                ->where('created_at <=', $six_months_end)
                ->group_by(["DATE_FORMAT(created_at, '%Y-%m')", "DATE_FORMAT(created_at, '%b %Y')"])
                ->order_by("month_val", "ASC")
                ->get('users')->result_array();

            // Filtered Recent Orders for the selected period
            $this->db->select('orders.*, users.name as buyer_name, products.name as product_name');
            $this->db->from('orders');
            $this->db->join('users', 'users.id = orders.user_id', 'inner');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->where('orders.created_at >=', $start_date);
            $this->db->where('orders.created_at <=', $end_date);
            $this->db->order_by('orders.id', 'DESC');
            $this->db->limit(5);
            $data['recent_orders'] = $this->db->get()->result();

            // Fallback if no orders in selected period
            if (empty($data['recent_orders'])) {
                $this->db->select('orders.*, users.name as buyer_name, products.name as product_name');
                $this->db->from('orders');
                $this->db->join('users', 'users.id = orders.user_id', 'inner');
                $this->db->join('products', 'products.id = orders.product_id', 'inner');
                $this->db->order_by('orders.id', 'DESC');
                $this->db->limit(5);
                $data['recent_orders'] = $this->db->get()->result();
                $data['is_fallback_recent_orders'] = true;
            } else {
                $data['is_fallback_recent_orders'] = false;
            }

            // Filtered Recent Members for the selected period
            $this->db->where('role', 0);
            $this->db->where('created_at >=', $start_date);
            $this->db->where('created_at <=', $end_date);
            $this->db->order_by('id', 'DESC');
            $this->db->limit(5);
            $data['recent_members'] = $this->db->get('users')->result();

            if (empty($data['recent_members'])) {
                $this->db->where('role', 0);
                $this->db->order_by('id', 'DESC');
                $this->db->limit(5);
                $data['recent_members'] = $this->db->get('users')->result();
                $data['is_fallback_recent_members'] = true;
            } else {
                $data['is_fallback_recent_members'] = false;
            }
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
