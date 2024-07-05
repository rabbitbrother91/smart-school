<?php

class Department extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->config->load("payroll");
        $this->load->model('department_model');
        $this->load->model('staff_model');
    }

    function department() {

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/department/department');

        $departmenttypeid = $this->input->post("departmenttypeid");
        $DepartmentTypes = $this->department_model->getDepartmentType();
        $data["departmenttype"] = $DepartmentTypes;
        $this->form_validation->set_rules(
                'type', $this->lang->line('name'), array('required',
            array('check_exists', array($this->department_model, 'valid_department'))
                )
        );
        $data["title"] = $this->lang->line('add_department');
        if ($this->form_validation->run()) {

            $type = $this->input->post("type");
            $departmenttypeid = $this->input->post("departmenttypeid");
            $status = $this->input->post("status");
            if (empty($departmenttypeid)) {

                if (!$this->rbac->hasPrivilege('department', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('department', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($departmenttypeid)) {
                $data = array('department_name' => $type, 'is_active' => 'yes', 'id' => $departmenttypeid);
            } else {

                $data = array('department_name' => $type, 'is_active' => 'yes');
            }
            $insert_id = $this->department_model->addDepartmentType($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect("admin/department/department");
        } else {

            $this->load->view("layout/header");
            $this->load->view("admin/staff/departmentType", $data);
            $this->load->view("layout/footer");
        }
    }

    function departmentedit($id) {

        $result = $this->department_model->getDepartmentType($id);

        $data["result"] = $result;
        $data["title"] = $this->lang->line('edit_department');
        $departmentTypes = $this->department_model->getDepartmentType();
        $data["departmenttype"] = $departmentTypes;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/departmentType", $data);
        $this->load->view("layout/footer");
    }

    function departmentdelete($id) {

        $this->department_model->deleteDepartment($id);
        redirect('admin/department/department');
    }

}

?>