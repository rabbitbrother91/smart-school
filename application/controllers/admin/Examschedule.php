<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ExamSchedule extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examschedule/index');
        //$data['title'] = 'Exam Schedule';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $userdata          = $this->customlib->getUserData();

        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data['student_due_fee'] = array();
            $data['class_id']        = $this->input->post('class_id');
            $data['section_id']      = $this->input->post('section_id');
            $examSchedule            = $this->examschedule_model->getExamByClassandSection($data['class_id'], $data['section_id']);
            $data['examSchedule']    = $examSchedule;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_view')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('exam_schedule_list');
        $exam          = $this->exam_model->get($id);
        $data['exam']  = $exam;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/exam_schedule/examShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {

        $data['title'] = 'Exam Schedule List';
        $this->exam_model->remove($id);
        redirect('admin/exam_schedule/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_add')) {
            access_denied();
        }
        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Schedule';
        $data['exam_id']    = "";
        $data['class_id']   = "";
        $data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist']   = $exam;
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $feecategory             = $this->feecategory_model->get();
        $data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $feecategory_id       = $this->input->post('feecategory_id');
            $exam_id              = $this->input->post('exam_id');
            $class_id             = $this->input->post('class_id');
            $section_id           = $this->input->post('section_id');
            $data['exam_id']      = $exam_id;
            $data['class_id']     = $class_id;
            $data['section_id']   = $section_id;
            $examSchedule         = $this->teachersubject_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
            $data['examSchedule'] = $examSchedule;
            if ($this->input->post('save_exam') == "save_exam") {
                $i = $this->input->post('i');
                foreach ($i as $key => $value) {
                    $data = array(
                        'session_id'         => $session,
                        'teacher_subject_id' => $value,
                        'exam_id'            => $this->input->post('exam_id'),
                        'date_of_exam'       => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_' . $value))),
                        'start_to'           => $this->input->post('stime_' . $value),
                        'end_from'           => $this->input->post('etime_' . $value),
                        'room_no'            => $this->input->post('room_' . $value),
                        'full_marks'         => $this->input->post('fmark_' . $value),
                        'passing_marks'      => $this->input->post('pmarks_' . $value),
                    );

                    $this->exam_model->add_exam_schedule($data);
                }
                redirect('admin/examschedule');
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('exam_schedule', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Exam Schedule';
        $data['id']    = $id;
        $exam          = $this->exam_model->get($id);
        $data['exam']  = $exam;
        $this->form_validation->set_rules('name', $this->lang->line('exam_schedule'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam_schedule/examEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div exam="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/exam_schedule/index');
        }
    }

    public function getexamscheduledetail()
    {
        $exam_id      = $this->input->post('exam_id');
        $section_id   = $this->input->post('section_id');
        $class_id     = $this->input->post('class_id');
        $examSchedule = $this->examschedule_model->getDetailbyClsandSection($class_id, $section_id, $exam_id);
        echo json_encode($examSchedule);
    }

}
