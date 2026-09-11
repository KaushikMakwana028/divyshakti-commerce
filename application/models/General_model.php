<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getOne($table, $where)
    {
        $query = $this->db->get_where($table, $where);
        return $query->row();
    }


    public function getAll($table, $where = '')
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get($table);
        return $query->result();
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function update($table, $where, $data)
    {
        return $this->db->update($table, $data, $where);
    }
    public function getCount($table, $where = [], $isActive = null)
    {
        if (!is_null($isActive)) {
            $where['isActive'] = $isActive;
        }

        if (!empty($where)) {
            $query = $this->db->select()
                ->where($where)
                ->get($table);
        } else {
            $query = $this->db->select()
                ->get($table);
        }

        return $query->num_rows();
    }
    public function getData($table, $selectFields = '*', $where = [])
    {
        $this->db->select($selectFields);
        $this->db->from($table);
        if (!empty($where)) {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCurrentMonthCustomers()
    {
        $query = $this->db->query("
            SELECT * FROM customers 
            WHERE MONTH(purchase_date) = MONTH(CURRENT_DATE()) 
            AND YEAR(purchase_date) = YEAR(CURRENT_DATE())
        ");

        return $query->result_array(); // Returns all matching records as an array
    }
    public function getCustomersAfter90Days()
    {
        $this->db->where('DATE(purchase_date) <=', date('Y-m-d', strtotime('-90 days')));
        $query = $this->db->get('customers');
        return $query->result();
    }
    public function get_all_categories($search = null)
    {
        if (!empty($search)) {
            $this->db->like('name', $search);
        }
        return $this->db->order_by('parent_id ASC, name ASC')
            ->get('categories')
            ->result();
    }

    /**
     * Generate sequential unique member ID starting from '0002001' (7-digit format)
     */
    public function generateUniqueCustomId()
    {
        $min_start = 2001;
        $this->db->select_max("CAST(custom_id AS UNSIGNED)", "max_id");
        $this->db->where("custom_id IS NOT NULL");
        $this->db->where("custom_id !=", "");
        $this->db->where("role", 0);
        $query = $this->db->get('users');
        $row = $query->row();

        $next_num = ($row && (int)$row->max_id >= $min_start) ? ((int)$row->max_id + 1) : $min_start;
        $custom_id = str_pad($next_num, 7, '0', STR_PAD_LEFT);

        // Ensure collision safety
        while ($this->getOne('users', ['custom_id' => $custom_id])) {
            $next_num++;
            $custom_id = str_pad($next_num, 7, '0', STR_PAD_LEFT);
        }

        return $custom_id;
    }

    /**
     * Calculate profile completion stats
     * 14 required fields for 100% completion (including gender)
     */
    public function calculateProfileCompletion($user)
    {
        $required_fields = [
            'name'                => 'Name',
            'phone'               => 'Phone',
            'gender'              => 'Gender',
            'address'             => 'Address',
            'aadhar_number'       => 'Aadhar Number',
            'aadhar_image'        => 'Aadhar Image',
            'pan_number'          => 'PAN Number',
            'pan_image'           => 'PAN Image',
            'account_holder_name' => 'Account Holder Name',
            'bank_name'           => 'Bank Name',
            'account_number'      => 'Account Number',
            'ifsc_code'           => 'IFSC Code',
            'account_type'        => 'Account Type',
            'branch_name'         => 'Branch Name',
        ];

        $completed_count = 0;
        $missing_fields = [];

        foreach ($required_fields as $field => $label) {
            $val = isset($user->$field) ? trim((string)$user->$field) : '';
            if ($val !== '') {
                $completed_count++;
            } else {
                $missing_fields[] = $field;
            }
        }

        $total_fields = count($required_fields);
        $percentage = (int) round(($completed_count / $total_fields) * 100);
        if ($percentage > 100) {
            $percentage = 100;
        }

        $is_completed = ($completed_count === $total_fields);

        return [
            'total_fields'    => $total_fields,
            'completed_count' => $completed_count,
            'percentage'      => $percentage,
            'is_completed'    => $is_completed,
            'missing_fields'  => $missing_fields
        ];
    }

    /**
     * Distributes referral and admin commissions for a delivered order.
     * Guaranteed idempotent - runs once per order upon reaching 'delivered' status.
     * Decided fixed commission amount is credited per level (NOT multiplied by quantity).
     *
     * @param int $order_id
     * @return bool
     */
    public function distribute_order_commissions($order_id)
    {
        $order_id = (int)$order_id;
        $order = $this->getOne('orders', ['id' => $order_id]);
        if (!$order) {
            return false;
        }

        // Must be in delivered or completed status
        if (!in_array($order->status, ['delivered', 'completed'])) {
            return false;
        }

        // Idempotency check 1: Check commission_distributed flag if column exists
        if (isset($order->commission_distributed) && (int)$order->commission_distributed === 1) {
            return false;
        }

        // Idempotency check 2: Check existing order_commissions or admin_commission transactions
        $has_commissions = $this->db->where('order_id', $order_id)->count_all_results('order_commissions') > 0;
        $has_admin_txn = $this->db->where([
            'reference_id' => $order_id,
            'source'       => 'admin_commission'
        ])->count_all_results('wallet_transactions') > 0;

        if ($has_commissions || $has_admin_txn) {
            // Ensure flag is updated if table has column
            try {
                $this->db->update('orders', ['commission_distributed' => 1], ['id' => $order_id]);
            } catch (\Throwable $e) {}
            return false;
        }

        $buyer = $this->getOne('users', ['id' => (int)$order->user_id]);
        if (!$buyer) {
            return false;
        }

        $order_amount = (float)$order->amount;

        // Fetch fixed amount settings per level from commission_settings
        $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        $levels_amount = [];
        foreach ($settings as $setting) {
            $levels_amount[(int)$setting->level] = (float)($setting->amount ?? $setting->percentage ?? 0.00);
        }

        // Find lowest-ID active admin for remainder cut
        $this->db->where('role', 1);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        $admin = $this->db->get('users')->row();

        $total_allocated_commission_sum = 0.00;
        $ancestor_id = ((int)$buyer->role !== 1) ? $buyer->parent_id : null;

        if (!empty($ancestor_id)) {
            for ($level = 1; $level <= 12; $level++) {
                if (empty($ancestor_id)) {
                    break;
                }

                $fixed_amount = isset($levels_amount[$level]) ? $levels_amount[$level] : 0.00;
                $ancestor = $this->getOne('users', ['id' => (int)$ancestor_id]);
                if (!$ancestor) {
                    break;
                }

                // Skip blocked ancestor (status == 0)
                if ((int)$ancestor->status === 0) {
                    $ancestor_id = $ancestor->parent_id;
                    continue;
                }

                // Skip admin ancestor (role == 1)
                if ((int)$ancestor->role === 1) {
                    $ancestor_id = $ancestor->parent_id;
                    continue;
                }

                // Decided commission amount ONLY - never multiply by quantity
                $level_comm = round($fixed_amount, 2);

                if ($level_comm > 0) {
                    // Credit ancestor wallet balance
                    $this->db->set('wallet_balance', 'wallet_balance + ' . $level_comm, FALSE);
                    $this->db->where('id', (int)$ancestor->id);
                    $this->db->update('users');

                    // Wallet transaction log
                    $this->db->insert('wallet_transactions', [
                        'user_id'      => (int)$ancestor->id,
                        'type'         => 'credit',
                        'amount'       => $level_comm,
                        'source'       => 'referral_commission',
                        'reference_id' => $order_id,
                        'remark'       => "Referral commission (₹" . number_format($fixed_amount, 2) . ") from level {$level} purchase (Order ID: #{$order_id})",
                        'created_at'   => date('Y-m-d H:i:s')
                    ]);

                    // Order commissions row
                    $this->db->insert('order_commissions', [
                        'order_id'    => $order_id,
                        'buyer_id'    => (int)$order->user_id,
                        'receiver_id' => (int)$ancestor->id,
                        'level'       => $level,
                        'amount'      => $level_comm,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);

                    $total_allocated_commission_sum += $level_comm;
                }

                $ancestor_id = $ancestor->parent_id;
            }
        }

        // Admin remainder cut: order total minus total allocated referral commissions
        $admin_commission = max(0, round($order_amount - $total_allocated_commission_sum, 2));

        if ($admin && $admin_commission > 0) {
            $this->db->set('wallet_balance', 'wallet_balance + ' . $admin_commission, FALSE);
            $this->db->where('id', (int)$admin->id);
            $this->db->update('users');

            $this->db->insert('wallet_transactions', [
                'user_id'      => (int)$admin->id,
                'type'         => 'credit',
                'amount'       => $admin_commission,
                'source'       => 'admin_commission',
                'reference_id' => $order_id,
                'remark'       => "Admin commission remainder cut for Order ID: #{$order_id}",
                'created_at'   => date('Y-m-d H:i:s')
            ]);
        }

        // Mark commission_distributed = 1
        try {
            $this->db->update('orders', ['commission_distributed' => 1], ['id' => $order_id]);
        } catch (\Throwable $e) {
            // In case column does not exist yet on DB
        }

        return true;
    }
}

