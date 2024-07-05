<?php

class Staffattendancemodel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function get($id = null) {
        $this->db->select()->join("staff", "staff.id = staff_attendance.staff_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        if ($id != null) {
            $this->db->where('staff_attendance.id', $id);
        } else {
            $this->db->order_by('staff_attendance.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getUserType() {

        $query = $this->db->query("select distinct user_type from staff where is_active = 1");

        return $query->result_array();
    }


    public function searchAttendenceUserTypeWithMode($user_type, $date,$mode) {
        $condition = '';

        if ($mode == 1) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=0";
        } elseif ($mode == 2) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=1";
        } elseif ($mode == 3) {
            $condition = " and staff_attendance.biometric_attendence= 1 and staff_attendance.qrcode_attendance=0";
        }


        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") {   

            $query = $this->db->query("select staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition order by staff_attendance.created_at asc");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition order by staff_attendance.created_at asc");
            
        }
        return $query->result_array();
    }


    public function searchAttendenceUserType($user_type, $date) {
        $condition = '';
        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") {   

            $query = $this->db->query("select staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition");
            
        }
        return $query->result_array();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_attendance', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_attendance', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
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

    public function getStaffAttendanceType() {

        $query = $this->db->select('*')->where("is_active", 'yes')->get("staff_attendance_type");

        return $query->result_array();
    }

    public function searchAttendanceReport($user_type, $date) {

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
             
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            $condition = '';
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $condition = "and staff_roles.role_id != 7";       
            } 
        }
        
        if ($user_type == "select") {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where staff.is_active = 1 $condition");
        } else {

            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff  left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where roles.name = '" . $user_type . "' and staff.is_active = 1 $condition");
        }

        return $query->result_array();
    }

    public function attendanceYearCount() {

        $query = $this->db->select("distinct year(date) as year")->get("staff_attendance");

        return $query->result_array();
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true) {

        $sql = "select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.id = " . $this->db->escape($staff_id);
        if ($active_staff || !isset($active_staff)) {
            $sql .= " and staff.is_active = 1";
        }
        $query = $this->db->query($sql);
        return $query->row_array();
    }


        public function onlineattendence($data) {

        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('date', $data['date']);
        $q = $this->db->get('staff_attendance');

        if ($q->num_rows() == 0) {
            $this->db->insert('staff_attendance', $data);
            return ($this->db->affected_rows() != 1) ? false : true;
        }
        return false;
    }


}
