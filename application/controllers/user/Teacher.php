<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Teacher extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->current_classSection = $this->customlib->getStudentCurrentClsSection();
    }

    public function index()
    {

        $this->session->set_userdata('top_menu', 'Teachers');
        $this->session->set_userdata('sub_menu', 'teacher/index');
        $data['title']      = 'Add Teacher';
        $data['teachers']   = $teachers   = array();
        $data['class_id']   = $class_id   = $this->current_classSection->class_id;
        $data['section_id'] = $section_id = $this->current_classSection->section_id;
        $data['resultlist'] = $this->subjecttimetable_model->getTeacherByClassandSection($class_id, $section_id); 
        $subject            = array();
        foreach ($data['resultlist'] as $value) {
            $teachers[$value->staff_id][] = $value;
        }
        $session_id                  = $this->session->userdata('student');
        $data['user_id']             = $session_id['id'];
        $data['role']                = $session_id['role'];

        $data['teacherlist']         = $teachers;
        $genderList                  = $this->customlib->getGender();
        $data['genderList']          = $genderList;
        $user_ratedstafflist         = $this->staff_model->get_RatedStaffByUser($session_id['id']);
        $data['user_ratedstafflist'] = $user_ratedstafflist;
        $get_ratingbystudent         = $this->staff_model->get_ratingbyuser($data['user_id'], 'student');
       
        if ($data['role'] == "student") {
            foreach ($get_ratingbystudent as $value) {
                $data['reviews'][$value['staff_id']] = $value['rate'];
                $data['comment'][$value['staff_id']] = $value['comment'];
                
            }
        } elseif ($data['role'] == "parent") {
            $all_rating           = $this->staff_model->all_rating();
   
            $data['rate_canview'] = 0;
            foreach ($all_rating as $value) {
                if ($value['total'] >= 3) {
                    $r = ($value['rate'] / $value['total']);

                    $data['avg_rate'][$value['staff_id']] = $r;
                    $data['rate_canview']                 = 1;
                } else {
                    $data['avg_rate'][$value['staff_id']] = 0;
                }
                $data['reviews'][$value['staff_id']] = $value['total'];
            }
        }

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/teacher/teacherList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function getSubjctByClassandSection()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data       = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);
        echo json_encode($data);
    }

    public function assignTeacher()
    {
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'teacher/assignTeacher');
        $data['title']       = 'Assign Teacher with Class and Subject wise';
        $teacher             = $this->teacher_model->get();
        $data['teacherlist'] = $teacher;
        $subject             = $this->subject_model->get();
        $data['subjectlist'] = $subject;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/assignTeacher', $data);
        $this->load->view('layout/footer', $data);
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $loop  = $this->input->post('i');
            $array = array();
            foreach ($loop as $key => $value) {
                $s                     = array();
                $s['session_id']       = $this->setting_model->getCurrentSession();
                $class_id              = $this->input->post('class_id');
                $section_id            = $this->input->post('section_id');
                $dt                    = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);
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
            $ids = implode(",", $array);
            $this->teachersubject_model->deleteBatch($ids);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/teacher/assignTeacher');
        }
    }

    public function getSubjectTeachers()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $dt         = $this->classsection_model->getDetailbyClassSection($class_id, $section_id);
        $data       = $this->teachersubject_model->getDetailByclassAndSection($dt['id']);
        echo json_encode($data);
    }

    public function view($id)
    {
        $data['title']   = 'Teacher List';
        $teacher         = $this->teacher_model->get($id);
        $data['teacher'] = $teacher;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/teacher/teacherShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Teacher List';
        $this->teacher_model->remove($id);
        redirect('admin/teacher/index');
    }

    public function create()
    {
        $data['title']      = 'Add teacher';
        $genderList         = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->form_validation->set_rules('name', $this->lang->line('teacher'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
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
                'image'    => $this->input->post('file'),
            );
            $insert_id = $this->teacher_model->add($data);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $insert_id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->student_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div teacher="alert alert-success text-center">' . $this->lang->line('employee_details_added_to_database') . '</div>');
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
            if ($_FILES["file"]["size"] > 102400) {
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
        $data['title']      = 'Edit Teacher';
        $data['id']         = $id;
        $genderList         = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $teacher            = $this->teacher_model->get($id);
        $data['teacher']    = $teacher;
        $this->form_validation->set_rules('name', $this->lang->line('teacher'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', $this->lang->line('password'), 'trim|required|xss_clean');
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
                'image'    => $this->input->post('file'),
            );
            $insert_id = $this->teacher_model->add($data);
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/teacher_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => 'uploads/teacher_images/' . $img_name);
                $this->student_model->add($data_img);
            }
            $this->session->set_flashdata('msg', '<div teacher="alert alert-success text-center">' . $this->lang->line('employee_details_added_to_database') . '</div>');
            redirect('admin/teacher/index');
        }
    }

    public function rating()
    {
        $this->form_validation->set_rules('comment', $this->lang->line('comment'), 'required');
        $this->form_validation->set_rules('rate', $this->lang->line('rating'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'comment' => form_error('comment'),
                'rate'    => form_error('rate'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data['staff_id'] = $this->input->post('staff_id');
            $data['comment']  = $this->input->post('comment');
            $data['rate']     = $this->input->post('rate');
            $data['user_id']  = $this->input->post('user_id');
            $data['role']     = $this->input->post('role');
            $this->teacher_model->rating($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('rating_successfully_saved'));
        }

        echo json_encode($array);
    }

}
