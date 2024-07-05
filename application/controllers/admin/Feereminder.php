<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feereminder extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function setting()
    {
        if (!$this->rbac->hasPrivilege('fees_reminder', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'feereminder/setting');
        $data = array();

        $feereminderlist         = $this->feereminder_model->get();
        $data['feereminderlist'] = $feereminderlist;

        if ($this->input->server('REQUEST_METHOD') == "POST") {

            $ids          = $this->input->post('ids');
            $update_array = array();
            foreach ($ids as $id_key => $id_value) {
                $array = array(
                    'id'        => $id_value,
                    'is_active' => 0,
                    'day'       => $this->input->post('days' . $id_value),
                );
                $is_active = $this->input->post('isactive_' . $id_value);

                if (isset($is_active)) {
                    $array['is_active'] = $is_active;
                }

                $update_array[] = $array;
            }

            $this->feereminder_model->updatebatch($update_array);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feereminder/setting');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/feereminder/setting', $data);
        $this->load->view('layout/footer', $data);
    }

}
