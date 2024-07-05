<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Exam extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('marksdivision_model'));
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'exam/index');
        $data['title']      = 'Add Exam';
        $data['title_list'] = 'Exam List';
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('employee_details_added_to_database') . '</div>');
            redirect('admin/exam/index');
        }
        $stuid              = $this->session->userdata('student');
        $stu_record         = $this->student_model->getRecentRecord($stuid['student_id']);
        $data['class_id']   = $stu_record['class_id'];
        $data['section_id'] = $stu_record['section_id'];
        $exam_result        = $this->examschedule_model->getExamByClassandSection($data['class_id'], $data['section_id']);
        $data['examlist']   = $exam_result;
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/exam/examList', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function view($id)
    {
        $data['title'] = 'Exam List';
        $exam          = $this->exam_model->get($id);
        $data['exam']  = $exam;
        $this->load->view('layout/header', $data);
        $this->load->view('exam/examShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByFeecategory()
    {
        $feecategory_id = $this->input->get('feecategory_id');
        $data           = $this->feetype_model->getTypeByFeecategory($feecategory_id);
        echo json_encode($data);
    }

    public function getStudentCategoryFee()
    {
        $type     = $this->input->post('type');
        $class_id = $this->input->post('class_id');
        $data     = $this->exam_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function delete($id)
    {
        $data['title'] = 'Exam List';
        $this->exam_model->remove($id);
        redirect('admin/exam/index');
    }

    public function create()
    {
        $data['title'] = 'Add Exam';
        $this->form_validation->set_rules('exam', $this->lang->line('exam'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('exam/examCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'exam' => $this->input->post('exam'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div exam="alert alert-success text-center">' . $this->lang->line('employee_details_added_to_database') . '</div>');
            redirect('exam/index');
        }
    }

    public function edit($id)
    {
        $data['title']      = 'Edit Exam';
        $data['id']         = $id;
        $exam               = $this->exam_model->get($id);
        $data['exam']       = $exam;
        $data['title_list'] = 'Exam List';
        $exam_result        = $this->exam_model->get();
        $data['examlist']   = $exam_result;
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam/examEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->exam_model->add($data);
            $this->session->set_flashdata('msg', '<div exam="alert alert-success text-center">' . $this->lang->line('employee_details_added_to_database') . '</div>');
            redirect('admin/exam/index');
        }
    }

    public function examSearch()
    {
        $data['title'] = 'Search exam';
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $search = $this->input->post('search');
            if ($search == "search_filter") {
                $data['exp_title']  = 'exam Result From ' . $this->input->post('date_from') . " To " . $this->input->post('date_to');
                $date_from          = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from')));
                $date_to            = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_to')));
                $resultList         = $this->exam_model->search("", $date_from, $date_to);
                $data['resultList'] = $resultList;
            } else {
                $data['exp_title']  = 'exam Result';
                $search_text        = $this->input->post('search_text');
                $resultList         = $this->exam_model->search($search_text, "", "");
                $data['resultList'] = $resultList;
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam/examSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/exam/examSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function examresult()
    {
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'examresult/index');
        $student_current_class  = $this->customlib->getStudentCurrentClsSection();
        $student_session_id     = $student_current_class->student_session_id;
        $marks_division         = $this->marksdivision_model->get();
        $data['marks_division'] = $marks_division;
        $data['exam_result']    = $this->examgroupstudent_model->searchStudentExams($student_session_id, true, true);
        $data['exam_grade']     = $this->grade_model->getGradeDetails();
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/examresult/index', $data);
        $this->load->view('layout/student/footer', $data);
    }

}
