<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Events extends Admin_Controller
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
        $this->load->library('imageResize');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('event', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/events');
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $event_content      = $this->config->item('ci_front_event_content');
        $listResult         = $this->cms_program_model->getByCategory($event_content);
        $data['listResult'] = $listResult;
        $this->load->view('layout/header');
        $this->load->view('admin/front/events/index', $data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('event', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/events');
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', $this->lang->line('start_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', $this->lang->line('event_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header');
            $this->load->view('admin/front/events/create', $data);
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
                'event_start'      => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('start_date'))),
                'event_end'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('end_date'))),
                'event_venue'      => $this->input->post('venue'),
                'feature_image'    => $this->input->post('image'),
                'sidebar'          => $this->input->post('sidebar'),
                'sidebar'          => $this->input->post('sidebar'),
                'type'             => $this->config->item('ci_front_event_content'),
                'meta_description' => $this->input->post('meta_description'),
            );
            $data['slug'] = $this->slug->create_uri($data);
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            $this->cms_program_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/front/events', 'refresh');
        }
    }

    public function delete_image()
    {
        $delte_image = $this->input->post('id');
        if ($delte_image == "" && !isset($delte_image)) {

        } else {
            $this->cms_program_model->removeImage($delte_image);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('image_deleted_successfully')));
            exit;
        }
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('something_went_wrong')));
    }

    public function enableFeatured()
    {
        $id        = $this->input->post('id');
        $record_id = $this->input->post('record_id');
        if ($id == "" && !isset($id)) {

        } else {
            $this->cms_program_model->updateFeaturedImage($id, $record_id);
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('update_message')));
            exit;
        }
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('something_went_wrong')));
    }

    public function ajaxupload()
    {
        if (isset($_FILES['files']) && !empty($_FILES['files'])) {
            $no_files = count($_FILES["files"]['name']);
            for ($i = 0; $i < $no_files; $i++) {
                if ($_FILES["files"]["error"][$i] > 0) {
                    echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                } else {

                    $destination_path = "./uploads/gallery/event_images/";
                    $thumb_path       = "./uploads/gallery/event_images/thumb/";
                    $responses        = $this->imageresize->resize($_FILES["files"], $destination_path, $thumb_path);
                    if (!empty($responses)) {

                        foreach ($responses["images"] as $response) {
                            $data = array(
                                'program_id' => $this->input->post('record_id'),
                                'image'      => $response['file_name'],
                                'thumb_path' => $response['thumb_path'],
                                'dir_path'   => $response['dir_path'],
                                'img_name'   => $response['store_name'],
                                'thumb_name' => 'thumb_' . $response['store_name'],
                            );
                            $insert_id        = $this->cms_program_model->addImage($data);
                            $data['image_id'] = $insert_id;
                            $msg              = $this->lang->line('image_upload_successfully');
                            echo json_encode(array('status' => 0, 'msg' => $msg, 'image_array' => $data));
                        }
                    }
                }
            }
        } else {
            $msg = $this->lang->line('');
            echo json_encode(array('status' => 1, 'msg' => $msg));
        }
    }

    public function edit($slug)
    {
        if (!$this->rbac->hasPrivilege('event', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/events');
        $data['title']      = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $result             = $this->cms_program_model->getBySlug(urldecode($slug));
        $data['result'] = $result;
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('start_date', $this->lang->line('start_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('end_date', $this->lang->line('event_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required');
        if ($this->form_validation->run() == false) {
            $listbook         = $this->book_model->listbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/front/events/edit', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'               => $this->input->post('id'),
                'title'            => $this->input->post('title'),
                'description'      => htmlspecialchars_decode($this->input->post('description', false)),
                'meta_title'       => $this->input->post('meta_title'),
                'meta_keyword'     => $this->input->post('meta_keywords'),
                'event_start'      => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('start_date'))),
                'event_end'        => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('end_date'))),
                'event_venue'      => $this->input->post('venue'),
                'feature_image'    => $this->input->post('image'),
                'sidebar'          => $this->input->post('sidebar'),
                'type'             => $this->config->item('ci_front_event_content'),
                'meta_description' => $this->input->post('meta_description'),
            );
            
            $data['slug'] = $this->slug->create_uri($data);
            $data['url']  = $this->config->item('ci_front_page_read_url') . $data['slug'];
            
            // $data['slug'] = $this->slug->create_uri($data, $this->input->post('id'));
            
            $this->cms_program_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/front/events');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('event', 'can_delete')) {
            access_denied();
        }

        $data['title'] = 'Fees Master List';
        $this->cms_program_model->removeBySlug(urldecode($id), 'events');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/front/events');
    }

}
