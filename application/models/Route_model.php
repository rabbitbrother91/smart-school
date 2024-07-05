<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Route_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select()->from('transport_route');
        if ($id != null) {
            $this->db->where('transport_route.id', $id);
        } else {
            $this->db->order_by('transport_route.route_title');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('transport_route');
        $message   = DELETE_RECORD_CONSTANT . " On transport route id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('transport_route', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  transport route id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
        } else {
            $this->db->insert('transport_route', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On transport route id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================

            $this->db->trans_complete(); # Completing transaction
            /* Optional */

            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            } else {
                //return $return_value;
            }
            return $insert_id;
        }
    }

    public function listroute($id = null)
    {
        $this->db->select()->from('transport_route');
        if ($id != null) {
            $this->db->where('transport_route.id', $id);

            $listtransport = $this->db->get();
            return $listtransport->row_array();

        } else {

            $listtransport = $this->db->get();
            return $listtransport->result_array();
        }

    }

    public function listpickup_point()
    {
        $this->db->select()->from('pickup_point');
        $listtransport = $this->db->get();
        return $listtransport->result_array();
    }

    public function listvehicles()
    {
        $this->db->select()->from('vehicles');
        $listvehicles = $this->db->get();
        return $listvehicles->result_array();
    }

    public function searchTransportDetails($section_id, $class_id, $transport_route_id, $pickup_point_id, $vehicle_id)
    {
        $userdata            = $this->customlib->getUserData();
        $class_section_array = $this->customlib->get_myClassSection();
        if (!empty($class_section_array)) {
            $this->db->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                $query_string = "";
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $query_string = "( student_session.class_id=" . $class_sectionkey . " and student_session.section_id=" . $class_sectionvaluevalue . " )";
                    $this->db->or_where($query_string);
                }
            }
            $this->db->group_end();
        }
        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if (!empty($transport_route_id)) {
            $this->db->where('route_pickup_point.transport_route_id', $transport_route_id);
        }

        if (!empty($pickup_point_id)) {
            $this->db->where('route_pickup_point.pickup_point_id', $pickup_point_id);
        }

        if (!empty($vehicle_id)) {
            $this->db->where('vehicles.id', $vehicle_id);
        }

        $this->db->where('students.is_active', 'yes');
        $query = $this->db->select('students.firstname,students.middlename,students.id,students.admission_no,students.father_name,students.mother_name, students.father_phone,students.mother_phone,classes.class,sections.section,students.lastname,students.mobileno,student_session.route_pickup_point_id,pickup_point.name as `pickup_name`,transport_route.route_title,route_pickup_point.fees,route_pickup_point.destination_distance,route_pickup_point.pickup_time,vehicles.vehicle_no,vehicles.vehicle_model,vehicles.driver_name,vehicles.driver_contact')
            ->join('student_session', 'students.id = student_session.student_id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('classes', 'classes.id = student_session.class_id')
            ->join('route_pickup_point', 'student_session.route_pickup_point_id= route_pickup_point.id')
            ->join('transport_route', 'transport_route.id= route_pickup_point.transport_route_id')
            ->join('pickup_point', 'pickup_point.id=route_pickup_point.pickup_point_id')
            ->join("vehicle_routes", "student_session.vehroute_id = vehicle_routes.id")
            ->join("vehicles", "vehicle_routes.vehicle_id = vehicles.id")
            ->order_by("classes.class", 'asc')
            ->order_by("sections.section", 'asc')
            ->where('student_session.session_id', $this->current_session)->get("students");

        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function getClass($student_id)
    {
        $query = $this->db->query("SELECT  classes.class, classes.id  FROM  `classes`  where id in ( SELECT max(class_id) from student_session WHERE student_id = $student_id) ");
        return $query->row_array();
    }

    public function getSection($student_id, $class_id)
    {
        $query = $this->db->query("SELECT  sections.section  FROM  `sections` join student_session on student_session.section_id = sections.id where student_session.class_id = " . $class_id . " and student_session.student_id = " . $student_id);
        return $query->row_array();
    }

}
