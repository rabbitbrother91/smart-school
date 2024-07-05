<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
    }

    public function getBirthDayStudents($date, $email = false, $contact_no = false)
    {
        $userdata            = $this->customlib->getUserData();
        $class_section_array = $this->customlib->get_myClassSection();
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno,students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');
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
        if ($email) {
            $this->db->where('students.email !=', "");
        }
        if ($contact_no) {
            $this->db->where('students.mobileno !=', "");
        }

        $this->db->where("DATE_FORMAT(students.dob,'%m-%d') = DATE_FORMAT('" . $date . "','%m-%d')");
        $this->db->order_by('students.id');

        $query  = $this->db->get();
        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function getStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.guardian_name, students.guardian_relation,students.guardian_email,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.role', 'student');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAppStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,  students.middlename,students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.app_key ,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.app_key !=', "");
        $this->db->where('users.role', 'student');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getRecentRecord($id = null)
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email,students.state,students.city,students.pincode,students.religion,students.dob,students.current_address,    students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is')->from('students');
        $this->db->join('student_session','student_session.student_id = students.id');
        $this->db->join('classes','student_session.class_id = classes.id');
        $this->db->join('sections','sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {

        }
        $this->db->order_by('students.id', 'desc');
        $this->db->limit(5);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getParentChilds($parent_id)
    {
        $sql   = "SELECT students.*,student_session.id as `student_session_id`,student_session.session_id,student_session.student_id,student_session.class_id,student_session.default_login,student_session.section_id,classes.class,sections.section From students inner JOIN student_session on student_session.student_id=students.id inner join classes on student_session.class_id=classes.id INNER JOIN sections on sections.id=student_session.section_id WHERE students.parent_id=" . $this->db->escape($parent_id) . " and student_session.session_id=" . $this->current_session . " and students.is_active = 'yes' order by student_session.default_login desc,student_session.class_id asc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentByClassSectionID($class_id = null, $section_id = null, $id = null, $session_id = null)
    {
        if ($session_id != "") {
            $session_id = $session_id;
        } else {
            $session_id = $this->current_session;
        }

        $this->db->select('pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname, students.middlename,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode, students.note, students.religion,students.cast,school_houses.house_name,students.dob,students.current_address,students.previous_school,
            students.guardian_is,students.parent_id,
            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic,students.height,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email,sessions.session, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('sessions', 'sessions.id = student_session.session_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('users.role', 'student');

        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getByStudentSession($student_session_id)
    {
        $this->db->select('pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.transport_fees,students.app_key,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.note, students.religion, students.cast, school_houses.house_name,students.dob ,students.current_address,students.previous_school,students.guardian_is,students.parent_id,          students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.guardian_name ,students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic ,students.guardian_pic ,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('student_session.id', $student_session_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getStudentBy_class_section_id($cls_section_id)
    {
        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('student_session.transport_fees,students.app_key,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,class_sections.id as `class_section_id`,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city, students.pincode,students.note, students.religion, students.cast, school_houses.house_name,students.dob ,students.current_address, students.previous_school,students.guardian_is,students.parent_id,        students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,students.app_key,students.parent_app_key,IFNULL(categories.category, "") as `category`,' . $field_variable)->from('students');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id = sections.id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('class_sections.id', $cls_section_id);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
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
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get($id = null)
    {
        $this->db->select('pickup_point.name as pickup_point_name,student_session.route_pickup_point_id,student_session.transport_fees,students.app_key,students.parent_app_key,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,vehicles.vehicle_model,vehicles.manufacture_year,vehicles.driver_licence,vehicles.vehicle_photo,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.note,students.religion,students.cast, school_houses.house_name,students.dob,students.current_address,students.previous_school,students.guardian_is,students.parent_id,  students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic ,students.height,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,users.id as user_id,students.dis_reason,students.dis_note,students.disable_at')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->where('students.is_active', 'yes');
            $this->db->order_by('students.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function findByAdmission($admission_no = null)
    {
        $this->db->select('student_session.transport_fees,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname, students.middlename,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode,students.note,students.religion, students.cast,school_houses.house_name,students.dob,students.current_address,students.previous_school,
            students.guardian_is,students.parent_id,            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.admission_no', $admission_no);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function search_alumniStudent($class_id = null, $section_id = null, $session_id = null)
    {
        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno,students.email,students.state,students.city, students.pincode,students.religion,students.dob,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code , students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session','student_session.student_id = students.id');
        $this->db->join('classes','student_session.class_id = classes.id');
        $this->db->join('sections','sections.id = student_session.section_id');
        $this->db->join('categories','students.category_id = categories.id', 'left');
        $this->db->where('student_session.is_alumni', 1);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($session_id != null) {
            $this->db->where('student_session.session_id', $session_id);
        }
        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSearchFullView($searchterm, $start, $limit, $search, $carray)
    {
        $class_section_array = $this->customlib->get_myClassSection();
        $userdata            = $this->customlib->getUserData();
        $staff_id            = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

      $field_variable = (empty($field_var_array))? "": ",".implode(',', $field_var_array);
      
        if (!empty($class_section_array)) {
            $this->db->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->db->or_group_start();
                    $this->db->where('student_session.class_id', $class_sectionkey);
                    $this->db->where('student_session.section_id', $class_sectionvaluevalue);
                    $this->db->group_end();
                }
            }
            $this->db->group_end();
        }

        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.father_name , students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.middlename', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('school_houses.house_name', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.mobileno', $searchterm);
        $this->db->or_like('students.email', $searchterm);
        $this->db->or_like('students.religion', $searchterm);
        $this->db->or_like('students.cast', $searchterm);
        $this->db->or_like('students.gender', $searchterm);
        $this->db->or_like('students.current_address', $searchterm);
        $this->db->or_like('students.permanent_address', $searchterm);
        $this->db->or_like('students.blood_group', $searchterm);
        $this->db->or_like('students.bank_name', $searchterm);
        $this->db->or_like('students.ifsc_code', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.father_phone', $searchterm);
        $this->db->or_like('students.father_occupation', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);
        $this->db->or_like('students.mother_phone', $searchterm);
        $this->db->or_like('students.mother_occupation', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.guardian_relation', $searchterm);
        $this->db->or_like('students.guardian_phone', $searchterm);
        $this->db->or_like('students.guardian_occupation', $searchterm);
        $this->db->or_like('students.guardian_address', $searchterm);
        $this->db->or_like('students.guardian_email', $searchterm);
        $this->db->or_like('students.previous_school', $searchterm);
        $this->db->or_like('students.note', $searchterm);
        $this->db->group_end();

        $searchable = 'students.admission_no,students.firstname,students.middlename,students.lastname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_variable;
        $column     = explode(',', $searchable);

        $search_colomn        = array();

        foreach ($column as $key => $col) {
            $col         = strtolower($col);
            $col         = strstr($col, ' as ', true) ?: $col;
            $search_colomn[] = $col;
        }
       $search= $search['value'];
        if ($search != '' && $searchable != '') {
                for ($i = 0; $i < count($search_colomn); $i++) {
                    if ($i == 0) {
                        $this->db->group_start();
                        $this->db->like($search_colomn[$i], $search);
                    } else {
                        $this->db->or_like($search_colomn[$i], $search);
                    }

                    if (count($search_colomn) - 1 == $i) //last loop
                    {
                        $this->db->group_end();
                    }
                    //close bracket
                }    
        } 

        $this->db->limit($limit, $start);
        $this->db->order_by('students.id');
        $query  = $this->db->get();
        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function search_alumniStudentbyAdmissionNo($searchterm, $carray)
    {
        $userdata        = $this->customlib->getUserData();
        $staff_id        = $userdata['id'];
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code ,students.father_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes','student_session.class_id = classes.id');
        $this->db->join('sections','sections.id = student_session.section_id');
        $this->db->join('categories','students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active','yes');
        $this->db->where('student_session.is_alumni', '1');
        $this->db->group_start();
        $this->db->like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->group_by('students.id');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function guardian_credential($parent_id)
    {
        $this->db->select('id,user_id,username,password')->from('users');
        $this->db->where('id', $parent_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function search_student()
    {
        $this->db->select('classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob,students.current_address,    students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('students.id', $id);
        } else {
            $this->db->order_by('students.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudentdoc($id)
    {
        $this->db->select()->from('student_doc');
        $this->db->where('student_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getDatatableByClassSection($class_id = null, $section_id = null)
    {
        $this->datatables
            ->select('classes.id as `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id as `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno,students.email,students.state,students.city, students.pincode,students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender')
            ->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone')
            ->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', "yes")
            ->sort('students.admission_no', 'asc');
        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }

        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }

    public function getDatatableByFullTextSearch($searchterm)
    {
        $userdata            = $this->customlib->getUserData();
        $class_section_array = $this->customlib->get_myClassSection();
        $this->datatables->select('`classes`.`id` as `class_id`,`students`.`id`,`student_session`.`id` as `student_session_id`,`classes`.`class`,sections.id as `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id');
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        if (!empty($class_section_array)) {
            $this->datatables->group_start();

            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->datatables->or_group_start();
                    $this->datatables->where('student_session.class_id', $class_sectionkey);
                    $this->datatables->where('student_session.section_id', $class_sectionvaluevalue);
                    $this->datatables->group_end();

                }
            }
            $this->datatables->group_end();
        }
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,students.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', 'yes');
        $this->datatables->sort('students.admission_no', 'asc');
        $this->datatables->searchable('class_id,section_id,admission_no,students.firstname,students.middlename,  students.lastname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->orderable('class_id,section_id,admission_no,students.firstname,students.father_name,students.dob,students.guardian_phone');
        $this->datatables->from('students');
        $std_data = $this->datatables->generate('json');
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $std_data       = json_decode($std_data);
            $std_data->data = array();
            return json_encode($std_data);
        } else {
            return $std_data;
        }
    }

    public function searchByClassSection($class_id = null, $section_id = null)
    {
        $userdata            = $this->customlib->getUserData();
        $i                   = 1;
        $class_section_array = $this->customlib->get_myClassSection();
        $custom_fields       = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array     = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,vehicles.vehicle_no,transport_route.route_title,route_pickup_point.id as `route_pickup_point_id`,pickup_point.name as `pickup_point`,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('route_pickup_point', 'student_session.route_pickup_point_id = route_pickup_point.id', 'left');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->join('vehicle_routes', 'student_session.vehroute_id = vehicle_routes.id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicle_routes.vehicle_id = vehicles.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
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
        $this->db->order_by('students.admission_no', 'asc');

        $query = $this->db->get();

        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }

        return $result;
    }

    public function searchByClassSectionWithoutCurrent($class_id = null, $section_id = null, $student_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        $this->db->where('students.id !=', $student_id);
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchdatatableByClassSectionCategoryGenderRte($class_id = null, $section_id = null, $category = null, $gender = null, $rte = null)
    {

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        if ($category != null) {
            $this->datatables->where('students.category_id', $category);
        }
        if ($gender != null) {
            $this->datatables->where('students.gender', $gender);
        }
        if ($rte != null) {
            $this->datatables->where('students.rte', $rte);
        }

        $this->datatables->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,students.category_id, categories.category,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code,students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')

            ->searchable('sections.section,students.admission_no,students.firstname,students.father_name,students.dob,students.gender,categories.category,students.mobileno,students.samagra_id,students.adhar_no,students.rte')
            ->orderable('sections.section,students.admission_no,students.firstname,students.father_name,students.dob,students.gender,categories.category,students.mobileno,students.samagra_id,students.adhar_no,students.rte')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', 'yes')
            ->sort('students.id')
            ->from('students');
        return $this->datatables->generate('json');
    }

    public function searchusersbyFullText($searchterm, $carray = null)
    {
        $class_section_array = $this->customlib->get_myClassSection();
        $userdata            = $this->customlib->getUserData();
        $staff_id            = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if (!empty($class_section_array)) {
            $this->db->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->db->or_group_start();
                    $this->db->where('student_session.class_id', $class_sectionkey);
                    $this->db->where('student_session.section_id', $class_sectionvaluevalue);
                    $this->db->group_end();
                }
            }
            $this->db->group_end();
        }

        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name , students.guardian_name ,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.middlename', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('school_houses.house_name', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.mobileno', $searchterm);
        $this->db->or_like('students.email', $searchterm);
        $this->db->or_like('students.religion', $searchterm);
        $this->db->or_like('students.cast', $searchterm);
        $this->db->or_like('students.gender', $searchterm);
        $this->db->or_like('students.current_address', $searchterm);
        $this->db->or_like('students.permanent_address', $searchterm);
        $this->db->or_like('students.blood_group', $searchterm);
        $this->db->or_like('students.bank_name', $searchterm);
        $this->db->or_like('students.ifsc_code', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.father_phone', $searchterm);
        $this->db->or_like('students.father_occupation', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);
        $this->db->or_like('students.mother_phone', $searchterm);
        $this->db->or_like('students.mother_occupation', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.guardian_relation', $searchterm);
        $this->db->or_like('students.guardian_phone', $searchterm);
        $this->db->or_like('students.guardian_occupation', $searchterm);
        $this->db->or_like('students.guardian_address', $searchterm);
        $this->db->or_like('students.guardian_email', $searchterm);
        $this->db->or_like('students.previous_school', $searchterm);
        $this->db->or_like('students.note', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
        $query  = $this->db->get();
        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function admission_report($searchterm, $carray = null, $condition = null)
    {
        $class_section_array = $this->customlib->get_myClassSection();
        $userdata            = $this->customlib->getUserData();

        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        if ($condition != null) {
            $this->datatables->where($condition);
        }

        /*----------------------------------------*/
        $this->datatables->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,   students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,' . $field_variable);
        $this->datatables->searchable('admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category');
        $this->datatables->orderable('admission_no,students.firstname,classes.class,students.father_name,students.dob,students.admission_date,students.gender,categories.category');
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', 'yes');
        if (!empty($class_section_array)) {
            $this->datatables->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                $query_string = "";
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $query_string = "( student_session.class_id=" . $class_sectionkey . " and student_session.section_id=" . $class_sectionvaluevalue . " )";
                    $this->datatables->or_where($query_string);
                }
            }
            $this->datatables->group_end();
        }
        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.lastname,students.guardian_name,students.adhar_no,students.samagra_id,students.roll_no,students.admission_no', $searchterm);
        $this->datatables->group_end();
        $this->datatables->sort('students.id');
        $this->datatables->from('students');
        return $this->datatables->generate('json');
    }

    public function student_ratio()
    {
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select(' count(*) as total_student, SUM(CASE WHEN `gender` = "Male" THEN 1 ELSE 0 END) AS "male",SUM(CASE WHEN `gender` = "Female" THEN 1 ELSE 0 END) AS "female", classes.class,sections.section, classes.id as class_id, sections.id as section_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('class_sections', 'class_sections.class_id = classes.id and class_sections.section_id=sections.id', 'inner');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_by('class_sections.id');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function sibling_report($searchterm, $carray = null, $condition = null)
    {
        $userdata        = $this->customlib->getUserData();
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,   students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.mother_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,students.parent_id,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($condition != null) {
            $this->db->where($condition);
        }
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function sibling_reportsearch($searchterm, $carray = null, $condition = null)
    {
        $userdata        = $this->customlib->getUserData();
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('students.parent_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($condition != null) {
            $this->db->where($condition);
        }
        $this->db->group_by('students.parent_id');
        $this->db->group_by('students.admission_no');
        $this->db->order_by('students.father_name');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentListBYStudentsessionID($array)
    {
        $array = implode(',', $array);
        $sql   = ' SELECT students.* FROM students INNER join (SELECT * FROM `student_session` WHERE `student_session`.`id` IN (' . $array . ')) as student_session on students.id=student_session.student_id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function remove($id)
    {
        $this->db->trans_start();

        $sql   = "SELECT * FROM `users` WHERE childs LIKE '%," . $id . ",%' OR childs LIKE '" . $id . ",%' OR childs LIKE '%," . $id . "' OR childs = " . $id;
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $result      = $query->row();
            $array_slice = explode(',', $result->childs);
            if (count($array_slice) > 1) {
                $arr    = array_diff($array_slice, array($id));
                $update = implode(",", $arr);
                $data   = array('childs' => $update);
                $this->db->where('id', $result->id);
                $this->db->update('users', $data);
            } else {
                $this->db->where('id', $result->id);
                $this->db->delete('users');
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('students');

        $this->db->where('student_id', $id);
        $this->db->delete('student_session');

        $this->db->where('user_id', $id);
        $this->db->where('role', 'student');
        $this->db->delete('users');
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function doc_delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_doc');
    }

    public function add($data, $data_setting = array())
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('students', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On students id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            if (!empty($data_setting)) {

                if ($data_setting['adm_auto_insert']) {
                    if ($data_setting['adm_update_status'] == 0) {
                        $data_setting['adm_update_status'] = 1;
                        $this->setting_model->add($data_setting);
                    }
                }
                $this->db->insert('students', $data);
                $insert_id = $this->db->insert_id();
                $message   = INSERT_RECORD_CONSTANT . " On students id " . $insert_id;
                $action    = "Insert";
                $record_id = $insert_id;
                $this->log($message, $record_id, $action);

                return $insert_id;
            }
        }
    }

    public function add_student_sibling($data_sibling)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_sibling', $data_sibling);
            $message   = UPDATE_RECORD_CONSTANT . " On  student sibling id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_sibling', $data_sibling);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On student sibling id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function add_student_session($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('session_id', $data['session_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $rec = $q->row_array();
            $this->db->where('id', $rec['id']);
            $this->db->update('student_session', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  student session id " . $rec['id'];
            $action    = "Update";
            $record_id = $rec['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_session', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  student session id " . $id;
            $action    = "Insert";
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
            return $record_id;
        }
    }

    public function get_student_vehicle_months($student_session_id)
    {
        $get_transportmonth = $this->db->select('*')->from('student_vehicle_months')->where('student_session_id', $student_session_id)->get()->result_array();
        foreach ($get_transportmonth as $mkey => $mvalue) {
            $get_transportmonth[] = $mvalue['month'];
        }
        return $get_transportmonth;
    }

    public function add_student_session_update($data)
    {
        $this->db->where('session_id', $data['session_id']);
        $q = $this->db->get('student_session');
        if ($q->num_rows() > 0) {
            $this->db->where('session_id', $student_session);
            $this->db->update('student_session', $data);
        } else {
            $this->db->insert('student_session', $data);
            return $this->db->insert_id();
        }
    }

    public function alumni_student_status($data)
    {
        $this->db->where('student_id', $data['student_id']);
        $this->db->where('session_id', $this->current_session);
        $this->db->update('student_session', $data);
    }

    public function adddoc($data)
    {
        $this->db->insert('student_doc', $data);
        return $this->db->insert_id();
    }

    public function read_siblings_students($parent_id)
    {
        $this->db->select('*')->from('students');
        $this->db->where('parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }

    public function getMySiblings($parent_id, $student_id)
    {
        $this->db->select('students.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section,student_session.session_id as `session_id`')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_not_in('students.id', $student_id);
        $this->db->where('students.parent_id', $parent_id);
        $this->db->where('students.is_active', 'yes');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAttedenceByDateandClass($date)
    {
        $sql   = "SELECT IFNULL(student_attendences.id, 0) as attencence FROM `student_session`left JOIN student_attendences on student_attendences.student_session_id=student_session.id and student_attendences.date=" . $this->db->escape($date) . " and student_attendences.attendence_type_id != 2 where student_session.class_id=7 and student_session.session_id=$this->current_session";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchCurrentSessionStudents()
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('students.firstname', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchLibraryStudent($class_id = null, $section_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,
           IFNULL(libarary_members.id,0) as `libarary_member_id`,
           IFNULL(libarary_members.library_card_no,0) as `library_card_no`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno,students.email,students.state,   students.city ,students.pincode ,students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('libarary_members', 'libarary_members.member_id = students.id and libarary_members.member_type = "student"', 'left');

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNameLike($searchterm)
    {
        $userdata            = $this->customlib->getUserData();
        $class_section_array = $this->customlib->get_myClassSection();
        $this->db->select('classes.id AS `class_id`,students.id, students.parent_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,   students.mobileno,students.email,students.state,students.city,students.pincode ,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_email,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.gender,students.rte,students.app_key,students.parent_app_key,student_session.session_id')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
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
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
        $this->db->limit(15);
        $query  = $this->db->get();
        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function searchGuardianNameLike($searchterm)
    {
        $this->db->select('classes.id AS `class_id`,students.id,`users`.`id` as `guardian_user_id`,students.parent_id,classes.class,sections.id AS `section_id`,sections.section,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email,students.state , students.city , students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code ,students.father_name,students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.gender,students.guardian_email,students.rte,student_session.session_id,students.app_key,students.parent_app_key')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->join('users', 'users.id = students.parent_id');
        $this->db->where('users.role', 'parent');
        $this->db->group_by('students.parent_id');
        $this->db->group_start();
        $this->db->like('students.guardian_name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('students.id');
        $this->db->limit(15);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchByClassSectionWithSession($class_id = null, $section_id = null, $session_id = null)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno,students.email,students.state,students.city , students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function searchNonPromotedStudents($class_id = null, $section_id = null, $promoted_session_id = null, $promoted_class_id = null, $promoted_section_id = null)
    {
        $sql = "SELECT promoted_students.id as `promoted_student_id`,`classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`, `students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` LEFT join (select * from student_session WHERE session_id=" . $promoted_session_id . " and class_id=" . $promoted_class_id . " and section_id=" . $promoted_section_id . ") as promoted_students on promoted_students.student_id=students.id WHERE `student_session`.`is_leave` =0 and  `student_session`.`session_id` = " . $this->current_session . " AND `students`.`is_active` = 'yes' AND `student_session`.`class_id` = " . $class_id . " AND `student_session`.`section_id` = " . $section_id . " and promoted_students.id IS NULL ORDER BY `students`.`id`";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPreviousSessionStudent($previous_session_id, $class_id, $section_id)
    {
        $sql   = "SELECT student_session.student_id as student_id, student_session.id as current_student_session_id, student_session.class_id as current_session_class_id ,previous_session.id as previous_student_session_id,students.firstname,students.middlename,students.lastname,students.admission_no,students.roll_no,students.father_name,students.admission_date FROM `student_session` left JOIN (SELECT * FROM `student_session` where session_id=$previous_session_id) as previous_session on student_session.student_id=previous_session.student_id INNER join students on students.id =student_session.student_id where student_session.session_id=$this->current_session and student_session.class_id=$class_id and student_session.section_id=$section_id and students.is_active='yes' ORDER BY students.firstname ASC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function studentGuardianDetails($carray)
    {
        $userdata = $this->customlib->getUserData();
        $this->db->SELECT("students.admission_no,students.firstname,students.middlename,students.mobileno,students.father_phone,students.mother_phone,students.lastname,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->get("students");
        return $query->result_array();
    }

    public function searchGuardianDetails($class_id, $section_id)
    {
        $this->db->SELECT("students.admission_no,students.firstname,students.middlename,students.lastname,students.mobileno,students.father_phone,students.mother_phone,students.father_name,students.mother_name,students.guardian_name,students.guardian_relation,students.guardian_phone,students.id,classes.class,sections.section");
        $this->db->join("student_session", "student_session.student_id = students.id");
        $this->db->join("classes", "student_session.class_id = classes.id");
        $this->db->join("sections", "student_session.section_id = sections.id");
        $this->db->where("students.is_active", "yes");
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id));
        $query = $this->db->get("students");
        return $query->result_array();
    }

    public function studentAdmissionDetails($carray = null)
    {
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->db->where_in("student_session.class_id", $carray);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $query = $this->db->SELECT("students.firstname,students.middlename,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name, students.guardian_relation,students.guardian_phone,classes.class,sessions.id,sections.section")->join("student_session", "students.id = student_session.student_id")->join("classes", "student_session.class_id = classes.id")->join("sections", "student_session.section_id = sections.id")->join("sessions", "student_session.session_id = sessions.id")->group_by("students.id")->get("students");
        return $query->result_array();
    }

    public function studentSessionDetails($id)
    {
        $query = $this->db->query("SELECT min(sessions.session) as start , max(sessions.session) as end, min(classes.class) as startclass, max(classes.class) as endclass from sessions join student_session on (sessions.id = student_session.session_id) join classes on (classes.id = student_session.class_id) where student_session.student_id = " . $id);
        return $query->row_array();
    }

    public function searchdatatablebyAdmissionDetails($class_id, $year)
    {
        if (!empty($year)) {
            $data = array('year(admission_date)' => $year, 'student_session.class_id' => $class_id);
        } else {
            $data = array('student_session.class_id' => $class_id);
        }

        $this->datatables->select('students.firstname,students.middlename,students.lastname,students.is_active, students.mobileno, students.id as sid ,students.admission_no, students.admission_date, students.guardian_name,students.guardian_relation,students.guardian_phone,classes.class,sessions.id,sections.section')
            ->searchable('students.admission_no,students.firstname,students.admission_date,students.mobileno,students.guardian_name,students.guardian_phone')
            ->join('student_session','students.id = student_session.student_id')
            ->join('classes','student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->join('sessions', 'student_session.session_id = sessions.id')
            ->where($data)
            ->group_by('students.id')
            ->orderable('students.admission_no,students.firstname,students.admission_date," "," "," ",students.mobileno,students.guardian_name,students.guardian_phone')
            ->sort('students.id')
            ->from('students');
        return $this->datatables->generate('json');
    }

    public function admissionYear()
    {
        $query = $this->db->SELECT("distinct(year(admission_date)) as year")->where_not_in('admission_date', array('0000-00-00', '1970-01-01'))->get("students");
        return $query->result_array();
    }

    public function getStudentSession($id)
    {
        $query = $this->db->query("SELECT  max(sessions.id) as student_session_id, max(sessions.session) as session from sessions join student_session on (sessions.id = student_session.session_id)  where student_session.student_id = " . $id);
        return $query->row_array();
    }

    public function valid_student_roll()
    {
        $roll_no    = $this->input->post('roll_no');
        $student_id = $this->input->post('studentid');
        $class      = $this->input->post('class_id');

        if ($roll_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_rollno_exists($roll_no, $student_id, $class)) {
                $this->form_validation->set_message('check_exists', 'Roll Number should be unique at Class level');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_rollno_exists($roll_no, $student_id, $class)
    {
        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'student_session.class_id' => $class, 'students.roll_no' => $roll_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->db->where(array('class_id' => $class, 'roll_no' => $roll_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function gethouselist()
    {
        $query = $this->db->where("is_active", "yes")->get("school_houses");
        return $query->result_array();
    }

    public function disableStudent($id, $data)
    {
        $this->db->where("id", $id)->update("students", $data);
    }

    public function getdisableStudent()
    {
        $class_section_array = $this->customlib->get_myClassSection();
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,    students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'no');
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
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentByClassSection($class, $section)
    {
        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email,students.state,students.city,students.pincode ,students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "no");
        if ($class != null) {
            $this->db->where('student_session.class_id', $class);
        }
        if ($section != null) {
            $this->db->where('student_session.section_id', $section);
        }
        $this->db->order_by('students.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function disablestudentFullText($searchterm)
    {
        $userdata            = $this->customlib->getUserData();
        $class_section_array = $this->customlib->get_myClassSection();
        $this->db->select('classes.id AS `class_id`,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,  students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,      students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id,dis_reason,dis_note')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->join('school_houses', 'students.school_house_id = school_houses.id', 'left');
        $this->db->where('students.is_active', 'no');
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
        $this->db->group_start();
        $this->db->like('students.firstname', $searchterm);
        $this->db->or_like('students.middlename', $searchterm);
        $this->db->or_like('students.lastname', $searchterm);
        $this->db->or_like('school_houses.house_name', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.adhar_no', $searchterm);
        $this->db->or_like('students.samagra_id', $searchterm);
        $this->db->or_like('students.roll_no', $searchterm);
        $this->db->or_like('students.admission_no', $searchterm);
        $this->db->or_like('students.mobileno', $searchterm);
        $this->db->or_like('students.email', $searchterm);
        $this->db->or_like('students.religion', $searchterm);
        $this->db->or_like('students.cast', $searchterm);
        $this->db->or_like('students.gender', $searchterm);
        $this->db->or_like('students.current_address', $searchterm);
        $this->db->or_like('students.permanent_address', $searchterm);
        $this->db->or_like('students.blood_group', $searchterm);
        $this->db->or_like('students.bank_name', $searchterm);
        $this->db->or_like('students.ifsc_code', $searchterm);
        $this->db->or_like('students.father_name', $searchterm);
        $this->db->or_like('students.father_phone', $searchterm);
        $this->db->or_like('students.father_occupation', $searchterm);
        $this->db->or_like('students.mother_name', $searchterm);
        $this->db->or_like('students.mother_phone', $searchterm);
        $this->db->or_like('students.mother_occupation', $searchterm);
        $this->db->or_like('students.guardian_name', $searchterm);
        $this->db->or_like('students.guardian_relation', $searchterm);
        $this->db->or_like('students.guardian_phone', $searchterm);
        $this->db->or_like('students.guardian_occupation', $searchterm);
        $this->db->or_like('students.guardian_address', $searchterm);
        $this->db->or_like('students.guardian_email', $searchterm);
        $this->db->or_like('students.previous_school', $searchterm);
        $this->db->or_like('students.note', $searchterm);
        $this->db->group_end();

        $this->db->order_by('students.id');
        $query  = $this->db->get();
        $result = $query->result_array();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $result = array();
        }
        return $result;
    }

    public function getClassSection($id)
    {
        $query = $this->db->SELECT("*")->join("sections", "class_sections.section_id = sections.id")->where("class_sections.class_id", $id)->get("class_sections");
        return $query->result_array();
    }

    public function getStudentClassSection($id, $sessionid)
    {
        $query = $this->db->SELECT("students.firstname,students.middlename,students.id,students.lastname,students.image,student_session.section_id, student_session.id as student_session_id")->join("student_session", "students.id = student_session.student_id")->where("student_session.class_id", $id)->where("student_session.session_id", $sessionid)->where("students.is_active", "yes")->get("students");

        return $query->result_array();
    }

    public function getStudentsByArray($array)
    {
        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students');

        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no,students.roll_no,students.admission_date,students.firstname, students.middlename,students.lastname,students.image,students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,     students.dob ,students.current_address,students.blood_group,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.cast,students.bank_name, students.ifsc_code,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.mother_name,students.updated_at,students.father_name,students.rte,students.gender,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where_in('students.id', $array);
        $this->db->order_by('students.id');
        $this->db->group_by('students.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_studentsession($student_session_id)
    {
        $query = $this->db->select('sessions.session')->join("student_session", "sessions.id = student_session.session_id")->where('student_session.id', $student_session_id)->get("sessions");
        return $query->row_array();
    }

    public function check_adm_exists($admission_no)
    {
        $this->db->where(array('admission_no' => $admission_no));
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function lastRecord()
    {
        $last_row = $this->db->select('*')->order_by('id', "desc")->limit(1)->get('students')->row();
        return $last_row;
    }

    public function currentClassSectionById($studentid, $schoolsessionId)
    {
        return $this->db->select('class_id,section_id')->from('student_session')->where('session_id', $schoolsessionId)->where('student_id', $studentid)->get()->row_array();
    }

    public function reportClassSection($class_id = null, $section_id = null)
    {
        $i = 1;

        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->db->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->db->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.rte,students.gender,' . $field_variable)->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', "yes");
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllClassSection($class_id = null, $section_id = null)
    {
        $where = array();
        if ($class_id != null) {
            $where['class_id'] = $class_id;
        }

        if ($section_id != null) {
            $where['section_id'] = $section_id;
        }

        return $this->db->select('*')->from('class_sections')->join('classes', 'class_sections.class_id=classes.id', 'inner')->join('sections', 'class_sections.section_id=sections.id', 'inner')->where($where)->get()->result_array();
    }

    public function student_profile($condition1, $condition2)
    {
        $this->db->select('student_session.transport_fees,student_session.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,students.hostel_room_id,student_session.id as `student_session_id`,student_session.fees_discount,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno,students.email ,students.state,students.city,students.pincode,students.note, students.religion, students.cast,school_houses.house_name,students.dob,students.current_address,students.previous_school,
            students.guardian_is,students.parent_id,            students.permanent_address,students.category_id,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code,students.guardian_name,students.father_pic ,students.height ,students.weight,students.measurement_date, students.mother_pic,students.guardian_pic , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.father_name,students.father_phone,students.blood_group,students.school_house_id,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_occupation,students.gender,students.guardian_is,students.rte,students.guardian_email, users.username,users.password,students.dis_reason,students.dis_note,category')->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = students.school_house_id', 'left');
        $this->db->join('users', 'users.user_id = students.id', 'left');
        $this->db->join('categories', 'categories.id = students.category_id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('users.role', 'student');
        $this->db->where('students.is_active', 'yes');
        if ($condition1 != '') {
            $this->db->where($condition1);
        }
        if ($condition2 != '') {
            $this->db->where($condition2);
        }

        $this->db->order_by('students.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function bulkdelete($students)
    {
        if (!empty($students)) {

            $this->db->trans_start();
            $student_comma_seprate = implode(', ', $students);
            //delete from students
            $this->db->where_in('id', $students);
            $this->db->delete('students');

            //delete from users
            $this->db->where_in('user_id', $students);
            $this->db->where_in('role', 'student');
            $this->db->delete('users');
            //delete from custom_field_value

            $sql = "DELETE FROM custom_field_values WHERE id IN (select * from (SELECT t2.id as `id` FROM `custom_fields` INNER JOIN custom_field_values as t2 on t2.custom_field_id=custom_fields.id WHERE custom_fields.belong_to='students' and t2.belong_table_id IN (" . implode(', ', $students) . ")) as m2)";

            $query = $this->db->query($sql);

            $sql_parent = "DELETE from users WHERE id in (SELECT id from (SELECT users.*,students.id as `student_id` FROM `users` LEFT JOIN students on users.id= students.parent_id WHERE role ='parent') as a WHERE a.student_id IS NULL)";
            $query      = $this->db->query($sql_parent);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function valid_student_admission_no()
    {
        $admission_no = $this->input->post('admission_no');
        $student_id   = $this->input->post('studentid');

        if ($admission_no != "") {

            if (!isset($student_id)) {
                $student_id = 0;
            }

            if ($this->check_admission_no_exists($admission_no, $student_id)) {
                $this->form_validation->set_message('check_admission_no_exists', 'Admission No Exists');
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_admission_no_exists($admission_no, $student_id)
    {
        if ($student_id != 0) {
            $data  = array('students.id != ' => $student_id, 'students.admission_no' => $admission_no);
            $query = $this->db->where($data)->join("student_session", "students.id = student_session.student_id")->get('students');
            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {

            $this->db->where(array('class_id' => $class, 'admission_no' => $admission_no));
            $query = $this->db->join("student_session", "students.id = student_session.student_id")->get('students');

            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function search_alumniStudentReport($class_id = null, $section_id = null, $session_id = null)
    {
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,  students.mobileno,students.email,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('alumni_students');
        $this->db->join('students', 'students.id = alumni_students.student_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.is_alumni', 1);
        $this->db->where('students.is_active', "yes");
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if ($session_id != null) {
            $this->db->where('student_session.session_id', $session_id);
        }
        $this->db->group_by('students.id');
        $this->db->order_by('students.admission_no', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function search_alumniStudentbyAdmissionNoReport($searchterm, $carray)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {

                $this->db->where_in("student_session.class_id", $carray);
                $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                foreach ($sections as $key => $value) {
                    $sections_id[] = $value['section_id'];
                }
                $this->db->where_in("student_session.section_id", $sections_id);
            } else {
                $this->db->where_in("student_session.class_id", "");
            }
        }
        $this->db->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,GROUP_CONCAT(classes.class,"(",sections.section,")") as class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno,students.email ,students.state,students.city,students.pincode,students.religion,students.dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id')->from('alumni_students');
        $this->db->join('students', 'students.id = alumni_students.student_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->where('student_session.is_alumni', '1');
        $this->db->group_start();
        $this->db->like('students.admission_no', $searchterm);
        $this->db->group_end();
        $this->db->group_by('students.id');
        $this->db->order_by('students.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getParentList()
    {
        $sql = "SELECT students.*,users.username,users.password,users.role,users.is_active FROM `students` 
        INNER JOIN users on users.id = students.parent_id 
        INNER JOIN student_session on student_session.student_id = students.id       
        WHERE parent_id != 0 and student_session.session_id = $this->current_session GROUP BY parent_id";
        $query   = $this->db->query($sql);
        $parents = $query->result();

        return $parents;
        
    }

    public function count_classteachers($class_id, $section_id)
    {
        $sql = "SELECT staff.id FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id`inner JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id   WHERE staff.is_active='1' and `subject_timetable`.`class_id` = " . $class_id . " AND `subject_timetable`.`section_id` = " . $section_id . "  AND `subject_timetable`.`session_id` = " . $this->current_session;

        $query   = $this->db->query($sql);
        $count   = $query->result();
        $teacher = array();
        if (!empty($count)) {
            foreach ($count as $key => $value) {
                $teacher[$value->id] = $value->id;
            }
        }

        return count($teacher);
        die;
    }

    //===========
    public function check_student_email_exists($str)
    {
        $email = $this->security->xss_clean($str);
        if ($email != "") {
            $id = $this->input->post('student_id');
            if (!isset($id)) {
                $id = 0;
            }

            if ($this->check_data_exists($email, $id)) {
                $this->form_validation->set_message('check_student_email_exists', $this->lang->line('record_already_exist'));
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_data_exists($email, $id)
    {
        $this->db->where('email', $email);
        $this->db->where('id !=', $id);
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function searchdtByClassSection($class_id = null, $section_id = null)
    {
        $userdata = $this->customlib->getUserData();
        
        
		if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $class_section_array = $this->customlib->get_myClassSection();
        }
        
        $i                    = 1;
        $custom_fields        = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array      = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }

        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name     = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }

        $this->datatables->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno,students.email ,students.state,students.city, students.pincode,students.religion,DATE(students.dob) as dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name,students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,students.created_at ,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender' . $field_variable);       
        $this->datatables->searchable('students.admission_no,students.firstname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_variable);
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
            
        if (!empty($class_section_array)) {
            $this->datatables->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->datatables->or_group_start();
                    $this->datatables->where('student_session.class_id', $class_sectionkey);
                    $this->datatables->where('student_session.section_id', $class_sectionvaluevalue);
                    $this->datatables->group_end();

                }
            }
            $this->datatables->group_end();
        }
            
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', "yes");
        $this->datatables->orderable('students.admission_no,students.firstname,classes.class,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_name);
        $this->datatables ->from('students');
        $this->datatables->sort('students.admission_no', 'asc');
        return $this->datatables->generate('json');
    }

    public function searchFullText($searchterm, $carray = null)
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata['id'];

        $i             = 1;
        $custom_fields = $this->customfield_model->get_custom_fields('students', 1);
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $class_section_array = $this->customlib->get_myClassSection();
        }
      

        $field_var_array      = array();
        $field_var_array_name = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                array_push($field_var_array_name, 'table_custom_' . $i . '.field_value');
                $i++;
            }
        }
        $field_variable = (empty($field_var_array)) ? "" : "," . implode(',', $field_var_array);
        $field_name     = (empty($field_var_array_name)) ? "" : "," . implode(',', $field_var_array_name);
      
        $this->datatables->select('classes.id AS `class_id`,students.id,student_session.id as student_session_id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,students.lastname,students.image,students.mobileno, students.email ,students.state,students.city,students.pincode,students.religion,DATE(students.dob) as dob ,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code ,students.father_name,students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.gender,students.rte,student_session.session_id' . $field_variable);
        $this->datatables->join('student_session', 'student_session.student_id = students.id');
        $this->datatables->join('classes', 'student_session.class_id = classes.id');
        $this->datatables->join('sections', 'sections.id = student_session.section_id');
        $this->datatables->join('categories', 'students.category_id = categories.id', 'left');
        $this->datatables->join('school_houses', 'students.school_house_id = school_houses.id', 'left');

        if (!empty($class_section_array)) {
            $this->datatables->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->datatables->or_group_start();
                    $this->datatables->where('student_session.class_id', $class_sectionkey);
                    $this->datatables->where('student_session.section_id', $class_sectionvaluevalue);
                    $this->datatables->group_end();
                }
            }
            $this->datatables->group_end();
        }

        $this->datatables->group_start();
        $this->datatables->or_like_string('students.firstname,students.middlename,students.lastname,school_houses.house_name,students.guardian_name,students.adhar_no,students.samagra_id,students.roll_no,students.admission_no,students.mobileno,students.email,students.religion,students.cast,students.gender,students.current_address,students.permanent_address,students.blood_group,students.bank_name,students.ifsc_code,students.father_name,students.father_phone,students.father_occupation,students.mother_name,students.mother_phone,students.mother_occupation,students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_occupation,students.guardian_address,students.guardian_email,students.previous_school,students.note', $searchterm);
        $this->datatables->group_end();
        $this->datatables->where('student_session.session_id', $this->current_session);
        $this->datatables->where('students.is_active', 'yes');       
        $this->datatables->searchable('students.admission_no,students.firstname,students.middlename,students.lastname,students.roll_no,classes.id,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_variable);            
        $this->datatables->orderable('students.admission_no,students.firstname,students.middlename,students.lastname,students.roll_no,classes.id,students.father_name,students.dob,students.gender,categories.category,students.mobileno' . $field_name);       
        $this->datatables->sort('students.id');
        $this->datatables->from('students');
        $std_data = $this->datatables->generate('json');

        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_section_array))) {
            $std_data       = json_decode($std_data);
            $std_data->data = array();
            return json_encode($std_data);
        } else {
            return $std_data;
        }
    }

    /* function to get record for login credential report */
    public function getdtforlogincredential($class_id = null, $section_id = null)
    {
        $i               = 1;
        $custom_fields   = $this->customfield_model->get_custom_fields('students', 1);
        $field_var_array = array();
        if (!empty($custom_fields)) {
            foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
                $tb_counter = "table_custom_" . $i;
                array_push($field_var_array, 'table_custom_' . $i . '.field_value as ' . $custom_fields_value->name);
                $this->datatables->join('custom_field_values as ' . $tb_counter, 'students.id = ' . $tb_counter . '.belong_table_id AND ' . $tb_counter . '.custom_field_id = ' . $custom_fields_value->id, 'left');
                $i++;
            }
        }

        $field_variable = implode(',', $field_var_array);

        $this->datatables
            ->select('classes.id AS `class_id`,student_session.id as student_session_id,students.id,classes.class,sections.id AS `section_id`,sections.section,students.id,students.admission_no, students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,students.mobileno,students.email,students.state,students.city, students.pincode,students.religion,students.dob,students.current_address,students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name,students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at,students.updated_at,students.father_name,students.app_key,students.parent_app_key,students.rte,students.gender,' . $field_variable)
            ->searchable('students.admission_no,students.firstname')
            ->orderable('students.admission_no,students.firstname," "," ", " "')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', "yes")
            ->from('students');

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        $this->datatables->sort('students.admission_no', 'asc');
        return $this->datatables->generate('json');

    }

    public function getUndefinedStudent()
    {
        $sql    = "SELECT students.id FROM `students` LEFT join student_session on student_session.student_id=students.id WHERE student_session.id IS NULL";
        $query  = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function biometric_attendance($admission_no = null)
    {
        $sql    = "SELECT staff.id,staff.employee_id,staff.name,staff.surname,staff.contact_no,staff.email,'staff' as `table_type` FROM `staff` WHERE employee_id=" . $this->db->escape($admission_no) . " UNION SELECT student_session.id as `student_session_id`,students.id, students.admission_no,students.firstname,students.middlename,students.lastname,'student' as `table_type` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `hostel_rooms` ON `hostel_rooms`.`id` = `students`.`hostel_room_id` LEFT JOIN `hostel` ON `hostel`.`id` = `hostel_rooms`.`hostel_id` LEFT JOIN `room_types` ON `room_types`.`id` = `hostel_rooms`.`room_type_id` LEFT JOIN `vehicle_routes` ON `vehicle_routes`.`id` = `student_session`.`vehroute_id` LEFT JOIN `route_pickup_point` ON `route_pickup_point`.`id` = `student_session`.`route_pickup_point_id` LEFT JOIN `pickup_point` ON `route_pickup_point`.`pickup_point_id` = `pickup_point`.`id` LEFT JOIN `transport_route` ON `vehicle_routes`.`route_id` = `transport_route`.`id` LEFT JOIN `vehicles` ON `vehicles`.`id` = `vehicle_routes`.`vehicle_id` LEFT JOIN `school_houses` ON `school_houses`.`id` = `students`.`school_house_id` LEFT JOIN `users` ON `users`.`user_id` = `students`.`id` WHERE `student_session`.`session_id` = '" . $this->current_session . "' AND `users`.`role` = 'student' AND `students`.`is_active` = 'yes' AND `students`.`admission_no` = " . $this->db->escape($admission_no);
        $query  = $this->db->query($sql);
        $result = $query->row();
        return $result;

    }
    //===========

    public function getonlineadmissionreport($class_id = null, $section_id = null, $status = null)
    {
        $this->datatables
            ->select('students.*,online_admissions.form_status,online_admissions.is_enroll,online_admissions.firstname, online_admissions.gender,online_admissions.dob,online_admissions.lastname,online_admissions.paid_status,online_admissions.reference_no,online_admissions.mobileno,classes.class,sections.section,(SELECT ifnull(SUM(online_admission_payment.paid_amount),0) as amount  from online_admission_payment WHERE online_admission_payment.online_admission_id= online_admissions.id) as paid_amount')
            ->searchable('online_admissions.admission_no,online_admissions.firstname')
            ->orderable('online_admissions.admission_no,online_admissions.firstname,online_admissions.mobileno," ",online_admissions.gender," "," "," "," "," " ," "')
            ->join('students', 'students.admission_no = online_admissions.admission_no', "left")
            ->join('student_session', 'student_session.student_id = students.id', "left")
            ->join('class_sections', 'class_sections.id = online_admissions.class_section_id', 'left')
            ->join('classes', 'class_sections.class_id = classes.id', 'left')
            ->join('sections', 'sections.id = class_sections.section_id', 'left')
            ->from('online_admissions');

        if ($class_id != null) {
            $this->datatables->where('student_session.class_id', $class_id);
        }

        if ($section_id != null) {
            $this->datatables->where('student_session.section_id', $section_id);
        }
        if ($status != null) {
            $this->datatables->where('online_admissions.is_enroll', $status);
        }

        $this->datatables->sort('online_admissions.admission_no', 'desc');
        return $this->datatables->generate('json');
    }

    public function getstudentdetailbyid($id)
    {
        $this->db->select('students.firstname,students.middlename,students.lastname,students.admission_no,students.guardian_name,email,mobileno,guardian_phone,guardian_email');
        $this->db->from('students');
        $this->db->where('students.id', $id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function check_guardian_email_exists($str)
    {
        $email = $this->security->xss_clean($str);
        if ($email != "") {
            $id = $this->input->post('student_id');
            if (!isset($id)) {
                $id = 0;
            }

            if ($this->check_guardian_data_exists($email, $id)) {
                $this->form_validation->set_message('check_guardian_email_exists', $this->lang->line('record_already_exist'));
                return false;
            } else {
                return true;
            }
        }
        return true;
    }

    public function check_guardian_data_exists($email, $id)
    {
        $this->db->where('guardian_email', $email);
        $this->db->where('id !=', $id);
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function check_student_mobile_no_exists($str)
    {
        $mobile = $this->security->xss_clean($str);
        if ($mobile != "") {
            $id = $this->input->post('student_id');
            if (!isset($id)) {
                $id = 0;
            }

            $isexist = $this->check_mobile_no_data_exists($mobile, $id);
            if ($isexist) {
                $this->form_validation->set_message('check_student_mobile_exists', $this->lang->line('record_already_exist'));
                return false;
            }
        }
        return true;
    }

    public function check_mobile_no_data_exists($mobile, $id)
    {
        $this->db->where('mobileno', $mobile);
        $this->db->where('id !=', $id);
        $query = $this->db->get('students');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function studentdocbyid($id)
    {
        $this->db->select()->from('student_doc');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getAdmissionNoByGuardianEmail($student_id,$guardian_email)
    {
        $this->db->select('students.firstname,students.middlename,students.lastname,students.admission_no,students.guardian_email');  
        $this->db->from('students');
        $this->db->where('students.id !=', $student_id);       
        $this->db->where('students.guardian_email', $guardian_email);       
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function getAdmissionNoByGuardianPhone($student_id,$guardian_phone)
    {
        $this->db->select('students.firstname,students.middlename,students.lastname,students.admission_no,students.guardian_phone');  
        $this->db->from('students');
        $this->db->where('students.id !=', $student_id);       
        $this->db->where('students.guardian_phone', $guardian_phone);       
        $query = $this->db->get();
        return $query->row_array();
    }
}
