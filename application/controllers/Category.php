<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
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
     * Lists all categories with search, status filtering, and pagination
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
            $this->db->like('name', $search);
        }
        if ($status !== '' && $status !== null) {
            $this->db->where('status', (int)$status);
        }

        // Count query
        $this->db->from('categories');
        $total_rows = $this->db->count_all_results('', FALSE);

        // Fetch query
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        $data['categories'] = $this->db->get()->result();

        $data['total_pages'] = ceil($total_rows / $limit);
        $data['current_page'] = $page;
        $data['search'] = $search;
        $data['status'] = $status;
        $data['total_rows'] = $total_rows;
        
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);

        $this->load->view('templates/header', $data);
        $this->load->view('category_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Adds a new category
     */
    public function add()
    {
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);
        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('name', 'Category Name', 'required|trim');
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[categories.slug]', [
                'is_unique' => 'This category slug is already in use.'
            ]);

            if ($this->form_validation->run() === TRUE) {
                $name = $this->input->post('name', TRUE);
                $slug = url_title(strtolower($this->input->post('slug', TRUE)));
                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Handle image upload
                $image_path = null;
                $upload_success = TRUE;

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

                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                        $upload_success = FALSE;
                    } else {
                        $upload_data = $this->upload->data();
                        $image_path = 'uploads/categories/' . $upload_data['file_name'];
                    }
                }

                if ($upload_success) {
                    $insert_data = [
                        'name'       => $name,
                        'slug'       => $slug,
                        'image'      => $image_path,
                        'status'     => $status,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    $this->General_model->insert('categories', $insert_data);
                    $this->session->set_flashdata('success', 'Category added successfully!');
                    redirect('admin/categories');
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('category_add', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Edits an existing category
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

        $data['category'] = $category;
        $data['user'] = $this->General_model->getOne('users', ['id' => $this->session->userdata('user_id')]);
        
        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $this->form_validation->set_rules('name', 'Category Name', 'required|trim');
            $this->form_validation->set_rules('slug', 'Slug', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $name = $this->input->post('name', TRUE);
                $slug = url_title(strtolower($this->input->post('slug', TRUE)));
                $status = (int)$this->input->post('status') === 1 ? 1 : 0;

                // Unique check for slug excluding current category
                $slug_check = $this->General_model->getOne('categories', ['slug' => $slug, 'id !=' => $id]);

                if ($slug_check) {
                    $this->session->set_flashdata('error', 'This slug is already used by another category.');
                } else {
                    $image_path = $category->image;
                    $upload_success = TRUE;

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
                            $upload_success = FALSE;
                        } else {
                            $upload_data = $this->upload->data();

                            // Delete old image file
                            if (!empty($category->image) && file_exists('./' . $category->image)) {
                                @unlink('./' . $category->image);
                            }

                            $image_path = 'uploads/categories/' . $upload_data['file_name'];
                        }
                    }

                    if ($upload_success) {
                        $update_data = [
                            'name'       => $name,
                            'slug'       => $slug,
                            'image'      => $image_path,
                            'status'     => $status,
                            'updated_at' => date('Y-m-d H:i:s')
                        ];

                        $this->General_model->update('categories', ['id' => $id], $update_data);
                        $this->session->set_flashdata('success', 'Category updated successfully!');
                        redirect('admin/categories');
                    }
                }
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('category_edit', $data);
        $this->load->view('templates/footer');
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
