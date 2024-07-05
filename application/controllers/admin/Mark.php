<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mark extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("classteacher_model");
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/exam_marks_report');
        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Marks';
        $data['exam_id']    = "";
        $data['class_id']   = "";
        $data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get();
        $data['examlist']   = $exam;
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id       = $this->input->post('feecategory_id');
            $exam_id              = $this->input->post('exam_id');
            $class_id             = $this->input->post('class_id');
            $section_id           = $this->input->post('section_id');
            $data['exam_id']      = $exam_id;
            $data['class_id']     = $class_id;
            $data['section_id']   = $section_id;
            $examSchedule         = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            $studentList          = $this->student_model->searchByClassSection($class_id, $section_id);
            $data['examSchedule'] = array();
            if (!empty($examSchedule)) {
                $new_array                      = array();
                $data['examSchedule']['status'] = "yes";
                foreach ($studentList as $stu_key => $stu_value) {
                    $array                 = array();
                    $array['student_id']   = $stu_value['id'];
                    $array['roll_no']      = $stu_value['roll_no'];
                    $array['firstname']    = $stu_value['firstname'];
                    $array['lastname']     = $stu_value['lastname'];
                    $array['admission_no'] = $stu_value['admission_no'];
                    $array['dob']          = $stu_value['dob'];
                    $array['father_name']  = $stu_value['father_name'];
                    $x                     = array();
                    foreach ($examSchedule as $ex_key => $ex_value) {
                        $exam_array                     = array();
                        $exam_array['exam_schedule_id'] = $ex_value['id'];
                        $exam_array['exam_id']          = $ex_value['exam_id'];
                        $exam_array['full_marks']       = $ex_value['full_marks'];
                        $exam_array['passing_marks']    = $ex_value['passing_marks'];
                        $exam_array['exam_name']        = $ex_value['name'];
                        $exam_array['exam_type']        = $ex_value['type'];
                        $student_exam_result            = $this->examresult_model->get_result($ex_value['id'], $stu_value['id']);

                        if (empty($student_exam_result)) {

                        } else {
                            $exam_array['attendence'] = $student_exam_result->attendence;
                            $exam_array['get_marks']  = $student_exam_result->get_marks;
                        }
                        $x[] = $exam_array;
                    }
                    if (empty($x)) {
                        $data['examSchedule']['status'] = "no";
                    }
                    $array['exam_array'] = $x;
                    $new_array[]         = $array;
                }

                $data['examSchedule']['result'] = $new_array;
            } else {
                $s                    = array('status' => 'no');
                $data['examSchedule'] = $s;
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('marks_register', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Mark List';
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/mark/markShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/mark/index');
    }

    public function create()
    {
        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Schedule';
        $data['exam_id']    = "";
        $data['class_id']   = "";
        $data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get();
        $data['examlist']   = $exam;
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id     = $this->input->post('feecategory_id');
            $exam_id            = $this->input->post('exam_id');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['exam_id']    = $exam_id;
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $userdata           = $this->customlib->getUserData();
            $getTeacherSubjects = array();
            if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                $getTeacherSubjects = $this->examschedule_model->getTeacherSubjects($class_id, $section_id, $userdata["id"]);
            }
            $data["teacher_subjects"] = $getTeacherSubjects;
            $examSchedule             = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);

            $studentList = $this->student_model->searchByClassSection($class_id, $section_id);

            if (!empty($examSchedule)) {
                $new_array = array();
                foreach ($studentList as $stu_key => $stu_value) {
                    $array                 = array();
                    $array['student_id']   = $stu_value['id'];
                    $array['admission_no'] = $stu_value['admission_no'];
                    $array['roll_no']      = $stu_value['roll_no'];
                    $array['firstname']    = $stu_value['firstname'];
                    $array['lastname']     = $stu_value['lastname'];
                    $array['dob']          = $stu_value['dob'];
                    $array['father_name']  = $stu_value['father_name'];
                    $x                     = array();
                    foreach ($examSchedule as $ex_key => $ex_value) {
                        $exam_array                     = array();
                        $exam_array['exam_schedule_id'] = $ex_value['id'];
                        $exam_array['exam_id']          = $ex_value['exam_id'];
                        $exam_array['subject_id']       = $ex_value['subject_id'];
                        $exam_array['full_marks']       = $ex_value['full_marks'];
                        $exam_array['passing_marks']    = $ex_value['passing_marks'];
                        $exam_array['exam_name']        = $ex_value['name'];
                        $exam_array['exam_type']        = $ex_value['type'];
                        $student_exam_result            = $this->examresult_model->get_exam_result($ex_value['id'], $stu_value['id']);
                        $exam_array['attendence']       = $student_exam_result->attendence;
                        $exam_array['get_marks']        = $student_exam_result->get_marks;
                        $x[]                            = $exam_array;
                    }
                    $array['exam_array'] = $x;
                    $new_array[]         = $array;
                }
                $data['examSchedule'] = $new_array;
            }
            if ($this->input->post('save_exam') == "save_exam") {
                $ex_array      = array();
                $exam_id       = $this->input->post('exam_id');
                $student_array = $this->input->post('student');
                $exam_array    = $this->input->post('exam_schedule');
                foreach ($student_array as $key => $student) {
                    foreach ($exam_array as $key => $exam) {
                        $record['get_marks']  = 0;
                        $record['attendence'] = "pre";
                        if ($this->input->post('student_absent' . $student . "_" . $exam) == "") {
                            $record['get_marks'] = $this->input->post('student_number' . $student . "_" . $exam);
                        } else {
                            $record['attendence'] = $this->input->post('student_absent' . $student . "_" . $exam);
                        }
                        $record['exam_schedule_id'] = $exam;
                        $record['student_id']       = $student;
                        $inserted_id                = $this->examresult_model->add_exam_result($record);

                        if ($inserted_id) {
                            $ex_array[$student] = $exam_id;
                        }
                    }
                }

                if (!empty($ex_array)) {
                    $this->mailsmsconf->mailsms('exam_result', $ex_array, null, $exam_array);
                }

                redirect('admin/mark');
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('marks_register', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Mark';
        $data['id']    = $id;
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->form_validation->set_rules('name', 'Mark', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/mark/markEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">Employee details added to Database!!!</div>');
            redirect('admin/mark/index');
        }
    }

}
