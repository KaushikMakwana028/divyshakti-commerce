<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
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

        // Ensure products and categories slug columns allow NULL
        $this->ensure_slug_is_nullable();
    }

    /**
     * Ensures `products`.`slug` and `categories`.`slug` columns allow NULL values
     */
    private function ensure_slug_is_nullable()
    {
        try {
            $slug_col = $this->db->query("SHOW COLUMNS FROM `products` LIKE 'slug'")->row();
            if ($slug_col && strtoupper($slug_col->Null) === 'NO') {
                $this->db->query("ALTER TABLE `products` MODIFY `slug` VARCHAR(255) NULL DEFAULT NULL");
            }
        } catch (\Throwable $e) {}

        try {
            $cat_slug_col = $this->db->query("SHOW COLUMNS FROM `categories` LIKE 'slug'")->row();
            if ($cat_slug_col && strtoupper($cat_slug_col->Null) === 'NO') {
                $this->db->query("ALTER TABLE `categories` MODIFY `slug` VARCHAR(255) NULL DEFAULT NULL");
            }
        } catch (\Throwable $e) {}
    }

    /**
     * Helper to resolve slug value safely: returns NULL if column allows NULL,
     * otherwise falls back to a safe slug string to prevent MySQL error 1048.
     */
    private function get_safe_slug($name)
    {
        try {
            $col = $this->db->query("SHOW COLUMNS FROM `products` LIKE 'slug'")->row();
            if ($col && strtoupper($col->Null) === 'NO') {
                try {
                    $this->db->query("ALTER TABLE `products` MODIFY `slug` VARCHAR(255) NULL DEFAULT NULL");
                    $col_check = $this->db->query("SHOW COLUMNS FROM `products` LIKE 'slug'")->row();
                    if ($col_check && strtoupper($col_check->Null) === 'YES') {
                        return null;
                    }
                } catch (\Throwable $ex) {}

                // If column is strictly NOT NULL, provide a generated slug to avoid crash
                return url_title($name, 'dash', TRUE) ?: ('prod-' . time());
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * Lists all products with search, category filtering, status filtering, and pagination
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $category_id = $this->input->get('category_id', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('products.name', $search);
            $this->db->or_like('products.description', $search);
            $this->db->group_end();
        }
        if (!empty($category_id)) {
            $this->db->where('products.category_id', (int)$category_id);
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('products.status', (int)$status);
        }

        // Count query
        $this->db->from('products');
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->select('products.*, categories.name as category_name');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->order_by('products.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['products'] = $this->db->get()->result();

        $data['categories'] = $this->General_model->getAll('categories', ['status' => 1]);

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['category_id'] = $category_id;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('product_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Helper to process multiple secondary gallery image uploads
    /**
     * Resizes and compresses an image to prevent server bloat and crashes.
     * Automatically downscales images wider/taller than $max_dim (default 1400px)
     * and applies optimal compression (JPEG/WebP 82%, PNG 7).
     *
     * @param string $full_path Absolute or relative path to image file
     * @param int $max_dim Maximum width/height
     * @param int $quality JPEG/WebP quality (0-100)
     */
    private function optimize_image($full_path, $max_dim = 1400, $quality = 82)
    {
        if (!file_exists($full_path) || !extension_loaded('gd')) {
            return;
        }

        $info = @getimagesize($full_path);
        if (!$info) {
            return;
        }

        list($orig_w, $orig_h, $type) = $info;
        if ($orig_w <= 0 || $orig_h <= 0) {
            return;
        }

        // Only downscale if larger than $max_dim
        $scale = min(1, $max_dim / max($orig_w, $orig_h));
        $target_w = (int)round($orig_w * $scale);
        $target_h = (int)round($orig_h * $scale);

        // If file is already small and within dimensions, skip reprocessing
        $filesize = @filesize($full_path);
        if ($scale >= 1 && $filesize < 350 * 1024) {
            return;
        }

        $src_img = null;
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src_img = @imagecreatefromjpeg($full_path);
                break;
            case IMAGETYPE_PNG:
                $src_img = @imagecreatefrompng($full_path);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagecreatefromwebp')) {
                    $src_img = @imagecreatefromwebp($full_path);
                }
                break;
            case IMAGETYPE_GIF:
                $src_img = @imagecreatefromgif($full_path);
                break;
        }

        if (!$src_img) {
            return;
        }

        // Fix EXIF orientation if available
        if (function_exists('exif_read_data')) {
            try {
                $exif = @exif_read_data($full_path);
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $src_img = imagerotate($src_img, 180, 0);
                            break;
                        case 6:
                            $src_img = imagerotate($src_img, -90, 0);
                            $t = $target_w;
                            $target_w = $target_h;
                            $target_h = $t;
                            break;
                        case 8:
                            $src_img = imagerotate($src_img, 90, 0);
                            $t = $target_w;
                            $target_w = $target_h;
                            $target_h = $t;
                            break;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $dst_img = imagecreatetruecolor($target_w, $target_h);
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_WEBP || $type === IMAGETYPE_GIF) {
            imagealphablending($dst_img, false);
            imagesavealpha($dst_img, true);
            $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
            imagefilledrectangle($dst_img, 0, 0, $target_w, $target_h, $transparent);
        }

        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $target_w, $target_h, imagesx($src_img), imagesy($src_img));

        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($dst_img, $full_path, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($dst_img, $full_path, 7);
                break;
            case IMAGETYPE_WEBP:
                if (function_exists('imagewebp')) {
                    imagewebp($dst_img, $full_path, $quality);
                }
                break;
            case IMAGETYPE_GIF:
                imagegif($dst_img, $full_path);
                break;
        }

        @imagedestroy($src_img);
        @imagedestroy($dst_img);
    }

    /**
     * Helper to process multiple gallery images with automatic optimization.
     *
     * @param int $product_id
     * @param int|null $chosen_default_index
     * @return array
     */
    private function process_gallery_uploads($product_id, $chosen_default_index = null)
    {
        @ini_set('memory_limit', '256M');
        @set_time_limit(300);

        $result = ['success' => true, 'uploaded_count' => 0, 'errors' => [], 'uploaded_images' => []];

        if (empty($_FILES['gallery_images']['name']) || !is_array($_FILES['gallery_images']['name'])) {
            return $result;
        }

        $upload_path = './uploads/products/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 10240; // 10MB (optimized on save)
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        // Check if product already has a default image
        $existing_default = $this->db->where([
            'product_id' => (int)$product_id,
            'is_default' => 1
        ])->get('product_gallery')->row();

        $file_count = count($_FILES['gallery_images']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if (empty($_FILES['gallery_images']['name'][$i])) {
                continue;
            }

            $_FILES['single_gallery_file'] = [
                'name'     => $_FILES['gallery_images']['name'][$i],
                'type'     => $_FILES['gallery_images']['type'][$i],
                'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                'error'    => $_FILES['gallery_images']['error'][$i],
                'size'     => $_FILES['gallery_images']['size'][$i]
            ];

            if (!$this->upload->do_upload('single_gallery_file')) {
                $result['errors'][] = $_FILES['gallery_images']['name'][$i] . ': ' . $this->upload->display_errors('', '');
            } else {
                $upload_data = $this->upload->data();
                $full_path = $upload_path . $upload_data['file_name'];
                $rel_path = 'uploads/products/' . $upload_data['file_name'];

                // Automatically downscale and compress to keep server light & fast
                $this->optimize_image($full_path);

                $is_this_default = 0;
                if ($chosen_default_index !== null && (int)$chosen_default_index === $result['uploaded_count']) {
                    $is_this_default = 1;
                }

                $gallery_id = $this->General_model->insert('product_gallery', [
                    'product_id' => (int)$product_id,
                    'image'      => $rel_path,
                    'is_default' => $is_this_default,
                    'sort_order' => $result['uploaded_count'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $result['uploaded_images'][] = [
                    'id'         => $gallery_id,
                    'path'       => $rel_path,
                    'is_default' => $is_this_default
                ];

                if ($is_this_default) {
                    $this->db->update('product_gallery', ['is_default' => 0], ['product_id' => (int)$product_id, 'id !=' => $gallery_id]);
                    $this->General_model->update('products', ['id' => (int)$product_id], ['image' => $rel_path]);
                }

                $result['uploaded_count']++;
            }
        }

        // If no default was specified and product has no main image yet, pick the 1st uploaded image
        $prod = $this->General_model->getOne('products', ['id' => (int)$product_id]);
        if ((empty($prod->image) || !$existing_default) && !empty($result['uploaded_images'])) {
            $has_default = false;
            foreach ($result['uploaded_images'] as $uimg) {
                if ($uimg['is_default'] === 1) {
                    $has_default = true;
                    break;
                }
            }
            if (!$has_default) {
                $first = $result['uploaded_images'][0];
                $this->db->update('product_gallery', ['is_default' => 1], ['id' => $first['id']]);
                $this->General_model->update('products', ['id' => (int)$product_id], ['image' => $first['path']]);
            }
        }

        if (!empty($result['errors'])) {
            $result['success'] = false;
        }

        return $result;
    }

    /**
     * Adds a new product with default image and optional secondary gallery images
     */
    public function add()
    {
        $data['categories'] = $this->General_model->getAll('categories', ['status' => 1]);
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
            $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('stock', 'Stock', 'integer|greater_than_equal_to[0]');

            if ($this->form_validation->run() === TRUE) {
                @ini_set('memory_limit', '256M');
                @set_time_limit(300);

                $category_id = (int)$this->input->post('category_id');
                $name = $this->input->post('name', TRUE);
                $description = $this->input->post('description', TRUE) ?: null;
                $price = (float)$this->input->post('price');

                $stock = $this->input->post('stock');
                $stock = ($stock !== NULL && $stock !== '') ? (int)$stock : 0;

                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Handle primary/default image upload (up to 10MB, auto-compressed)
                $image_path = null;
                $upload_success = TRUE;

                if (!empty($_FILES['image']['name'])) {
                    $upload_path = './uploads/products/';
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                    $config['max_size']      = 10240; // 10MB
                    $config['encrypt_name']  = TRUE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('image')) {
                        $err_msg = 'Main image error: ' . $this->upload->display_errors('', '');
                        if ($this->input->is_ajax_request()) {
                            return $this->output->set_content_type('application/json')->set_output(json_encode([
                                'status'  => false,
                                'message' => $err_msg
                            ]));
                        }
                        $this->session->set_flashdata('error', $err_msg);
                        $upload_success = FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $this->optimize_image($upload_path . $upload_data['file_name']);
                        $image_path = 'uploads/products/' . $upload_data['file_name'];
                    }
                }

                $sizes_input = $this->input->post('sizes');
                $sizes_str = null;
                if (is_array($sizes_input)) {
                    $valid_sizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', 'Free size'];
                    $selected_sizes = array_values(array_intersect($valid_sizes, $sizes_input));
                    $sizes_str = !empty($selected_sizes) ? implode(',', $selected_sizes) : null;
                } elseif (is_string($sizes_input)) {
                    $sizes_str = trim($sizes_input) ?: null;
                }

                if ($upload_success) {
                    $insert_data = [
                        'category_id'    => $category_id,
                        'name'           => $name,
                        'slug'           => $this->get_safe_slug($name),
                        'description'    => $description,
                        'price'          => $price,
                        'image'          => $image_path,
                        'stock'          => $stock,
                        'sizes'          => $sizes_str,
                        'status'         => $status,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ];

                    $product_id = $this->General_model->insert('products', $insert_data);

                    if ($product_id) {
                        // 1. If primary image uploaded, insert into product_gallery as default
                        if ($image_path) {
                            $this->General_model->insert('product_gallery', [
                                'product_id' => (int)$product_id,
                                'image'      => $image_path,
                                'is_default' => 1,
                                'sort_order' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                        // 2. Process secondary gallery images (support chosen default index)
                        $chosen_default = $this->input->post('default_gallery_index');
                        $chosen_default_idx = ($chosen_default !== null && $chosen_default !== '') ? (int)$chosen_default : null;
                        $gallery_res = $this->process_gallery_uploads($product_id, $chosen_default_idx);

                        // If no main image was uploaded, but gallery images were, make the first or chosen one default
                        if (!$image_path && $gallery_res['uploaded_count'] > 0) {
                            $def_gallery = $this->db->where(['product_id' => $product_id, 'is_default' => 1])->get('product_gallery')->row();
                            if (!$def_gallery) {
                                $def_gallery = $this->db->where('product_id', $product_id)->order_by('id', 'ASC')->get('product_gallery')->row();
                                if ($def_gallery) {
                                    $this->db->update('product_gallery', ['is_default' => 1], ['id' => $def_gallery->id]);
                                }
                            }
                            if ($def_gallery) {
                                $this->db->update('products', ['image' => $def_gallery->image], ['id' => $product_id]);
                            }
                        }

                        $msg = 'Product added successfully!';
                        if (!empty($gallery_res['errors'])) {
                            $msg .= ' (Some gallery images failed: ' . implode(', ', $gallery_res['errors']) . ')';
                        }

                        if ($this->input->is_ajax_request()) {
                            $this->session->set_flashdata('success', $msg);
                            return $this->output->set_content_type('application/json')->set_output(json_encode([
                                'status'     => true,
                                'message'    => $msg,
                                'product_id' => (int)$product_id,
                                'redirect'   => base_url('admin/products')
                            ]));
                        }

                        $this->session->set_flashdata('success', $msg);
                        redirect('admin/products');
                    }
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    return $this->output->set_content_type('application/json')->set_output(json_encode([
                        'status'  => false,
                        'message' => strip_tags(validation_errors())
                    ]));
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('product_add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Edits an existing product with primary image and gallery management
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            redirect('admin/products');
        }

        $product = $this->General_model->getOne('products', ['id' => $id]);
        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('admin/products');
        }

        $data['product'] = $product;
        $data['gallery'] = $this->db->order_by('is_default DESC, id ASC')->get_where('product_gallery', ['product_id' => (int)$id])->result();
        $data['categories'] = $this->General_model->getAll('categories', ['status' => 1]);
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
            $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('stock', 'Stock', 'integer|greater_than_equal_to[0]');

            if ($this->form_validation->run() === TRUE) {
                @ini_set('memory_limit', '256M');
                @set_time_limit(300);

                $category_id = (int)$this->input->post('category_id');
                $name = $this->input->post('name', TRUE);
                $description = $this->input->post('description', TRUE) ?: null;
                $price = (float)$this->input->post('price');

                $stock = $this->input->post('stock');
                $stock = ($stock !== NULL && $stock !== '') ? (int)$stock : 0;

                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                $image_path = $product->image;
                $upload_success = TRUE;

                // Handle primary image update
                if (!empty($_FILES['image']['name'])) {
                    $upload_path = './uploads/products/';
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                    $config['max_size']      = 10240; // 10MB
                    $config['encrypt_name']  = TRUE;

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if (!$this->upload->do_upload('image')) {
                        $err_msg = 'Main image error: ' . $this->upload->display_errors('', '');
                        if ($this->input->is_ajax_request()) {
                            return $this->output->set_content_type('application/json')->set_output(json_encode([
                                'status'  => false,
                                'message' => $err_msg
                            ]));
                        }
                        $this->session->set_flashdata('error', $err_msg);
                        $upload_success = FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $this->optimize_image($upload_path . $upload_data['file_name']);
                        $image_path = 'uploads/products/' . $upload_data['file_name'];

                        // Set existing default images to 0
                        $this->db->update('product_gallery', ['is_default' => 0], ['product_id' => (int)$id]);

                        // Insert new image into product_gallery as default
                        $this->General_model->insert('product_gallery', [
                            'product_id' => (int)$id,
                            'image'      => $image_path,
                            'is_default' => 1,
                            'sort_order' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }

                $sizes_input = $this->input->post('sizes');
                $sizes_str = null;
                if (is_array($sizes_input)) {
                    $valid_sizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL', 'Free size'];
                    $selected_sizes = array_values(array_intersect($valid_sizes, $sizes_input));
                    $sizes_str = !empty($selected_sizes) ? implode(',', $selected_sizes) : null;
                } elseif (is_string($sizes_input)) {
                    $sizes_str = trim($sizes_input) ?: null;
                }

                if ($upload_success) {
                    $update_data = [
                        'category_id'    => $category_id,
                        'name'           => $name,
                        'slug'           => $this->get_safe_slug($name),
                        'description'    => $description,
                        'price'          => $price,
                        'image'          => $image_path,
                        'stock'          => $stock,
                        'sizes'          => $sizes_str,
                        'status'         => $status,
                        'updated_at'     => date('Y-m-d H:i:s')
                    ];

                    $this->General_model->update('products', ['id' => $id], $update_data);

                    // Process any uploaded secondary gallery images
                    $gallery_res = $this->process_gallery_uploads($id);

                    $msg = 'Product updated successfully!';
                    if (!empty($gallery_res['errors'])) {
                        $msg .= ' (Some gallery images failed: ' . implode(', ', $gallery_res['errors']) . ')';
                    }

                    if ($this->input->is_ajax_request()) {
                        $this->session->set_flashdata('success', $msg);
                        return $this->output->set_content_type('application/json')->set_output(json_encode([
                            'status'     => true,
                            'message'    => $msg,
                            'product_id' => (int)$id,
                            'redirect'   => base_url('admin/products')
                        ]));
                    }

                    $this->session->set_flashdata('success', $msg);
                    redirect('admin/products');
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    return $this->output->set_content_type('application/json')->set_output(json_encode([
                        'status'  => false,
                        'message' => strip_tags(validation_errors())
                    ]));
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('product_edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * AJAX endpoint to upload gallery images one by one or in batches.
     * Prevents post_max_size / timeout crashes and updates gallery in real time without page reload.
     */
    public function ajax_upload_gallery($product_id = null)
    {
        @ini_set('memory_limit', '256M');
        @set_time_limit(300);

        if (!$this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'AJAX request required.'
            ]));
        }

        if (empty($product_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Product ID is missing.'
            ]));
        }

        $product = $this->General_model->getOne('products', ['id' => (int)$product_id]);
        if (!$product) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Product not found.'
            ]));
        }

        // Check incoming file from standard keys: 'file', 'image', 'gallery_image', or 'gallery_images'
        $file_key = null;
        if (!empty($_FILES['file']['name'])) {
            $file_key = 'file';
        } elseif (!empty($_FILES['image']['name'])) {
            $file_key = 'image';
        } elseif (!empty($_FILES['gallery_image']['name'])) {
            $file_key = 'gallery_image';
        } elseif (!empty($_FILES['gallery_images']['name'])) {
            $file_key = 'gallery_images';
        }

        if (!$file_key) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'No image file uploaded.'
            ]));
        }

        $upload_path = './uploads/products/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 10240; // 10MB (optimized on save)
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file_key)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => $this->upload->display_errors('', '')
            ]));
        }

        $upload_data = $this->upload->data();
        $full_filepath = $upload_path . $upload_data['file_name'];
        $rel_path = 'uploads/products/' . $upload_data['file_name'];

        // Automatically optimize & compress to prevent bloat
        $this->optimize_image($full_filepath);

        // Check if product currently has any default image
        $existing_default = $this->db->where([
            'product_id' => (int)$product_id,
            'is_default' => 1
        ])->get('product_gallery')->row();

        $is_default = 0;
        if (!$existing_default || empty($product->image)) {
            $is_default = 1;
            // Update products.image as well
            $this->General_model->update('products', ['id' => (int)$product_id], ['image' => $rel_path]);
        }

        // Get max sort_order
        $last_item = $this->db->where('product_id', (int)$product_id)->order_by('sort_order', 'DESC')->get('product_gallery')->row();
        $sort_order = $last_item ? ((int)$last_item->sort_order + 1) : 0;

        $gallery_id = $this->General_model->insert('product_gallery', [
            'product_id' => (int)$product_id,
            'image'      => $rel_path,
            'is_default' => $is_default,
            'sort_order' => $sort_order,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $total_count = $this->db->where('product_id', (int)$product_id)->count_all_results('product_gallery');

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'status'      => true,
            'message'     => 'Image uploaded successfully.',
            'gallery_id'  => (int)$gallery_id,
            'product_id'  => (int)$product_id,
            'image_url'   => base_url($rel_path),
            'rel_path'    => $rel_path,
            'is_default'  => $is_default,
            'total_count' => $total_count
        ]));
    }

    /**
     * AJAX endpoint to set an image as default without page reload.
     */
    public function ajax_set_default($product_id = null, $gallery_id = null)
    {
        if (!$this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'AJAX request required.'
            ]));
        }

        if (empty($product_id) || empty($gallery_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Invalid parameters.'
            ]));
        }

        $gallery_item = $this->General_model->getOne('product_gallery', [
            'id'         => (int)$gallery_id,
            'product_id' => (int)$product_id
        ]);

        if (!$gallery_item) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Gallery image not found.'
            ]));
        }

        $this->db->update('product_gallery', ['is_default' => 0], ['product_id' => (int)$product_id]);
        $this->db->update('product_gallery', ['is_default' => 1], ['id' => (int)$gallery_id]);
        $this->General_model->update('products', ['id' => (int)$product_id], [
            'image'      => $gallery_item->image,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'status'     => true,
            'message'    => 'Default product image updated successfully.',
            'image_url'  => base_url($gallery_item->image),
            'gallery_id' => (int)$gallery_id
        ]));
    }

    /**
     * AJAX endpoint to delete a gallery image without page reload.
     */
    public function ajax_delete_gallery($product_id = null, $gallery_id = null)
    {
        if (!$this->input->is_ajax_request()) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'AJAX request required.'
            ]));
        }

        if (empty($product_id) || empty($gallery_id)) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Invalid parameters.'
            ]));
        }

        $gallery_item = $this->General_model->getOne('product_gallery', [
            'id'         => (int)$gallery_id,
            'product_id' => (int)$product_id
        ]);

        if (!$gallery_item) {
            return $this->output->set_content_type('application/json')->set_output(json_encode([
                'status'  => false,
                'message' => 'Gallery image not found.'
            ]));
        }

        if (!empty($gallery_item->image) && file_exists('./' . $gallery_item->image)) {
            @unlink('./' . $gallery_item->image);
        }

        $was_default = ((int)$gallery_item->is_default === 1);
        $this->db->delete('product_gallery', ['id' => (int)$gallery_id]);

        $new_default_id = null;
        $new_default_url = null;

        if ($was_default) {
            $next = $this->db->where('product_id', (int)$product_id)->order_by('id', 'ASC')->get('product_gallery')->row();
            if ($next) {
                $this->db->update('product_gallery', ['is_default' => 1], ['id' => $next->id]);
                $this->db->update('products', ['image' => $next->image], ['id' => (int)$product_id]);
                $new_default_id = (int)$next->id;
                $new_default_url = base_url($next->image);
            } else {
                $this->db->update('products', ['image' => null], ['id' => (int)$product_id]);
            }
        }

        $total_count = $this->db->where('product_id', (int)$product_id)->count_all_results('product_gallery');

        return $this->output->set_content_type('application/json')->set_output(json_encode([
            'status'          => true,
            'message'         => 'Gallery image deleted successfully.',
            'was_default'     => $was_default,
            'new_default_id'  => $new_default_id,
            'new_default_url' => $new_default_url,
            'total_count'     => $total_count
        ]));
    }

    /**
     * Sets a specific gallery image as the primary default image
     */
    public function set_default_image($product_id = null, $gallery_id = null)
    {
        if (empty($product_id) || empty($gallery_id)) {
            redirect('admin/products');
        }

        $gallery_item = $this->General_model->getOne('product_gallery', [
            'id'         => (int)$gallery_id,
            'product_id' => (int)$product_id
        ]);

        if (!$gallery_item) {
            $this->session->set_flashdata('error', 'Gallery image not found.');
            redirect('admin/products/edit/' . $product_id);
        }

        // Set all other images to is_default = 0
        $this->db->update('product_gallery', ['is_default' => 0], ['product_id' => (int)$product_id]);
        // Set this image to is_default = 1
        $this->db->update('product_gallery', ['is_default' => 1], ['id' => (int)$gallery_id]);
        // Sync products.image with the new default image
        $this->db->update('products', [
            'image'      => $gallery_item->image,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => (int)$product_id]);

        $this->session->set_flashdata('success', 'Default product image updated successfully.');
        redirect('admin/products/edit/' . $product_id);
    }

    /**
     * Deletes a gallery image and file from disk
     */
    public function delete_gallery_image($product_id = null, $gallery_id = null)
    {
        if (empty($product_id) || empty($gallery_id)) {
            redirect('admin/products');
        }

        $gallery_item = $this->General_model->getOne('product_gallery', [
            'id'         => (int)$gallery_id,
            'product_id' => (int)$product_id
        ]);

        if (!$gallery_item) {
            $this->session->set_flashdata('error', 'Gallery image not found.');
            redirect('admin/products/edit/' . $product_id);
        }

        // Delete physical file
        if (!empty($gallery_item->image) && file_exists('./' . $gallery_item->image)) {
            @unlink('./' . $gallery_item->image);
        }

        // Delete DB record
        $this->db->delete('product_gallery', ['id' => (int)$gallery_id]);

        // If the deleted image was the default image, pick another remaining image
        if ((int)$gallery_item->is_default === 1) {
            $next = $this->db->where('product_id', (int)$product_id)->order_by('id', 'ASC')->get('product_gallery')->row();
            if ($next) {
                $this->db->update('product_gallery', ['is_default' => 1], ['id' => $next->id]);
                $this->db->update('products', ['image' => $next->image], ['id' => (int)$product_id]);
            } else {
                $this->db->update('products', ['image' => null], ['id' => (int)$product_id]);
            }
        }

        $this->session->set_flashdata('success', 'Gallery image removed successfully.');
        redirect('admin/products/edit/' . $product_id);
    }

    /**
     * Deletes a product and all its gallery images
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            redirect('admin/products');
        }

        $product = $this->General_model->getOne('products', ['id' => $id]);
        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('admin/products');
        }

        // Delete all associated gallery images from disk
        $gallery_items = $this->General_model->getAll('product_gallery', ['product_id' => (int)$id]);
        foreach ($gallery_items as $item) {
            if (!empty($item->image) && file_exists('./' . $item->image)) {
                @unlink('./' . $item->image);
            }
        }
        $this->db->delete('product_gallery', ['product_id' => (int)$id]);

        // Delete primary image file if still around
        if (!empty($product->image) && file_exists('./' . $product->image)) {
            @unlink('./' . $product->image);
        }

        // Delete product from database
        $this->db->delete('products', ['id' => $id]);

        $this->session->set_flashdata('success', 'Product and gallery images deleted successfully!');
        redirect('admin/products');
    }

    /**
     * View details of a specific product with its gallery images
     */
    public function detail($id = null)
    {
        if (empty($id)) {
            redirect('admin/products');
        }

        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', (int)$id);
        $product = $this->db->get()->row();

        if (!$product) {
            $this->session->set_flashdata('error', 'Product not found.');
            redirect('admin/products');
        }

        $data['product'] = $product;
        $data['gallery'] = $this->db->order_by('is_default DESC, id ASC')->get_where('product_gallery', ['product_id' => (int)$id])->result();
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('product_detail', $data);
        $this->load->view('templates/footer');
    }
}
