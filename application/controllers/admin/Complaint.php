<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Complaint extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('media_storage');
        $this->load->model("complaint_Model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('complaint', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/complaint');
        $this->form_validation->set_rules('name', $this->lang->line('complain_by'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');
        if ($this->form_validation->run() == false) {
            $data['complaint_list']  = $this->complaint_Model->complaint_list();
            $data['complaint_type']  = $this->complaint_Model->getComplaintType();
            $data['complaintsource'] = $this->complaint_Model->getComplaintSource();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/complaintview', $data);
            $this->load->view('layout/footer');
        } else {
            $img_name  = $this->media_storage->fileupload("file", "./uploads/front_office/complaints/");
            $complaint = array(
                'complaint_type' => $this->input->post('complaint'),
                'source'         => $this->input->post('source'),
                'name'           => $this->input->post('name'),
                'contact'        => $this->input->post('contact'),
                'date'           => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'description'    => $this->input->post('description'),
                'action_taken'   => $this->input->post('action_taken'),
                'assigned'       => $this->input->post('assigned'),
                'note'           => $this->input->post('note'),
                'image'          => $img_name,
            );

            $complaint_id = $this->complaint_Model->add($complaint);        

            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/complaint');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('complaint', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('name', $this->lang->line('complaint_by'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');

        $data['complaint_data'] = $this->complaint_Model->complaint_list($id);

        if ($this->form_validation->run() == false) {
            $data['complaint_list'] = $this->complaint_Model->complaint_list();

            $data['complaint_type']  = $this->complaint_Model->getComplaintType();
            $data['complaintsource'] = $this->complaint_Model->getComplaintSource();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/complainteditview', $data);
            $this->load->view('layout/footer');
        } else {

            $complaint = array(
                'complaint_type' => $this->input->post('complaint'),
                'source'         => $this->input->post('source'),
                'name'           => $this->input->post('name'),
                'contact'        => $this->input->post('contact'),
                'date'           => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'description'    => $this->input->post('description'),
                'action_taken'   => $this->input->post('action_taken'),
                'assigned'       => $this->input->post('assigned'),
                'note'           => $this->input->post('note'),
            );
           
            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/front_office/complaints/");
            } else {
                $img_name = $data['complaint_data']['image'];
            }

            $complaint['image'] = $img_name;

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $this->media_storage->filedelete($data['complaint_data']['image'], "uploads/front_office/complaints/");
            }

            $this->complaint_Model->compalaint_update($id, $complaint);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/complaint');
        }
    }

    public function details($id)
    {
        if (!$this->rbac->hasPrivilege('complaint', 'can_view')) {
            access_denied();
        }

        $data['complaint_data'] = $this->complaint_Model->complaint_list($id);
        $this->load->view('admin/frontoffice/Complaintmodalview', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('complaint', 'can_delete')) {
            access_denied();
        }
        $row = $this->complaint_Model->complaint_list($id);

        if ($row['documents'] != '') {
            $this->media_storage->filedelete($row['documents'], "uploads/front_office/complaints/");
        }

        $this->complaint_Model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');

        redirect('admin/complaint');
    }

    public function download($id)
    {
        $complaint_list = $this->complaint_Model->complaint_list($id);
        $this->media_storage->filedownload($complaint_list['image'], "./uploads/front_office/complaints");
    }

    public function check_default($post_string)
    {
        return $post_string == "" ? false : true;
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }

            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line("file_type_extension_error_uploading_image"));
                return false;
            }

            return true;
        }
        return true;

    }

}
