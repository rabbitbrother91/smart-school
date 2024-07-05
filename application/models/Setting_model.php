<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getMysqlVersion() {
        $mysqlVersion = $this->db->query('SELECT VERSION() as version')->row();
        return $mysqlVersion;
    }

    public function getSqlMode() {

        $sqlMode = $this->db->query('SELECT @@sql_mode as mode')->row();
        return $sqlMode;
    }
 
    public function get($id = null) {

        $this->db->select('sch_settings.lastname,sch_settings.middlename,sch_settings.id,sch_settings.lang_id,sch_settings.languages,sch_settings.class_teacher,sch_settings.cron_secret_key, sch_settings.timezone,sch_settings.superadmin_restriction,sch_settings.student_timeline,sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.time_format,sch_settings.phone,languages.language,sch_settings.attendence_type,sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.currency_place,sch_settings.start_month,sch_settings.start_week,sch_settings.session_id,sch_settings.fee_due_days,sch_settings.image,sch_settings.theme,sessions.session,sch_settings.online_admission,sch_settings.is_duplicate_fees_invoice,sch_settings.is_student_house,sch_settings.is_blood_group,sch_settings.admin_logo,sch_settings.admin_small_logo,sch_settings.mobile_api_url,sch_settings.app_primary_color_code,sch_settings.app_secondary_color_code,sch_settings.app_logo,sch_settings.student_profile_edit,sch_settings.staff_barcode,sch_settings.student_barcode,languages.is_rtl,sch_settings.student_panel_login,sch_settings.parent_panel_login,sch_settings.currency_format,sch_settings.exam_result,sch_settings.base_url,sch_settings.folder_path,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,sch_settings.online_admission_application_form,currencies.id as currency_id,sch_settings.admin_login_page_background,sch_settings.user_login_page_background,sch_settings.low_attendance_limit');
     
        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
        $this->db->join('currencies', 'currencies.id = sch_settings.currency');
        if ($id != null) {
            $this->db->where('sch_settings.id', $id);
        } else {
            $this->db->order_by('sch_settings.id');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            $session_array = $this->session->has_userdata('session_array');
            $result = $query->result_array();

            $result[0]['current_session'] = array(
                'session_id' => $result[0]['session_id'],
                'session' => $result[0]['session']
            );

            if ($session_array) {
                $session_array = $this->session->userdata('session_array');
                $result[0]['session_id'] = $session_array['session_id'];
                $result[0]['session'] = $session_array['session'];
            }

            return $result;
        }
    }

    public function get_studentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('user_id', $id)->get()->row_array();
        return $data;
    } 
    
    public function get_guestlang($id) {
        $data = $this->db->select('guest.lang_id')->from('guest')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_parentlang($id) {
        $data = $this->db->select('users.lang_id')->from('users')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function get_stafflang($id) {
        $data = $this->db->select('staff.lang_id')->from('staff')->where('id', $id)->get()->row_array();
        return $data;
    }

    public function getSchoolDetail($id = null) {

        $this->db->select('sch_settings.id,sch_settings.base_url,sch_settings.folder_path,sch_settings.lang_id,sch_settings.is_rtl,sch_settings.timezone,
          sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.phone,languages.language,sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.start_month,sch_settings.start_week,sch_settings.session_id,sch_settings.image,sch_settings.theme,sessions.session,sch_settings.online_admission,sch_settings.maintenance_mode,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency');
        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
        $this->db->join('currencies', 'currencies.id = sch_settings.currency');
        $this->db->order_by('sch_settings.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSetting() {  

        $this->db->select('sch_settings.id,sch_settings.base_url,sch_settings.folder_path,sch_settings.attendence_type,sch_settings.lang_id,sch_settings.is_rtl,sch_settings.fee_due_days,sch_settings.staff_notification_email,sch_settings.class_teacher,sch_settings.cron_secret_key,sch_settings.timezone,sch_settings.online_admission_application_form,          sch_settings.name,sch_settings.email,sch_settings.biometric,sch_settings.biometric_device,sch_settings.phone,sch_settings.adm_prefix,sch_settings.adm_start_from,languages.language,sch_settings.adm_no_digit,sch_settings.adm_update_status,sch_settings.adm_auto_insert,sch_settings.staffid_prefix,sch_settings.staffid_start_from,sch_settings.staffid_auto_insert,sch_settings.staffid_no_digit,sch_settings.staffid_update_status,sch_settings.superadmin_restriction,sch_settings.student_timeline,sch_settings.address,sch_settings.dise_code,sch_settings.date_format,sch_settings.currency,sch_settings.currency_place,sch_settings.start_month,sch_settings.start_week,sch_settings.session_id,sch_settings.image,sch_settings.theme,sessions.session,online_admission,sch_settings.is_duplicate_fees_invoice,sch_settings.is_student_house,sch_settings.is_blood_group,sch_settings.roll_no,sch_settings.lastname,sch_settings.middlename,sch_settings.category,sch_settings.cast,sch_settings.religion,sch_settings.mobile_no,sch_settings.student_email,sch_settings.admission_date,sch_settings.student_photo,sch_settings.student_height,sch_settings.student_weight,sch_settings.measurement_date,sch_settings.father_name,sch_settings.father_phone,sch_settings.father_occupation,sch_settings.father_pic,sch_settings.mother_name,sch_settings.mother_phone,sch_settings.mother_occupation,sch_settings.mother_pic,sch_settings.guardian_phone,sch_settings.guardian_name,sch_settings.guardian_relation,sch_settings.guardian_email,sch_settings.guardian_pic,sch_settings.guardian_occupation,sch_settings.guardian_address,sch_settings.current_address,sch_settings.permanent_address,sch_settings.route_list,sch_settings.hostel_id,sch_settings.bank_account_no,sch_settings.bank_name,sch_settings.ifsc_code,sch_settings.national_identification_no,sch_settings.local_identification_no,sch_settings.rte,sch_settings.previous_school_details,sch_settings.student_note,sch_settings.upload_documents,sch_settings.staff_designation,sch_settings.staff_department,sch_settings.staff_last_name,sch_settings.staff_father_name,sch_settings.staff_mother_name,sch_settings.staff_date_of_joining,sch_settings.staff_phone,sch_settings.staff_emergency_contact,sch_settings.staff_marital_status,sch_settings.staff_photo,sch_settings.staff_current_address,sch_settings.staff_permanent_address,sch_settings.staff_qualification,sch_settings.staff_work_experience,sch_settings.staff_note,sch_settings.staff_epf_no,sch_settings.staff_basic_salary,sch_settings.staff_contract_type,sch_settings.staff_work_shift,sch_settings.staff_work_location,sch_settings.staff_leaves,sch_settings.staff_account_details,sch_settings.staff_social_media,sch_settings.staff_upload_documents,sch_settings.admin_logo,sch_settings.admin_small_logo,sch_settings.mobile_api_url,sch_settings.app_primary_color_code,sch_settings.app_secondary_color_code,sch_settings.app_logo,languages.short_code as `language_code`,sch_settings.student_profile_edit,sch_settings.my_question,sch_settings.online_admission_payment,online_admission_amount,sch_settings.online_admission_instruction,sch_settings.online_admission_conditions,sch_settings.languages as activelanguage,sch_settings.single_page_print as single_page_print, sch_settings.student_barcode, sch_settings.staff_barcode,sch_settings.calendar_event_reminder,sch_settings.student_login,sch_settings.parent_login,sch_settings.student_panel_login,sch_settings.parent_panel_login,sch_settings.currency_format,sch_settings.exam_result,sch_settings.admin_mobile_api_url,sch_settings.admin_app_primary_color_code,sch_settings.admin_app_secondary_color_code,is_student_feature_lock,is_offline_fee_payment,lock_grace_period,sch_settings.collect_back_date_fees,sch_settings.event_reminder,sch_settings.event_reminder,sch_settings.maintenance_mode,currencies.symbol as currency_symbol,currencies.base_price,currencies.short_name as currency,currencies.id as currency_id,sch_settings.offline_bank_payment_instruction,sch_settings.admin_login_page_background,sch_settings.user_login_page_background,sch_settings.low_attendance_limit,sch_settings.scan_code_type');

        $this->db->from('sch_settings');
        $this->db->join('sessions', 'sessions.id = sch_settings.session_id');
        $this->db->join('languages', 'languages.id = sch_settings.lang_id');
		$this->db->join('currencies', 'currencies.id = sch_settings.currency');
        $this->db->order_by('sch_settings.id');
        $query = $this->db->get();    
        
        $result = $query->row();
        
        $json_languages = json_decode($result->activelanguage);        
        foreach ($json_languages as $key => $value) {       
       
            $langresult  =        $this->language_model->get($value);							
            $language[$key] =  $langresult;
           					
		}
        
        $result->activelanguage2    =   $language;        
        return 	$result;
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('sch_settings');
        $message = DELETE_RECORD_CONSTANT . " On settings id " . $id;
        $action = "Delete";
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

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('sch_settings', $data);
            $message = UPDATE_RECORD_CONSTANT . " On settings id " . $data['id'];
            $action = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('sch_settings', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On settings id " . $insert_id;
            $action = "Insert";
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
 
    public function getCurrentSession() {
        $session_result = $this->get();

        return $session_result[0]['session_id'];
    }

    public function getOnlineAdmissionStatus() {
        $setting_result = $this->get();

        if ($setting_result[0]['online_admission']) {
            return true;
        }
        return false;
    }

    public function getexamresultstatus() {
        $setting_result = $this->get();

        if ($setting_result[0]['exam_result']) {
            return true;
        }
        return false;
    }

    public function getCurrentSessionName() {
        $session_result = $this->get();
        return $session_result[0]['session'];
    }

    public function getCurrentSchoolName() {
        $session_result = $this->get();
        return $session_result[0]['name'];
    }

    public function getStartMonth() {
        $session_result = $this->get();
        return $session_result[0]['start_month'];
    }

    public function getCurrentSessiondata() {
        $session_result = $this->get();
        return $session_result[0];
    }

    public function getCurrency() {
        $session_result = $this->get();
        return $session_result[0]['currency'];
    }

    public function getCurrencySymbol() {
        $session_result = $this->get();
        return $session_result[0]['currency_symbol'];
    }

    public function getDateYmd() {
        return date('Y-m-d');
    }

    public function getDateDmy() {
        return date('d-m-Y');
    }

    public function add_cronsecretkey($data, $id) {

        $this->db->where("id", $id)->update("sch_settings", $data);
    }

    public function getLanguage() {

        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['admin']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }

    
    public function getuserLanguage() {

        $query = $this->db->select('languages.language,languages.short_code')->where('id', $this->session->userdata['student']['language']['lang_id'])->get('languages');
        return $query->row_array();
    }

    public function getAdminlogo() {
        $query = $this->db->select('admin_logo')->get('sch_settings');
        $logo = $query->row_array();
        return $logo['admin_logo'];
    }

    public function getAdminsmalllogo() {
        $query = $this->db->select('admin_small_logo')->get('sch_settings');
        $logo = $query->row_array();
        return  $logo['admin_small_logo'];
    }

    public function get_appname() {

        $query = $this->db->select('name')->get('sch_settings');
        $name = $query->row_array();
        echo $name['name'];
    }

    public function check_haederimage($type) {
        $check = $this->db->select('*')->from('print_headerfooter')->where('print_type', $type)->get()->row_array();


        if (empty($check['header_image'])) {
            return 0;
        } else {
            return 1;
        }
    }

    public function add_printheader($data) {

        $this->db->where('print_type', $data['print_type']);
        $this->db->update('print_headerfooter', $data);
       
    }

    public function get_printheader() {
        return $this->db->select('*')->from('print_headerfooter')->get()->result_array();
    }

    public function get_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_receiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_receiptfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'student_receipt')->get()->row_array();
        return $image['footer_content'];
    }

    public function get_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function unlink_payslipheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['header_image'];
    }

    public function get_payslipfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'staff_payslip')->get()->row_array();
        return $image['footer_content'];
    }

    public function unlink_onlinereceiptheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        return $image['header_image'];
    }

    public function get_onlineadmissionheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['header_image'];
    }

    public function get_onlineadmissionfooter() {
        $image = $this->db->select('footer_content')->from('print_headerfooter')->where('print_type', 'online_admission_receipt')->get()->row_array();
        echo $image['footer_content'];
    }
    
    public function get_onlineexamheader() {
        $image = $this->db->select('header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();
        echo $image['header_image'];
    }
    
    public function get_onlineexamfooter() {
        $query = $this->db->select('footer_content,header_image')->from('print_headerfooter')->where('print_type', 'online_exam')->get()->row_array();        
        return $query ;
    }

}
