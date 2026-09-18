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
     * Validates max 2MB (2048 KB) per image and allowed formats.
     *
     * @param int $product_id
     * @return array
     */
    private function process_gallery_uploads($product_id)
    {
        $result = ['success' => true, 'uploaded_count' => 0, 'errors' => []];

        if (empty($_FILES['gallery_images']['name']) || !is_array($_FILES['gallery_images']['name'])) {
            return $result;
        }

        $upload_path = './uploads/products/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload');
        $this->upload->initialize($config);

        $file_count = count($_FILES['gallery_images']['name']);
        for ($i = 0; $i < $file_count; $i++) {
            if (empty($_FILES['gallery_images']['name'][$i])) {
                continue;
            }

            // Client/Server size check: 2MB limit (2 * 1024 * 1024 bytes)
            if ($_FILES['gallery_images']['size'][$i] > 2 * 1024 * 1024) {
                $result['errors'][] = "Image '{$_FILES['gallery_images']['name'][$i]}' exceeds 2MB limit.";
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
                $rel_path = 'uploads/products/' . $upload_data['file_name'];
                $this->General_model->insert('product_gallery', [
                    'product_id' => (int)$product_id,
                    'image'      => $rel_path,
                    'is_default' => 0,
                    'sort_order' => $result['uploaded_count'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $result['uploaded_count']++;
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
                $category_id = (int)$this->input->post('category_id');
                $name = $this->input->post('name', TRUE);
                $description = $this->input->post('description', TRUE) ?: null;
                $price = (float)$this->input->post('price');
                
                $stock = $this->input->post('stock');
                $stock = ($stock !== NULL && $stock !== '') ? (int)$stock : 0;
                
                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Handle primary/default image upload (max 2MB)
                $image_path = null;
                $upload_success = TRUE;

                if (!empty($_FILES['image']['name'])) {
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        $this->session->set_flashdata('error', 'Main product image exceeds 2MB limit.');
                        $upload_success = FALSE;
                    } else {
                        $upload_path = './uploads/products/';
                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }

                        $config['upload_path']   = $upload_path;
                        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                        $config['max_size']      = 2048; // 2MB
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata('error', 'Main image error: ' . $this->upload->display_errors('', ''));
                            $upload_success = FALSE;
                        } else {
                            $upload_data = $this->upload->data();
                            $image_path = 'uploads/products/' . $upload_data['file_name'];
                        }
                    }
                }

                if ($upload_success) {
                    $insert_data = [
                        'category_id'    => $category_id,
                        'name'           => $name,
                        'slug'           => null,
                        'description'    => $description,
                        'price'          => $price,
                        'image'          => $image_path,
                        'stock'          => $stock,
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

                        // 2. Process secondary gallery images
                        $gallery_res = $this->process_gallery_uploads($product_id);

                        // If no main image was uploaded, but gallery images were, make the first one default
                        if (!$image_path && $gallery_res['uploaded_count'] > 0) {
                            $first_gallery = $this->db->where('product_id', $product_id)->order_by('id', 'ASC')->get('product_gallery')->row();
                            if ($first_gallery) {
                                $this->db->update('product_gallery', ['is_default' => 1], ['id' => $first_gallery->id]);
                                $this->db->update('products', ['image' => $first_gallery->image], ['id' => $product_id]);
                            }
                        }

                        $msg = 'Product added successfully!';
                        if (!empty($gallery_res['errors'])) {
                            $msg .= ' (Some gallery images failed: ' . implode(', ', $gallery_res['errors']) . ')';
                        }
                        $this->session->set_flashdata('success', $msg);
                        redirect('admin/products');
                    }
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
                    if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                        $this->session->set_flashdata('error', 'Main product image exceeds 2MB limit.');
                        $upload_success = FALSE;
                    } else {
                        $upload_path = './uploads/products/';
                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }

                        $config['upload_path']   = $upload_path;
                        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                        $config['max_size']      = 2048; // 2MB
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                            $upload_success = FALSE;
                        } else {
                            $upload_data = $this->upload->data();
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
                }

                if ($upload_success) {
                    $update_data = [
                        'category_id'    => $category_id,
                        'name'           => $name,
                        'slug'           => null,
                        'description'    => $description,
                        'price'          => $price,
                        'image'          => $image_path,
                        'stock'          => $stock,
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
                    $this->session->set_flashdata('success', $msg);
                    redirect('admin/products/edit/' . $id);
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('product_edit', $data);
        $this->load->view('templates/footer');
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
