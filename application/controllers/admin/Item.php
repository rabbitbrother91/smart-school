<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Item extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('item', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'Item/index');
        $data['title']      = 'Add Item';
        $data['title_list'] = 'Recent Items';

        $this->form_validation->set_rules('name', $this->lang->line('item'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'item_category_id', $this->lang->line('item_category'), array('required', array('check_exists', array($this->item_model, 'valid_check_exists')),
            )
        );

        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'item_category_id' => $this->input->post('item_category_id'),
                'name'             => $this->input->post('name'),
                'unit'             => $this->input->post('unit'),
                'description'      => $this->input->post('description'),
            );
            $insert_id = $this->item_model->add($data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/item/index');
        }

        $item_result      = $this->item_model->get();
        $data['itemlist'] = $item_result;

        $itemcategory        = $this->itemcategory_model->get();
        $data['itemcatlist'] = $itemcategory;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/item/itemList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($file)
    {
        $this->load->helper('download');
        $filepath = "./uploads/inventory_items/" . $this->uri->segment(6);
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment();
        force_download($name, $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('item', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->item_model->remove($id);
        redirect('admin/item/index');
    }

    public function getAvailQuantity()
    {
        $item_id = $this->input->get('item_id');
        $data    = $this->item_model->getItemAvailable($item_id);

        $available = ($data['added_stock'] - $data['issued']);
        if ($available >= 0) {
            echo json_encode(array('available' => $available));
        } else {
            echo json_encode(array('available' => 0));
        }
    }

    public function handle_upload()
    {
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                $_FILES["file"]["type"] != 'image/jpeg' &&
                $_FILES["file"]["type"] != 'image/png') {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["file"]["size"] > 10240000) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            return true;
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('item', 'can_edit')) {
            access_denied();
        }
        $data['title']    = 'Edit Fees Master';
        $data['id']       = $id;
        $item             = $this->item_model->get($id);
        $data['item']     = $item;
        $item_result      = $this->item_model->get();
        $data['itemlist'] = $item_result;

        $itemcategory        = $this->itemcategory_model->get();
        $data['itemcatlist'] = $itemcategory;

        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('unit', $this->lang->line('unit'), 'trim|required|xss_clean');

        $this->form_validation->set_rules(
            'item_category_id', $this->lang->line('item_category'), array('required', array('check_exists', array($this->item_model, 'valid_check_exists')),
            )
        );
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/item/itemEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'               => $id,
                'item_category_id' => $this->input->post('item_category_id'),
                'name'             => $this->input->post('name'),
                'unit'             => $this->input->post('unit'),
                'description'      => $this->input->post('description'),
            );
            $insert_id = $this->item_model->add($data);
            $insert_id = $this->item_model->add($data);
            if (isset($_FILES["item_photo"]) && !empty($_FILES['item_photo']['name'])) {
                $fileInfo = pathinfo($_FILES["item_photo"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["item_photo"]["tmp_name"], "./uploads/inventory_items/" . $img_name);
                $data_img = array('id' => $id, 'item_photo' => 'uploads/inventory_items/' . $img_name);
                $this->item_model->add($data_img);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/item/index');
        }
    }

}
