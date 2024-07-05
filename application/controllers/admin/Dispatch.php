<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dispatch extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('media_storage');
        $this->load->model("dispatch_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('postal_dispatch', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'admin/dispatch');
        $this->form_validation->set_rules('to_title', $this->lang->line('to_title'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');
        if ($this->form_validation->run() == false) {
            $data['DispatchList'] = $this->dispatch_model->dispatch_list();
            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/dispatchview', $data);
            $this->load->view('layout/footer');
        } else {

            $img_name = $this->media_storage->fileupload("file", "./uploads/front_office/dispatch_receive/");

            $dispatch = array(
                'reference_no' => $this->input->post('ref_no'),
                'to_title'     => $this->input->post('to_title'),
                'address'      => $this->input->post('address'),
                'note'         => $this->input->post('note'),
                'from_title'   => $this->input->post('from'),
                'date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'type'         => 'dispatch',
                'image'        => $img_name,
            );

            $dispatch_id = $this->dispatch_model->insert('dispatch_receive', $dispatch);

            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/dispatch');
        }
    }

    public function editdispatch($id)
    {
        if (!$this->rbac->hasPrivilege('postal_dispatch', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('to_title', $this->lang->line('to_title'), 'required');
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_upload[file]');
        $data['Dispatch_data'] = $this->dispatch_model->dis_rec_data($id, 'dispatch');

        if ($this->form_validation->run() == false) {
            $data['DispatchList'] = $this->dispatch_model->dispatch_list();

            $this->load->view('layout/header');
            $this->load->view('admin/frontoffice/dispatchedit', $data);
            $this->load->view('layout/footer');
        } else {

            $id;

            $dispatch = array(
                'reference_no' => $this->input->post('ref_no'),
                'to_title'     => $this->input->post('to_title'),
                'address'      => $this->input->post('address'),
                'note'         => $this->input->post('note'),
                'from_title'   => $this->input->post('from'),
                'date'         => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'type'         => 'dispatch',
            );

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $img_name = $this->media_storage->fileupload("file", "./uploads/front_office/dispatch_receive/");
            } else {
                $img_name = $data['Dispatch_data']['image'];
            }

            $dispatch['image'] = $img_name;

            if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {

                $this->media_storage->filedelete($data['Dispatch_data']['image'], "uploads/front_office/dispatch_receive/");
            }

            $this->dispatch_model->update_dispatch('dispatch_receive', $id, 'dispatch', $dispatch);

            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/dispatch');
        }
    }

    public function download($id)
    {
        $dispatch_list = $this->dispatch_model->dis_rec_data($id, 'dispatch');     

        $this->media_storage->filedownload($dispatch_list['image'], "./uploads/front_office/dispatch_receive");
        
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('postal_dispatch', 'can_delete')) {
            access_denied();
        }
        $row = $this->dispatch_model->dis_rec_data($id, 'dispatch');

        if ($row['image'] != '') {
            $this->media_storage->filedelete($row['image'], "uploads/front_office/dispatch_receive/");
        }

        $this->dispatch_model->delete($id);
    }

    public function details($id, $type)
    {

        if (!$this->rbac->hasPrivilege('postal_dispatch', 'can_view')) {
            access_denied();
        }
        $data['data'] = $this->dispatch_model->dis_rec_data($id, $type);
        $this->load->view('admin/frontoffice/dispacthreceviemodel', $data);
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
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;

    }

}
