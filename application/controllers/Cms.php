<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Cms Controller
 * Admin management for Legal Content (Privacy Policy & Terms and Conditions)
 */
class Cms extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cms');
        $this->load->library('session');
        $this->load->database();

        // Ensure user is logged in as Admin
        if (!$this->session->userdata('logged_in')) {
            redirect('admin/login');
        }

        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => (int)$user_id])->row();

        if (!$user || (int)$user->role !== 1) {
            $this->session->set_flashdata('error', 'Access denied. Administrator privileges required.');
            redirect('admin/dashboard');
        }
    }

    /**
     * Display CMS Editor Page
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $user = $this->db->get_where('users', ['id' => (int)$user_id])->row();

        // Fetch pages with custom status
        $privacy_page = cms_get_page('privacy_policy');
        $terms_page   = cms_get_page('terms_conditions');

        // Fetch default content as well so admin can compare or load template
        $default_privacy = cms_get_default_content('privacy_policy');
        $default_terms   = cms_get_default_content('terms_conditions');

        $active_tab = $this->input->get('tab') ?: 'privacy_policy';

        $data = [
            'user'             => $user,
            'privacy_page'     => $privacy_page,
            'terms_page'       => $terms_page,
            'default_privacy'  => $default_privacy,
            'default_terms'    => $default_terms,
            'active_tab'       => $active_tab
        ];

        $this->load->view('templates/header', $data);
        $this->load->view('cms_view', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Save/Update CMS Content
     */
    public function update()
    {
        $slug = trim(strtolower($this->input->post('slug')));
        $allowed = ['privacy_policy', 'terms_conditions'];

        if (!in_array($slug, $allowed)) {
            $this->session->set_flashdata('error', 'Invalid policy page specified.');
            redirect('admin/cms');
        }

        $title = trim((string)$this->input->post('title'));
        $content = trim((string)$this->input->post('content'));
        // Clean any escaped slashes (e.g. \" to ") so text remains clean and unescaped
        $content = stripslashes($content);

        if (empty($title)) {
            $title = ($slug === 'privacy_policy') ? 'Privacy Policy' : 'Terms and Conditions';
        }

        $existing = $this->db->get_where('cms_pages', ['slug' => $slug])->row();

        if ($existing) {
            $this->db->update('cms_pages', [
                'title'      => $title,
                'content'    => $content,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['slug' => $slug]);
        } else {
            $this->db->insert('cms_pages', [
                'slug'       => $slug,
                'title'      => $title,
                'content'    => $content,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $label = ($slug === 'privacy_policy') ? 'Privacy Policy' : 'Terms & Conditions';
        $this->session->set_flashdata('success', "{$label} has been updated successfully! Your public page and mobile app APIs are now serving this content.");
        redirect('admin/cms?tab=' . $slug);
    }

    /**
     * Reset Page to Default Content
     */
    public function reset_default($slug)
    {
        $slug = trim(strtolower($slug));
        $allowed = ['privacy_policy', 'terms_conditions'];

        if (!in_array($slug, $allowed)) {
            $this->session->set_flashdata('error', 'Invalid policy page specified.');
            redirect('admin/cms');
        }

        // Clearing the content will cause cms_get_page() to automatically fallback to rich default content
        $existing = $this->db->get_where('cms_pages', ['slug' => $slug])->row();
        if ($existing) {
            $this->db->update('cms_pages', [
                'content'    => null,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['slug' => $slug]);
        }

        $label = ($slug === 'privacy_policy') ? 'Privacy Policy' : 'Terms & Conditions';
        $this->session->set_flashdata('info', "{$label} reset to our built-in default content. Live pages and APIs are now serving the default legal template.");
        redirect('admin/cms?tab=' . $slug);
    }
}
