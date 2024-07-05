<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customfield extends Admin_Controller
{

    public $custom_fields_list = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encoding_lib');
        $this->custom_fields_list = $this->config->item('custom_fields');
        $this->custom_field_table = $this->config->item('custom_field_table');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('custom_fields', 'can_view')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/customfield');
        $customfields               = $this->customfield_model->get();
        $data['custom_fields_list'] = $this->custom_fields_list;
        $customfield_bundle         = $this->myCustomFieldBundle($customfields);
        $data['customfields']       = $customfield_bundle;
        $data['custom_field_table'] = $this->custom_field_table;
        $this->form_validation->set_rules('belong_to', $this->lang->line('field_belongs_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('field_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('column', $this->lang->line('column'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('field_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('field_values', $this->lang->line('field_values'), 'callback_validate_type');
        if ($this->form_validation->run() == true) {

            $data = array(
                'belong_to'        => $this->input->post('belong_to'),
                'type'             => $this->input->post('type'),
                'bs_column'        => $this->input->post('column'),
                'name'             => $this->input->post('name'),
                'field_values'     => $this->input->post('field_values'),
                'validation'       => isset($_POST['validation']) ? $_POST['validation'] : "",
                'visible_on_table' => isset($_POST['display_tbl']) ? $_POST['display_tbl'] : "",
            );

            $this->customfield_model->add($data);

            /* code for adding custom fields in system field also */

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/customfield/index');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/customfield/index', $data);
        $this->load->view('layout/footer');
    }

    public function validate_type()
    {
        if (isset($_POST['type']) and ($_POST['type'] == "select" || $_POST['type'] == "multiselect" || $_POST['type'] == "checkbox" || $_POST['type'] == "link")) {
            if ($this->input->post('field_values') == "") {
                $this->form_validation->set_message('validate_type', $this->lang->line("fields_values_required"));
                return false;
            }
        }
        return true;
    }

    public function edit($id)
    {
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/customfield');
        $cus_field                  = $this->customfield_model->get($id);
        $data['cus_field']          = $cus_field;
        $customfields               = $this->customfield_model->get();
        $data['custom_fields_list'] = $this->custom_fields_list;

        $customfield_bundle   = $this->myCustomFieldBundle($customfields);
        $data['customfields'] = $customfield_bundle;

        $data['custom_field_table'] = $this->custom_field_table;
        $this->form_validation->set_rules('belong_to', $this->lang->line('field_belongs_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('field_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('field_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('field_values', $this->lang->line('field_values'), 'callback_validate_type');

        if ($this->form_validation->run() == true) {
            $data = array(
                'id'               => $this->input->post('id'),
                'bs_column'        => $this->input->post('column'),
                'belong_to'        => $this->input->post('belong_to'),
                'type'             => $this->input->post('type'),
                'name'             => $this->input->post('name'),
                'field_values'     => $this->input->post('field_values'),
                'validation'       => isset($_POST['validation']) ? $_POST['validation'] : "",
                'visible_on_table' => isset($_POST['display_tbl']) ? $_POST['display_tbl'] : "",
            );

            $this->customfield_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/customfield/index');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/customfield/edit', $data);
        $this->load->view('layout/footer');
    }

    public function delete($id)
    {
        $this->customfield_model->remove($id);
        redirect('admin/customfield/index');
    }

    public function updateorder()
    {
        $belong_to = $this->input->post('belong_to');
        $items     = $this->input->post('items');

        if (!empty($items)) {
            $updateorder = array();
            $i           = 1;
            foreach ($items as $item_key => $item_value) {
                $updateorder[] = $array = array('id' => $item_value, 'weight' => $i);

                $i++;
            }

            $this->customfield_model->updateorder($updateorder);
        }

        $array = array('status' => '1', 'msg' => $this->lang->line('update_message'));

        echo json_encode($array);
    }

    public function myCustomFieldBundle($customfield_values)
    {
        $field_array = array();

        if (!empty($customfield_values)) {
            foreach ($customfield_values as $f_key => $f_value) {
                $field_array[$f_value['belong_to']][] = $customfield_values[$f_key];
            }
        }
        return $field_array;
    }
}
