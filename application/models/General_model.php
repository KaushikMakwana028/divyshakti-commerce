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
        // Only consider standard 7-digit sequence (e.g. '0002001' to '0099999')
        // Ignores any out-of-range or large numbers (such as 15215145) from corrupting the sequence
        $this->db->select_max("CAST(custom_id AS UNSIGNED)", "max_id");
        $this->db->where("custom_id IS NOT NULL");
        $this->db->where("custom_id !=", "");
        $this->db->where("role", 0);
        $this->db->where("LENGTH(custom_id)", 7);
        $this->db->where("CAST(custom_id AS UNSIGNED) <", 1000000);
        $this->db->like("custom_id", "00", "after");
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
     * Distributes referral commissions across all eligible upline levels when an admin activates a member.
     * Guaranteed idempotent - runs only ONCE per member when the account is activated.
     * Fixed money amounts configured in commission_settings are credited directly to each upline member's wallet.
     *
     * @param int $user_id
     * @return bool
     */
    public function distribute_activation_commissions($user_id)
    {
        $user_id = (int)$user_id;
        $member = $this->getOne('users', ['id' => $user_id]);
        if (!$member || (int)$member->role === 1) {
            return false;
        }

        // Must be active
        if ((int)($member->is_profile_active ?? 0) !== 1) {
            return false;
        }

        // Idempotency check 1: Column flag
        if (isset($member->is_commission_distributed) && (int)$member->is_commission_distributed === 1) {
            return false;
        }

        // Idempotency check 2: Check existing wallet_transactions for this member activation
        $has_txns = $this->db->where([
            'reference_id' => $user_id,
            'source'       => 'referral_commission'
        ])->like('remark', 'member activation')->count_all_results('wallet_transactions') > 0;

        if ($has_txns) {
            try {
                $this->db->update('users', ['is_commission_distributed' => 1], ['id' => $user_id]);
            } catch (\Throwable $e) {}
            return false;
        }

        // Fetch fixed amount settings per level from commission_settings
        $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        $levels_amount = [];
        foreach ($settings as $setting) {
            $levels_amount[(int)$setting->level] = (float)($setting->amount ?? $setting->percentage ?? 0.00);
        }

        $ancestor_id = $member->parent_id;
        if (empty($ancestor_id)) {
            // Member has no upline sponsor, mark commission distributed and exit
            try {
                $this->db->update('users', ['is_commission_distributed' => 1], ['id' => $user_id]);
            } catch (\Throwable $e) {}
            return true;
        }

        $this->db->trans_begin();

        $member_display = !empty($member->name) ? $member->name : ('Member #' . $member->id);
        $member_custom_id = !empty($member->custom_id) ? " ({$member->custom_id})" : "";

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

            $level_comm = round($fixed_amount, 2);

            if ($level_comm > 0) {
                // 1. Directly credit ancestor wallet balance
                $this->db->set('wallet_balance', 'wallet_balance + ' . $level_comm, FALSE);
                $this->db->where('id', (int)$ancestor->id);
                $this->db->update('users');

                // 2. Insert wallet transaction record
                $this->db->insert('wallet_transactions', [
                    'user_id'      => (int)$ancestor->id,
                    'type'         => 'credit',
                    'amount'       => $level_comm,
                    'source'       => 'referral_commission',
                    'reference_id' => $user_id,
                    'remark'       => "Referral commission (₹" . number_format($level_comm, 2) . ") from Level {$level} member activation ({$member_display}{$member_custom_id})",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);

                // 3. Insert into member_commissions audit log
                try {
                    $this->db->insert('member_commissions', [
                        'member_id'   => $user_id,
                        'receiver_id' => (int)$ancestor->id,
                        'level'       => $level,
                        'amount'      => $level_comm,
                        'created_at'  => date('Y-m-d H:i:s')
                    ]);
                } catch (\Throwable $e) {}
            }

            $ancestor_id = $ancestor->parent_id;
        }

        // Mark member as having commission distributed
        $this->db->update('users', ['is_commission_distributed' => 1], ['id' => $user_id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    /**
     * Deprecated: Order-based commission distribution is disabled.
     * Referral commissions are now distributed exclusively upon member activation.
     *
     * @param int $order_id
     * @return bool
     */
    public function distribute_order_commissions($order_id)
    {
        return false;
    }

    /**
     * Validates whether a referrer user is eligible to sponsor other members.
     * Requires:
     * 1. Referrer exists
     * 2. Account status is unblocked (status == 1)
     * 3. Profile is active / approved by Admin (is_profile_active == 1)
     *
     * @param object|array $referrer
     * @param string|null &$error_message
     * @return bool
     */
    public function isReferrerEligible($referrer, &$error_message = null)
    {
        if (!$referrer) {
            $error_message = 'Invalid referral code. Referrer not found.';
            return false;
        }

        // Cast to object if array
        if (is_array($referrer)) {
            $referrer = (object)$referrer;
        }

        if (isset($referrer->status) && (int)$referrer->status === 0) {
            $error_message = 'This referral code belongs to a suspended account.';
            return false;
        }

        if (empty($referrer->is_profile_active) || (int)$referrer->is_profile_active !== 1) {
            $error_message = 'This referral code cannot be used because the referrer\'s profile is not active yet.';
            return false;
        }

        return true;
    }

    /**
     * Retrieve system setting value by key with optional fallback default
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->db->get_where('system_settings', ['setting_key' => $key])->row();
        if ($setting && isset($setting->setting_value)) {
            return $setting->setting_value;
        }
        return $default;
    }

    /**
     * Set or update a system setting value by key
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function setSetting($key, $value)
    {
        $existing = $this->db->get_where('system_settings', ['setting_key' => $key])->row();
        if ($existing) {
            return $this->db->update('system_settings', [
                'setting_value' => $value,
                'updated_at'    => date('Y-m-d H:i:s')
            ], ['setting_key' => $key]);
        } else {
            return $this->db->insert('system_settings', [
                'setting_key'   => $key,
                'setting_value' => $value,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Get minimum withdrawal amount configured by admin
     *
     * @return float
     */
    public function getMinWithdrawAmount()
    {
        $val = $this->getSetting('min_withdraw_amount', '500.00');
        return max(1.0, (float)$val);
    }

    /**
     * Set minimum withdrawal amount
     *
     * @param float|int|string $amount
     * @return bool
     */
    public function setMinWithdrawAmount($amount)
    {
        $amount = max(1.0, (float)$amount);
        return $this->setSetting('min_withdraw_amount', number_format($amount, 2, '.', ''));
    }
}


