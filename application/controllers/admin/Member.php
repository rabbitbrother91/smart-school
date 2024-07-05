<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title']      = 'Member';
        $data['title_list'] = 'Members';
        $memberList         = $this->librarymember_model->get();

        $superadmin_visible = $this->customlib->superadmin_visible();

        if ($superadmin_visible == 'disabled') {
            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);

            if ($staffrole->id != 7) {
                foreach ($memberList as $key => $member) {
                    if ($member['member_type'] != "student") {
                        $getrole = $this->staff_model->getAll($member['staff_id']);

                        if ($getrole['role_id'] != 7) {
                            $result[] = $member;
                        }

                    } else {
                        $result[] = $member;
                    }
                }
            } else {
                $result = $memberList;
            }
        } else {
            $result = $memberList;
        }

        $data['memberList']  = $result;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }

    public function issue($id)
    {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title']        = 'Member';
        $data['title_list']   = 'Members';
        $memberList           = $this->librarymember_model->getByMemberID($id);
        $data['memberList']   = $memberList;
        $issued_books         = $this->bookissue_model->getMemberBooks($id);
        $data['issued_books'] = $issued_books;
        $bookList             = $this->book_model->get();
        
        $this->form_validation->set_rules('return_date', $this->lang->line('due_return_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_id', $this->lang->line('books'), array('required', array('check_exists', array($this->bookissue_model, 'valid_check_exists')),
        )
        );
        if ($this->form_validation->run() == false) {

        } else {
            $member_id = $this->input->post('member_id');
            $data      = array(
                'book_id'        => $this->input->post('book_id'),
                'duereturn_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('return_date'))),
                'issue_date'     => date('Y-m-d'),
                'member_id'      => $this->input->post('member_id'),
            );
            $this->bookissue_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/member/issue/' . $member_id);
        }
       
        
        $data['bookList']     = $bookList;
        
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/issue', $data);
        $this->load->view('layout/footer');
    }

    public function bookreturn()
    {
        $this->form_validation->set_rules('id', $this->lang->line('id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('member_id', $this->lang->line('member_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('return_date'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'id'        => form_error('id'),
                'member_id' => form_error('member_id'),
                'date'      => form_error('date'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $id        = $this->input->post('id');
            $member_id = $this->input->post('member_id');
            $date      = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));
            $data      = array(
                'id'          => $id,
                'return_date' => $date,
                'is_returned' => 1,
            );
            $this->bookissue_model->update($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function student()
    {
        if (!$this->rbac->hasPrivilege('add_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/student');
        $data['title']     = 'Student Search';
        $class             = $this->class_model->get();
        $data['classlist'] = $class;
        $button            = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {

                    } else {
                        $data['searchby']    = "filter";
                        $data['class_id']    = $this->input->post('class_id');
                        $data['section_id']  = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->searchLibraryStudent($class, $section);

                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby']    = "text";
                    $data['class_id']    = $this->input->post('class_id');
                    $data['section_id']  = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->searchFullText($search_text);
                    $data['resultlist']  = $resultlist;
                }
            }
            $data['sch_setting'] = $this->sch_setting_detail;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function add()
    {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', $this->lang->line('library_card_number'), 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student         = $this->input->post('member_id');
                $data            = array(
                    'member_type'     => 'student',
                    'member_id'       => $student,
                    'library_card_no' => $library_card_no,
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student         = $this->input->post('member_id');
            $data            = array(
                'member_type'     => 'student',
                'member_id'       => $student,
                'library_card_no' => $library_card_no,
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function check_cardno_exists()
    {
        $data['library_card_no'] = $this->security->xss_clean($this->input->post('library_card_no'));

        if ($this->librarymanagement_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', $this->lang->line('card_no_already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function teacher()
    {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'Library/member/teacher');
        $data['title']       = 'Add Teacher';
        $data['teacherlist'] = $this->teacher_model->getLibraryTeacher(); 
        $data['genderList'] = $this->customlib->getGender();         
        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/teacher', $data);
        $this->load->view('layout/footer', $data);
    }

    public function addteacher()
    {
        if ($this->input->post('library_card_no') != "") {
            $this->form_validation->set_rules('library_card_no', $this->lang->line('library_card_number'), 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student         = $this->input->post('member_id');
                $data            = array(
                    'member_type'     => 'teacher',
                    'member_id'       => $student,
                    'library_card_no' => $library_card_no,
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student         = $this->input->post('member_id');
            $data            = array(
                'member_type'     => 'teacher',
                'member_id'       => $student,
                'library_card_no' => $library_card_no,
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array       = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'), 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function surrender()
    {
        $this->form_validation->set_rules('member_id', $this->lang->line('book'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

        } else {
            $member_id = $this->input->post('member_id');
              $row_affected=$this->librarymember_model->surrender($member_id);
            $array = array('status' => 'success', 'row_affected'=>$row_affected, 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

}
