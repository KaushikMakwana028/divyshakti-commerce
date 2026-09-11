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
}

