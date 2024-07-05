<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("staff_model");
        $this->load->model("classteacher_model");
    }

    public function index()
    {

        if (!$this->rbac->hasPrivilege('class_time_table', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');
        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Marks';
        $data['exam_id']    = "";
        $data['class_id']   = "";
        $data['section_id'] = "";

        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $section_id         = $this->input->post('group_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $result_subjects    = $this->teachersubject_model->getSubjectByClsandSection($class_id, $section_id);

            $getDaysnameList         = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $final_array             = array();
            if (!empty($result_subjects)) {
                foreach ($result_subjects as $subject_k => $subject_v) {
                    $result_array = array();
                    foreach ($getDaysnameList as $day_key => $day_value) {
                        $where_array = array(
                            'teacher_subject_id' => $subject_v['id'],
                            'day_name'           => $day_value,
                        );
                        $result = $this->timetable_model->get($where_array);
                        if (!empty($result)) {
                            $obj                      = new stdClass();
                            $obj->status              = "Yes";
                            $obj->start_time          = $result[0]['start_time'];
                            $obj->end_time            = $result[0]['end_time'];
                            $obj->room_no             = $result[0]['room_no'];
                            $result_array[$day_value] = $obj;
                        } else {
                            $obj                      = new stdClass();
                            $obj->status              = "No";
                            $obj->start_time          = "N/A";
                            $obj->end_time            = "N/A";
                            $obj->room_no             = "N/A";
                            $result_array[$day_value] = $obj;
                        }
                    }
                    $final_array[$subject_v['name']] = $result_array;
                }
            }

            $data['result_array'] = $final_array;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableList', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function mytimetable()
    {
        if (!$this->rbac->hasPrivilege('teachers_time_table', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'My Timetable';
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable/mytimetable');
        $my_role  = $this->customlib->getStaffRole();
        $role     = json_decode($my_role);
        $is_admin = false;

        if ($role->id != "2") {
            $staff_list         = $this->staff_model->getEmployee('2');
            $data['staff_list'] = $staff_list;
            $is_admin           = true;
        }

        $staff_id          = $this->customlib->getStaffID();
        $data['timetable'] = array();
        $days              = $this->customlib->getDaysname();

        foreach ($days as $day_key => $day_value) {
            $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
        }

        $this->load->view('layout/header', $data);
        if ($is_admin) {
            $this->load->view('admin/timetable/admintimetable', $data);
        } else {
            $data['staff_id']=$staff_id;
            $this->load->view('admin/timetable/mytimetable', $data);
        }
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('mark_list');
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/timetableShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        $data['title'] = 'Mark List';
        $this->mark_model->remove($id);
        redirect('admin/timetable/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');

        $session            = $this->setting_model->getCurrentSession();
        $data['title']      = 'Exam Schedule';
        $data['subject_id'] = "";
        $data['class_id']   = "";
        $data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist']   = $exam;
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();
        $staff                   = $this->staff_model->getStaffbyrole(2);
        $data['staff']           = $staff;
        $data['subject']         = array();
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required|xss_clean');
        $class_id         = $this->input->post('class_id');
        $section_id       = $this->input->post('section_id');
        $subject_group_id = $this->input->post('subject_group_id');

        $data['class_id']         = $class_id;
        $data['section_id']       = $section_id;
        $data['subject_group_id'] = $subject_group_id;

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $getDaysnameList         = $this->customlib->getDaysname();
            $data['getDaysnameList'] = $getDaysnameList;
            $subject                 = $this->subjectgroup_model->getGroupsubjects($subject_group_id);
            $data['subject']         = $subject;
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableCreate', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function classreport()
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'Academics/timetable');
        $session                 = $this->setting_model->getCurrentSession();
        $data['title']           = 'Exam Schedule';
        $data['subject_id']      = "";
        $data['class_id']        = "";
        $data['section_id']      = "";
        $exam                    = $this->exam_model->get();
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['examlist']        = $exam;
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $staff                   = $this->staff_model->getStaffbyrole(2);
        $data['staff']           = $staff;
        $data['subject']         = array();

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            if (isset($_POST['search'])) {

                $class_id    = $this->input->post('class_id');
                $section_id  = $this->input->post('section_id');
                $days        = $this->customlib->getDaysname();
                $days_record = array();
                foreach ($days as $day_key => $day_value) {
                    $class_id              = $this->input->post('class_id');
                    $section_id            = $this->input->post('section_id');
                    $days_record[$day_key] = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day_key);
                }

                $data['timetable'] = $days_record;
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/timetable/classreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('class_timetable', 'can_edit')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('edit_mark');
        $data['id']    = $id;
        $mark          = $this->mark_model->get($id);
        $data['mark']  = $mark;
        $this->form_validation->set_rules('name', $this->lang->line('mark'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/timetable/timetableEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
                'note' => $this->input->post('note'),
            );
            $this->mark_model->add($data);
            $this->session->set_flashdata('msg', '<div mark="alert alert-success text-center">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/timetable/index');
        }
    }

    public function getBydategroupclasssection()
    {
        $data                = array();
        $data['total_count'] = 1;
        $day                 = $this->input->post('day');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $subject_group_id    = $this->input->post('subject_group_id');
        $subject             = $this->subjectgroup_model->getGroupsubjects($subject_group_id);

        $prev_record = $this->subjecttimetable_model->getBySubjectGroupDayClassSection($subject_group_id, $day, $class_id, $section_id);

        $staff         = $this->staff_model->getStaffbyrole(2);
        $data['staff'] = $staff;
        if (empty($prev_record)) {
            $data['prev_record'] = array();
        } else {
            $data['total_count'] = count($prev_record);
            $data['prev_record'] = $prev_record;
        }
        $data['subject']          = $subject;
        $data['day']              = $day;
        $data['class_id']         = $class_id;
        $data['section_id']       = $section_id;
        $data['subject_group_id'] = $subject_group_id;

        $data['html'] = $this->load->view('admin/timetable/addrow', $data, true);
        echo json_encode($data);
    }

    public function savegroup()
    {
        $json = array();
        $this->form_validation->set_rules('subject_group_id', $this->lang->line('subject_group'), 'trim|required');
        $this->form_validation->set_rules('day', $this->lang->line('day'), 'trim|required');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required');
        $total_rows = $this->input->post('total_row');

        if (isset($total_rows) && !empty($total_rows)) {

            foreach ($this->input->post('total_row') as $key => $value) {
                $this->form_validation->set_rules('subject_' . $value, 'Subject', 'trim|required');
                $this->form_validation->set_rules('staff_' . $value, 'Staff', 'trim|required');
                $this->form_validation->set_rules('time_from_' . $value, 'Time From', 'trim|required');
                $this->form_validation->set_rules('time_to_' . $value, 'Time To', 'trim|required');
                $this->form_validation->set_rules('room_no_' . $value, 'Room No', 'trim|required');
            }
        }

        if (!$this->form_validation->run()) {
            $json = array(
                'subject_group_id' => form_error('subject_group_id', '<li>', '</li>'),
                'section_id'       => form_error('section_id', '<li>', '</li>'),
                'day'              => form_error('day', '<li>', '</li>'),
                'class_id'         => form_error('class_id', '<li>', '</li>'),
                'rows'             => form_error('rows', '<li>', '</li>'),
            );
            if (isset($total_rows) && !empty($total_rows)) {
                foreach ($this->input->post('total_row') as $key => $value) {
                    $json['subject_' . $value]   = form_error('subject_' . $value, '<li>', '</li>');
                    $json['staff_' . $value]     = form_error('staff_' . $value, '<li>', '</li>');
                    $json['time_from_' . $value] = form_error('time_from_' . $value, '<li>', '</li>');
                    $json['time_to_' . $value]   = form_error('time_to_' . $value, '<li>', '</li>');
                    $json['room_no_' . $value]   = form_error('room_no_' . $value, '<li>', '</li>');
                }
            }

            $json_array = array('status' => '0', 'error' => $json);
        } else {
            $day              = $this->input->post('day');
            $class_id         = $this->input->post('class_id');
            $section_id       = $this->input->post('section_id');
            $subject_group_id = $this->input->post('subject_group_id');
            $total_row        = $this->input->post('total_row');
            $session          = $this->setting_model->getCurrentSession();
            $insert_array     = array();
            $update_array     = array();
            $old_input        = array();
            $prev_array       = $this->input->post('prev_array');
            if (isset($prev_array)) {
                foreach ($prev_array as $prev_arr_key => $prev_arr_value) {
                    $old_input[] = $prev_arr_value;
                }
            }
            $preserve_array = array();
            if (isset($total_row)) {
                foreach ($total_row as $total_key => $total_value) {
                    $prev_id = $this->input->post('prev_id_' . $total_value);

                    if ($prev_id == 0) {
                        $insert_array[] = array(
                            'day'                      => $day,
                            'class_id'                 => $class_id,
                            'section_id'               => $section_id,
                            'subject_group_id'         => $subject_group_id,
                            'subject_group_subject_id' => $this->input->post('subject_' . $total_value),
                            'staff_id'                 => $this->input->post('staff_' . $total_value),
                            'time_from'                => $this->input->post('time_from_' . $total_value),
                            'time_to'                  => $this->input->post('time_to_' . $total_value),
                            'start_time'               => $this->customlib->timeFormat($this->input->post('time_from_' . $total_value), true),
                            'end_time'                 => $this->customlib->timeFormat($this->input->post('time_to_' . $total_value), true),
                            'room_no'                  => $this->input->post('room_no_' . $total_value),
                            'session_id'               => $session,
                        );
                    } else {
                        $preserve_array[] = $prev_id;
                        $update_array[]   = array(
                            'id'                       => $prev_id,
                            'day'                      => $day,
                            'class_id'                 => $class_id,
                            'section_id'               => $section_id,
                            'subject_group_id'         => $subject_group_id,
                            'subject_group_subject_id' => $this->input->post('subject_' . $total_value),
                            'staff_id'                 => $this->input->post('staff_' . $total_value),
                            'time_from'                => $this->input->post('time_from_' . $total_value),
                            'time_to'                  => $this->input->post('time_to_' . $total_value),
                            'start_time'               => $this->customlib->timeFormat($this->input->post('time_from_' . $total_value), true),
                            'end_time'                 => $this->customlib->timeFormat($this->input->post('time_to_' . $total_value), true),
                            'room_no'                  => $this->input->post('room_no_' . $total_value),
                            'session_id'               => $session,
                        );
                    }
                }
            }

            $delete_array = array_diff($old_input, $preserve_array);

            $result       = $this->subjecttimetable_model->add($delete_array, $insert_array, $update_array);
            if ($result) {
                $json_array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $json_array = array('status' => '2', 'error' => '', 'message' => $this->lang->line('something_went_wrong'));
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }

    public function getteachertimetable()
    {
        $json = array();
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('teacher', $this->lang->line('teacher'), 'trim|required');

        if (!$this->form_validation->run()) {
            $json = array(
                'teacher' => form_error('teacher'),
            );

            $json_array = array('status' => '0', 'error' => $json);
        } else {
            $staff_id          = $this->input->post('teacher');
            $data['timetable'] = array();
            $data['staff_id'] = $staff_id;

            $days              = $this->customlib->getDaysname();

            foreach ($days as $day_key => $day_value) {
                $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
            }

            $timetable_page = $this->load->view('admin/timetable/_partialgetteachertimetable', $data, true);
            $json_array = array('status' => '1', 'error' => '', 'message' => $timetable_page);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }


    public function printclasstimetable()
    {


        $class_id    = $this->input->post('class_id');
        $section_id  = $this->input->post('section_id');
        $days        = $this->customlib->getDaysname();
        $class_section=$this->section_model->getClassAndSectionNameByClassIDSectionID($class_id, $section_id);
        $data['class_section']=$class_section;
        $days_record = array();
        foreach ($days as $day_key => $day_value) {

            $days_record[$day_key] = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day_key);
        }
        $data['timetable']=$days_record;
        $timetable_page = $this->load->view('admin/timetable/_printclasstimetable', $data, true);
        $json_array = array('status' => '1', 'error' => '', 'page' => $timetable_page);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }


    public function printteachertimetable()
    {

        $staff_id          = $this->input->post('staff_id');
        $staff = $this->staff_model->get($staff_id);
        $data['staff'] = $staff;
        $data['timetable'] = array();
        $days              = $this->customlib->getDaysname();

        foreach ($days as $day_key => $day_value) {
            $data['timetable'][$day_value] = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_key);
        }

        $timetable_page = $this->load->view('admin/timetable/_printteachertimetable', $data, true);
        $json_array = array('status' => '1', 'error' => '', 'page' => $timetable_page);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($json_array));
    }
}
