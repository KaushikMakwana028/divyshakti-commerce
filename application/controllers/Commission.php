<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Commission extends CI_Controller
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
     * Lists commission settings for levels 1 to 12.
     * Guarantees levels 1 to 12 always exist and auto-seeds them if DB table was emptied/truncated.
     */
    public function index()
    {
        // 1. Ensure table commission_settings exists
        if (!$this->db->table_exists('commission_settings')) {
            try {
                $this->db->query("CREATE TABLE IF NOT EXISTS `commission_settings` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `level` int(11) NOT NULL,
                    `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
                    `percentage` decimal(5,2) DEFAULT 0.00,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `level_unique` (`level`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            } catch (\Throwable $e) {}
        } else {
            try {
                $cols = $this->db->list_fields('commission_settings');
                if (!in_array('amount', $cols)) {
                    $this->db->query("ALTER TABLE `commission_settings` ADD COLUMN `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `level`");
                }
            } catch (\Throwable $e) {}
        }

        // 2. Fetch existing settings from DB
        $db_rows = [];
        try {
            $db_rows = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        } catch (\Throwable $e) {}

        $existing_by_level = [];
        if (!empty($db_rows)) {
            foreach ($db_rows as $row) {
                $existing_by_level[(int)$row->level] = $row;
            }
        }

        // Default amounts for levels 1 to 12
        $default_amounts = [
            1 => '50.00', 2 => '35.00', 3 => '25.00', 4 => '20.00',
            5 => '15.00', 6 => '12.00', 7 => '10.00', 8 => '8.00',
            9 => '6.00',  10 => '4.00', 11 => '3.00', 12 => '2.00'
        ];

        // 3. Ensure all 12 levels are represented and auto-seed missing rows in DB
        $settings = [];
        for ($lvl = 1; $lvl <= 12; $lvl++) {
            if (isset($existing_by_level[$lvl])) {
                $settings[] = $existing_by_level[$lvl];
            } else {
                $def_amt = $default_amounts[$lvl] ?? '0.00';
                $new_obj = (object)[
                    'id'         => $lvl,
                    'level'      => $lvl,
                    'amount'     => $def_amt,
                    'percentage' => 0.00
                ];
                $settings[] = $new_obj;

                // Auto-seed into DB if table exists so DB data is automatically restored
                try {
                    $this->db->insert('commission_settings', [
                        'level'      => $lvl,
                        'amount'     => $def_amt,
                        'percentage' => 0.00
                    ]);
                } catch (\Throwable $e) {}
            }
        }

        $data['settings'] = $settings;
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('commission_settings_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Saves updated money amounts for levels 1 to 12.
     * Uses UPSERT (insert if missing, update if existing) to handle empty/truncated DB table.
     */
    public function update()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/commissions');
        }

        // Support 'amounts' (fixed money ₹) or fallback to 'percentages'
        $amounts = $this->input->post('amounts', TRUE);
        if (!is_array($amounts)) {
            $amounts = $this->input->post('percentages', TRUE);
        }

        if (!is_array($amounts)) {
            $this->session->set_flashdata('error', 'Invalid input format.');
            redirect('admin/commissions');
        }

        $total_amount = 0.00;
        // Basic pre-validation: check non-negative numbers
        foreach ($amounts as $level => $val) {
            if (!is_numeric($val) || (float)$val < 0) {
                $this->session->set_flashdata('error', "Commission amount for Level {$level} must be a non-negative number.");
                redirect('admin/commissions');
            }
            $total_amount += (float)$val;
        }

        // Ensure table commission_settings exists
        if (!$this->db->table_exists('commission_settings')) {
            try {
                $this->db->query("CREATE TABLE IF NOT EXISTS `commission_settings` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `level` int(11) NOT NULL,
                    `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
                    `percentage` decimal(5,2) DEFAULT 0.00,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `level_unique` (`level`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            } catch (\Throwable $e) {}
        } else {
            try {
                $cols = $this->db->list_fields('commission_settings');
                if (!in_array('amount', $cols)) {
                    $this->db->query("ALTER TABLE `commission_settings` ADD COLUMN `amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `level`");
                }
            } catch (\Throwable $e) {}
        }

        $this->db->trans_begin();

        // UPSERT: for each level 1 to 12, insert if not present or update if present
        for ($lvl = 1; $lvl <= 12; $lvl++) {
            $val = isset($amounts[$lvl]) ? $amounts[$lvl] : 0.00;
            $amt = round((float)$val, 2);

            $existing = $this->db->get_where('commission_settings', ['level' => $lvl])->row();
            if ($existing) {
                $this->db->update(
                    'commission_settings',
                    [
                        'amount'     => $amt,
                        'percentage' => 0.00
                    ],
                    ['level' => $lvl]
                );
            } else {
                $this->db->insert(
                    'commission_settings',
                    [
                        'level'      => $lvl,
                        'amount'     => $amt,
                        'percentage' => 0.00
                    ]
                );
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to update commission settings due to a database error.');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', "Commission settings updated successfully. Total level payout is ₹" . number_format($total_amount, 2) . ".");
        }

        redirect('admin/commissions');
    }
}
