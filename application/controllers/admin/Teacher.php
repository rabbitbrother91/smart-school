<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Teacher extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mailsmsconf');
        $this->load->model("classteacher_model");
        $this->role;
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'teacher/index');
        $data['title']       = 'Add Teacher';
        $data['teacherlist'] = $this->teacher_model->get();         
        $data['genderList']  = $this->customlib->getGender(); 
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/teacherList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getSubjctByClassandSection()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data       = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

    public function assignteacher()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/viewassignteacher');
        $data['title'] = 'Assign Teacher with Class and Subject wise';

        $teacher             = $this->staff_model->getStaffbyrole(2);
        $data['teacherlist'] = $teacher;
        $subject             = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $userdata            = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/assignTeacher', $data);
        $this->load->view('layout/footer', $data);
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $loop  = $this->input->post('i');
            $array = array();
            $dt    = array();

            foreach ($loop as $key => $value) {
                $s               = array();
                $s['session_id'] = $this->setting_model->getCurrentSession();
                $class_id        = $this->input->post('class_id');
                $section_id      = $this->input->post('section_id');
                $dt              = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);

                $s['class_section_id'] = $dt['id'];
                $s['teacher_id']       = $this->input->post('teacher_id_' . $value);
                $s['subject_id']       = $this->input->post('subject_id_' . $value);
                $row_id                = $this->input->post('row_id_' . $value);
                if ($row_id == 0) {
                    $insert_id = $this->teachersubject_model->add($s);
                    $array[]   = $insert_id;
                } else {
                    $s['id'] = $row_id;
                    $array[] = $row_id;
                    $this->teachersubject_model->add($s);
                }
            }

            $ids              = $array;
            $class_section_id = $dt['id'];
            $this->teachersubject_model->deleteBatch($ids, $class_section_id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/teacher/assignteacher');
        }
    }

    public function viewassignteacher()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/viewassignteacher');
        $data['title'] = 'Assign Teacher with Class and Subject wise';

        $teacher             = $this->staff_model->getStaffbyrole(2);
        $data['teacherlist'] = $teacher;
        $subject             = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $userdata            = $this->customlib->getUserData();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/viewassignTeacher', $data);
        $this->load->view('layout/footer', $data);
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $loop  = $this->input->post('i');
            $array = array();
            foreach ($loop as $key => $value) {
                $s               = array();
                $s['session_id'] = $this->setting_model->getCurrentSession();
                $class_id        = $this->input->post('class_id');
                $section_id      = $this->input->post('section_id');
                $dt              = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);

                $s['class_section_id'] = $dt['id'];
                $s['teacher_id']       = $this->input->post('teacher_id_' . $value);
                $s['subject_id']       = $this->input->post('subject_id_' . $value);
                $row_id                = $this->input->post('row_id_' . $value);
                if ($row_id == 0) {
                    $insert_id = $this->teachersubject_model->add($s);
                    $array[]   = $insert_id;
                } else {
                    $s['id'] = $row_id;
                    $array[] = $row_id;
                    $this->teachersubject_model->add($s);
                }
            }

            $ids              = $array;
            $class_section_id = $dt['id'];
            $this->teachersubject_model->deleteBatch($ids, $class_section_id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/teacher/assignteacher');
        }
    }

    public function getSubjectTeachers()
    {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run()) {
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $dt         = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);
            $data       = $this->teachersubject_model->getDetailByclassAndSection($dt['id']);
            echo json_encode(array('st' => 0, 'msg' => $data));
        } else {
            $data = array(
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_view')) {
            access_denied();
        }
        $data['title']          = 'Teacher List';
        $teacher                = $this->teacher_model->get($id);
        $teachersubject         = $this->teachersubject_model->getTeacherClassSubjects($id);
        $data['teacher']        = $teacher;
        $data['teachersubject'] = $teachersubject;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/teacherShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Teacher List';
        $this->teacher_model->remove($id);
        redirect('admin/teacher/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('assign_subject', 'can_add')) {
            access_denied();
        }
        $data['title']      = 'Add teacher';
        $genderList         = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->form_validation->set_rules('name', $this->lang->line('teacher'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $teacher_result      = $this->teacher_model->get();
            $data['teacherlist'] = $teacher_result;
            $genderList          = $this->customlib->getGender();
            $data['genderList']  = $genderList;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/teacher/teacherCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name'     => $this->input->post('name'),
                'email'    => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'sex'      => $this->input->post('gender'),
                'dob'      => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'address'  => $this->input->post('address'),
                'phone'    => $this->input->post('phone'),
                'image'    => 'uploads/student_images/no_image.png',
            );
            $insert_id          = $this->teacher_model->add($data);
            $user_password      = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_student_login = array(
                'username' => $this->teacher_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id'  => $insert_id,
                'role'     => 'teacher',
            );
            $this->user_model->add($data_student_login);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->teacher_model->add($data_img);
            }
            $teacher_login_detail = array('id' => $insert_id, 'credential_for' => 'teacher', 'username' => $this->teacher_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('phone'));

            $this->mailsmsconf->mailsms('login_credential', $teacher_login_detail);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/teacher/index');
        }
    }

    public function handle_upload()
    {
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["file"]["type"] != 'image/gif' &&
                $_FILES["file"]["type"] != 'image/jpeg' &&
                $_FILES["file"]["type"] != 'image/png') {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["file"]["size"] > 10240000) {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            return true;
        }
    }

    public function edit($id)
    {

        if (!$this->rbac->hasPrivilege('assign_subject', 'can_edit')) {
            access_denied();
        }

        $data['title']      = 'Edit Teacher';
        $data['id']         = $id;
        $genderList         = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $teacher            = $this->teacher_model->get($id);
        $data['teacher']    = $teacher;
        $this->form_validation->set_rules('name', $this->lang->line('teacher'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {

            $teacher_result      = $this->teacher_model->get();
            $data['teacherlist'] = $teacher_result;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/teacher/teacherEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $data = array(
                'id'       => $id,
                'name'     => $this->input->post('name'),
                'email'    => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'sex'      => $this->input->post('gender'),
                'dob'      => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('dob'))),
                'address'  => $this->input->post('address'),
                'phone'    => $this->input->post('phone'),
            );
            $insert_id = $this->teacher_model->add($data);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->teacher_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/teacher/index');
        }
    }

    public function getlogindetail()
    {
        $teacher_id   = $this->input->post('teacher_id');
        $examSchedule = $this->user_model->getTeacherLoginDetails($teacher_id);
        echo json_encode($examSchedule);
    }

    public function assign_class_teacher()
    {
        if (!$this->rbac->hasPrivilege('assign_class_teacher', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/teacher/assign_class_teacher');
        $data['title']      = 'Add Class Teacher';
        $data['title_list'] = 'Class List';

        $this->form_validation->set_rules(
            'class', $this->lang->line('class'), array(
                'required',
                array('class_exists', array($this->class_model, 'class_teacher_exists')),
            )
        );
        $this->form_validation->set_rules('section', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('teachers[]', $this->lang->line('class_teacher'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

        } else {

            $class    = $this->input->post("class");
            $section  = $this->input->post("section");
            $teachers = $this->input->post("teachers");

            $i = 0;
            foreach ($teachers as $key => $value) {

                $classteacherid = $this->input->post("classteacherid");
                if (isset($classteacherid)) {

                    $data = array('id' => $classteacherid[$i],
                        'class_id'         => $class,
                        'section_id'       => $section,
                        'staff_id'         => $teachers[$i],
                        'session_id'       => $this->current_session,
                    );
                } else {
                    $data = array('class_id' => $class,
                        'section_id'             => $section,
                        'staff_id'               => $teachers[$i],
                        'session_id'             => $this->current_session,
                    );
                }
                $i++;
                $this->classteacher_model->addClassTeacher($data);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/teacher/assign_class_teacher');
        }
        $classlist         = $this->class_model->get();
        $data['classlist'] = $classlist;

        $sectionlist         = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;

        $assignteacherlist         = $this->class_model->getClassTeacher();
        $data['assignteacherlist'] = $assignteacherlist;

        foreach ($assignteacherlist as $key => $value) {
            $class_id   = $value["class_id"];
            $section_id = $value["section_id"];

            $tlist[] = $this->classteacher_model->teacherByClassSection($class_id, $section_id);
        }
        if (!empty($tlist)) {
            $data["tlist"] = $tlist;
        }
        $teacherlist = $this->staff_model->getStaffbyrole($role = 2);

        $data['teacherlist'] = $teacherlist;

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacher', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classteacheredit1111($class_id, $section_id)
    {
        if (!$this->rbac->hasPrivilege('assign_class_teacher', 'can_edit')) {
            access_denied();
        }

        $result = $this->classteacher_model->teacherByClassSection($class_id, $section_id);

        $data["result"] = $result;

        $assignteacherlist         = $this->class_model->getClassTeacher();
        $data['assignteacherlist'] = $assignteacherlist;
        foreach ($assignteacherlist as $key => $value) {
            $classid   = $value["class_id"];
            $sectionid = $value["section_id"];

            $tlist[] = $this->classteacher_model->teacherByClassSection($classid, $sectionid);
        }

        $data["tlist"]       = $tlist;
        $teacherlist         = $this->staff_model->getStaffbyrole($role = 2);
        $data['teacherlist'] = $teacherlist;
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $classlist           = $this->class_model->get();
        $data['classlist']   = $classlist;
        $sectionlist         = $this->section_model->get();
        $data['sectionlist'] = $sectionlist;

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacherEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update_class_teacher($class_id, $section_id)
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'classes/index');
        $data['title']      = 'Add Class Teacher';
        $data['title_list'] = 'Class List';

        $this->form_validation->set_rules(
            'class', $this->lang->line('class'), array(
                'required',
                array('class_exists', array($this->class_model, 'class_teacher_exists')),
            )
        );
        $this->form_validation->set_rules('section', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('teachers[]', $this->lang->line('class_teacher'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $result = $this->classteacher_model->teacherByClassSection($class_id, $section_id);

            $data["result"] = $result;

            $assignteacherlist         = $this->class_model->getClassTeacher();
            $data['assignteacherlist'] = $assignteacherlist;
            foreach ($assignteacherlist as $key => $value) {
                $classid   = $value["class_id"];
                $sectionid = $value["section_id"];

                $tlist[] = $this->classteacher_model->teacherByClassSection($classid, $sectionid);
            }

            $data["tlist"]       = $tlist;
            $teacherlist         = $this->staff_model->getStaffbyrole($role = 2);
            $data['teacherlist'] = $teacherlist;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $classlist         = $this->class_model->get();
            $data['classlist'] = $classlist;
            $sectionlist         = $this->section_model->get();
            $data['sectionlist'] = $sectionlist;
        } else {

            $section      = $this->input->post('section');
            $prev_teacher = $this->input->post('classteacherid');
            $staff_id     = $this->input->post('teachers');
            $class_id     = $this->input->post('class');
            if (!isset($prev_teacher)) {
                $prev_teacher = array();
            }
            $add_result    = array_diff($staff_id, $prev_teacher);
            $delete_result = array_diff($prev_teacher, $staff_id);

            if (!empty($add_result)) {
                $teacher_batch_array = array();
                foreach ($add_result as $teacher_add_key => $teacher_add_value) {
                    $teacher_batch_array[] = $teacher_add_value;
                }

                $insert_array = array();
                foreach ($teacher_batch_array as $vec_key => $vec_value) {

                    $vehicle_array = array(
                        'class_id'   => $class_id,
                        'section_id' => $section,
                        'staff_id'   => $vec_value,
                        'session_id' => $this->current_session,
                    );
                    $this->classteacher_model->addClassTeacher($vehicle_array);
                    $insert_array[] = $vehicle_array;
                }
            } else {
                $prev_class_id   = $this->input->post('prev_class_id');
                $prev_section_id = $this->input->post('prev_section_id');
                $previd          = $this->input->post('previd');

                if (!empty($previd)) {

                    if ($prev_class_id != $class_id || $prev_section_id != $section) {
                        $this->classteacher_model->updateTeacher($previd, $class_id, $section);
                    }
                }
            }

            if (!empty($delete_result)) {
                $classteacher_delete_array = array();
                foreach ($delete_result as $vec_delete_key => $vec_delete_value) {
                    $classteacher_delete_array[] = $vec_delete_value;
                }

                $this->classteacher_model->delete($class_id, $section, $classteacher_delete_array);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/teacher/assign_class_teacher');
        }

        $this->load->view('layout/header', $data);
        $this->load->view('class/classTeacherEdit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classteacherdelete($class_id, $section_id)
    {
        if ((!empty($class_id)) && (!empty($section_id))) {

            $this->classteacher_model->delete($class_id, $section_id, null);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('delete_message') . '</div>');
            redirect("admin/teacher/assign_class_teacher");
        }
    }

}
