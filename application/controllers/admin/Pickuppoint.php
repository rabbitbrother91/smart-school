<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pickuppoint extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("pickuppoint_model", "routepickuppoint_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library("datatables");
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('pickup_point', 'can_view'))) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'pickuppoint/index');
        $data = array();
        $this->load->view('layout/header');
        $this->load->view('admin/pickuppoint/index', $data);
        $this->load->view('layout/footer');
    }

    public function getpickpointlist()
    {
        $listpickup_point         = $this->pickuppoint_model->listpickup_point();
        $data['listpickup_point'] = $listpickup_point;

        $m       = json_decode($listpickup_point);
        $dt_data = array();

        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                
                if ($this->rbac->hasPrivilege('pickup_point', 'can_edit')) {                    
                    $action = '<button class="btn btn-default btn-xs pickup_map" data-pick-location="' . $value->id . '"data-toggle="tooltip" title="' . $this->lang->line("map") . '"><i class="fa fa-map-marker"></i></button><a onclick="edit(' . $value->id . ')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="' . $this->lang->line('edit') . '"><i class="fa fa-pencil"></i></a>';         
                }else{
                    $action = '';
                }
                
                if ($this->rbac->hasPrivilege('pickup_point', 'can_delete')) {
                    $deletebtn = " <a href=" . base_url() . 'admin/pickuppoint/delete_point/' . $value->id . " class='btn btn-default btn-xs' data-toggle='tooltip'  title=" . $this->lang->line('delete') . " '  onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' ><i class='fa fa fa-remove'></i></a>";
                }else{
                    $deletebtn = '';
                }

                $row       = array();
                $row[]     = $value->name;
                $row[]     = $value->latitude;
                $row[]     = $value->longitude;
                $row[]     = $action . ' ' . $deletebtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);

    }

    public function add_point()
    {
        $id = $this->input->post('id');

        $this->form_validation->set_rules('name', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('latitude', $this->lang->line('latitude'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('longitude', $this->lang->line('longitude'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg['name']      = form_error('name');
            $msg['longitude'] = form_error('longitude');
            $msg['latitude']  = form_error('latitude');
            $array            = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array(
                'name'      => $this->input->post('name'),
                'latitude'  => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
            );
            if (!empty($id)) {
                $data['id'] = $id;
            }
            $this->pickuppoint_model->add_pickup_point($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function add_student_fees()
    {
        $data_insert           = array();
        $data_update           = array();
        $transport_route_fees  = $this->input->post('transport_route_fee');
        $student_session_id    = $this->input->post('student_session_id');
        $route_pickup_point_id = $this->input->post('route_pickup_point_id');
        $prev_ids              = array_filter($this->input->post('prev_ids'));
        $not_deleted           = array();

        if (isset($_POST['transport_route_fee'])) {
            foreach ($transport_route_fees as $transport_key => $transport_value) {

                $is_prev_inserted = $this->input->post('student_transport_fee_id_' . $transport_value);

                if ($is_prev_inserted == "") {
                    $data_insert[] = array(
                        'student_session_id'     => $student_session_id,
                        'route_pickup_point_id'  => $route_pickup_point_id,
                        'transport_feemaster_id' => $transport_value, 
                    );
                } else {
                    $not_deleted[] = $is_prev_inserted;
                }
            }
        }
        $remove_ids = array_diff($prev_ids, $not_deleted);

        $this->studenttransportfee_model->add($data_insert, $student_session_id, $remove_ids, $route_pickup_point_id);
        $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function student_fees()
    {
        if (!$this->rbac->hasPrivilege('student_transport_fees', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'pickuppoint/student_fees');
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

        } else {
            $class                   = $this->class_model->get();
            $data['classlist']       = $class;
            $data['student_due_fee'] = array();
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');

            $students         = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['students'] = $students;
        }

        $this->load->view('layout/header');
        $this->load->view('admin/pickuppoint/student_fees', $data);
        $this->load->view('layout/footer');
    }

    public function reorder()
    {
        $data           = array();
        $id             = $this->input->post('route_id');
        $data['result'] = $this->pickuppoint_model->reorder_pickup_point($id);
        echo json_encode($this->load->view("admin/pickuppoint/_reorder", $data, true));
    }

    public function reorder_pointid()
    {
        $result = $this->pickuppoint_model->reorder($this->input->post('position'));
        echo $result['transport_route_id'];
    }

    public function create()
    {
        if ($_POST['action_type'] == 'add') {
            $this->form_validation->set_rules('route_id', $this->lang->line('route'), 'trim|required|xss_clean|is_unique[route_pickup_point.transport_route_id]');
        } else {
            $this->form_validation->set_rules('route_id', $this->lang->line('route'), 'trim|required|xss_clean');
        }
        $validate  = 1;
        $duplicate = 0;
        $pickup_array=array();
        if (!empty($_POST['pickup_point'])) {

            foreach ($_POST['pickup_point'] as $pickup_pointkey => $pickup_pointvalue) {
                if ($pickup_pointvalue == '') {
                    $validate            = 0;
                    $msg['pickup_point'] = "<p>" . $this->lang->line('the_pickup_point_field_is_required') . "</p>";
                          break;
                }

                if (array_key_exists($pickup_pointvalue,$pickup_array) && $pickup_pointvalue != "") {
                    $duplicate=1;
                    break;
                   
                } else {
                 
                    $pickup_array[$pickup_pointvalue] = 0;
                }
            }
        } else {
            $validate            = 0;
            $msg['pickup_point'] = "<p>" . $this->lang->line('the_pickup_point_field_is_required') . "</p>";
        }

        if (!empty($_POST['monthly_fees'])) {
            foreach ($_POST['monthly_fees'] as $monthly_feeskey => $monthly_feesvalue) {
                if ($monthly_feesvalue == '') {
                    $validate            = 0;
                    $msg['monthly_fees'] = "<p>" . $this->lang->line('the_monthly_fees_field_is_required') . "</p>";
                    break;
                }else{
                   
                  $expr = '/^[0-9]*(\.[0-9]{0,2})?$/';
                if (!preg_match($expr, $monthly_feesvalue)) {
                   $validate            = 0;
                   $msg['monthly_fees'] = "<p>" . $this->lang->line('invalid_amount') . "</p>";
                   break;
                }


                }
            }
        } else {
            $validate            = 0;
            $msg['monthly_fees'] = "<p>" . $this->lang->line('the_monthly_fees_field_is_required') . "</p>";
        }

        if (!empty($_POST['time'])) {
            foreach ($_POST['time'] as $timekey => $timevalue) {
                if ($timevalue == '') {
                    $validate    = 0;
                    $msg['time'] = "<p>" . $this->lang->line('the_pickup_time_field_is_required') . "</p>";
                          break;
                }
            }
        } else {
            $validate    = 0;
            $msg['time'] = "<p>" . $this->lang->line('the_pickup_time_field_is_required') . "</p>";
        }

        if ($duplicate > 0) {
            $validate                      = 0;
            $msg['duplicate_pickup_point'] = "<p>" . $this->lang->line('duplicate_pickup_point_found') . "</p>";
        }
        if ($this->form_validation->run() == false) {
            $msg['route_id'] = form_error('route_id');
            $array           = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } elseif ($validate == 0) {

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            if (!empty($_POST['delete_ides'])) {
                foreach ($_POST['delete_ides'] as $delete_ideskey => $delete_idesvalue) {
                    $this->pickuppoint_model->remove_pickupfromroute($delete_idesvalue);
                }}
            if (!empty($_POST['pickup_point'])) {
                $sn = 1;
                foreach ($_POST['pickup_point'] as $pickup_pointkey => $pickup_pointvalue) {
                    $time = $this->input->post('time')[$pickup_pointkey];

                    $data = array(
                        'id'                   => $this->input->post('pickup_point_id')[$pickup_pointkey],
                        'transport_route_id'   => $this->input->post('route_id'),
                        'pickup_point_id'      => $this->input->post('pickup_point')[$pickup_pointkey],
                        'destination_distance' => $this->input->post('destination_distance')[$pickup_pointkey],
                        'fees'                 => convertCurrencyFormatToBaseAmount($this->input->post('monthly_fees')[$pickup_pointkey]),
                        'pickup_time'          => $this->customlib->timeFormat($time, true),
                    );
                    if (empty($data['id'])) {
                        $data['order_number'] = $sn;
                    }
                    $sn++;
                    $insert_id = $this->pickuppoint_model->add($data);

                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'Fees Master List';
        $this->pickuppoint_model->remove($id);
        redirect('admin/pickuppoint/assign');
    }

    public function delete_point($id)
    {
        $data['title'] = 'Fees Master List';
        $this->pickuppoint_model->remove_point($id);
        redirect('admin/pickuppoint');
    }

    public function assign()
    {
        if (!($this->rbac->hasPrivilege('route_pickup_point', 'can_view'))) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'pickuppoint/assign');
        $assign_pickup_point              = $this->pickuppoint_model->route_pickup_point();
        $vehroute_result                  = $this->pickuppoint_model->get_routelist();
        $data['vehroutelist']             = $vehroute_result; 
        $data['assign_pickup_point_list'] = $assign_pickup_point;
        $this->load->view('layout/header');
        $this->load->view('admin/pickuppoint/assign', $data);
        $this->load->view('layout/footer');
    }

    public function pointmap()
    {
        $pick_location    = $this->input->post('pick_location');
        $data['location'] = $this->pickuppoint_model->get($pick_location);
        $data['page']     = $this->load->view('admin/pickuppoint/_pointmap', $data, true);
        $array            = array('status' => '1', 'error' => '', 'page' => $data);
        echo json_encode($array);
    }

    public function student_transport_months()
    {
        $data                          = array();
        $data['sch_setting']           = $this->sch_setting_detail;
        $month_list                    = $this->customlib->getMonthDropdown($this->sch_setting_detail->start_month);
        $data['title']                 = 'student fees';
        $data['month_list']            = $month_list;
        $student_session_id            = $this->input->post('student_session_id');
        $student                       = $this->student_model->getByStudentSession($student_session_id);
        $data['student']               = $student;
        $data['student_session_id']    = $student_session_id;
        $route_pickup_point_id         = $student['route_pickup_point_id'];
        $data['route_pickup_point_id'] = $route_pickup_point_id;
        $route_pickup_point            = $this->routepickuppoint_model->get($route_pickup_point_id);
        $data['route_pickup_point']    = $route_pickup_point;
        foreach($month_list as $key=>$value){
         $data['fees'][]                  = $this->studenttransportfee_model->getTransportFeeByMonthStudentSession($student_session_id, $route_pickup_point_id,$key);   
        }
        

        $page  = $this->load->view('admin/pickuppoint/student_transport_months', $data, true);
        $array = array('status' => '1', 'error' => '', 'page' => $page);
        echo json_encode($array);
    }

    public function addmore_point()
    {
        $data                     = array();
        $id                       = $this->input->post('id');
        $pickup_pointdata=['id'=>"",
                            'destination_distance'=>"",
                            'pickup_point_id'=>"",
                            'pickup_time'=>"",
                            'fees'=>0
                          ];


        if(isset($id)){
             $pickup_pointdata         = $this->pickuppoint_model->getpickup_pointbyid($id);
        }
       
        $listpickup_point         = $this->pickuppoint_model->dropdownpickup_point();
        $data['listpickup_point'] = $listpickup_point;

        $data['result']           = $pickup_pointdata;
        $data['delete_string']    = $this->input->post('delete_string');
        echo json_encode($this->load->view("admin/pickuppoint/_addpickuppoint", $data, true));
    }

    public function get_pointdata()
    {
        $id               = $this->input->post('point_id');
        $listpickup_point = $this->pickuppoint_model->get($id);
        echo json_encode($listpickup_point);
    }

    public function get_assigndetails()
    {
        $id             = $this->input->post('id');
        $data['result'] = $this->pickuppoint_model->getPickupPointByRouteID($id);
        echo json_encode($data['result']);
    }

    public function get_pickupdropdownlist()
    {
        $vehroute_id           = $this->input->post('vehroute_id');
        $vehicle_route_pickups = $this->pickuppoint_model->getPickupPointsByvehrouteId($vehroute_id);
        echo json_encode($vehicle_route_pickups);
    }

    public function getpickuppointsbyroute()
    {
        $transport_route_id    = $this->input->post('transport_route_id');
        $vehicle_route_pickups = $this->pickuppoint_model->getPickupPointByRouteID($transport_route_id);
        $routes_vehicle        = $this->vehroute_model->getVechileByRoute($transport_route_id);
        $result                = array('vehicle_route_pickups' => $vehicle_route_pickups, 'routes_vehicle' => $routes_vehicle);
        echo json_encode($result);
    }

    public function get_pickuppointfees($id)
    {
        $result = $this->pickuppoint_model->getpickup_pointbyid($id);
        echo json_encode($result);
    }
}
