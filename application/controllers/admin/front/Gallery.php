<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gallery extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $config = array(
            'field' => 'slug',
            'title' => 'title',
            'table' => 'front_cms_programs',
            'id'    => 'id',
        );
        $this->load->library('slug', $config);
        $this->load->config('ci-blog');
        $this->load->library('media_storage');   
        $this->load->library('imageResize');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('gallery', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/gallery');
        $data               = array();
        $notice_content     = $this->config->item('ci_front_gallery_content');
        $listResult         = $this->cms_program_model->getByCategory($notice_content);
        $data['listResult'] = $listResult;
        $this->load->view('layout/header');
        $this->load->view('admin/front/gallery/index', $data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('gallery', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add Gallery';
        $data['title_list'] = 'Gallery Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/gallery');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header');
            $this->load->view('admin/front/gallery/create', $data);
            $this->load->view('layout/footer');
        } else {
            $gallery_images = $this->input->post('gallery_images');
            $gallery_array  = array();
            if (isset($gallery_images)) {
                foreach ($gallery_images as $gallery_key => $gallery_value) {
                    $array                     = array();
                    $array['program_id']       = 0;
                    $array['media_gallery_id'] = $gallery_value;
                    $gallery_array[]           = $array;
                }
            }
            $category = $this->input->post('content_category');
            if (isset($category)) {
                $contents_category = $category;
            } else {
                $contents_category = "";
            }
            $data = array(
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description')),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'sidebar'          => $this->input->post('sidebar'),
                'type'             => $this->config->item('ci_front_gallery_content'),
                'meta_description' => $this->input->post('meta_description'),
            );

            $data['slug'] = $this->slug->create_uri($data);
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            $this->cms_program_model->inst_batch($data, $gallery_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/front/gallery');
        }
    }

    public function edit($slug)
    {
        if (!$this->rbac->hasPrivilege('gallery', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit Gallery';
        $data['title_list'] = 'Gallery Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/gallery');
        $result         = $this->cms_program_model->getBySlug(urldecode($slug));
        $data['result'] = $result;
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header');
            $this->load->view('admin/front/gallery/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $old_gallery = array();
            if (!empty($result['page_contents'])) {

                foreach ($result['page_contents'] as $old_gallery_key => $old_gallery_value) {
                    $old_gallery[] = $old_gallery_value->id;
                }
            }

            $gallery_images = $this->input->post('gallery_images');
            $gallery_array  = array();
            if (isset($gallery_images)) {
                $new_gallery = $gallery_images;
            } else {
                $new_gallery = array();
            }

            $add_result  = array_diff($new_gallery, $old_gallery);
            $remove_list = array_diff($old_gallery, $new_gallery);

            if (isset($gallery_images)) {
                foreach ($add_result as $gallery_key => $gallery_value) {
                    $new_gallery[]             = $gallery_value;
                    $array                     = array();
                    $array['program_id']       = 0;
                    $array['media_gallery_id'] = $gallery_value;
                    $gallery_array[]           = $array;
                }
            }

            $data = array(
                'id'               => $this->input->post('id'),
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description')),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'sidebar'          => $this->input->post('sidebar'),
                'type'             => $this->config->item('ci_front_gallery_content'),
                'meta_description' => $this->input->post('meta_description'),
            );

            if ($_POST['image'] != '') {
                $data['feature_image'] = $this->input->post('image');
            }

            $data['slug'] = $this->slug->create_uri($data, $this->input->post('id'));
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            $this->cms_program_model->update_batch($data, $gallery_array, $remove_list);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/front/gallery');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('gallery', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->cms_program_model->removeBySlug(urldecode($id), 'gallery');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/front/gallery/');
    }

}
