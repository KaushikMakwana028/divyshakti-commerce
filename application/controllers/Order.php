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
            $clean_search = trim($search);
            $clean_id = ltrim($clean_search, '#');
            $this->db->group_start();
            if (is_numeric($clean_id) && (int)$clean_id > 0) {
                $this->db->where('orders.id', (int)$clean_id);
                $this->db->or_like('users.name', $clean_search);
                $this->db->or_like('users.email', $clean_search);
                $this->db->or_like('products.name', $clean_search);
            } else {
                $this->db->like('users.name', $clean_search);
                $this->db->or_like('users.email', $clean_search);
                $this->db->or_like('products.name', $clean_search);
            }
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('orders.status', $status);
        }

        // Count query
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->select('orders.*, users.name as buyer_name, users.email as buyer_email, users.phone as buyer_phone, products.name as product_name, products.image as product_image');
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['orders'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        // Overall KPI Metrics for Dashboard Header
        $data['kpi_total_orders'] = $this->db->count_all('orders');
        $revenue_row = $this->db->select_sum('amount')->where_not_in('status', ['cancelled', 'pending'])->get('orders')->row();
        $data['kpi_total_revenue'] = (float)($revenue_row->amount ?? 0);
        $data['kpi_delivered_orders'] = $this->db->where_in('status', ['delivered', 'completed'])->from('orders')->count_all_results();
        $data['kpi_pending_orders'] = $this->db->where_in('status', ['pending', 'placed'])->from('orders')->count_all_results();

        // Breakdown counts for quick status filter pills
        $status_counts = [
            'all'              => 0,
            'pending'          => 0,
            'placed'           => 0,
            'confirmed'        => 0,
            'packed'           => 0,
            'out_for_delivery' => 0,
            'delivered'        => 0,
            'cancelled'        => 0
        ];
        $counts_query = $this->db->select('status, COUNT(*) as cnt')->group_by('status')->get('orders')->result();
        foreach ($counts_query as $row) {
            $st = $row->status;
            $status_counts['all'] += (int)$row->cnt;
            if ($st === 'completed') {
                $status_counts['delivered'] += (int)$row->cnt;
            } elseif (isset($status_counts[$st])) {
                $status_counts[$st] = (int)$row->cnt;
            }
        }
        $data['status_counts'] = $status_counts;

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
        $this->db->select('orders.*, users.name as buyer_name, users.email as buyer_email, users.phone as buyer_phone, users.referral_code as buyer_ref, products.name as product_name, products.price as product_price, products.image as product_image');
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

        // Check if payment was already deducted at checkout via wallet
        $data['is_already_paid'] = (bool)$this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'purchase'
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

        $new_status = strtolower(trim((string)$this->input->post('status', TRUE)));
        $valid_all_statuses = ['pending', 'placed', 'confirmed', 'packed', 'out_for_delivery', 'delivered', 'cancelled'];
        if (!in_array($new_status, $valid_all_statuses)) {
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

        // Terminal states cannot change
        if ($order->status === 'delivered') {
            $this->session->set_flashdata('error', "Order #{$id} is already delivered (terminal status) and cannot be changed.");
            redirect("admin/orders/detail/{$id}");
        }
        if ($order->status === 'cancelled') {
            $this->session->set_flashdata('error', "Order #{$id} is already cancelled (terminal status) and cannot be changed.");
            redirect("admin/orders/detail/{$id}");
        }

        // Pending orders cannot move forward (payment-gated)
        if ($order->status === 'pending' && $new_status !== 'cancelled') {
            $this->session->set_flashdata('error', "Order #{$id} is awaiting buyer payment (pending) and cannot be moved forward. Only paid orders can proceed in fulfillment.");
            redirect("admin/orders/detail/{$id}");
        }

        $allowed_transitions = [
            'pending'          => ['cancelled'],
            'placed'           => ['confirmed', 'cancelled'],
            'confirmed'        => ['packed', 'cancelled'],
            'packed'           => ['out_for_delivery', 'cancelled'],
            'out_for_delivery' => ['delivered', 'cancelled']
        ];

        if (!isset($allowed_transitions[$order->status]) || !in_array($new_status, $allowed_transitions[$order->status])) {
            $this->session->set_flashdata('error', "Transition from {$order->status} to {$new_status} is not allowed.");
            redirect("admin/orders/detail/{$id}");
        }

        $is_already_paid = (bool)$this->General_model->getOne('wallet_transactions', [
            'reference_id' => (int)$id,
            'source'       => 'purchase'
        ]);

        $this->db->trans_begin();

        if ($new_status === 'cancelled') {
            if ($is_already_paid) {
                $buyer = $this->General_model->getOne('users', ['id' => (int)$order->user_id]);
                $order_amount = (float)$order->amount;

                // 1. Refund buyer balance
                if ($buyer) {
                    $this->db->set('wallet_balance', 'wallet_balance + ' . $order_amount, FALSE);
                    $this->db->where('id', (int)$buyer->id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$buyer->id,
                        'type'         => 'credit',
                        'amount'       => $order_amount,
                        'source'       => 'admin_credit',
                        'reference_id' => (int)$id,
                        'remark'       => "Refund for cancelled Order #{$id} by Admin",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }

                // 2. Restore product stock
                $current_prod = $this->General_model->getOne('products', ['id' => (int)$order->product_id]);
                if ($current_prod) {
                    $new_stock = (int)$current_prod->stock + (int)$order->quantity;
                    $this->db->update('products', ['stock' => $new_stock], ['id' => (int)$order->product_id]);
                }

                // 3. Reverse MLM level commissions
                $commissions = $this->db->get_where('order_commissions', ['order_id' => (int)$id])->result();
                foreach ($commissions as $comm) {
                    $receiver = $this->General_model->getOne('users', ['id' => (int)$comm->receiver_id]);
                    if ($receiver) {
                        $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$comm->amount, FALSE);
                        $this->db->where('id', (int)$receiver->id);
                        $this->db->update('users');

                        $this->db->insert('wallet_transactions', [
                            'user_id'      => (int)$receiver->id,
                            'type'         => 'debit',
                            'amount'       => (float)$comm->amount,
                            'source'       => 'admin_debit',
                            'reference_id' => (int)$id,
                            'remark'       => "Reversal of Level {$comm->level} referral commission for cancelled Order #{$id}",
                            'created_at'   => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                // 4. Reverse Admin commission cut
                $admin_txn = $this->General_model->getOne('wallet_transactions', [
                    'reference_id' => (int)$id,
                    'source'       => 'admin_commission'
                ]);

                if ($admin_txn) {
                    $this->db->set('wallet_balance', 'wallet_balance - ' . (float)$admin_txn->amount, FALSE);
                    $this->db->where('id', (int)$admin_txn->user_id);
                    $this->db->update('users');

                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$admin_txn->user_id,
                        'type'         => 'debit',
                        'amount'       => (float)$admin_txn->amount,
                        'source'       => 'admin_debit',
                        'reference_id' => (int)$id,
                        'remark'       => "Reversal of Admin commission cut for cancelled Order #{$id}",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);
                }

                // 5. Delete order commissions records
                $this->db->delete('order_commissions', ['order_id' => (int)$id]);
            }

            $cancel_data = [
                'status'     => 'cancelled',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            try {
                $cancel_data['commission_distributed'] = 0;
            } catch (\Throwable $e) {}
            $this->db->update('orders', $cancel_data, ['id' => (int)$order->id]);
        } else {
            // Forward status transition
            $this->db->update('orders', [
                'status'     => $new_status,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => (int)$order->id]);
            // Note: MLM commissions are distributed exclusively once upon member account activation
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Status modification failed due to a database integrity error.');
        } else {
            $this->db->trans_commit();
            $success_msg = ($new_status === 'cancelled')
                ? "Order #{$id} has been cancelled" . ($is_already_paid ? ' and paid amount refunded to buyer.' : '.')
                : "Order status successfully updated to " . strtoupper($new_status) . ".";
            $this->session->set_flashdata('success', $success_msg);
        }

        redirect("admin/orders/detail/{$id}");
    }

    /**
     * Deletes an order and cleans up associated records safely
     */
    public function delete($id = null)
    {
        if (empty($id) || !is_numeric($id)) {
            if ($this->input->is_ajax_request() || (strpos($this->input->server('HTTP_ACCEPT') ?? '', 'application/json') !== false)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(400)
                    ->set_output(json_encode(['status' => false, 'message' => 'Invalid Order ID.']));
                return;
            }
            $this->session->set_flashdata('error', 'Invalid Order ID.');
            redirect('admin/orders');
        }

        $order = $this->General_model->getOne('orders', ['id' => (int)$id]);
        if (!$order) {
            if ($this->input->is_ajax_request() || (strpos($this->input->server('HTTP_ACCEPT') ?? '', 'application/json') !== false)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(404)
                    ->set_output(json_encode(['status' => false, 'message' => 'Order not found.']));
                return;
            }
            $this->session->set_flashdata('error', 'Order not found.');
            redirect('admin/orders');
        }

        $this->db->trans_begin();

        // If order was placed/confirmed/packed and not yet delivered or cancelled, restore product stock
        if (in_array($order->status, ['placed', 'confirmed', 'packed']) && (int)$order->quantity > 0) {
            $current_prod = $this->General_model->getOne('products', ['id' => (int)$order->product_id]);
            if ($current_prod) {
                $new_stock = (int)$current_prod->stock + (int)$order->quantity;
                $this->db->update('products', ['stock' => $new_stock], ['id' => (int)$order->product_id]);
            }
        }

        // Delete associated commissions
        $this->db->delete('order_commissions', ['order_id' => (int)$id]);

        // Delete order record
        $this->db->delete('orders', ['id' => (int)$id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            if ($this->input->is_ajax_request() || (strpos($this->input->server('HTTP_ACCEPT') ?? '', 'application/json') !== false)) {
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(500)
                    ->set_output(json_encode(['status' => false, 'message' => 'Failed to delete order due to a database error.']));
                return;
            }
            $this->session->set_flashdata('error', 'Failed to delete order.');
            redirect('admin/orders');
        }

        $this->db->trans_commit();

        $success_msg = "Order #{$id} has been permanently deleted.";
        if ($this->input->is_ajax_request() || (strpos($this->input->server('HTTP_ACCEPT') ?? '', 'application/json') !== false)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => true, 'message' => $success_msg]));
            return;
        }

        $this->session->set_flashdata('success', $success_msg);
        redirect('admin/orders');
    }
}
