<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form', 'string']);

        // Enforce admin authentication
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * Lists all categories with search, status filtering, stats, and pagination
     */
    public function index()
    {
        $search = $this->input->get('search', TRUE);
        $status = $this->input->get('status', TRUE);
        $page = $this->input->get('page', TRUE) ?: 1;
        $limit = 10;

        $page = (int)$page < 1 ? 1 : (int)$page;
        $offset = ($page - 1) * $limit;

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('name', $search);
            $this->db->or_like('slug', $search);
            $this->db->group_end();
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('status', (int)$status);
        }

        // Count query
        $this->db->from('categories');
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query with product counts
        $this->db->select('categories.*, (SELECT COUNT(id) FROM products WHERE products.category_id = categories.id) AS product_count');
        $this->db->order_by('categories.id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['categories'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;
        
        // Summary stats
        $data['stats_total'] = $this->db->count_all('categories');
        $data['stats_active'] = $this->db->where('status', 1)->count_all_results('categories');
        $data['stats_inactive'] = $this->db->where('status', 0)->count_all_results('categories');
        $data['stats_products'] = $this->db->count_all('products');

        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('category_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Adds a new category (submitted via Modal)
     */
    public function add()
    {
        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/categories');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() !== TRUE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('admin/categories');
        }

        $name = trim((string)$this->input->post('name', TRUE));
        $status = (int)$this->input->post('status') === 1 ? 1 : 0;

        // Generate slug
        $base_slug = url_title($name, 'dash', TRUE);
        if (empty($base_slug)) {
            $base_slug = 'category-' . time();
        }
        $slug = $base_slug;
        $counter = 1;
        while ($this->db->where('slug', $slug)->count_all_results('categories') > 0) {
            $slug = $base_slug . '-' . $counter;
            $counter++;
        }

        // Handle image upload
        $image_path = null;
        if (!empty($_FILES['image']['name'])) {
            $upload_path = './uploads/categories/';
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
                redirect('admin/categories');
            } else {
                $upload_data = $this->upload->data();
                $image_path = 'uploads/categories/' . $upload_data['file_name'];
            }
        }

        $insert_data = [
            'name'       => $name,
            'slug'       => $slug,
            'image'      => $image_path,
            'status'     => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->General_model->insert('categories', $insert_data);
        $this->session->set_flashdata('success', 'Category "' . $name . '" created successfully!');
        redirect('admin/categories');
    }

    /**
     * Edits an existing category (submitted via Modal)
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            redirect('admin/categories');
        }

        $category = $this->General_model->getOne('categories', ['id' => $id]);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/categories');
        }

        if ($this->input->method(TRUE) !== 'POST') {
            redirect('admin/categories');
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Category Name', 'required|trim');

        if ($this->form_validation->run() !== TRUE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('admin/categories');
        }

        $name = trim((string)$this->input->post('name', TRUE));
        $status = (int)$this->input->post('status') === 1 ? 1 : 0;

        // Re-generate slug if name changed or slug is empty
        $slug = $category->slug;
        if ($name !== $category->name || empty($slug)) {
            $base_slug = url_title($name, 'dash', TRUE);
            if (empty($base_slug)) {
                $base_slug = 'category-' . $id;
            }
            $slug = $base_slug;
            $counter = 1;
            while ($this->db->where('slug', $slug)->where('id !=', $id)->count_all_results('categories') > 0) {
                $slug = $base_slug . '-' . $counter;
                $counter++;
            }
        }

        $image_path = $category->image;

        if (!empty($_FILES['image']['name'])) {
            $upload_path = './uploads/categories/';
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
                redirect('admin/categories');
            } else {
                $upload_data = $this->upload->data();

                // Delete old image file if it exists
                if (!empty($category->image) && file_exists('./' . $category->image)) {
                    @unlink('./' . $category->image);
                }

                $image_path = 'uploads/categories/' . $upload_data['file_name'];
            }
        }

        $update_data = [
            'name'       => $name,
            'slug'       => $slug,
            'image'      => $image_path,
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->General_model->update('categories', ['id' => $id], $update_data);
        $this->session->set_flashdata('success', 'Category "' . $name . '" updated successfully!');
        redirect('admin/categories');
    }

    /**
     * AJAX endpoint to get category details for editing
     */
    public function ajax_get($id = null)
    {
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }
        $category = $this->General_model->getOne('categories', ['id' => $id]);
        if (!$category) {
            echo json_encode(['success' => false, 'message' => 'Category not found']);
            return;
        }
        echo json_encode([
            'success'  => true,
            'category' => [
                'id'         => $category->id,
                'name'       => $category->name,
                'slug'       => $category->slug,
                'image_url'  => $category->image ? base_url($category->image) : null,
                'status'     => (int)$category->status,
                'created_at' => date('M d, Y h:i A', strtotime($category->created_at))
            ]
        ]);
    }

    /**
     * Quick status toggle (Active/Inactive)
     */
    public function toggle_status($id = null)
    {
        if (empty($id)) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Invalid ID']);
                return;
            }
            redirect('admin/categories');
        }

        $category = $this->General_model->getOne('categories', ['id' => $id]);
        if (!$category) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => 'Category not found']);
                return;
            }
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/categories');
        }

        $new_status = ((int)$category->status === 1) ? 0 : 1;
        $this->General_model->update('categories', ['id' => $id], [
            'status'     => $new_status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'success'    => true,
                'new_status' => $new_status,
                'message'    => 'Category is now ' . ($new_status === 1 ? 'Active' : 'Inactive')
            ]);
            return;
        }

        $this->session->set_flashdata('success', 'Category status updated successfully!');
        redirect('admin/categories');
    }

    /**
     * Deletes a category
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            redirect('admin/categories');
        }

        $category = $this->General_model->getOne('categories', ['id' => $id]);
        if (!$category) {
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('admin/categories');
        }

        // Delete associated image file
        if (!empty($category->image) && file_exists('./' . $category->image)) {
            @unlink('./' . $category->image);
        }

        // Delete from database
        $this->db->delete('categories', ['id' => $id]);

        $this->session->set_flashdata('success', 'Category deleted successfully!');
        redirect('admin/categories');
    }
}
