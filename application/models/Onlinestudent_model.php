<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlinestudent_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        $this->db->insert('online_admissions', $data);
        return $this->db->insert_id();
    }

    public function edit($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('online_admissions', $data);
    }

    public function get($id = null, $carray = null)
    {
        $this->db->select('online_admissions.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type,online_admissions.hostel_room_id,class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,online_admissions.id,online_admissions.admission_no, online_admissions.roll_no,online_admissions.admission_date,online_admissions.firstname,online_admissions.middlename, online_admissions.lastname,online_admissions.image,online_admissions.mobileno,online_admissions.email,online_admissions.state,   online_admissions.city,online_admissions.pincode,online_admissions.note,online_admissions.religion,online_admissions.cast, school_houses.house_name,online_admissions.dob,online_admissions.current_address,online_admissions.previous_school,
            online_admissions.guardian_is,
            online_admissions.permanent_address,IFNULL(online_admissions.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,online_admissions.adhar_no,online_admissions.samagra_id,online_admissions.bank_account_no,online_admissions.bank_name, online_admissions.ifsc_code,online_admissions.guardian_name,online_admissions.father_pic,online_admissions.height ,online_admissions.weight,online_admissions.measurement_date,online_admissions.mother_pic,online_admissions.guardian_pic, online_admissions.guardian_relation,online_admissions.guardian_phone,online_admissions.guardian_address,online_admissions.is_enroll ,online_admissions.created_at,online_admissions.document ,online_admissions.updated_at,online_admissions.father_name,online_admissions.father_phone,online_admissions.blood_group,online_admissions.school_house_id,online_admissions.father_occupation,online_admissions.mother_name,online_admissions.mother_phone,online_admissions.mother_occupation,online_admissions.guardian_occupation,online_admissions.gender,online_admissions.guardian_is,online_admissions.rte,online_admissions.guardian_email,online_admissions.paid_status,online_admissions.form_status,online_admissions.reference_no,online_admissions.class_section_id')->from('online_admissions');

        $this->db->join('class_sections', 'class_sections.id = online_admissions.class_section_id', 'left');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = online_admissions.hostel_room_id', 'left');
        $this->db->join('hostel', 'hostel.id = hostel_rooms.hostel_id', 'left');
        $this->db->join('room_types', 'room_types.id = hostel_rooms.room_type_id', 'left');
        $this->db->join('categories', 'online_admissions.category_id = categories.id', 'left');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = online_admissions.vehroute_id', 'left');
        $this->db->join('transport_route', 'vehicle_routes.route_id = transport_route.id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
        $this->db->join('school_houses', 'school_houses.id = online_admissions.school_house_id', 'left');

        if ($carray != null) {
            $this->db->where_in('classes.id', $carray);
        }

        if ($id != null) {
            $this->db->where('online_admissions.id', $id);
        } else {

            $this->db->order_by('online_admissions.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getstudentlist($carray = null, $id = null)
    {
        $class_section_array=$this->customlib->get_myClassSection();        

        if ($id != null) {
            $this->datatables->where('online_admissions.id', $id);
        } else {
            $this->datatables->orderable('online_admissions.id', 'desc');
        }        
        
        $this->datatables
            ->select('online_admissions.vehroute_id,vehicle_routes.route_id,vehicle_routes.vehicle_id,transport_route.route_title,vehicles.vehicle_no,hostel_rooms.room_no,vehicles.driver_name,vehicles.driver_contact,hostel.id as `hostel_id`,hostel.hostel_name,room_types.id as `room_type_id`,room_types.room_type ,online_admissions.hostel_room_id,class_sections.id as class_section_id,classes.id AS `class_id`,classes.class,sections.id AS `section_id`,sections.section,online_admissions.id,online_admissions.admission_no, online_admissions.roll_no,online_admissions.admission_date,online_admissions.firstname, online_admissions.lastname,online_admissions.image,    online_admissions.mobileno,online_admissions.email,online_admissions.state,online_admissions.city , online_admissions.pincode , online_admissions.note, online_admissions.religion,online_admissions.cast, school_houses.house_name,online_admissions.dob ,online_admissions.current_address, online_admissions.previous_school,
            online_admissions.guardian_is,
            online_admissions.permanent_address,IFNULL(online_admissions.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`,online_admissions.adhar_no,online_admissions.samagra_id,online_admissions.bank_account_no,online_admissions.bank_name, online_admissions.ifsc_code , online_admissions.guardian_name,online_admissions.father_pic,online_admissions.height ,online_admissions.weight,online_admissions.measurement_date, online_admissions.mother_pic,online_admissions.guardian_pic, online_admissions.guardian_relation,online_admissions.guardian_phone,online_admissions.guardian_address,online_admissions.is_enroll ,online_admissions.created_at,online_admissions.document ,online_admissions.updated_at,online_admissions.father_name,online_admissions.father_phone,online_admissions.blood_group,online_admissions.school_house_id,online_admissions.father_occupation,online_admissions.mother_name,online_admissions.mother_phone,online_admissions.mother_occupation,online_admissions.guardian_occupation,online_admissions.gender,online_admissions.guardian_is,online_admissions.rte,online_admissions.guardian_email,online_admissions.reference_no,online_admissions.paid_status,online_admissions.form_status,online_admissions.submit_date,online_admissions.middlename')
            ->orderable('online_admissions.reference_no,online_admissions.firstname,classes.class,online_admissions.father_name,online_admissions.dob,online_admissions.gender,categories.category,online_admissions.mobileno," "," "," " ')
            ->searchable('online_admissions.reference_no,online_admissions.firstname,classes.class,online_admissions.father_name,online_admissions.dob,online_admissions.gender,categories.category,online_admissions.mobileno')
           
            ->join('class_sections','class_sections.id = online_admissions.class_section_id', 'left')
            ->join('classes','class_sections.class_id = classes.id', 'left')
            ->join('sections','sections.id = class_sections.section_id', 'left')
            ->join('hostel_rooms','hostel_rooms.id = online_admissions.hostel_room_id', 'left')
            ->join('hostel','hostel.id = hostel_rooms.hostel_id', 'left')
            ->join('room_types','room_types.id = hostel_rooms.room_type_id', 'left')
            ->join('categories','online_admissions.category_id = categories.id', 'left')
            ->join('vehicle_routes','vehicle_routes.id = online_admissions.vehroute_id', 'left')
            ->join('transport_route','vehicle_routes.route_id = transport_route.id', 'left')
            ->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left')
            ->join('school_houses', 'school_houses.id = online_admissions.school_house_id', 'left');            
            if(!empty($class_section_array)){
                foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                    $query_string="";
                        foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                            $query_string="( class_sections.class_id=".$class_sectionkey." and class_sections.section_id=".$class_sectionvaluevalue." )";
                            $this->datatables->or_where($query_string);
                        }    
                }
            }

            $this->datatables->from('online_admissions');
            $this->datatables->sort('online_admissions.id','desc');

        return $this->datatables->generate('json');

    }

    public function checkpaymentstatus($id)
    {
        $this->db->select('paid_status,form_status');
        $this->db->from('online_admissions');
        $this->db->where('id', $id);
        $query  = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function update($data, $fee_session_group_id,$transport_feemaster_id,$action = "save")
    {
        $record_update_status = true;
        $student_id           = "";
        $user_password        = "";
        $parent_password      = "";
        if (isset($data['id'])) {
            $this->db->trans_begin();
            $data_id          = $data['id'];
            $class_section_id = $data['class_section_id'];

            if ($action == "enroll") {
                //==========================
                $insert             = true;
                $sch_setting_detail = $this->setting_model->getSetting();

                if ($sch_setting_detail->adm_auto_insert) {
                    if ($sch_setting_detail->adm_update_status) {

                        $admission_no = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;

                        $last_student = $this->student_model->lastRecord();
                        if (empty($last_student)) {
                            $admission_no         = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;
                            $data['admission_no'] = $admission_no;
                        } else {
                            $last_admission_digit = str_replace($sch_setting_detail->adm_prefix, "", $last_student->admission_no);

                            $admission_no = $sch_setting_detail->adm_prefix . sprintf("%0" . $sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);

                            $data['admission_no'] = $admission_no;
                        }

                    } else {
                        $admission_no         = $sch_setting_detail->adm_prefix . $sch_setting_detail->adm_start_from;
                        $data['admission_no'] = $admission_no;
                    }
                }

                $admission_no_exists = $this->student_model->check_adm_exists($data['admission_no']);
                if ($admission_no_exists) {
                    $insert               = false;
                    $record_update_status = false;
                }

                //============================
                if ($insert) {
                    $this->db->select('class_sections.*')->from('class_sections');
                    $this->db->where('class_sections.id', $data['class_section_id']);
                    $query                 = $this->db->get();
                    $classs_section_result = $query->row();
                    $route_pickup_point_id=$data['route_pickup_point_id'];
                    $vehroute_id=$data['vehroute_id'];
                    unset($data['route_pickup_point_id']);
                    unset($data['vehroute_id']);
                    unset($data['class_section_id']);
                    unset($data['id']);
                    $this->db->insert('students', $data);
                    $student_id = $this->db->insert_id();
                    $data_new   = array(
                        'student_id' => $student_id,
                        'class_id'   => $classs_section_result->class_id,
                        'section_id' => $classs_section_result->section_id,
                        'session_id' => $this->current_session,
                        'route_pickup_point_id' => $route_pickup_point_id,
                        'vehroute_id'           => $vehroute_id,
                    );
                    $this->db->insert('student_session', $data_new);
                    $student_session_id = $this->db->insert_id();


                if ($fee_session_group_id) {
                $this->studentfeemaster_model->assign_bulk_fees($fee_session_group_id, $student_session_id, array());
                }

                if (!empty($transport_feemaster_id)) {
                    $trns_data_insert = array();
                    foreach ($transport_feemaster_id as $transport_feemaster_key => $transport_feemaster_value) {
                        $trns_data_insert[] = array(
                            'student_session_id'     => $student_session_id,
                            'route_pickup_point_id'  => $route_pickup_point_id,
                            'transport_feemaster_id' => $transport_feemaster_value
                        );
                    }

                    $student_session_is = $this->studenttransportfee_model->add($trns_data_insert, $student_session_id, array(), $route_pickup_point_id);
                }

                    //===============Start Student ID===========
                    $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                    $data_student_login = array(
                        'username' => $this->student_login_prefix . $student_id,
                        'password' => $user_password,
                        'user_id'  => $student_id,
                        'role'     => 'student',
                    );

                    $this->user_model->add($data_student_login);
                    //===============End Student ID===========
                    //===============Start Parent ID===========

                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                    $temp              = $student_id;
                    $data_parent_login = array(
                        'username' => $this->parent_login_prefix . $student_id,
                        'password' => $parent_password,
                        'user_id'  => 0,
                        'role'     => 'parent',
                        'childs'   => $temp,
                    );
                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                    $update_student = array(
                        'id'        => $student_id,
                        'parent_id' => $ins_parent_id,
                    );
                    $this->student_model->add($update_student);

                    //===============End Parent ID===========
                    //============== Update setting modal===============================

                    if ($sch_setting_detail->adm_auto_insert) {
                        if ($sch_setting_detail->adm_update_status == 0) {
                            $data_setting                      = array();
                            $data_setting['id']                = $sch_setting_detail->id;
                            $data_setting['adm_update_status'] = 1;
                            $this->setting_model->add($data_setting);
                        }
                    }

                    //=============================================

                    $data['is_enroll']        = 1;
                    $data['class_section_id'] = $class_section_id;
                }
            }
            
            unset($data['route_pickup_point_id']);
            unset($data['vehroute_id']);
            $this->db->where('id', $data_id);
            $this->db->update('online_admissions', $data);

            $message   = UPDATE_RECORD_CONSTANT . " On  online admissions id " . $data_id;
            $action    = "Update";
            $record_id = $data_id;
            $this->log($message, $record_id, $action);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
        return json_encode(array('record_update_status' => $record_update_status, 'admission_no' => $data['admission_no'] , 'student_id' => $student_id, 'user_password' => $user_password, 'parent_password' => $parent_password));
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('belong_table_id', $id);
        $this->db->delete('online_admission_custom_field_value');
        
        $this->db->where('id', $id);
        $this->db->delete('online_admissions');
        $message   = DELETE_RECORD_CONSTANT . " On online admissions id " . $id;
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

    public function checkadmissionstatus($reference_no, $dob)
    {
        $arr = array('reference_no' => $reference_no, 'dob' => $dob);

        $this->db->select('id')->from('online_admissions')->where($arr);
        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return $result['id'];
        } else {
            return 0;
        }

    }

    public function getformfields()
    {
        $this->db->select('*');
        $this->db->from('online_admission_fields');
        $query = $this->db->get();
        return $query->result();
    }

    public function getfieldstatus($fieldname)
    {
        $this->db->where('name', $fieldname);
        $this->db->select('status');
        $this->db->from('online_admission_fields');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['status'];
        }
    }

    public function checkfieldexist($fieldname)
    {
        $this->db->where('name', $fieldname);
        $this->db->select("*");
        $this->db->from('online_admission_fields');
        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function addformfields($record)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('name', $record['name']);
        $q = $this->db->get('online_admission_fields');

        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('online_admission_fields', $record);
            $message   = UPDATE_RECORD_CONSTANT . " On  online_admission_fields id " . $results->id;
            $action    = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('online_admission_fields', $record);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On online_admission_fields id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getAdmissionData($admission_no)
    {
        $result = $this->db->select("*")
            ->where("id", $admission_no)
            ->get("online_admissions")
            ->row();
        return $result;
    }

    public function paymentSuccess($payment)
    {   
        $paid_status=1;
        if(isset($payment['paid_status']) && !empty($payment['paid_status'])){
            $paid_status=$payment['paid_status'];
        }
        unset($payment['paid_status']);        
        $this->db->update("online_admissions", array("paid_status" => $paid_status, "form_status" => 1), array("id" => $payment['online_admission_id']));        
        $this->db->insert("online_admission_payment", $payment);
    }

    public function getclassbyclasssectionid($class_section_id)
    {
        $this->db->select("class_id,class");
        $this->db->from('class_sections');
        $this->db->join('classes', "classes.id=class_sections.class_id", "inner");
        $this->db->where("class_sections.id", $class_section_id);
        $query  = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function checkreferenceno($reference_no)
    {
        $this->db->select("*");
        $this->db->from('online_admissions');
        $this->db->where("reference_no", $reference_no);
        $query  = $this->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getcustomfields()
    {
        $this->db->select("name");
        $this->db->from('custom_fields');
        $this->db->where("belong_to", 'students');
        $query  = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    /**
     * this function is used to validate movie sell price
     */
    public function validate_paymentamount()
    {
        $str = $this->input->post('online_admission_amount');
        if ($str > 0) {
            return true;
        } elseif ($str == 0) {
            $this->form_validation->set_message('check_exists', $this->lang->line('invalid_payment_amount'));
            // return false;
        } elseif ($str = "") {
            $this->form_validation->set_message('check_exists', $this->lang->line('required'));
            // return false;
        }
    }

    //===========
    public function check_student_email_exists($str)
    {
        $email = $this->security->xss_clean($str);
        if ($email != "") {
            $id = $this->input->post('admission_id');
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

    public function check_data_exists($email, $id = null)
    {
        $this->db->where('email', $email);
        if ($id != null) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get('online_admissions');
        $student_query = $this->db->query("select email from students where email='" . $email . "' ");
        if ($query->num_rows() > 0 || $student_query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function editguardianfield($status)
    {
        $data = array('guardian_relation', 'guardian_name', 'guardian_phone', 'guardian_photo', 'guardian_occupation', 'guardian_email', 'guardian_address');
        foreach ($data as $value) {
            $this->db->query("update online_admission_fields set status=" . $status . " where name='" . $value . "'   ");
        }
    }

    public function getidbyrefno($reference_no)
    {
        $query  = $this->db->query("select id from online_admissions where reference_no=" . $reference_no . "   ");
        $result = $query->row_array();
        return $result['id'];
    }

    public function gethousename($id)
    {
        $query  = $this->db->query("select house_name from school_houses where id=" . $id . "   ");
        $result = $query->row_array();
        return $result['house_name'];
    }

    public function gettransactionid($id)
    {
        $query = $this->db->query("select transaction_id from online_admission_payment where online_admission_id=" . $id . "   ");
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['transaction_id'];
        } else {
            return 0;
        }
    }
    
    public function gettransactionpaidamount($id)
    {
        $query = $this->db->query("select paid_amount from online_admission_payment where online_admission_id=" . $id . "   ");
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['paid_amount'];
        } else {
            return 0;
        }
    }

    public function getOnlineAdmissionFeeCollectionReport($start_date, $end_date)
    {
        $query = "SELECT online_admissions.*,online_admission_payment.*,classes.class,sections.section FROM online_admissions
        join online_admission_payment on online_admissions.id = online_admission_payment.online_admission_id
        left join class_sections on class_sections.id = online_admissions.class_section_id
        left join classes on class_sections.class_id = classes.id
        left join sections on sections.id = class_sections.section_id
        left join hostel_rooms on hostel_rooms.id = online_admissions.hostel_room_id
        left join hostel on hostel.id = hostel_rooms.hostel_id
        left join room_types on room_types.id = hostel_rooms.room_type_id
        left join categories on online_admissions.category_id = categories.id
        left join vehicle_routes on vehicle_routes.id = online_admissions.vehroute_id
        left join transport_route on vehicle_routes.route_id = transport_route.id
        left join vehicles on vehicles.id = vehicle_routes.vehicle_id
        left join school_houses on school_houses.id = online_admissions.school_house_id
        where DATE_FORMAT(online_admission_payment.date, '%Y-%m-%d') >= '$start_date'
        and DATE_FORMAT(online_admission_payment.date, '%Y-%m-%d') <= '$end_date'
        ";
        $query = $this->db->query($query);
        return $query->result();
    }

    public function checkisenroll($reference_no)
    {
        $this->db->select("is_enroll");
        $this->db->from('online_admissions');
        $this->db->where("reference_no", $reference_no);
        $query  = $this->db->get();
        $result = $query->row_array();
        if ($result['is_enroll'] == 1) {
            return 1;
        } else {
            return 0;
        }
    }
}
