<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('General_model');
        $this->load->library('session');
        $this->load->helper('url');
        
        // Enforce authentication on all profile routes
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }
    }

    /**
     * Renders the user profile page and handles updates to user details and password (web form)
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->General_model->getOne('users', ['id' => $user_id]);

        if (!$user || (int)$user->status === 0) {
            $this->session->sess_destroy();
            redirect('admin/login');
        }

        $this->load->library('form_validation');

        if ($this->input->method(TRUE) === 'POST') {
            $action = $this->input->post('action', TRUE);

            if ($action === 'update_profile') {
                $this->form_validation->set_rules('name', 'Name', 'required|trim');
                $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
                $this->form_validation->set_rules('phone', 'Phone', 'required|trim|regex_match[/^[0-9]{10,15}$/]', [
                    'regex_match' => 'The phone number must be between 10 and 15 digits.'
                ]);

                if ($this->form_validation->run() === TRUE) {
                    $name = $this->input->post('name', TRUE);
                    $email = $this->input->post('email', TRUE);
                    $phone = $this->input->post('phone', TRUE);
                    $address = $this->input->post('address', TRUE) ?: null;

                    // Unique check for email excluding current user
                    $email_check = $this->General_model->getOne('users', ['email' => $email, 'id !=' => $user_id]);

                    if ($email_check) {
                        $this->session->set_flashdata('error', 'This email is already in use by another user.');
                    } else {
                        // Handle profile image upload
                        $profile_image_path = $user->profile_image;
                        $upload_success = TRUE;

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
                                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                                $upload_success = FALSE;
                            } else {
                                $upload_data = $this->upload->data();
                                
                                // Delete old image file if it exists
                                if (!empty($user->profile_image) && file_exists('./' . $user->profile_image)) {
                                    @unlink('./' . $user->profile_image);
                                }
                                
                                $profile_image_path = 'uploads/profile_images/' . $upload_data['file_name'];
                            }
                        }

                        if ($upload_success) {
                            $update_data = [
                                'name'          => $name,
                                'email'         => $email,
                                'phone'         => $phone,
                                'profile_image' => $profile_image_path,
                                'address'       => $address,
                                'updated_at'    => date('Y-m-d H:i:s')
                            ];

                            $this->General_model->update('users', ['id' => $user_id], $update_data);
                            
                            // Update session details
                            $this->session->set_userdata([
                                'name'          => $name,
                                'email'         => $email,
                                'phone'         => $phone,
                                'profile_image' => $profile_image_path,
                                'address'       => $address
                            ]);

                            $this->session->set_flashdata('success', 'Profile updated successfully!');
                            redirect('admin/profile');
                        }
                    }
                }
            } elseif ($action === 'change_password') {
                $this->form_validation->set_rules('current_password', 'Current Password', 'required');
                $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]');
                $this->form_validation->set_rules('confirm_new_password', 'Confirm New Password', 'required|matches[new_password]', [
                    'matches' => 'The Confirm New Password field does not match the New Password field.'
                ]);

                if ($this->form_validation->run() === TRUE) {
                    $current_password = $this->input->post('current_password');
                    $new_password = $this->input->post('new_password');

                    if (password_verify($current_password, $user->password)) {
                        $update_data = [
                            'password'   => password_hash($new_password, PASSWORD_BCRYPT),
                            'updated_at' => date('Y-m-d H:i:s')
                        ];
                        $this->General_model->update('users', ['id' => $user_id], $update_data);
                        $this->session->set_flashdata('success', 'Password changed successfully!');
                        redirect('admin/profile');
                    } else {
                        $this->session->set_flashdata('error', 'Incorrect current password.');
                    }
                }
            }
        }

        // Reload fresh user info for display
        $data['user'] = $this->General_model->getOne('users', ['id' => $user_id]);

        $this->load->view('templates/header', $data);
        $this->load->view('profile_view', $data);
        $this->load->view('templates/footer');
    }
}
