<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Itemstock extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->helper('form');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('item_stock', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Inventory');
        $this->session->set_userdata('sub_menu', 'Itemstock/index');
        $data['title']      = 'Add Item';
        $data['title_list'] = 'Recent Items';

        $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        
        $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('purchase_price', $this->lang->line('purchase_price'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('item_category_id', $this->lang->line('item_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('item_photo', $this->lang->line('file'), 'callback_handle_upload[item_photo]');

        if ($this->form_validation->run() == false) {

        } else {

            $img_name = $this->media_storage->fileupload("item_photo", "./uploads/inventory_items/");

            $store_id = ($this->input->post('store_id')) ? $this->input->post('store_id') : null;
            
            $data     = array(
                'item_id'        => $this->input->post('item_id'),
                'symbol'         => $this->input->post('symbol'),
                'supplier_id'    => $this->input->post('supplier_id'),
                'store_id'       => $store_id,
                'quantity'       => $this->input->post('symbol') . $this->input->post('quantity'),
                'purchase_price' => convertCurrencyFormatToBaseAmount($this->input->post('purchase_price')),
                'date'           => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'description'    => $this->input->post('description'),
                'attachment'     => $img_name,
            );
            $insert_id = $this->itemstock_model->add($data);         

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/itemstock/index');
        }
        $item_result = $this->itemstock_model->get();

        $data['itemlist']     = $item_result;
        $itemcategory         = $this->itemcategory_model->get();
        $data['itemcatlist']  = $itemcategory;
        $itemsupplier         = $this->itemsupplier_model->get();
        $data['itemsupplier'] = $itemsupplier;
        $itemstore            = $this->itemstore_model->get();
        $data['itemstore']    = $itemstore;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/itemstock/itemList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($id)
    {
        $itemlist = $this->itemstock_model->get($id);
        $this->media_storage->filedownload($itemlist['attachment'], "./uploads/inventory_items");

    }

    public function getItemByCategory()
    {
        $item_category_id = $this->input->get('item_category_id');
        $data             = $this->item_model->getItemByCategory($item_category_id);
        echo json_encode($data);
    }

    public function getItemunit()
    {
        $id   = $this->input->get('id');
        $data = $this->item_model->getItemunit($id);
        echo json_encode($data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('item_stock', 'can_delete')) {
            access_denied();
        } 
        $row = $this->itemstock_model->get($id);
        if ($row['attachment'] != '') {
            $this->media_storage->filedelete($row['attachment'], "uploads/inventory_items/");
        }

        $this->itemstock_model->remove($id);
        redirect('admin/itemstock/index');
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

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('item_stock', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit Fees Master';
        $data['id']           = $id;
        $item                 = $this->itemstock_model->get($id);
        $data['item']         = $item;
        $data['title_list']   = 'Fees Master List';
        $item_result          = $this->itemstock_model->get();
        $data['itemlist']     = $item_result;
        $itemcategory         = $this->itemcategory_model->get();
        $data['itemcatlist']  = $itemcategory;
        $itemsupplier         = $this->itemsupplier_model->get();
        $data['itemsupplier'] = $itemsupplier;
        $itemstore            = $this->itemstore_model->get();
        $data['itemstore']    = $itemstore;

        $itemunitdata      = $this->item_model->getItemunit($item['item_id']);
        $data['item_unit'] = $itemunitdata['unit'];

        $this->form_validation->set_rules('item_id', $this->lang->line('item'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('item_category_id', $this->lang->line('item_category'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('purchase_price', $this->lang->line('price'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('quantity', $this->lang->line('quantity'), 'trim|required|numeric|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/itemstock/itemEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $store_id = ($this->input->post('store_id')) ? $this->input->post('store_id') : null;
            $data     = array(
                'id'             => $id,
                'item_id'        => $this->input->post('item_id'),
                'symbol'         => $this->input->post('symbol'),
                'supplier_id'    => $this->input->post('supplier_id'),
                'store_id'       => $store_id,
                'quantity'       => $this->input->post('symbol') . $this->input->post('quantity'),
                'purchase_price' => convertCurrencyFormatToBaseAmount($this->input->post('purchase_price')),
                'date'           => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'description'    => $this->input->post('description'),
            );

            if (isset($_FILES["item_photo"]) && $_FILES['item_photo']['name'] != '' && (!empty($_FILES['item_photo']['name']))) {

                $img_name = $this->media_storage->fileupload("item_photo", "./uploads/inventory_items/");
            } else {
                $img_name = $item['attachment'];
            }

            $data['attachment'] = $img_name;

            if (isset($_FILES["item_photo"]) && $_FILES['item_photo']['name'] != '' && (!empty($_FILES['item_photo']['name']))) {

                $this->media_storage->filedelete($item['attachment'], "uploads/inventory_items");
            }

            $this->itemstock_model->add($data);         

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/itemstock/index');
        }
    }

}
