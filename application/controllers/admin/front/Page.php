<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Page extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $config = array(
            'field' => 'slug',
            'title' => 'title',
            'table' => 'front_cms_pages',
            'id'    => 'id',
        );
        $this->load->library('slug', $config);
        $this->load->config('ci-blog');
        $this->load->library('imageResize');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('pages', 'can_view')) {
            access_denied();
        }
        $data = array();
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/page');
        $listPages         = $this->cms_page_model->get();
        $data['listPages'] = $listPages;
        $this->load->view('layout/header');
        $this->load->view('admin/front/pages/index', $data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('pages', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/page');
        $data['category'] = $this->customlib->getPageContentCategory();
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header');
            $this->load->view('admin/front/pages/create', $data);
            $this->load->view('layout/footer');
        } else {
            $category = $this->input->post('content_category');
            if (isset($category)) {
                $contents_category = $category;
            } else {
                $contents_category = "";
            }

            $data = array(
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description', false)),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'type'             => 'page',
                'sidebar'          => $this->input->post('sidebar'),
                'meta_description' => $this->input->post('meta_description'),
            );
            $data['slug']     = $this->slug->create_uri($data);
            $data['url']      = $this->config->item('ci_front_page_url') . $data['slug'];
            $insert_id        = $this->cms_page_model->add($data);
            $content_category = $this->input->post('content_category');
            if (isset($content_category)) {
                if ($content_category != "standard") {
                    $contents   = array();
                    $contents[] = array('page_id' => $insert_id, 'content_type' => $content_category);
                    $insert_id  = $this->cms_page_content_model->batch_insert($contents);
                }
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/front/page');
        }
    }

    public function edit($slug)
    {
        if (!$this->rbac->hasPrivilege('pages', 'can_edit')) {
            access_denied();
        }
        $data['title']      = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/page');
        $result = $this->cms_page_model->getBySlug(urldecode($slug));
        if (empty($result['category_content'])) {
            $result['category_content'] = array('standard');
        }

        $data['category'] = $this->customlib->getPageContentCategory();
        $data['result']   = $result;
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');

        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/front/pages/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'               => $this->input->post('id'),
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description', false)),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'feature_image'    => $this->input->post('image'),
                'sidebar'          => $this->input->post('sidebar'),
                'meta_description' => $this->input->post('meta_description'),
            );

            $content_category = $this->input->post('content_category');
            if (isset($content_category)) {
                if ($content_category != "standard") {
                    $contents  = array('page_id' => $this->input->post('id'), 'content_type' => $content_category);
                    $insert_id = $this->cms_page_content_model->insertOrUpdate($contents);
                } else {
                    $insert_id = $this->cms_page_content_model->deleteByPageID($this->input->post('id'));
                }
            }
            $data['slug'] = $this->slug->create_uri($data, $this->input->post('id'));
            $data['url']  = $this->config->item('ci_front_page_url') . $data['slug'];
            $this->cms_page_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/front/page');
        }
    }

    public function delete($slug)
    {
        if (!$this->rbac->hasPrivilege('pages', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->cms_page_model->removeBySlug(urldecode($slug));
        redirect('admin/front/page');
    }

}
