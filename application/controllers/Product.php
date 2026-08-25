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
     * Adds a new product
     */
    public function add()
    {
        $data['categories'] = $this->General_model->getAll('categories', ['status' => 1]);
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);
        
        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
            $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[products.slug]', [
                'is_unique' => 'This product slug is already in use.'
            ]);
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('stock', 'Stock', 'integer|greater_than_equal_to[0]');

            if ($this->form_validation->run() === TRUE) {
                $category_id = (int)$this->input->post('category_id');
                $name = $this->input->post('name', TRUE);
                $slug = url_title(strtolower($this->input->post('slug', TRUE)));
                $description = $this->input->post('description', TRUE) ?: null;
                $price = (float)$this->input->post('price');
                
                $stock = $this->input->post('stock');
                $stock = ($stock !== NULL && $stock !== '') ? (int)$stock : 0;
                
                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Handle image upload
                $image_path = null;
                $upload_success = TRUE;

                if (!empty($_FILES['image']['name'])) {
                    $upload_path = './uploads/products/';
                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    $config['upload_path']   = $upload_path;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                    $config['max_size']      = 2048; // 2MB
                    $config['encrypt_name']  = TRUE;

                    $this->load->library('upload', $config);

                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        $upload_success = FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $image_path = 'uploads/products/' . $upload_data['file_name'];
                    }
                }

                if ($upload_success) {
                    $insert_data = [
                        'category_id'    => $category_id,
                        'name'           => $name,
                        'slug'           => $slug,
                        'description'    => $description,
                        'price'          => $price,
                        'image'          => $image_path,
                        'stock'          => $stock,
                        'status'         => $status,
                        'created_at'     => date('Y-m-d H:i:s'),
                        'updated_at'     => date('Y-m-d H:i:s')
                    ];

                    $this->General_model->insert('products', $insert_data);
                    $this->session->set_flashdata('success', 'Product added successfully!');
                    redirect('admin/products');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('product_add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Edits an existing product
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
        $data['categories'] = $this->General_model->getAll('categories', ['status' => 1]);
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);
        
        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('category_id', 'Category', 'required|numeric');
            $this->form_validation->set_rules('name', 'Product Name', 'required|trim');
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('stock', 'Stock', 'integer|greater_than_equal_to[0]');

            if ($this->form_validation->run() === TRUE) {
                $category_id = (int)$this->input->post('category_id');
                $name = $this->input->post('name', TRUE);
                $slug = url_title(strtolower($this->input->post('slug', TRUE)));
                $description = $this->input->post('description', TRUE) ?: null;
                $price = (float)$this->input->post('price');
                
                $stock = $this->input->post('stock');
                $stock = ($stock !== NULL && $stock !== '') ? (int)$stock : 0;
                
                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Unique check for slug excluding current product
                $slug_check = $this->General_model->getOne('products', ['slug' => $slug, 'id !=' => $id]);

                if ($slug_check) {
                    $this->session->set_flashdata('error', 'This slug is already used by another product.');
                } else {
                    $image_path = $product->image;
                    $upload_success = TRUE;

                    if (!empty($_FILES['image']['name'])) {
                        $upload_path = './uploads/products/';
                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }

                        $config['upload_path']   = $upload_path;
                        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                        $config['max_size']      = 2048;
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                            $upload_success = FALSE;
                        } else {
                            $upload_data = $this->upload->data();

                            // Delete old image file
                            if (!empty($product->image) && file_exists('./' . $product->image)) {
                                @unlink('./' . $product->image);
                            }

                            $image_path = 'uploads/products/' . $upload_data['file_name'];
                        }
                    }

                    if ($upload_success) {
                        $update_data = [
                            'category_id'    => $category_id,
                            'name'           => $name,
                            'slug'           => $slug,
                            'description'    => $description,
                            'price'          => $price,
                            'image'          => $image_path,
                            'stock'          => $stock,
                            'status'         => $status,
                            'updated_at'     => date('Y-m-d H:i:s')
                        ];

                        $this->General_model->update('products', ['id' => $id], $update_data);
                        $this->session->set_flashdata('success', 'Product updated successfully!');
                        redirect('admin/products');
                    }
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('product_edit', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Deletes a product
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

        // Delete associated image file
        if (!empty($product->image) && file_exists('./' . $product->image)) {
            @unlink('./' . $product->image);
        }

        // Delete from database
        $this->db->delete('products', ['id' => $id]);

        $this->session->set_flashdata('success', 'Product deleted successfully!');
        redirect('admin/products');
    }

    /**
     * View details of a specific product
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
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('product_detail', $data);
        $this->load->view('templates/footer');
    }
}
