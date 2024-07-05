<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Route extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('routes', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'route/index');
        $listroute         = $this->route_model->listroute();
        $data['listroute'] = $listroute;
        $this->load->view('layout/header');
        $this->load->view('admin/route/createroute', $data);
        $this->load->view('layout/footer');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('routes', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Route';
        $this->form_validation->set_rules('route_title', $this->lang->line('route_title'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listroute         = $this->route_model->listroute();
            $data['listroute'] = $listroute;
            $this->load->view('layout/header');
            $this->load->view('admin/route/createroute', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'route_title'   => $this->input->post('route_title'),
                'no_of_vehicle' => $this->input->post('no_of_vehicle'),
            );
            $this->route_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/route/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('routes', 'can_edit')) {
            access_denied();
        }
        $data['title']     = 'Add Route';
        $data['id']        = $id;
        $editroute         = $this->route_model->get($id);
        $data['editroute'] = $editroute;
        $this->form_validation->set_rules('route_title', $this->lang->line('route_title'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $listroute         = $this->route_model->listroute();
            $data['listroute'] = $listroute;
            $this->load->view('layout/header');
            $this->load->view('admin/route/editroute', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id'            => $this->input->post('id'),
                'route_title'   => $this->input->post('route_title'),
                'no_of_vehicle' => $this->input->post('no_of_vehicle'),
            );
            $this->route_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/route/index');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('routes', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->route_model->remove($id);
        redirect('admin/route/index');
    }

    public function studenttransportdetails()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'reports/studenttransportdetails');
        $data['title']     = 'Student Hostel Details';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata          = $this->customlib->getUserData();
        $carray            = array();

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $vehroute_result      = $this->route_model->get();
        $data['vehroutelist'] = $vehroute_result;
        $section_id           = $this->input->post("section_id");
        $class_id             = $this->input->post("class_id");
        $transport_route_id   = $this->input->post("transport_route_id");
        $pickup_point_id      = $this->input->post("pickup_point_id");
        $vehicle_id           = $this->input->post("vehicle_id");
 
        if (isset($_POST["search"])) {
            $details            = $this->route_model->searchTransportDetails($section_id, $class_id, $transport_route_id, $pickup_point_id, $vehicle_id);
            $data["resultlist"] = $details;
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view("layout/header", $data);
        $this->load->view("admin/route/studentroutedetails", $data);
        $this->load->view("layout/footer", $data);
    }

    public function pickup_point()
    {
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'route/index');
        $listpickup_point         = $this->route_model->listpickup_point();
        $data['listpickup_point'] = $listpickup_point;
        $this->load->view('layout/header');
        $this->load->view('admin/route/pickup_point', $data);
        $this->load->view('layout/footer');
    }

}
