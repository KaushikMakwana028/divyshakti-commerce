<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends CI_Controller
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
     * Lists all orders in the system with search, status filtering, and pagination
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'inner');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('users.name', $search);
            $this->db->or_like('products.name', $search);
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('orders.status', $status);
        }

        // Count query
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->select('orders.*, users.name as buyer_name, users.email as buyer_email, products.name as product_name');
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['orders'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('order_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Inspects details of a single order including commission payouts
     */
    public function detail($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            show_404();
        }

        // Fetch order details
        $this->db->select('orders.*, users.name as buyer_name, users.email as buyer_email, users.phone as buyer_phone, users.referral_code as buyer_ref, products.name as product_name, products.slug as product_slug, products.price as product_price, products.image as product_image');
        $this->db->from('orders');
        $this->db->join('users', 'users.id = orders.user_id', 'inner');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');
        $this->db->where('orders.id', (int)$id);
        $order = $this->db->get()->row();

        if (!$order) {
            show_404();
        }

        $data['order'] = $order;

        $data['shipping_address'] = null;
        if (!empty($order->address_id)) {
            $data['shipping_address'] = $this->General_model->getOne('user_addresses', ['id' => $order->address_id]);
        }

        // Fetch level commission setting chain payouts for this order
        $this->db->select('order_commissions.*, users.name as receiver_name, users.email as receiver_email, users.role as receiver_role');
        $this->db->from('order_commissions');
        $this->db->join('users', 'users.id = order_commissions.receiver_id', 'inner');
        $this->db->where('order_commissions.order_id', (int)$id);
        $this->db->order_by('order_commissions.level', 'ASC');
        $data['commissions'] = $this->db->get()->result();

        // Fetch admin remainder commission log if any
        $data['admin_commission'] = $this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'admin_commission'
        ]);

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('order_detail', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Updates an order status with transaction procedurals (payouts or reversals)
     */
    public function update_status($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/orders');
        }

        if (empty($id) || !is_numeric($id)) {
            $this->session->set_flashdata('error', 'Invalid Order ID.');
            redirect('admin/orders');
        }

        $new_status = $this->input->post('status', TRUE);
        if (!in_array($new_status, ['pending', 'confirmed', 'packed', 'out_for_delivery', 'delivered', 'cancelled'])) {
            $this->session->set_flashdata('error', 'Invalid status selected.');
            redirect("admin/orders/detail/{$id}");
        }

        $order = $this->General_model->getOne('orders', ['id' => (int)$id]);
        if (!$order) {
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
        }

        if ($order->status === $new_status) {
            $this->session->set_flashdata('error', 'Order already has this status.');
            redirect("admin/orders/detail/{$id}");
        }

        $buyer = $this->General_model->getOne('users', ['id' => $order->user_id]);
        $product = $this->General_model->getOne('products', ['id' => $order->product_id]);
        $order_amount = (float)$order->amount;

        $this->db->trans_begin();

        // Pending to Confirmed/Packed/Out for Delivery/Delivered Transition (Offline payment / manual approval)
        $paid_statuses = ['completed', 'confirmed', 'packed', 'out_for_delivery', 'delivered'];
        
        if ($order->status === 'pending' && in_array($new_status, $paid_statuses)) {
            // Verify buyer balance
            if ((float)$buyer->wallet_balance < $order_amount) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Buyer has insufficient wallet balance (₹' . number_format($order_amount, 2) . ' required). Please load user wallet first.');
                redirect("admin/orders/detail/{$id}");
            }

            // Verify stock
            if ((int)$product->stock < (int)$order->quantity) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Product has insufficient stock (' . $product->stock . ' units left). Cannot complete order.');
                redirect("admin/orders/detail/{$id}");
            }

            // 1. Deduct wallet balance from buyer
            $buyer_new_balance = (float)$buyer->wallet_balance - $order_amount;
            $this->db->update('users', ['wallet_balance' => $buyer_new_balance], ['id' => $buyer->id]);

            // 2. Insert debit wallet transaction log
            $this->db->insert('wallet_transactions', [
                'user_id'      => $buyer->id,
                'type'         => 'debit',
                'amount'       => $order_amount,
                'source'       => 'purchase',
                'reference_id' => (int)$id,
                'remark'       => "Manual order payment approval by Admin (Order ID: #{$id})",
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // 3. Deduct stock from products table
            $new_stock = (int)$product->stock - (int)$order->quantity;
            $this->db->update('products', ['stock' => $new_stock], ['id' => $product->id]);

            // 4. Update order status
            $this->db->update('orders', ['status' => $new_status, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);

            // 5. MLM level commission chain traversal
            $levels_percentage = [];
            $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
            foreach ($settings as $setting) {
                $levels_percentage[(int)$setting->level] = (float)$setting->percentage;
            }

            $total_allocated_percentage_sum = 0.00;
            $buyer_role = (int)$buyer->role;
            
            // Skip referral commissions if buyer is admin or has no sponsor
            $ancestor_id = ($buyer_role !== 1) ? $buyer->parent_id : null;

            if (!empty($ancestor_id)) {
                for ($level = 1; $level <= 12; $level++) {
                    if (empty($ancestor_id)) {
                        break;
                    }

                    $level_percentage = isset($levels_percentage[$level]) ? $levels_percentage[$level] : 0.00;
                    $ancestor = $this->General_model->getOne('users', ['id' => $ancestor_id]);
                    if (!$ancestor) {
                        break;
                    }

                    // Skip blocked referrers
                    if ((int)$ancestor->status === 0) {
                        $ancestor_id = $ancestor->parent_id;
                        continue;
                    }

                    // Skip admin referrers
                    if ((int)$ancestor->role === 1) {
                        $ancestor_id = $ancestor->parent_id;
                        continue;
                    }

                    $level_comm = $order_amount * ($level_percentage / 100);

                    // Credit referrer
                    $ancestor_new_balance = (float)$ancestor->wallet_balance + $level_comm;
                    $this->db->update('users', ['wallet_balance' => $ancestor_new_balance], ['id' => $ancestor->id]);

                    // Payout transactions log
                    $this->db->insert('wallet_transactions', [
                        'user_id'      => $ancestor->id,
                        'type'         => 'credit',
                        'amount'       => $level_comm,
                        'source'       => 'referral_commission',
                        'reference_id' => (int)$id,
                        'remark'       => "Referral commission from level {$level} purchase (Order ID: #{$id})",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);

                    // Payout order log
                    $this->db->insert('order_commissions', [
                        'order_id'    => (int)$id,
                        'buyer_id'    => $buyer->id,
                        'receiver_id' => $ancestor->id,
                        'level'       => $level,
                        'amount'      => $level_comm,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

                    $total_allocated_percentage_sum += $level_percentage;
                    $ancestor_id = $ancestor->parent_id;
                }
            }

            // 6. Admin remainder cut
            $admin_percentage = 100.00 - $total_allocated_percentage_sum;
            $admin_commission = $order_amount * ($admin_percentage / 100);

            $this->db->where('role', 1);
            $this->db->order_by('id', 'ASC');
            $this->db->limit(1);
            $admin = $this->db->get('users')->row();

            if ($admin && $admin_commission > 0) {
                $admin_new_balance = (float)$admin->wallet_balance + $admin_commission;
                $this->db->update('users', ['wallet_balance' => $admin_new_balance], ['id' => $admin->id]);

                $this->db->insert('wallet_transactions', [
                    'user_id'      => $admin->id,
                    'type'         => 'credit',
                    'amount'       => $admin_commission,
                    'source'       => 'admin_commission',
                    'reference_id' => (int)$id,
                    'remark'       => "Admin commission cut (remainder: {$admin_percentage}%) for Order ID: #{$id}",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 2. Transition from any Paid status (confirmed, packed, out_for_delivery, delivered) to Cancelled (Reverse everything)
        elseif (in_array($order->status, $paid_statuses) && $new_status === 'cancelled') {
            // 1. Refund buyer balance
            $buyer_new_balance = (float)$buyer->wallet_balance + $order_amount;
            $this->db->update('users', ['wallet_balance' => $buyer_new_balance], ['id' => $buyer->id]);

            // 2. Insert refund wallet transaction log
            $this->db->insert('wallet_transactions', [
                'user_id'      => $buyer->id,
                'type'         => 'credit',
                'amount'       => $order_amount,
                'source'       => 'admin_credit',
                'reference_id' => (int)$id,
                'remark'       => "Refund for cancelled Order #{$id} by Admin",
                'created_at'   => date('Y-m-d H:i:s')
            ]);

            // 3. Restore product stock
            $new_stock = (int)$product->stock + (int)$order->quantity;
            $this->db->update('products', ['stock' => $new_stock], ['id' => $product->id]);

            // 4. Reverse MLM level commissions
            $commissions = $this->db->get_where('order_commissions', ['order_id' => (int)$id])->result();
            foreach ($commissions as $comm) {
                $receiver = $this->General_model->getOne('users', ['id' => $comm->receiver_id]);
                if ($receiver) {
                    $receiver_new_balance = (float)$receiver->wallet_balance - (float)$comm->amount;
                    $this->db->update('users', ['wallet_balance' => $receiver_new_balance], ['id' => $receiver->id]);

                    // Insert debit reversal transaction
                    $this->db->insert('wallet_transactions', [
                        'user_id'      => $receiver->id,
                        'type'         => 'debit',
                        'amount'       => (float)$comm->amount,
                        'source'       => 'admin_debit',
                        'reference_id' => (int)$id,
                        'remark'       => "Reversal of Level {$comm->level} referral commission for cancelled Order #{$id}",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // 5. Reverse Admin commission cut
            $admin_txn = $this->General_model->getOne('wallet_transactions', [
                'reference_id' => (int)$id,
                'source'       => 'admin_commission'
            ]);

            if ($admin_txn) {
                $this->db->where('role', 1);
                $this->db->order_by('id', 'ASC');
                $this->db->limit(1);
                $admin = $this->db->get('users')->row();

                if ($admin) {
                    $admin_new_balance = (float)$admin->wallet_balance - (float)$admin_txn->amount;
                    $this->db->update('users', ['wallet_balance' => $admin_new_balance], ['id' => $admin->id]);

                    // Log debit reversal
                    $this->db->insert('wallet_transactions', [
                        'user_id'      => $admin->id,
                        'type'         => 'debit',
                        'amount'       => (float)$admin_txn->amount,
                        'source'       => 'admin_debit',
                        'reference_id' => (int)$id,
                        'remark'       => "Reversal of Admin commission cut for cancelled Order #{$id}",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // Delete order commissions records
            $this->db->delete('order_commissions', ['order_id' => (int)$id]);

            // 6. Update order status
            $this->db->update('orders', ['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);
        }

        // 3. Transition between Paid statuses (confirmed -> packed -> out_for_delivery -> delivered) - simple status update
        elseif (in_array($order->status, $paid_statuses) && in_array($new_status, $paid_statuses)) {
            $this->db->update('orders', ['status' => $new_status, 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);
        }

        // 4. Pending to Cancelled Transition (Simple cancel, no money transactions done yet)
        elseif ($order->status === 'pending' && $new_status === 'cancelled') {
            $this->db->update('orders', ['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);
        }

        // Any other transition is blocked
        else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', "Transition from {$order->status} to {$new_status} is not allowed.");
            redirect("admin/orders/detail/{$id}");
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Status modification failed due to a database integrity error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Order status successfully updated to " . strtoupper($new_status) . ".");
        }

        redirect("admin/orders/detail/{$id}");
    }
}
