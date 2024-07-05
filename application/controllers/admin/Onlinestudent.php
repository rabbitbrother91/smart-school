<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlinestudent extends Admin_Controller
{

    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model(array("timeline_model", "classteacher_model", 'transportfee_model'));
        $this->blood_group        = $this->config->item('bloodgroup');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
		$this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'onlinestudent');
        $data['title']       = 'Student List';
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/onlinestudent/studentList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/student_documents/online_admission_doc/" . $doc;
        $data     = file_get_contents($filepath);
        $name     = $this->uri->segment(6);
        force_download($name, $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_delete')) {
            access_denied();
        }
        $this->onlinestudent_model->remove($id);

        redirect('admin/onlinestudent');
    }

    public function onlineadmission_download($id)
    {        
		$doc   = $this->onlinestudent_model->get($id);		
		$this->media_storage->filedownload($doc['document'], "uploads/student_documents/online_admission_doc");
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_edit')) {
            access_denied();
        }
        $data['adm_auto_insert']       = $this->sch_setting_detail->adm_auto_insert;
        $data['title']                 = $this->lang->line('edit_student');
        $session                       = $this->setting_model->getCurrentSession();
        $data['transport_fees']        = $this->transportfee_model->getSessionFees($session);
        $data['feesessiongroup_model'] = $this->feesessiongroup_model->getFeesByGroup();
        $data['id']                    = $id;
        $student                       = $this->onlinestudent_model->get($id);
        $genderList                    = $this->customlib->getGender();
        $data['student']               = $student;
        $data['genderList']            = $genderList;
        $session                       = $this->setting_model->getCurrentSession();
        $vehroute_result               = $this->vehroute_model->getRouteVehiclesList();
        $data['vehroutelist']          = $vehroute_result;
        $class                         = $this->class_model->get();
        $setting_result                = $this->setting_model->get();
        $data["bloodgroup"]            = $this->blood_group;
        $data["student_categorize"]    = 'class';
        $data['classlist']             = $class;
        $category                      = $this->category_model->get();
        $data['categorylist']          = $category;
        $hostelList                    = $this->hostel_model->get();
        $data['hostelList']            = $hostelList;
        $houses                        = $this->houselist_model->get();
        $data['houses']                = $houses;
        $data['sch_setting']           = $this->sch_setting_detail;

        if ($this->input->post('save') == 'enroll') {
            if (!$this->sch_setting_detail->adm_auto_insert) {

                $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), array('required', array('check_admission_no_exists', array($this->student_model, 'valid_student_admission_no'))));
            }

            $this->form_validation->set_rules(
                'email', $this->lang->line('email'), array(
                    'valid_email',
                    array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
                )
            );
            
             
            $transport_feemaster_id = $this->input->post('transport_feemaster_id');
            if($transport_feemaster_id){
                $this->form_validation->set_rules('vehroute_id', $this->lang->line('route_list'), 'trim|required|xss_clean');
                $this->form_validation->set_rules('route_pickup_point_id', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
                $this->form_validation->set_rules('transport_feemaster_id[]', $this->lang->line('fees_month'), 'trim|required|xss_clean');
            }
            
        }

        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[file]');
        $this->form_validation->set_rules('father_pic', $this->lang->line('image'), 'callback_handle_upload[father_pic]');
        $this->form_validation->set_rules('mother_pic', $this->lang->line('image'), 'callback_handle_upload[mother_pic]');
        $this->form_validation->set_rules('guardian_pic', $this->lang->line('image'), 'callback_handle_upload[guardian_pic]');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
        }
        if ($this->sch_setting_detail->rte) {
            $this->form_validation->set_rules('rte', $this->lang->line('rtl'), 'trim|required|xss_clean');
        } 
       

        $custom_fields = $this->customfield_model->getByBelong('students');
        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation'] && $this->customlib->getfieldstatus($custom_fields_value['name'])) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }
       

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlinestudent/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $fee_session_group_id   = $this->input->post('fee_session_group_id');
            $transport_feemaster_id = $this->input->post('transport_feemaster_id');

            $student_id     = $this->input->post('student_id');
            $class_id       = $this->input->post('class_id');
            $section_id     = $this->input->post('section_id');
            $hostel_room_id = empty2null($this->input->post('hostel_room_id'));
            $fees_discount  = $this->input->post('fees_discount');

            // if (empty($hostel_room_id)) {
                // $hostel_room_id = 0;
            // }  

            $route_pickup_point_id = empty2null($this->input->post('route_pickup_point_id'));
            $vehroute_id           = empty2null($this->input->post('vehroute_id'));
            $category_id           = empty2null($this->input->post('category_id'));
            

            // if (empty($category_id)) {
                // $category_id = null;
            // }
            
            // if (empty($vehroute_id)) {
                // $vehroute_id = null;
            // }

            // if (empty($route_pickup_point_id)) {
                // $route_pickup_point_id = null;
            // }

            $data = array(
                'id'                    => $student_id,
                'admission_no'          => $this->input->post('admission_no'),
                'roll_no'               => $this->input->post('roll_no'),
                'firstname'             => $this->input->post('firstname'),
                'middlename'            => $this->input->post('middlename'),
                'lastname'              => $this->input->post('lastname'),
                'rte'                   => $this->input->post('rte'),
                'mobileno'              => $this->input->post('mobileno'),
                'email'                 => $this->input->post('email'),
                'state'                 => $this->input->post('state'),
                'city'                  => $this->input->post('city'),
                'previous_school'       => $this->input->post('previous_school'),
                'pincode'               => $this->input->post('pincode'),
                'measurement_date'      => $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date')),
                'religion'              => $this->input->post('religion'),
                'dob'                   => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'admission_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date')),
                'current_address'       => $this->input->post('current_address'),
                'permanent_address'     => $this->input->post('permanent_address'),
                'category_id'           => $category_id,
                'adhar_no'              => $this->input->post('adhar_no'),
                'samagra_id'            => $this->input->post('samagra_id'),
                'bank_account_no'       => $this->input->post('bank_account_no'),
                'bank_name'             => $this->input->post('bank_name'),
                'ifsc_code'             => $this->input->post('ifsc_code'),
                'cast'                  => $this->input->post('cast'),
                'father_name'           => $this->input->post('father_name'),
                'father_phone'          => $this->input->post('father_phone'),
                'father_occupation'     => $this->input->post('father_occupation'),
                'mother_name'           => $this->input->post('mother_name'),
                'mother_phone'          => $this->input->post('mother_phone'),
                'mother_occupation'     => $this->input->post('mother_occupation'),
                'guardian_email'        => $this->input->post('guardian_email'),
                'gender'                => $this->input->post('gender'),
                'guardian_name'         => $this->input->post('guardian_name'),
                'guardian_relation'     => $this->input->post('guardian_relation'),
                'guardian_phone'        => $this->input->post('guardian_phone'),
                'guardian_address'      => $this->input->post('guardian_address'),
                'hostel_room_id'        => $hostel_room_id,
                'note'                  => $this->input->post('note'),
                'class_section_id'      => $section_id,
                'route_pickup_point_id' => $route_pickup_point_id,
                'vehroute_id'           => $vehroute_id,
            );
            if ($this->sch_setting_detail->guardian_name) {
                $data['guardian_is'] = $this->input->post('guardian_is');
            }

            if ($this->sch_setting_detail->is_student_house) {
                $data['school_house_id'] = empty2null($this->input->post('house'));
            }

            if ($this->sch_setting_detail->guardian_occupation) {
                $data['guardian_occupation'] = $this->input->post('guardian_occupation');
            }

            if ($this->sch_setting_detail->is_blood_group) {
                $data['blood_group'] = $this->input->post('blood_group');
            }

            if ($this->sch_setting_detail->student_height) {
                $data['height'] = $this->input->post('height');
            }

            if ($this->sch_setting_detail->student_weight) {
                $data['weight'] = $this->input->post('weight');
            }
            if ($this->sch_setting_detail->measurement_date) {
                $data['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }
 
            $response = $this->onlinestudent_model->update($data, $fee_session_group_id, $transport_feemaster_id, $this->input->post('save'));

            if ($response) {
                $response = json_decode($response);

                $custom_field_post = $this->input->post("custom_fields[students]");

                if (isset($custom_field_post)) {
                    $custom_value_array = array();
                    foreach ($custom_field_post as $key => $value) {
                        $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                        $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                        $array_custom     = array( 
                            'custom_field_id' => $key,
                            'field_value'     => $field_value,
                        );

                        if ($this->input->post('save') == "enroll") {
                            $array_custom['belong_table_id'] = $response->student_id;
                        }

                        $custom_value_array[] = $array_custom;
                    }

                    if ($this->input->post('save') == "enroll") {

                        $this->customfield_model->updateRecord($custom_value_array, $id, 'students');
                    } else {

                        $this->customfield_model->onlineadmissionupdateRecord($custom_value_array, $id, 'students');
                    }

                }
                //to upload document from online student to main firl
                if (isset($student['document']) && !empty($student['document'])) {
                    $uploaddir = './uploads/student_documents/' . $response->student_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }

                    $file_name           = basename($student['document']);
                    $img_name            = $uploaddir . $file_name;
                    $filePath            = "./uploads/student_documents/online_admission_doc/" . $student['document'];
                    $destinationFilePath = $img_name;
                    copy($filePath, $destinationFilePath);

                    $data_img = array('student_id' => $response->student_id, 'doc' => $file_name);
                    $this->student_model->adddoc($data_img);
                }

                // to upload father mother student and guardian image
                if ($this->input->post('save') == "enroll") {

                    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                        $fileInfo = pathinfo($_FILES["file"]["name"]);
                        $img_name = $response->student_id . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/" . $img_name);
                        $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    } else {

                        if ($student['image'] != "") {
                            $filePath = $student['image'];

                            $fileInfo = pathinfo($student['image']);
                            $img_name = $response->student_id . '.' . $fileInfo['extension'];

                            $uploaddir           = './uploads/student_images/' . $img_name;
                            $destinationFilePath = $uploaddir;

                            copy($filePath, $destinationFilePath);
                            $data_img = array('id' => $response->student_id, 'image' => 'uploads/student_images/' . $img_name);
                            $this->student_model->add($data_img);
                        }

                    }

                    if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {

                        $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                        $img_name = $response->student_id . "father" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                        $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    } else {
                        if ($student['father_pic'] != "") {

                            $filePath            = $student['father_pic'];
                            $fileInfo            = pathinfo($student['father_pic']);
                            $img_name            = $response->student_id . "father" . '.' . $fileInfo['extension'];
                            $uploaddir           = './uploads/student_images/' . $img_name;
                            $destinationFilePath = $uploaddir;
                            copy($filePath, $destinationFilePath);
                            $data_img = array('id' => $response->student_id, 'father_pic' => 'uploads/student_images/' . $img_name);
                            $this->student_model->add($data_img);
                        }
                    }

                    if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                        $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                        $img_name = $response->student_id . "mother" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                        $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    } else {

                        if ($student['mother_pic'] != "") {
                            $filePath            = $student['mother_pic'];
                            $fileInfo            = pathinfo($student['mother_pic']);
                            $img_name            = $response->student_id . "mother" . '.' . $fileInfo['extension'];
                            $uploaddir           = './uploads/student_images/' . $img_name;
                            $destinationFilePath = $uploaddir;
                            copy($filePath, $destinationFilePath);
                            $data_img = array('id' => $response->student_id, 'mother_pic' => 'uploads/student_images/' . $img_name);
                            $this->student_model->add($data_img);

                        }
                    }

                    if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                        $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                        $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/" . $img_name);
                        $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                        $this->student_model->add($data_img);
                    } else {
                        if ($student['guardian_pic'] != "") {
                            $filePath = $student['guardian_pic'];
                            $fileInfo = pathinfo($student['guardian_pic']);
                            $img_name = $response->student_id . "guardian" . '.' . $fileInfo['extension'];

                            $uploaddir           = './uploads/student_images/' . $img_name;
                            $destinationFilePath = $uploaddir;
                            copy($filePath, $destinationFilePath);
                            $data_img = array('id' => $response->student_id, 'guardian_pic' => 'uploads/student_images/' . $img_name);
                            $this->student_model->add($data_img);

                        }
                    }

                } else {
                    // to update image in online student table

                    if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                        $fileInfo = pathinfo($_FILES["file"]["name"]);
                        $img_name = $student['id'] . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/student_images/online_admission_image/" . $img_name);
                        $data_img = array('id' => $student['id'], 'image' => 'uploads/student_images/online_admission_image/' . $img_name);
                        $this->onlinestudent_model->edit($data_img);
                    }

                    if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                        $fileInfo = pathinfo($_FILES["father_pic"]["name"]);
                        $img_name = $student['id'] . "father" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["father_pic"]["tmp_name"], "./uploads/student_images/online_admission_image/" . $img_name);
                        $data_img = array('id' => $student['id'], 'father_pic' => 'uploads/student_images/online_admission_image/' . $img_name);
                        $this->onlinestudent_model->edit($data_img);
                    }

                    if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                        $fileInfo = pathinfo($_FILES["mother_pic"]["name"]);
                        $img_name = $student['id'] . "mother" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["mother_pic"]["tmp_name"], "./uploads/student_images/online_admission_image/" . $img_name);
                        $data_img = array('id' => $student['id'], 'mother_pic' => 'uploads/student_images/online_admission_image/' . $img_name);
                        $this->onlinestudent_model->edit($data_img);
                    }

                    if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                        $fileInfo = pathinfo($_FILES["guardian_pic"]["name"]);
                        $img_name = $student['id'] . "guardian" . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["guardian_pic"]["tmp_name"], "./uploads/student_images/online_admission_image/" . $img_name);
                        $data_img = array('id' => $student['id'], 'guardian_pic' => 'uploads/student_images/online_admission_image/' . $img_name);
                        $this->onlinestudent_model->edit($data_img);
                    }

                }

                if ($response->student_id != "") {

                    $sender_details = array('student_id' => $response->student_id, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('student_admission', $sender_details);

                    $student_login_detail = array('id' => $response->student_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $response->student_id, 'password' => $response->user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'), 'admission_no' => $response->admission_no);
                    $this->mailsmsconf->mailsms('student_login_credential', $student_login_detail);

                    $parent_login_detail = array('id' => $response->student_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $response->student_id, 'password' => $response->parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'));
                    $this->mailsmsconf->mailsms('login_credential', $parent_login_detail);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
                redirect('admin/onlinestudent');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('please_check_student_admission_no') . '</div>');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function getByClass()
    {
        $class_id = $this->input->post('class_id');
        $data     = $this->section_model->getClassBySection($class_id);
        $this->jsonlib->output(200, $data);
    }

    public function getstudentlist()
    {
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $sch_setting       = $this->sch_setting_detail;
        $carray = array();
        if (!empty($data['classlist'])) {
            foreach ($data['classlist'] as $key => $value) {
                $carray[] = $value['id'];
            }
        }

        $student_result = $this->onlinestudent_model->getstudentlist($carray, null);

        $m               = json_decode($student_result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $document  = '';
                $last_name = "";
                $mobileno  = "";
                $printbtn  = "";
                $status    = 'admin';

                if ($this->rbac->hasPrivilege('online_admission', 'can_edit')) {
                    if (!$value->is_enroll) {
                        $editbtn = "<a  class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('edit_and_enroll') . "' onclick='return checkpaymentstatus(" . '"' . $value->id . '"' . "  )'><i class='fa fa-pencil'></i></a>";
                    }
                }

                if ($this->rbac->hasPrivilege('online_admission', 'can_delete')) {
                    $deletebtn = '';

                    $deletebtn = "<a href='" . base_url() . 'admin/onlinestudent/delete/' . $value->id . "' class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )'><i class='fa fa-remove'></i></a>";
                }

                if (!empty($value->reference_no)) {
                    $printbtn = "<a target='_blank' href='" . $this->customlib->getBaseUrl() . 'welcome/online_admission_review/' . $value->reference_no . "'  class='btn btn-default btn-xs mt-5 pull-right' data-toggle='tooltip' title='" . $this->lang->line('print') . "' ><i class='fa fa-print'></i></a>";
                } else {
                    $printbtn = "";
                }

                if (($value->is_enroll)) {
                    $enroll = "<i class='fa fa-check'></i><span style='display:none'>" . $this->lang->line('yes') . "</span>";
                } else {
                    $enroll = "<i class='fa fa-minus-circle'></i><span style='display:none'>" . $this->lang->line('no') . "</span>";
                }

                if ($value->dob != null) {
                    $dob = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->dob));
                } else {
                    $dob = "";
                }

                if ($value->submit_date != null) {
                    $submit_date = " (" . date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->submit_date)) . ")";
                } else {
                    $submit_date = "";
                }

                $created_at = "";
                if ($value->document) {
                    $document = "<a href='" . site_url("admin/onlinestudent/onlineadmission_download/" . $value->id) . "' class='btn btn-default btn-xs mt5'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }

                if ($sch_setting->lastname) {
                    $last_name = $value->lastname;
                }
                 $middlename ='';
                if ($sch_setting->middlename) {
                    $middlename = $value->middlename;
                }

                $row   = array();
                $row[] = $value->reference_no;
                $row[] = $value->firstname . " " . $middlename. " " . $last_name;
                $row[] = $value->class . "(" . $value->section . ")";

                if ($sch_setting->father_name) {
                    $row[] = $value->father_name;
                }

                $row[] = $dob;
                $row[] = $this->lang->line(strtolower($value->gender));
                $row[] = $value->category;

                if ($sch_setting->mobile_no) {
                    $row[] = $value->mobileno;
                }

                if ($value->form_status == 1) {
                    $row[] = '<span class="label label-success">' . $this->lang->line('submitted') . '  ' . $submit_date . '</span>';
                } else if ($value->form_status == 0) {
                    $row[] = '<span class="label label-danger">' . $this->lang->line('not_submitted') . '</span>';
                }

                if ($sch_setting->online_admission_payment == 'yes') {
                    if ($value->paid_status == 1) {
                        $row[] = '<span class="label label-success">' . $this->lang->line('paid') . '</span>';
                    } elseif ($value->paid_status == 2) {
                        $row[] = '<span class="label label-info">' . $this->lang->line('processing') . '</span>';
                    } else {
                        $row[] = '<span class="label label-danger">' . $this->lang->line('unpaid') . '</span>';
                    }

                }

                $row[]     =  $enroll;
                $row[]     =  date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($value->created_at))));
                
                $row[]     = $document . ' ' . $printbtn . ' ' . $editbtn . ' ' . $deletebtn;
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

    public function checkpaymentstatus()
    {
        $id          = $_REQUEST['id'];
        $sch_setting = $this->sch_setting_detail;
        $and         = "";
        $result      = $this->onlinestudent_model->checkpaymentstatus($id);

        if ($result['form_status'] != 1 && $sch_setting->online_admission_payment == 'yes' && $result['paid_status'] == 0) {

            $message = $this->lang->line('form_status') . "         : " . $this->lang->line('not_submitted') . " \n" . $this->lang->line('payment_status') . "    : " . $this->lang->line('unpaid') . " \n \n" . $this->lang->line('do_you_still_want_to_enroll_it') . " ";

        } else if ($result['form_status'] != 1 && $sch_setting->online_admission_payment == 'no') {

            $message = $this->lang->line('form_status') . "         : " . $this->lang->line('not_submitted') . " \n \n " . $this->lang->line('do_you_still_want_to_enroll_it') . " ";

        } else if ($result['form_status'] == 1 && $sch_setting->online_admission_payment == 'yes' && $result['paid_status'] == 0) {

            $message = $this->lang->line('payment_status') . "   : " . $this->lang->line('unpaid') . " \n \n " . $this->lang->line('do_you_still_want_to_enroll_it') . " ";
        } else {
            $message = "";
        }

        echo $message;
    }

    public function handle_upload($str, $var)
    {
        // $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

}
