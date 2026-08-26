<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        // Ensure default JSON responses don't output html headers on errors
        $this->load->helper('url');
    }

    /**
     * Helper to output JSON responses and terminate
     */
    private function response($status, $message, $data = null, $status_code = 200)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode([
                'status' => (bool)$status,
                'message' => $message,
                'data' => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    /**
     * Helper to authenticate JWT token and ensure it's not blacklisted
     */
    private function check_auth()
    {
        // Extract Authorization header
        $auth_header = $this->input->get_request_header('Authorization', TRUE);
        if (!$auth_header) {
            // Fallback for Apache CGI/FastCGI environments where header is stripped
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
            } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $auth_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            }
        }

        if (!$auth_header) {
            $this->response(false, 'Authorization header missing', null, 401);
        }

        if (!preg_match('/Bearer\s(\S+)/', $auth_header, $matches)) {
            $this->response(false, 'Invalid Authorization header format', null, 401);
        }

        $token = $matches[1];

        // Check if token exists in blacklist
        $blacklisted = $this->General_model->getOne('token_blacklist', ['token' => $token]);
        if ($blacklisted) {
            $this->response(false, 'Token has been blacklisted', null, 401);
        }

        // Verify and decode JWT
        try {
            $jwt_secret = $this->config->item('jwt_secret') ?: 'DivyShaktiSecretJWTKey2026SuperSecureAndLongKey';
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($jwt_secret, 'HS256'));

            if (isset($decoded->exp) && $decoded->exp < time()) {
                $this->response(false, 'Token has expired', null, 401);
            }

            return [
                'decoded' => $decoded,
                'token' => $token
            ];
        } catch (\Exception $e) {
            $this->response(false, 'Unauthorized: ' . $e->getMessage(), null, 401);
        }
    }

    /**
     * Generate unique 8-character alphanumeric referral code
     */
    private function generate_unique_referral_code()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $chars[rand(0, strlen($chars) - 1)];
            }
            $exists = $this->General_model->getOne('users', ['referral_code' => $code]);
        } while ($exists);
        return $code;
    }

    /**
     * POST api/register
     */
    public function register()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $this->load->library('form_validation');

        // Validation Rules
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.email]', [
            'is_unique' => 'This email is already registered.'
        ]);
        $this->form_validation->set_rules('phone', 'Phone', 'required|trim|regex_match[/^[0-9]{10,15}$/]', [
            'regex_match' => 'The phone number must be between 10 and 15 digits.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        // Check referral code if provided
        $parent_id = null;
        $referral_code = $this->input->post('referral_code', TRUE);
        if (!empty($referral_code)) {
            $referrer = $this->General_model->getOne('users', ['referral_code' => $referral_code]);
            if (!$referrer) {
                $this->response(false, 'Invalid referral code. Referrer not found.', null, 400);
            }
            $parent_id = $referrer->id;
        }

        // Generate referral code for new user
        $new_referral_code = $this->generate_unique_referral_code();

        // Handle profile image upload
        $profile_image_path = null;
        if (!empty($_FILES['profile_image']['name'])) {
            $upload_path = './uploads/profile_images/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('profile_image')) {
                $this->response(false, $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();
                $profile_image_path = 'uploads/profile_images/' . $upload_data['file_name'];
            }
        }

        $insert_data = [
            'name'           => $this->input->post('name', TRUE),
            'email'          => $this->input->post('email', TRUE),
            'phone'          => $this->input->post('phone', TRUE),
            'password'       => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'profile_image'  => $profile_image_path,
            'address'        => $this->input->post('address', TRUE) ?: null,
            'role'           => 0,
            'referral_code'  => $new_referral_code,
            'parent_id'      => $parent_id,
            'wallet_balance' => 0.00,
            'status'         => 1,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $insert_id = $this->General_model->insert('users', $insert_data);

        if ($insert_id) {
            $this->response(true, 'Registration successful', [
                'id'            => $insert_id,
                'referral_code' => $new_referral_code
            ], 201);
        } else {
            $this->response(false, 'Database insert failed', null, 500);
        }
    }

    /**
     * POST api/login
     */
    public function login()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $email    = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->General_model->getOne('users', ['email' => $email]);

        if (!$user || !password_verify($password, $user->password)) {
            $this->response(false, 'Invalid email or password', null, 400);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact admin.', null, 403);
        }

        // Generate JWT token (expires in 24 hours)
        $issued_at       = time();
        $expiration_time = $issued_at + (24 * 60 * 60);
        $jwt_secret      = $this->config->item('jwt_secret') ?: 'DivyShaktiSecretJWTKey2026SuperSecureAndLongKey';

        $payload = [
            'iss'     => base_url(),
            'aud'     => base_url(),
            'iat'     => $issued_at,
            'exp'     => $expiration_time,
            'user_id' => (int)$user->id,
            'email'   => $user->email,
            'role'    => (int)$user->role
        ];

        try {
            $token = \Firebase\JWT\JWT::encode($payload, $jwt_secret, 'HS256');
        } catch (\Exception $e) {
            $this->response(false, 'Failed to generate token: ' . $e->getMessage(), null, 500);
        }

        // Return user details without password
        $user_data = [
            'id'             => (int)$user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'profile_image'  => $user->profile_image,
            'address'        => $user->address,
            'role'           => (int)$user->role,
            'referral_code'  => $user->referral_code,
            'parent_id'      => $user->parent_id ? (int)$user->parent_id : null,
            'wallet_balance' => (float)$user->wallet_balance,
            'status'         => (int)$user->status,
            'created_at'     => $user->created_at,
            'updated_at'     => $user->updated_at
        ];

        $this->response(true, 'Login successful', [
            'token' => $token,
            'user'  => $user_data
        ], 200);
    }

    /**
     * POST api/logout
     */
    public function logout()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // This will authenticate token and reject if expired/invalid/blacklisted
        $auth = $this->check_auth();

        $token   = $auth['token'];
        $decoded = $auth['decoded'];

        // Add to token blacklist table
        $blacklist_data = [
            'token'      => $token,
            'user_id'    => $decoded->user_id,
            'expires_at' => date('Y-m-d H:i:s', $decoded->exp),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->General_model->insert('token_blacklist', $blacklist_data);

        $this->response(true, 'Logout successful', null, 200);
    }

    /**
     * GET/POST api/get_profile
     * Authenticated endpoint to fetch the user's profile details
     */
    public function get_profile()
    {
        $auth = $this->check_auth();
        $user_id = $auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user) {
            $this->response(false, 'User not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        $user_data = [
            'id'             => (int)$user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'phone'          => $user->phone,
            'profile_image'  => $user->profile_image,
            'address'        => $user->address,
            'role'           => (int)$user->role,
            'referral_code'  => $user->referral_code,
            'parent_id'      => $user->parent_id ? (int)$user->parent_id : null,
            'wallet_balance' => (float)$user->wallet_balance,
            'status'         => (int)$user->status,
            'created_at'     => $user->created_at,
            'updated_at'     => $user->updated_at
        ];

        $this->response(true, 'Profile retrieved successfully', $user_data, 200);
    }

    /**
     * POST api/update_profile
     * Authenticated endpoint to partially update the user's profile
     */
    public function update_profile()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = $auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user) {
            $this->response(false, 'User not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $update_data = [];

        // Support partial updates: check if field is explicitly passed
        if (isset($_POST['name'])) {
            $name = trim($this->input->post('name', TRUE));
            if (empty($name)) {
                $this->response(false, 'Name field cannot be empty.', null, 400);
            }
            $update_data['name'] = $name;
        }

        if (isset($_POST['email'])) {
            $email = trim($this->input->post('email', TRUE));
            if (empty($email)) {
                $this->response(false, 'Email field cannot be empty.', null, 400);
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->response(false, 'Invalid email format.', null, 400);
            }
            // Check uniqueness excluding current user
            $email_check = $this->General_model->getOne('users', ['email' => $email, 'id !=' => $user_id]);
            if ($email_check) {
                $this->response(false, 'This email is already registered by another user.', null, 400);
            }
            $update_data['email'] = $email;
        }

        if (isset($_POST['phone'])) {
            $phone = trim($this->input->post('phone', TRUE));
            if (empty($phone)) {
                $this->response(false, 'Phone field cannot be empty.', null, 400);
            }
            if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
                $this->response(false, 'The phone number must be between 10 and 15 digits.', null, 400);
            }
            $update_data['phone'] = $phone;
        }

        if (isset($_POST['address'])) {
            $address = $this->input->post('address', TRUE);
            $update_data['address'] = ($address !== NULL && trim($address) !== '') ? trim($address) : null;
        }

        // Handle profile image upload
        if (!empty($_FILES['profile_image']['name'])) {
            $upload_path = './uploads/profile_images/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_image')) {
                $this->response(false, $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();

                // Delete old image if exists
                if (!empty($user->profile_image) && file_exists('./' . $user->profile_image)) {
                    @unlink('./' . $user->profile_image);
                }

                $update_data['profile_image'] = 'uploads/profile_images/' . $upload_data['file_name'];
            }
        }

        // Only update if there are changes submitted
        if (!empty($update_data)) {
            $update_data['updated_at'] = date('Y-m-d H:i:s');
            $this->General_model->update('users', ['id' => $user_id], $update_data);
        }

        // Return the fresh updated profile details
        $updated_user = $this->General_model->getOne('users', ['id' => $user_id]);

        $user_data = [
            'id'             => (int)$updated_user->id,
            'name'           => $updated_user->name,
            'email'          => $updated_user->email,
            'phone'          => $updated_user->phone,
            'profile_image'  => $updated_user->profile_image,
            'address'        => $updated_user->address,
            'role'           => (int)$updated_user->role,
            'referral_code'  => $updated_user->referral_code,
            'parent_id'      => $updated_user->parent_id ? (int)$updated_user->parent_id : null,
            'wallet_balance' => (float)$updated_user->wallet_balance,
            'status'         => (int)$updated_user->status,
            'created_at'     => $updated_user->created_at,
            'updated_at'     => $updated_user->updated_at
        ];

        $this->response(true, 'Profile updated successfully', $user_data, 200);
    }

    /**
     * GET/POST api/get_category_list
     * User-facing API endpoint to retrieve all active categories
     */
    public function get_category_list()
    {
        $categories = $this->General_model->getAll('categories', ['status' => 1]);

        $list = [];
        foreach ($categories as $cat) {
            $list[] = [
                'id'         => (int)$cat->id,
                'name'       => $cat->name,
                'slug'       => $cat->slug,
                'image'      => $cat->image ? base_url($cat->image) : null,
                'status'     => (int)$cat->status,
                'created_at' => $cat->created_at,
                'updated_at' => $cat->updated_at
            ];
        }

        $this->response(true, 'Categories retrieved successfully', $list, 200);
    }

    /**
     * GET/POST api/get_category_detail
     * User-facing API endpoint to retrieve details for a specific active category
     */
    public function get_category_detail()
    {
        $id = $this->input->post('id', TRUE) ?: $this->input->get('id', TRUE);
        $slug = $this->input->post('slug', TRUE) ?: $this->input->get('slug', TRUE);

        if (empty($id) && empty($slug)) {
            $this->response(false, 'Category id or slug is required.', null, 400);
        }

        $where = [];
        if (!empty($id)) {
            $where['id'] = $id;
        } else {
            $where['slug'] = $slug;
        }
        $where['status'] = 1; // view-only active categories

        $cat = $this->General_model->getOne('categories', $where);
        if (!$cat) {
            $this->response(false, 'Category not found or inactive.', null, 404);
        }

        $detail = [
            'id'         => (int)$cat->id,
            'name'       => $cat->name,
            'slug'       => $cat->slug,
            'image'      => $cat->image ? base_url($cat->image) : null,
            'status'     => (int)$cat->status,
            'created_at' => $cat->created_at,
            'updated_at' => $cat->updated_at
        ];

        $this->response(true, 'Category detail retrieved successfully', $detail, 200);
    }

    /**
     * GET api/products
     * User-facing API endpoint to retrieve list of active products with search, sorting, filtering, and pagination
     */
    public function get_product_list()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $search = $this->input->get('search', TRUE);
        $category_id = $this->input->get('category_id', TRUE);
        $min_price = $this->input->get('min_price', TRUE);
        $max_price = $this->input->get('max_price', TRUE);
        $sort_by = $this->input->get('sort_by', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Build total count query parameters
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.status', 1);

        if (!empty($search)) {
            $this->db->like('products.name', $search);
        }
        if (!empty($category_id)) {
            $this->db->where('products.category_id', (int)$category_id);
        }
        if ($min_price !== NULL && $min_price !== '') {
            $this->db->where('products.price >=', (float)$min_price);
        }
        if ($max_price !== NULL && $max_price !== '') {
            $this->db->where('products.price <=', (float)$max_price);
        }

        // Keep parameter conditions for next call
        $total = $this->db->count_all_results('', FALSE);

        // Apply sorting
        if ($sort_by === 'price_low') {
            $this->db->order_by('products.price', 'ASC');
        } elseif ($sort_by === 'price_high') {
            $this->db->order_by('products.price', 'DESC');
        } elseif ($sort_by === 'newest') {
            $this->db->order_by('products.id', 'DESC');
        } else {
            $this->db->order_by('products.id', 'DESC');
        }

        // Apply pagination
        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        $this->db->select('products.*, categories.name as category_name');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $products_raw = $query->result();

        $products = [];
        foreach ($products_raw as $prod) {
            $products[] = [
                'id'             => (int)$prod->id,
                'category_id'    => (int)$prod->category_id,
                'category_name'  => $prod->category_name,
                'name'           => $prod->name,
                'slug'           => $prod->slug,
                'description'    => $prod->description,
                'price'          => (float)$prod->price,
                'image'          => $prod->image ? base_url($prod->image) : null,
                'stock'          => (int)$prod->stock,
                'status'         => (int)$prod->status,
                'created_at'     => $prod->created_at,
                'updated_at'     => $prod->updated_at
            ];
        }

        $response_data = [
            'products' => $products,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ];

        $this->response(true, 'Products retrieved successfully', $response_data, 200);
    }

    /**
     * GET api/products/category/(:num)
     * User-facing API endpoint to retrieve products in a specific category with sorting, filtering, and pagination
     */
    public function get_products_by_category($category_id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $category_id = $category_id ?: ($this->input->get('category_id') ?: $this->input->post('category_id'));

        if (empty($category_id) || !is_numeric($category_id)) {
            $this->response(false, 'Invalid category id', null, 400);
        }

        // Validate that category exists and is active
        $category = $this->General_model->getOne('categories', ['id' => $category_id, 'status' => 1]);
        if (!$category) {
            $this->response(false, 'Category not found or inactive', null, 404);
        }

        $search = $this->input->get('search', TRUE);
        $min_price = $this->input->get('min_price', TRUE);
        $max_price = $this->input->get('max_price', TRUE);
        $sort_by = $this->input->get('sort_by', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        // Count query
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.status', 1);
        $this->db->where('products.category_id', (int)$category_id);

        if (!empty($search)) {
            $this->db->like('products.name', $search);
        }
        if ($min_price !== NULL && $min_price !== '') {
            $this->db->where('products.price >=', (float)$min_price);
        }
        if ($max_price !== NULL && $max_price !== '') {
            $this->db->where('products.price <=', (float)$max_price);
        }

        $total = $this->db->count_all_results('', FALSE);

        // Sorting
        if ($sort_by === 'price_low') {
            $this->db->order_by('products.price', 'ASC');
        } elseif ($sort_by === 'price_high') {
            $this->db->order_by('products.price', 'DESC');
        } elseif ($sort_by === 'newest') {
            $this->db->order_by('products.id', 'DESC');
        } else {
            $this->db->order_by('products.id', 'DESC');
        }

        // Pagination
        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        $this->db->select('products.*, categories.name as category_name');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $products_raw = $query->result();

        $products = [];
        foreach ($products_raw as $prod) {
            $products[] = [
                'id'             => (int)$prod->id,
                'category_id'    => (int)$prod->category_id,
                'category_name'  => $prod->category_name,
                'name'           => $prod->name,
                'slug'           => $prod->slug,
                'description'    => $prod->description,
                'price'          => (float)$prod->price,
                'image'          => $prod->image ? base_url($prod->image) : null,
                'stock'          => (int)$prod->stock,
                'status'         => (int)$prod->status,
                'created_at'     => $prod->created_at,
                'updated_at'     => $prod->updated_at
            ];
        }

        $response_data = [
            'products' => $products,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ];

        $this->response(true, 'Products retrieved successfully', $response_data, 200);
    }

    /**
     * GET api/products/(:num)
     * User-facing API endpoint to retrieve specific active product details
     */
    public function get_product_detail($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $id = $id ?: ($this->input->get('id') ?: $this->input->post('id'));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid product id', null, 400);
        }

        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', (int)$id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $prod = $query->row();

        if (!$prod) {
            $this->response(false, 'Product not found', null, 404);
        }

        $product_data = [
            'id'             => (int)$prod->id,
            'category_id'    => (int)$prod->category_id,
            'category_name'  => $prod->category_name,
            'name'           => $prod->name,
            'slug'           => $prod->slug,
            'description'    => $prod->description,
            'price'          => (float)$prod->price,
            'image'          => $prod->image ? base_url($prod->image) : null,
            'stock'          => (int)$prod->stock,
            'status'         => (int)$prod->status,
            'created_at'     => $prod->created_at,
            'updated_at'     => $prod->updated_at
        ];

        $this->response(true, 'Product details retrieved successfully', $product_data, 200);
    }

    /**
     * POST api/members/(:num)/wallet
     * Admin-only endpoint to directly credit/add money to a user's wallet
     */
    public function add_wallet_money($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        // Enforce bearer token authentication
        $auth = $this->check_auth();
        $admin_id = (int)$auth['decoded']->user_id;
        $admin_role = (int)$auth['decoded']->role;

        // Admin role check (role = 1)
        if ($admin_role !== 1) {
            $this->response(false, 'Forbidden: Admin access only', null, 403);
        }

        $id = $id ?: ($this->input->post('user_id') ?: $this->input->get('user_id'));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing user ID', null, 400);
        }

        $user = $this->General_model->getOne('users', ['id' => (int)$id]);
        if (!$user) {
            $this->response(false, 'Target user not found', null, 404);
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('remark', 'Remark', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $amount = (float)$this->input->post('amount');
        $remark = $this->input->post('remark', TRUE) ?: null;

        // Perform inside a database transaction block
        $this->db->trans_begin();

        // 1. Record credit transaction
        $txn_data = [
            'user_id'    => (int)$id,
            'type'       => 'credit',
            'amount'     => $amount,
            'source'     => 'admin_credit',
            'remark'     => $remark,
            'added_by'   => $admin_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert('wallet_transactions', $txn_data);

        // 2. Increment wallet balance
        $new_balance = (float)$user->wallet_balance + $amount;
        $this->db->update('users', ['wallet_balance' => $new_balance], ['id' => (int)$id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to add money due to a database transaction error', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Wallet money credited successfully', [
                'user_id'     => (int)$id,
                'amount'      => $amount,
                'new_balance' => $new_balance
            ], 200);
        }
    }

    /**
     * GET api/wallet/balance
     * Authenticated endpoint to fetch the user's current wallet balance
     */
    public function get_wallet_balance()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user) {
            $this->response(false, 'User account not found', null, 404);
        }

        if ((int)$user->status === 0) {
            $this->response(false, 'Your account has been blocked. Please contact support.', null, 403);
        }

        $this->response(true, 'Wallet balance retrieved successfully', [
            'wallet_balance' => (float)$user->wallet_balance
        ], 200);
    }

    /**
     * GET api/wallet/transactions
     * Authenticated endpoint to retrieve current user's wallet transactions, paginated and sorted newest first
     */
    public function get_wallet_transactions()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        // Build count query
        $this->db->from('wallet_transactions');
        $this->db->where('user_id', $user_id);
        $total = $this->db->count_all_results('', FALSE);

        // Fetch paginated transactions sorted by newest first
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $txns = $query->result();

        $list = [];
        foreach ($txns as $t) {
            $list[] = [
                'id'           => (int)$t->id,
                'user_id'      => (int)$t->user_id,
                'type'         => $t->type,
                'amount'       => (float)$t->amount,
                'source'       => $t->source,
                'reference_id' => $t->reference_id ? (int)$t->reference_id : null,
                'remark'       => $t->remark,
                'added_by'     => $t->added_by ? (int)$t->added_by : null,
                'created_at'   => $t->created_at
            ];
        }

        $this->response(true, 'Transactions retrieved successfully', [
            'transactions' => $list,
            'total'        => (int)$total,
            'page'         => (int)$page,
            'limit'         => (int)$limit
        ], 200);
    }

    /**
     * POST api/cart/add
     * Authenticated endpoint to add an item to the shopping cart
     */
    public function add_to_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $product_id = (int)$this->input->post('product_id');
        $quantity = (int)$this->input->post('quantity');

        // Check if product exists, is active and has sufficient stock
        $product = $this->General_model->getOne('products', ['id' => $product_id, 'status' => 1]);
        if (!$product) {
            $this->response(false, 'Product not found or inactive', null, 404);
        }

        if ((int)$product->stock < $quantity) {
            $this->response(false, 'Insufficient stock available. Only ' . $product->stock . ' units left.', null, 400);
        }

        // Check if already in user's cart
        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => $product_id]);

        if ($existing) {
            $new_quantity = (int)$existing->quantity + $quantity;
            if ($new_quantity > (int)$product->stock) {
                $this->response(false, 'Cannot add more. Total quantity in your cart (' . $new_quantity . ') exceeds product stock (' . $product->stock . ').', null, 400);
            }

            $this->db->update('cart', ['quantity' => $new_quantity, 'updated_at' => date('Y-m-d H:i:s')], ['id' => (int)$existing->id]);
            $cart_row_id = (int)$existing->id;
            $msg = 'Cart quantity updated successfully';
            $status_code = 200;
        } else {
            $insert_data = [
                'user_id'    => $user_id,
                'product_id' => $product_id,
                'quantity'   => $quantity,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $cart_id = $this->General_model->insert('cart', $insert_data);
            $cart_row_id = (int)$cart_id;
            $msg = 'Product added to cart successfully';
            $status_code = 201;
        }

        // Fetch full details of the cart row
        $this->db->select('cart.id, cart.product_id, cart.quantity, products.name as product_name, products.price as product_price, products.image as product_image');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.id', $cart_row_id);
        $full_cart_item = $this->db->get()->row();

        if ($full_cart_item) {
            $full_cart_item->id = (int)$full_cart_item->id;
            $full_cart_item->product_id = (int)$full_cart_item->product_id;
            $full_cart_item->quantity = (int)$full_cart_item->quantity;
            $full_cart_item->product_price = (float)$full_cart_item->product_price;
            $full_cart_item->product_image = $full_cart_item->product_image ? base_url($full_cart_item->product_image) : null;
            $full_cart_item->subtotal = (float)($full_cart_item->product_price * $full_cart_item->quantity);
        }

        $this->response(true, $msg, $full_cart_item, $status_code);
    }

    /**
     * GET api/cart
     * Authenticated endpoint to retrieve all items in the user's cart
     */
    public function get_cart()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->select('cart.id as cart_id, cart.quantity, products.id as product_id, products.name as product_name, products.slug as product_slug, products.price, products.image, products.stock as product_stock');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $rows = $query->result();

        $cart_items = [];
        foreach ($rows as $row) {
            $cart_items[] = [
                'cart_id'       => (int)$row->cart_id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => $row->product_slug,
                'price'         => (float)$row->price,
                'quantity'      => (int)$row->quantity,
                'product_stock' => (int)$row->product_stock,
                'image'         => $row->image ? base_url($row->image) : null,
                'total_price'   => (float)$row->price * (int)$row->quantity
            ];
        }

        $this->response(true, 'Cart items retrieved successfully', $cart_items, 200);
    }

    /**
     * POST api/cart/update
     * Authenticated endpoint to update absolute quantity of an item in the cart
     */
    public function update_cart_quantity()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_id', 'Product ID', 'required|numeric');
        $this->form_validation->set_rules('quantity', 'Quantity', 'required|integer|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $product_id = (int)$this->input->post('product_id');
        $quantity = (int)$this->input->post('quantity');

        // Check product stock
        $product = $this->General_model->getOne('products', ['id' => $product_id, 'status' => 1]);
        if (!$product) {
            $this->response(false, 'Product not found or inactive', null, 404);
        }

        if ((int)$product->stock < $quantity) {
            $this->response(false, 'Insufficient stock. Only ' . $product->stock . ' units available.', null, 400);
        }

        // Check if cart item exists
        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => $product_id]);
        if (!$existing) {
            $this->response(false, 'Product not found in your cart', null, 404);
        }

        $this->db->update('cart', ['quantity' => $quantity, 'updated_at' => date('Y-m-d H:i:s')], ['id' => (int)$existing->id]);

        // Fetch full details of the cart row
        $this->db->select('cart.id, cart.product_id, cart.quantity, products.name as product_name, products.price as product_price, products.image as product_image');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.id', (int)$existing->id);
        $full_cart_item = $this->db->get()->row();

        if ($full_cart_item) {
            $full_cart_item->id = (int)$full_cart_item->id;
            $full_cart_item->product_id = (int)$full_cart_item->product_id;
            $full_cart_item->quantity = (int)$full_cart_item->quantity;
            $full_cart_item->product_price = (float)$full_cart_item->product_price;
            $full_cart_item->product_image = $full_cart_item->product_image ? base_url($full_cart_item->product_image) : null;
            $full_cart_item->subtotal = (float)($full_cart_item->product_price * $full_cart_item->quantity);
        }

        $this->response(true, 'Cart quantity updated successfully', $full_cart_item, 200);
    }

    /**
     * POST api/cart/remove
     * Authenticated endpoint to delete a product from the user's cart
     */
    public function remove_from_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $product_id = $this->input->post('product_id', TRUE);
        if (empty($product_id) || !is_numeric($product_id)) {
            $this->response(false, 'Invalid or missing product ID', null, 400);
        }

        $existing = $this->General_model->getOne('cart', ['user_id' => $user_id, 'product_id' => (int)$product_id]);
        if (!$existing) {
            $this->response(false, 'Product not found in your cart', null, 404);
        }

        $this->db->delete('cart', ['user_id' => $user_id, 'product_id' => (int)$product_id]);
        $this->response(true, 'Product removed from cart successfully', null, 200);
    }

    /**
     * POST api/cart/clear
     * Authenticated endpoint to clear the entire cart database table for current user
     */
    public function clear_cart()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->delete('cart', ['user_id' => $user_id]);
        $this->response(true, 'Cart cleared successfully', null, 200);
    }

    /**
     * GET api/cart/row
     * Authenticated endpoint to retrieve cart details for an individual product
     */
    public function get_cart_row()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $product_id = $this->input->get('product_id', TRUE);
        if (empty($product_id) || !is_numeric($product_id)) {
            $this->response(false, 'Invalid or missing product ID', null, 400);
        }

        $this->db->select('cart.id as cart_id, cart.quantity, products.id as product_id, products.name as product_name, products.slug as product_slug, products.price, products.image, products.stock as product_stock');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('cart.product_id', (int)$product_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $row = $query->row();

        if (!$row) {
            $this->response(true, 'Product not in cart', null, 200);
        } else {
            $item = [
                'cart_id'       => (int)$row->cart_id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => $row->product_slug,
                'price'         => (float)$row->price,
                'quantity'      => (int)$row->quantity,
                'product_stock' => (int)$row->product_stock,
                'image'         => $row->image ? base_url($row->image) : null,
                'total_price'   => (float)$row->price * (int)$row->quantity
            ];
            $this->response(true, 'Cart row retrieved successfully', $item, 200);
        }
    }

    /**
     * GET api/cart/summary
     * Authenticated endpoint to fetch cart total quantity count and subtotal amount sum
     */
    public function get_cart_summary()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->select('cart.quantity, products.price');
        $this->db->from('cart');
        $this->db->join('products', 'products.id = cart.product_id', 'inner');
        $this->db->where('cart.user_id', $user_id);
        $this->db->where('products.status', 1);
        $query = $this->db->get();
        $rows = $query->result();

        $total_items = 0;
        $subtotal = 0.00;

        foreach ($rows as $row) {
            $total_items += (int)$row->quantity;
            $subtotal += (float)$row->price * (int)$row->quantity;
        }

        $this->response(true, 'Cart summary retrieved successfully', [
            'total_items' => $total_items,
            'subtotal'    => $subtotal
        ], 200);
    }

    /**
     * POST api/orders/place
     * Authenticated endpoint to place a pending product order
     */
    public function place_order()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $product_id = $this->input->post('product_id');
        $quantity = $this->input->post('quantity');
        $input_address_id = $this->input->post('address_id');

        // Resolve address_id
        $address_id = null;
        if (!empty($input_address_id)) {
            $address = $this->General_model->getOne('user_addresses', [
                'id' => (int)$input_address_id,
                'user_id' => $user_id
            ]);
            if (!$address) {
                $this->response(false, 'Invalid Address ID. Shipping address not found or unauthorized.', null, 400);
            }
            $address_id = (int)$address->id;
        } else {
            // Find default address
            $address = $this->General_model->getOne('user_addresses', [
                'user_id' => $user_id,
                'is_default' => 1
            ]);
            // Fallback to any user address if no explicit default is flagged
            if (!$address) {
                $address = $this->General_model->getOne('user_addresses', ['user_id' => $user_id]);
            }
            if (!$address) {
                $this->response(false, 'Please configure a shipping address before placing an order.', null, 400);
            }
            $address_id = (int)$address->id;
        }

        if (empty($product_id)) {
            // Flow A: Checkout from Cart
            $cart_items = $this->General_model->getAll('cart', ['user_id' => $user_id]);
            if (empty($cart_items)) {
                $this->response(false, 'Your cart is empty. Please add products to your cart before checking out.', null, 400);
            }

            // Verify stock for all cart items first
            foreach ($cart_items as $item) {
                $prod = $this->General_model->getOne('products', ['id' => (int)$item->product_id, 'status' => 1]);
                if (!$prod) {
                    $this->response(false, 'One or more products in your cart are no longer active/available.', null, 400);
                }
                if ((int)$prod->stock < (int)$item->quantity) {
                    $this->response(false, 'Insufficient stock available for ' . htmlspecialchars($prod->name) . '. Only ' . $prod->stock . ' units left.', null, 400);
                }
            }

            // Place an order for each cart item
            $placed_orders = [];
            foreach ($cart_items as $item) {
                $prod = $this->General_model->getOne('products', ['id' => (int)$item->product_id]);
                $total_amount = (float)$prod->price * (int)$item->quantity;

                $order_data = [
                    'user_id'    => $user_id,
                    'product_id' => (int)$item->product_id,
                    'quantity'   => (int)$item->quantity,
                    'amount'     => $total_amount,
                    'status'     => 'pending',
                    'address_id' => $address_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $order_id = $this->General_model->insert('orders', $order_data);

                // Fetch details of this order
                $this->db->select('orders.*, products.name as product_name, products.slug as product_slug, products.image as product_image, products.price as product_price, categories.name as category_name');
                $this->db->from('orders');
                $this->db->join('products', 'products.id = orders.product_id', 'inner');
                $this->db->join('categories', 'categories.id = products.category_id', 'left');
                $this->db->where('orders.id', (int)$order_id);
                $order_row = $this->db->get()->row();

                $shipping_address = null;
                if ($order_row && !empty($order_row->address_id)) {
                    $addr = $this->General_model->getOne('user_addresses', ['id' => $order_row->address_id]);
                    if ($addr) {
                        $shipping_address = [
                            'id'            => (int)$addr->id,
                            'full_name'     => $addr->full_name,
                            'mobile'        => $addr->mobile,
                            'address_line1' => $addr->address_line1,
                            'address_line2' => $addr->address_line2,
                            'landmark'      => $addr->landmark,
                            'city'          => $addr->city,
                            'state'         => $addr->state,
                            'pincode'       => $addr->pincode,
                            'country'       => $addr->country,
                            'is_default'    => (int)$addr->is_default
                        ];
                    }
                }

                if ($order_row) {
                    $placed_orders[] = [
                        'id'               => (int)$order_row->id,
                        'buyer_id'         => (int)$order_row->user_id,
                        'product_id'       => (int)$order_row->product_id,
                        'product_name'     => $order_row->product_name,
                        'product_slug'     => $order_row->product_slug,
                        'product_price'    => (float)$order_row->product_price,
                        'product_image'    => $order_row->product_image ? base_url($order_row->product_image) : null,
                        'category_name'    => $order_row->category_name ?: 'Uncategorized',
                        'quantity'         => (int)$order_row->quantity,
                        'amount'           => (float)$order_row->amount,
                        'status'           => $order_row->status,
                        'address_id'       => $order_row->address_id ? (int)$order_row->address_id : null,
                        'shipping_address' => $shipping_address,
                        'created_at'       => $order_row->created_at,
                        'updated_at'       => $order_row->updated_at
                    ];
                }
            }

            $this->response(true, 'Orders placed successfully from cart. Please verify payment to complete.', $placed_orders, 201);
        } else {
            // Flow B: Buy Now (Single Product)
            if (empty($quantity) || !is_numeric($quantity) || (int)$quantity <= 0) {
                $this->response(false, 'Quantity is required and must be a positive integer for buy now.', null, 400);
            }

            $product = $this->General_model->getOne('products', ['id' => (int)$product_id, 'status' => 1]);
            if (!$product) {
                $this->response(false, 'Product not found or inactive', null, 404);
            }

            if ((int)$product->stock < (int)$quantity) {
                $this->response(false, 'Insufficient stock available to place this order.', null, 400);
            }

            $total_amount = (float)$product->price * (int)$quantity;

            $order_data = [
                'user_id'    => $user_id,
                'product_id' => (int)$product_id,
                'quantity'   => (int)$quantity,
                'amount'     => $total_amount,
                'status'     => 'pending',
                'address_id' => $address_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $order_id = $this->General_model->insert('orders', $order_data);

            // Fetch details of this order
            $this->db->select('orders.*, products.name as product_name, products.slug as product_slug, products.image as product_image, products.price as product_price, categories.name as category_name');
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->join('categories', 'categories.id = products.category_id', 'left');
            $this->db->where('orders.id', (int)$order_id);
            $order = $this->db->get()->row();

            $shipping_address = null;
            if ($order && !empty($order->address_id)) {
                $addr = $this->General_model->getOne('user_addresses', ['id' => $order->address_id]);
                if ($addr) {
                    $shipping_address = [
                        'id'            => (int)$addr->id,
                        'full_name'     => $addr->full_name,
                        'mobile'        => $addr->mobile,
                        'address_line1' => $addr->address_line1,
                        'address_line2' => $addr->address_line2,
                        'landmark'      => $addr->landmark,
                        'city'          => $addr->city,
                        'state'         => $addr->state,
                        'pincode'       => $addr->pincode,
                        'country'       => $addr->country,
                        'is_default'    => (int)$addr->is_default
                    ];
                }
            }

            $order_detail = null;
            if ($order) {
                $order_detail = [
                    'id'               => (int)$order->id,
                    'buyer_id'         => (int)$order->user_id,
                    'product_id'       => (int)$order->product_id,
                    'product_name'     => $order->product_name,
                    'product_slug'     => $order->product_slug,
                    'product_price'    => (float)$order->product_price,
                    'product_image'    => $order->product_image ? base_url($order->product_image) : null,
                    'category_name'    => $order->category_name ?: 'Uncategorized',
                    'quantity'         => (int)$order->quantity,
                    'amount'           => (float)$order->amount,
                    'status'           => $order->status,
                    'address_id'       => $order->address_id ? (int)$order->address_id : null,
                    'shipping_address' => $shipping_address,
                    'created_at'       => $order->created_at,
                    'updated_at'       => $order->updated_at
                ];
            }

            $this->response(true, 'Order placed successfully. Please verify payment to complete.', $order_detail, 201);
        }
    }

    /**
     * POST api/orders/verify
     * Authenticated endpoint to verify payment and process MLM level commissions
     */
    public function verify_order_payment()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $order_id = $this->input->post('order_id', TRUE);
        if (empty($order_id) || !is_numeric($order_id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        // Get the order
        $order = $this->General_model->getOne('orders', ['id' => (int)$order_id, 'user_id' => $user_id]);
        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        if ($order->status !== 'pending') {
            $this->response(false, 'Payment has already been processed or order is cancelled. Status: ' . $order->status, null, 400);
        }

        $buyer = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$buyer) {
            $this->response(false, 'Buyer user not found', null, 404);
        }

        $order_amount = (float)$order->amount;

        // Verify sufficient wallet balance
        if ((float)$buyer->wallet_balance < $order_amount) {
            $this->response(false, 'Insufficient wallet balance. Total amount: ₹' . number_format($order_amount, 2) . ', current balance: ₹' . number_format($buyer->wallet_balance, 2), null, 400);
        }

        // Check if stock is still sufficient
        $product = $this->General_model->getOne('products', ['id' => (int)$order->product_id, 'status' => 1]);
        if (!$product) {
            $this->response(false, 'Product is no longer available', null, 404);
        }

        if ((int)$product->stock < (int)$order->quantity) {
            $this->response(false, 'Insufficient stock available. Only ' . $product->stock . ' units left.', null, 400);
        }

        // Begin DB Transaction
        $this->db->trans_begin();

        // 1. Deduct order amount from buyer's wallet_balance
        $buyer_new_balance = (float)$buyer->wallet_balance - $order_amount;
        $this->db->update('users', ['wallet_balance' => $buyer_new_balance], ['id' => $user_id]);

        // 2. Insert debit wallet transaction log
        $this->db->insert('wallet_transactions', [
            'user_id'      => $user_id,
            'type'         => 'debit',
            'amount'       => $order_amount,
            'source'       => 'purchase',
            'reference_id' => (int)$order_id,
            'remark'       => "Debited for product order purchase (Order ID: #{$order_id})",
            'created_at'   => date('Y-m-d H:i:s')
        ]);

        // 3. Deduct stock from products table
        $new_stock = (int)$product->stock - (int)$order->quantity;
        $this->db->update('products', ['stock' => $new_stock], ['id' => $product->id]);

        // 4. Update order status to confirmed
        $this->db->update('orders', ['status' => 'confirmed', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);

        // 5. MLM level commission chain traversal
        $levels_percentage = [];
        $settings = $this->db->order_by('level', 'ASC')->get('commission_settings')->result();
        foreach ($settings as $setting) {
            $levels_percentage[(int)$setting->level] = (float)$setting->percentage;
        }

        $total_allocated_percentage_sum = 0.00;
        $buyer_role = (int)$buyer->role;

        // If buyer is admin or has no parent chain, skip referral commissions (remainder pool gets 100%)
        $ancestor_id = ($buyer_role !== 1) ? $buyer->parent_id : null;

        if (!empty($ancestor_id)) {
            for ($level = 1; $level <= 12; $level++) {
                if (empty($ancestor_id)) {
                    break; // chain ended
                }

                $level_percentage = isset($levels_percentage[$level]) ? $levels_percentage[$level] : 0.00;
                $ancestor = $this->General_model->getOne('users', ['id' => $ancestor_id]);
                if (!$ancestor) {
                    break; // user deleted
                }

                // If ancestor is blocked (status = 0), skip crediting them but let the percentage go to admin remainder
                if ((int)$ancestor->status === 0) {
                    $ancestor_id = $ancestor->parent_id;
                    continue;
                }

                // If ancestor is Admin (role = 1), skip crediting them (remainder gets this percentage)
                if ((int)$ancestor->role === 1) {
                    $ancestor_id = $ancestor->parent_id;
                    continue;
                }

                // Ancestor is active Member (role = 0). Calculate commission.
                $level_comm = $order_amount * ($level_percentage / 100);

                // Increment ancestor wallet balance
                $ancestor_new_balance = (float)$ancestor->wallet_balance + $level_comm;
                $this->db->update('users', ['wallet_balance' => $ancestor_new_balance], ['id' => $ancestor->id]);

                // Insert wallet log for level commission
                $this->db->insert('wallet_transactions', [
                    'user_id'      => $ancestor->id,
                    'type'         => 'credit',
                    'amount'       => $level_comm,
                    'source'       => 'referral_commission',
                    'reference_id' => (int)$order_id,
                    'remark'       => "Referral commission from level {$level} purchase (Order ID: #{$order_id})",
                    'created_at'   => date('Y-m-d H:i:s')
                ]);

                // Insert order commission log
                $this->db->insert('order_commissions', [
                    'order_id'    => (int)$order_id,
                    'buyer_id'    => $user_id,
                    'receiver_id' => $ancestor->id,
                    'level'       => $level,
                    'amount'      => $level_comm,
                    'created_at'  => date('Y-m-d H:i:s')
                ]);

                $total_allocated_percentage_sum += $level_percentage;

                // Move up chain to parent referrer
                $ancestor_id = $ancestor->parent_id;
            }
        }

        // 6. Calculate remainder commission cut for main admin (lowest ID admin)
        $admin_percentage = 100.00 - $total_allocated_percentage_sum;
        $admin_commission = $order_amount * ($admin_percentage / 100);

        $this->db->where('role', 1);
        $this->db->order_by('id', 'ASC');
        $this->db->limit(1);
        $admin = $this->db->get('users')->row();

        if ($admin && $admin_commission > 0) {
            $admin_new_balance = (float)$admin->wallet_balance + $admin_commission;
            $this->db->update('users', ['wallet_balance' => $admin_new_balance], ['id' => $admin->id]);

            // Insert wallet log for admin commission and remainder
            $this->db->insert('wallet_transactions', [
                'user_id'      => $admin->id,
                'type'         => 'credit',
                'amount'       => $admin_commission,
                'source'       => 'admin_commission',
                'reference_id' => (int)$order_id,
                'remark'       => "Admin commission cut (remainder: {$admin_percentage}%) for Order ID: #{$order_id}",
                'created_at'   => date('Y-m-d H:i:s')
            ]);
        }

        // 7. Remove from cart if item was present in the user's cart
        $this->db->delete('cart', ['user_id' => $user_id, 'product_id' => (int)$order->product_id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Transaction failed during order payment processing', null, 500);
        } else {
            $this->db->trans_commit();
            // Fetch full details of the verified order
            $this->db->select('orders.*, products.name as product_name, products.slug as product_slug, products.image as product_image, products.price as product_price, categories.name as category_name');
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id', 'inner');
            $this->db->join('categories', 'categories.id = products.category_id', 'left');
            $this->db->where('orders.id', (int)$order_id);
            $full_order = $this->db->get()->row();

            $shipping_address = null;
            if ($full_order && !empty($full_order->address_id)) {
                $addr = $this->General_model->getOne('user_addresses', ['id' => $full_order->address_id]);
                if ($addr) {
                    $shipping_address = [
                        'id'            => (int)$addr->id,
                        'full_name'     => $addr->full_name,
                        'mobile'        => $addr->mobile,
                        'address_line1' => $addr->address_line1,
                        'address_line2' => $addr->address_line2,
                        'landmark'      => $addr->landmark,
                        'city'          => $addr->city,
                        'state'         => $addr->state,
                        'pincode'       => $addr->pincode,
                        'country'       => $addr->country,
                        'is_default'    => (int)$addr->is_default
                    ];
                }
            }

            $order_detail = null;
            if ($full_order) {
                $order_detail = [
                    'id'                    => (int)$full_order->id,
                    'buyer_id'              => (int)$full_order->user_id,
                    'product_id'            => (int)$full_order->product_id,
                    'product_name'          => $full_order->product_name,
                    'product_slug'          => $full_order->product_slug,
                    'product_price'         => (float)$full_order->product_price,
                    'product_image'         => $full_order->product_image ? base_url($full_order->product_image) : null,
                    'category_name'         => $full_order->category_name ?: 'Uncategorized',
                    'quantity'              => (int)$full_order->quantity,
                    'amount'                => (float)$full_order->amount,
                    'status'                => $full_order->status,
                    'address_id'            => $full_order->address_id ? (int)$full_order->address_id : null,
                    'shipping_address'      => $shipping_address,
                    'buyer_updated_balance' => (float)$buyer_new_balance,
                    'created_at'            => $full_order->created_at,
                    'updated_at'            => $full_order->updated_at
                ];
            }

            $this->response(true, 'Payment verified and order confirmed successfully', $order_detail, 200);
        }
    }

    /**
     * GET api/orders
     * Authenticated endpoint to fetch the user's paginated order history list
     */
    public function get_orders()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        // Build count query
        $this->db->from('orders');
        $this->db->where('user_id', $user_id);
        $total = $this->db->count_all_results('', FALSE);

        // Fetch paginated order rows joining with products
        $this->db->select('orders.*, products.name as product_name, products.slug as product_slug, products.image as product_image');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');
        $this->db->order_by('orders.id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows = $query->result();

        $orders = [];
        foreach ($rows as $row) {
            $orders[] = [
                'id'            => (int)$row->id,
                'product_id'    => (int)$row->product_id,
                'product_name'  => $row->product_name,
                'product_slug'  => $row->product_slug,
                'product_image' => $row->product_image ? base_url($row->product_image) : null,
                'quantity'      => (int)$row->quantity,
                'amount'        => (float)$row->amount,
                'status'        => $row->status,
                'created_at'    => $row->created_at,
                'updated_at'    => $row->updated_at
            ];
        }

        $this->response(true, 'Orders history retrieved successfully', [
            'orders' => $orders,
            'total'  => (int)$total,
            'page'   => (int)$page,
            'limit'  => (int)$limit
        ], 200);
    }

    /**
     * GET api/orders/(:num)
     * Authenticated endpoint to fetch specific details of an individual order
     */
    public function get_order_details($id = null)
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        $id = $id ?: ($this->input->get('order_id') ?: ($this->input->get('id') ?: ($this->input->post('order_id') ?: $this->input->post('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        $this->db->select('orders.*, products.name as product_name, products.slug as product_slug, products.image as product_image, products.price as product_price, categories.name as category_name');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id', 'inner');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('orders.id', (int)$id);
        $query = $this->db->get();
        $order = $query->row();

        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        // Auth restriction: user must be the buyer, OR an admin
        if ($role !== 1 && (int)$order->user_id !== $user_id) {
            $this->response(false, 'Unauthorized access to this order details', null, 403);
        }

        $shipping_address = null;
        if (!empty($order->address_id)) {
            $addr = $this->General_model->getOne('user_addresses', ['id' => $order->address_id]);
            if ($addr) {
                $shipping_address = [
                    'id'            => (int)$addr->id,
                    'full_name'     => $addr->full_name,
                    'mobile'        => $addr->mobile,
                    'address_line1' => $addr->address_line1,
                    'address_line2' => $addr->address_line2,
                    'landmark'      => $addr->landmark,
                    'city'          => $addr->city,
                    'state'         => $addr->state,
                    'pincode'       => $addr->pincode,
                    'country'       => $addr->country,
                    'is_default'    => (int)$addr->is_default
                ];
            }
        }

        $order_detail = [
            'id'               => (int)$order->id,
            'buyer_id'         => (int)$order->user_id,
            'product_id'       => (int)$order->product_id,
            'product_name'     => $order->product_name,
            'product_slug'     => $order->product_slug,
            'product_price'    => (float)$order->product_price,
            'product_image'    => $order->product_image ? base_url($order->product_image) : null,
            'category_name'    => $order->category_name ?: 'Uncategorized',
            'quantity'         => (int)$order->quantity,
            'amount'           => (float)$order->amount,
            'status'           => $order->status,
            'address_id'       => $order->address_id ? (int)$order->address_id : null,
            'shipping_address' => $shipping_address,
            'created_at'       => $order->created_at,
            'updated_at'       => $order->updated_at
        ];

        $this->response(true, 'Order details retrieved successfully', $order_detail, 200);
    }

    /**
     * POST api/orders/(:num)/cancel
     * Authenticated endpoint to cancel an individual pending order
     */
    public function cancel_order($id = null)
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;
        $role = (int)$auth['decoded']->role;

        $id = $id ?: ($this->input->post('order_id') ?: ($this->input->post('id') ?: ($this->input->get('order_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid or missing order ID', null, 400);
        }

        $order = $this->General_model->getOne('orders', ['id' => (int)$id]);
        if (!$order) {
            $this->response(false, 'Order not found', null, 404);
        }

        // Restrict cancellation to buyer OR admin
        if ($role !== 1 && (int)$order->user_id !== $user_id) {
            $this->response(false, 'Unauthorized to cancel this order', null, 403);
        }

        if ($order->status !== 'pending') {
            $this->response(false, 'Only pending orders can be cancelled. Status: ' . $order->status, null, 400);
        }

        // Update status to cancelled
        $this->db->update('orders', ['status' => 'cancelled', 'updated_at' => date('Y-m-d H:i:s')], ['id' => $order->id]);
        $this->response(true, 'Order cancelled successfully', ['order_id' => (int)$order->id, 'status' => 'cancelled'], 200);
    }

    /**
     * POST api/wallet/deposit
     * Authenticated endpoint to submit a wallet deposit request (cash/online with proof)
     */
    public function request_wallet_deposit()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON request body if present
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('payment_method', 'Payment Method', 'required|in_list[cash,online]');
        $this->form_validation->set_rules('remark', 'Remark', 'trim');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        $amount = (float)$this->input->post('amount');
        $payment_method = $this->input->post('payment_method', TRUE);
        $remark = $this->input->post('remark', TRUE) ?: null;
        $proof_file_path = null;

        // If online payment method, require screenshot or PDF proof file
        if ($payment_method === 'online') {
            if (empty($_FILES['proof_file']['name'])) {
                $this->response(false, 'Proof receipt file is required for online deposit requests.', null, 400);
            }

            $upload_path = './uploads/deposit_proofs/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('proof_file')) {
                $this->response(false, $this->upload->display_errors('', ''), null, 400);
            } else {
                $upload_data = $this->upload->data();
                $proof_file_path = 'uploads/deposit_proofs/' . $upload_data['file_name'];
            }
        }

        $insert_data = [
            'user_id'        => $user_id,
            'amount'         => $amount,
            'payment_method' => $payment_method,
            'proof_file'     => $proof_file_path,
            'remark'         => $remark,
            'status'         => 'pending',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ];

        $request_id = $this->General_model->insert('wallet_deposit_requests', $insert_data);

        if ($request_id) {
            $this->response(true, 'Deposit request submitted successfully. Pending admin approval.', [
                'request_id'     => (int)$request_id,
                'amount'         => $amount,
                'payment_method' => $payment_method,
                'status'         => 'pending'
            ], 201);
        } else {
            $this->response(false, 'Failed to insert request into database', null, 500);
        }
    }

    /**
     * GET api/wallet/deposits
     * Authenticated endpoint to retrieve current user's deposit requests
     */
    public function get_deposit_requests()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = $this->input->get('limit', TRUE) ?: 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $limit = (int)$limit < 1 ? 10 : (int)$limit;
        $offset = ($page - 1) * $limit;

        // Count query
        $this->db->from('wallet_deposit_requests');
        $this->db->where('user_id', $user_id);
        $total = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        $rows = $query->result();

        $requests = [];
        foreach ($rows as $row) {
            $requests[] = [
                'id'             => (int)$row->id,
                'amount'         => (float)$row->amount,
                'payment_method' => $row->payment_method,
                'proof_file'     => $row->proof_file ? base_url($row->proof_file) : null,
                'remark'         => $row->remark,
                'status'         => $row->status,
                'created_at'     => $row->created_at,
                'updated_at'     => $row->updated_at
            ];
        }

        $this->response(true, 'Deposit requests retrieved successfully', [
            'requests' => $requests,
            'total'    => (int)$total,
            'page'     => (int)$page,
            'limit'    => (int)$limit
        ], 200);
    }

    /**
     * GET api/addresses
     * Authenticated endpoint to retrieve all shipping addresses for the logged-in user
     */
    public function get_addresses()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->db->where('user_id', $user_id);
        $this->db->order_by('is_default', 'DESC');
        $this->db->order_by('id', 'DESC');
        $addresses = $this->db->get('user_addresses')->result();

        $this->response(true, 'Addresses retrieved successfully', [
            'addresses' => $addresses
        ], 200);
    }

    /**
     * POST api/addresses/save
     * Authenticated endpoint to save a new shipping address
     */
    public function save_address()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse JSON body
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|max_length[15]');
        $this->form_validation->set_rules('address_line1', 'Address Line 1', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('address_line2', 'Address Line 2', 'trim|max_length[255]');
        $this->form_validation->set_rules('landmark', 'Landmark', 'trim|max_length[150]');
        $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('pincode', 'Pincode', 'required|trim|max_length[20]');
        $this->form_validation->set_rules('country', 'Country', 'trim|max_length[100]');
        $this->form_validation->set_rules('is_default', 'Is Default', 'integer|in_list[0,1]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response(false, implode(' ', $errors), $errors, 400);
        }

        // Check if this is the user's first address
        $existing_count = $this->db->where('user_id', $user_id)->count_all_results('user_addresses');
        $is_default = (int)$this->input->post('is_default');

        if ($existing_count === 0) {
            $is_default = 1; // Force first address to be default
        }

        // If setting this address to default, set all others to 0
        if ($is_default === 1 && $existing_count > 0) {
            $this->db->update('user_addresses', ['is_default' => 0], ['user_id' => $user_id]);
        }

        $insert_data = [
            'user_id'       => $user_id,
            'full_name'     => $this->input->post('full_name', TRUE),
            'mobile'        => $this->input->post('mobile', TRUE),
            'address_line1' => $this->input->post('address_line1', TRUE),
            'address_line2' => $this->input->post('address_line2', TRUE) ?: null,
            'landmark'      => $this->input->post('landmark', TRUE) ?: null,
            'city'          => $this->input->post('city', TRUE),
            'state'         => $this->input->post('state', TRUE),
            'pincode'       => $this->input->post('pincode', TRUE),
            'country'       => $this->input->post('country', TRUE) ?: 'India',
            'is_default'    => $is_default,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $address_id = $this->General_model->insert('user_addresses', $insert_data);

        if ($address_id) {
            $full_address = $this->General_model->getOne('user_addresses', ['id' => $address_id]);
            if ($full_address) {
                $full_address->id = (int)$full_address->id;
                $full_address->user_id = (int)$full_address->user_id;
                $full_address->is_default = (int)$full_address->is_default;
            }
            $this->response(true, 'Address saved successfully', $full_address, 201);
        } else {
            $this->response(false, 'Failed to save address', null, 500);
        }
    }

    /**
     * PUT/POST api/addresses/update/(:num)
     * Authenticated endpoint to update an existing shipping address
     */
    public function update_address($id = null)
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'PUT' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Parse PUT/POST request body
        $content_type = $this->input->server('CONTENT_TYPE');
        if ($content_type && (strpos($content_type, 'application/json') !== false)) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $_POST = array_merge($_POST, $json);
            }
        } else if ($method === 'PUT') {
            parse_str(file_get_contents('php://input'), $put_data);
            if (is_array($put_data)) {
                $_POST = array_merge($_POST, $put_data);
            }
        }

        $id = $id ?: ($this->input->post('address_id') ?: ($this->input->post('id') ?: ($this->input->get('address_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid Address ID', null, 400);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $address = $this->General_model->getOne('user_addresses', ['id' => (int)$id]);
        if (!$address || (int)$address->user_id !== $user_id) {
            $this->response(false, 'Address not found or unauthorized access.', null, 404);
        }

        $update_data = [];

        if (isset($_POST['full_name'])) {
            $full_name = trim($this->input->post('full_name', TRUE));
            if (empty($full_name)) {
                $this->response(false, 'Full name cannot be empty.', null, 400);
            }
            $update_data['full_name'] = $full_name;
        }

        if (isset($_POST['mobile'])) {
            $mobile = trim($this->input->post('mobile', TRUE));
            if (empty($mobile)) {
                $this->response(false, 'Mobile cannot be empty.', null, 400);
            }
            $update_data['mobile'] = $mobile;
        }

        if (isset($_POST['address_line1'])) {
            $address_line1 = trim($this->input->post('address_line1', TRUE));
            if (empty($address_line1)) {
                $this->response(false, 'Address Line 1 cannot be empty.', null, 400);
            }
            $update_data['address_line1'] = $address_line1;
        }

        if (isset($_POST['address_line2'])) {
            $update_data['address_line2'] = trim($this->input->post('address_line2', TRUE)) ?: null;
        }

        if (isset($_POST['landmark'])) {
            $update_data['landmark'] = trim($this->input->post('landmark', TRUE)) ?: null;
        }

        if (isset($_POST['city'])) {
            $city = trim($this->input->post('city', TRUE));
            if (empty($city)) {
                $this->response(false, 'City cannot be empty.', null, 400);
            }
            $update_data['city'] = $city;
        }

        if (isset($_POST['state'])) {
            $state = trim($this->input->post('state', TRUE));
            if (empty($state)) {
                $this->response(false, 'State cannot be empty.', null, 400);
            }
            $update_data['state'] = $state;
        }

        if (isset($_POST['pincode'])) {
            $pincode = trim($this->input->post('pincode', TRUE));
            if (empty($pincode)) {
                $this->response(false, 'Pincode cannot be empty.', null, 400);
            }
            $update_data['pincode'] = $pincode;
        }

        if (isset($_POST['country'])) {
            $update_data['country'] = trim($this->input->post('country', TRUE)) ?: 'India';
        }

        $existing_count = $this->db->where('user_id', $user_id)->count_all_results('user_addresses');

        $is_default = (int)$address->is_default;
        if (isset($_POST['is_default'])) {
            $is_default = (int)$this->input->post('is_default');
        }

        if ($existing_count === 1) {
            $is_default = 1; // Force default if it's the user's only address
        }

        // If setting this to default, reset others
        if ($is_default === 1) {
            $this->db->update('user_addresses', ['is_default' => 0], ['user_id' => $user_id]);
        }

        $update_data['is_default'] = $is_default;
        $update_data['updated_at'] = date('Y-m-d H:i:s');

        if ($this->db->update('user_addresses', $update_data, ['id' => $address->id])) {
            $full_address = $this->General_model->getOne('user_addresses', ['id' => $address->id]);
            if ($full_address) {
                $full_address->id = (int)$full_address->id;
                $full_address->user_id = (int)$full_address->user_id;
                $full_address->is_default = (int)$full_address->is_default;
            }
            $this->response(true, 'Address updated successfully', $full_address, 200);
        } else {
            $this->response(false, 'Failed to update address', null, 500);
        }
    }

    /**
     * DELETE/POST api/addresses/delete/(:num)
     * Authenticated endpoint to delete a shipping address
     */
    public function delete_address($id = null)
    {
        $method = $this->input->method(TRUE);
        if ($method !== 'DELETE' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $id = $id ?: ($this->input->post('address_id') ?: ($this->input->post('id') ?: ($this->input->get('address_id') ?: $this->input->get('id'))));

        if (empty($id) || !is_numeric($id)) {
            $this->response(false, 'Invalid Address ID', null, 400);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        $address = $this->General_model->getOne('user_addresses', ['id' => (int)$id]);
        if (!$address || (int)$address->user_id !== $user_id) {
            $this->response(false, 'Address not found or unauthorized access.', null, 404);
        }

        $was_default = (int)$address->is_default;

        $this->db->trans_begin();

        // 1. Delete the address
        $this->db->delete('user_addresses', ['id' => $address->id]);

        // 2. If it was the default address, promote another one
        if ($was_default === 1) {
            $next_address = $this->db->where('user_id', $user_id)
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get('user_addresses')
                ->row();
            if ($next_address) {
                $this->db->update('user_addresses', ['is_default' => 1], ['id' => $next_address->id]);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to delete address due to database transaction error', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Address deleted successfully', null, 200);
        }
    }

    /**
     * GET api/get_referrals
     * Authenticated endpoint to fetch the user's direct referrals list
     */
    public function get_referrals()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $auth = $this->check_auth();
        $user_id = (int)$auth['decoded']->user_id;

        // Fetch users who registered using the current user's referral code (parent_id = current user's ID)
        $this->db->select('id, name, email, referral_code, status, created_at');
        $this->db->from('users');
        $this->db->where('parent_id', $user_id);
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $rows = $query->result();

        $referrals = [];
        foreach ($rows as $row) {
            $referrals[] = [
                'id'            => (int)$row->id,
                'name'          => $row->name,
                'email'         => $row->email,
                'referral_code' => $row->referral_code,
                'status'        => (int)$row->status,
                'created_at'    => $row->created_at
            ];
        }

        $this->response(true, 'Direct referrals retrieved successfully', [
            'referrals' => $referrals,
            'total'     => count($referrals)
        ], 200);
    }

    /**
     * GET api/privacy_policy
     * Returns the default Privacy Policy text
     */
    public function privacy_policy()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $policy_text =
            "<h3>Privacy Policy</h3>" .
            "<p><em>Last updated: " . date('F Y') . "</em></p>" .
            "<p>Your privacy is important to us. It is Divy Shakti's policy to respect your privacy regarding any information we may collect from you across our website and mobile application.</p>" .

            "<h4>1. Information We Collect</h4>" .
            "<p>We collect information you provide directly, such as your name, email address, phone number, delivery address, and payment details when you register, place an order, or request a wallet deposit. We may also collect referral and network data (such as your sponsor/referrer relationship) required to operate our MLM commission structure.</p>" .

            "<h4>2. How We Use Your Information</h4>" .
            "<ul>" .
            "<li>To create and manage your account</li>" .
            "<li>To process orders, payments, and wallet transactions</li>" .
            "<li>To calculate and credit referral commissions accurately</li>" .
            "<li>To communicate order updates, promotions, and support responses</li>" .
            "<li>To improve our app, detect fraud, and maintain security</li>" .
            "</ul>" .

            "<h4>3. Data Storage & Security</h4>" .
            "<p>We only retain collected information for as long as necessary to provide you with your requested service. What data we store, we protect within commercially acceptable means to prevent loss, theft, unauthorized access, disclosure, copying, use, or modification.</p>" .

            "<h4>4. Sharing of Information</h4>" .
            "<p>We do not sell your personal information. We do not share personally identifying information publicly or with third parties, except:</p>" .
            "<ul>" .
            "<li>When required to by law or a valid legal process</li>" .
            "<li>With payment gateways strictly to process transactions</li>" .
            "<li>With delivery partners strictly to fulfill your orders</li>" .
            "</ul>" .

            "<h4>5. Your Rights & Choices</h4>" .
            "<p>You may access, update, or request deletion of your personal data at any time via the app's Profile settings or the Delete Account option. You are free to refuse providing certain personal information, understanding that we may then be unable to offer some services.</p>" .

            "<h4>6. Third-Party Links</h4>" .
            "<p>Our website/app may link to external sites that are not operated by us. We have no control over the content and practices of these sites and cannot accept responsibility or liability for their respective privacy policies.</p>" .

            "<h4>7. Children's Privacy</h4>" .
            "<p>Our services are not directed at individuals under the age of 18. We do not knowingly collect personal information from minors.</p>" .

            "<h4>8. Changes to This Policy</h4>" .
            "<p>We may update this Privacy Policy from time to time. Continued use of our website/app after changes are posted constitutes acceptance of the revised policy.</p>" .

            "<h4>9. Contact Us</h4>" .
            "<p>If you have any questions about how we handle your data, please reach out to us through the Support section of the app.</p>";

        $this->response(true, 'Privacy Policy retrieved successfully', [
            'title'   => 'Privacy Policy',
            'content' => $policy_text
        ], 200);
    }

    /**
     * GET api/terms_conditions
     * Returns the default Terms & Conditions text
     */
    public function terms_conditions()
    {
        if ($this->input->method(TRUE) !== 'GET') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        $terms_text =
            "<h3>Terms & Conditions</h3>" .
            "<p><em>Last updated: " . date('F Y') . "</em></p>" .
            "<p>Welcome to Divy Shakti. By accessing or using our mobile application and services, you agree to be bound by the following terms and conditions. Please read them carefully.</p>" .

            "<ol>" .
            "<li><strong>Acceptance of Terms:</strong> By creating an account or purchasing products, you agree to comply with and be bound by these terms. If you do not agree, please discontinue use of the app.</li>" .

            "<li><strong>Eligibility:</strong> You must be at least 18 years old and legally capable of entering into binding contracts to register and use our services.</li>" .

            "<li><strong>User Account:</strong> You are responsible for maintaining the confidentiality of your account credentials and authentication token. You are liable for all activity performed under your account, and must notify us immediately of any unauthorized use.</li>" .

            "<li><strong>MLM and Referrals:</strong> Referral commissions and network structures must strictly follow our published commission guidelines. Fake accounts, self-referrals, or any attempt to manipulate the referral network for undue gain is strictly prohibited and will result in immediate account suspension and forfeiture of pending commissions.</li>" .

            "<li><strong>Orders and Products:</strong> Product availability, pricing, and delivery timelines are subject to change without prior notice. We reserve the right to cancel or refuse any order at our discretion, including in cases of suspected fraud or pricing errors.</li>" .

            "<li><strong>Wallet and Transactions:</strong> Wallet balances, deposits, withdrawals, and commission credits are subject to validation and approval by the admin team. All transactions are logged for audit purposes. Any dispute regarding a wallet transaction must be raised through Support within 7 days of the transaction date.</li>" .

            "<li><strong>Payments:</strong> All payments made through the app are processed via secure third-party payment gateways. Divy Shakti does not store your full payment card details.</li>" .

            "<li><strong>Prohibited Conduct:</strong> You agree not to misuse the platform for unlawful purposes, harass other users, attempt to breach system security, or reverse-engineer any part of the application.</li>" .

            "<li><strong>Account Suspension & Termination:</strong> We reserve the right to suspend or terminate accounts found to be in violation of these terms, including fraudulent referral activity, chargebacks, or abusive behavior.</li>" .

            "<li><strong>Account Deletion:</strong> You have the right to delete your account at any time using the Delete Account option. Deletion is permanent — your wallet balance, transaction history, and referral position will be permanently and irreversibly deleted.</li>" .

            "<li><strong>Limitation of Liability:</strong> Divy Shakti shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use of, or inability to use, our services.</li>" .

            "<li><strong>Governing Law:</strong> These terms shall be governed by and construed in accordance with the applicable laws of India, without regard to conflict of law principles.</li>" .

            "<li><strong>Changes to Terms:</strong> We reserve the right to update these terms at any time. Your continued use of the application after such changes constitutes your acceptance of the new terms.</li>" .
            "</ol>" .

            "<p>If you have any questions regarding these Terms & Conditions, please contact our support team through the app.</p>";

        $this->response(true, 'Terms & Conditions retrieved successfully', [
            'title'   => 'Terms & Conditions',
            'content' => $terms_text
        ], 200);
    }

    /**
     * GET/POST/DELETE api/delete_account
     * - GET: Returns steps to delete the account.
     * - POST/DELETE: Performs authenticated account deletion.
     */
    public function delete_account()
    {
        $method = $this->input->method(TRUE);

        if ($method === 'GET') {
            $steps = [
                "steps" => [
                    "Step 1: Go to the 'Profile' or 'Settings' tab in your app.",
                    "Step 2: Scroll down and tap on the 'Delete Account' button.",
                    "Step 3: Review the warning message carefully.",
                    "Step 4: Confirm the action on the confirmation dialog by entering your password or OTP if prompted.",
                    "Step 5: All of your personal profile data, addresses, active cart, wallet balance, and transaction history will be permanently deleted.",
                    "Step 6: Your referral position in the network tree will be removed, and any downline members will be reassigned per platform policy."
                ],
                "what_gets_deleted" => [
                    "Profile information (name, email, phone, address)",
                    "Profile image / avatar",
                    "Wallet balance and full transaction history",
                    "Saved addresses",
                    "Active and past cart data",
                    "Referral code and network position"
                ],
                "what_is_retained" => [
                    "Order records may be retained in anonymized form for legal, tax, and accounting compliance, as required by applicable law."
                ],
                "warning" => "WARNING: This action is permanent and irreversible. Your wallet balance and referral position will be permanently lost and cannot be recovered."
            ];
            $this->response(true, 'Steps to delete account retrieved successfully', $steps, 200);
        }

        if ($method !== 'DELETE' && $method !== 'POST') {
            $this->response(false, 'Method Not Allowed', null, 405);
        }

        // Authenticate the user token
        $auth = $this->check_auth();
        $user_id = (int) $auth['decoded']->user_id;

        // Retrieve user to check profile image
        $user = $this->General_model->getOne('users', ['id' => $user_id]);
        if (!$user) {
            $this->response(false, 'User not found.', null, 404);
        }

        // Delete profile image file if exists
        if (!empty($user->profile_image) && file_exists('./' . $user->profile_image)) {
            @unlink('./' . $user->profile_image);
        }

        $this->db->trans_begin();

        // Delete dependent records first to avoid orphaned rows / FK constraint issues
        $this->db->delete('wallet_transactions', ['user_id' => $user_id]);
        $this->db->delete('addresses', ['user_id' => $user_id]);
        $this->db->delete('cart', ['user_id' => $user_id]);

        // Finally delete the user account itself
        $this->db->delete('users', ['id' => $user_id]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->response(false, 'Failed to delete account due to a database transaction error.', null, 500);
        } else {
            $this->db->trans_commit();
            $this->response(true, 'Your account has been deleted successfully.', [
                'message_detail' => 'All associated profile information, wallet history, addresses, and network position have been permanently deleted.'
            ], 200);
        }
    }
}
