<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendence extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getdaysubattendence()
    {
        $date = $this->input->post('date');
        $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));

        $attendencetypes = $this->attendencetype_model->get();
        $timestamp       = strtotime($date);
        $day             = date('l', $timestamp);

        $student_id                    = $this->customlib->getStudentSessionUserID();
        $student                       = $this->student_model->get($student_id);
        $student_current_class         = $this->customlib->getStudentCurrentClsSection();
        $student_session_id            = $student_current_class->student_session_id;
        $class_id                      = $student_current_class->class_id;
        $section_id                    = $student_current_class->section_id;
        $result['attendencetypeslist'] = $attendencetypes;
        $result['attendence']          = $this->studentsubjectattendence_model->studentAttendanceByDate($class_id, $section_id, $day, $date, $student_session_id);
        $result_page                   = $this->load->view('user/attendence/_getdaysubattendence', $result, true);
        echo json_encode(array('status' => 1, 'result_page' => $result_page));
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Attendence');
        $this->session->set_userdata('sub_menu', 'book/index');
        $data['title']      = 'Attendence List';
        $result             = array();
        $data['resultList'] = $result;
        $setting_result     = $this->setting_model->get();

        $setting_result = ($setting_result[0]);
        $setting_result['attendence_type'];
        $session                    = $this->session->userdata('student');
        $data['language_shortcode'] = $this->language_model->get($session['language']['lang_id']);
        $this->load->view('layout/student/header');
        if ($setting_result['attendence_type']) {
            $this->load->view('user/attendence/attendenceSubject', $data);
        } else {
            $this->load->view('user/attendence/attendenceIndex', $data);
        }

        $this->load->view('layout/student/footer');
    }  

    public function getAttendence()
    {
        $date['start']         = $this->input->get('start');
        $date['end']           = $this->input->get('end');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student               = $this->student_model->get($student_id);
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;

        $result = array();

        $student_attendence_result = $this->attendencetype_model->getStudentAttendenceRange($date, $student_session_id);

        if (!empty($student_attendence_result)) {
            foreach ($student_attendence_result as $key => $student_attendence) {

                $s           = array();
                $s['date']   = $student_attendence->date;
                $s['badge']  = false;
                $s['footer'] = "Extra information";
                $type        = $student_attendence->type;
                $s['title']  = $type;

                if ($type == 'Present') {

                    $eventdata[] = array('title' => $this->lang->line('present'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#27ab00',
                        'borderColor'                => '#27ab00',
                        'event_type'                 => 'Present',
                    );
                } else if ($type == 'Absent') {
                    $eventdata[] = array('title' => $this->lang->line('absent'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#fa2601',
                        'borderColor'                => '#fa2601',
                        'event_type'                 => 'Absent',
                    );
                } else if ($type == 'Late') {
                    $eventdata[] = array('title' => $this->lang->line('late'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#ffeb00',
                        'borderColor'                => '#ffeb00',
                        'event_type'                 => 'Late',
                    );
                } else if ($type == 'Late with excuse') {
                    $eventdata[] = array('title' => $this->lang->line('late_with_excuse'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#ffeb00',
                        'borderColor'                => '#ffeb00',
                        'event_type'                 => 'Late with excuse',
                    );
                } else if ($type == 'Holiday') {
                    $eventdata[] = array('title' => $this->lang->line('holiday'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#a7a7a7',
                        'borderColor'                => '#a7a7a7',
                        'event_type'                 => 'Holiday',
                    );
                } else if ($type == 'Half Day') {
                    $eventdata[] = array('title' => $this->lang->line('half_day'),
                        'start'                      => $s['date'],
                        'end'                        => $s['date'],
                        'description'                => $student_attendence->remark,
                        'id'                         => 0,
                        'backgroundColor'            => '#fa8a00',
                        'borderColor'                => '#fa8a00',
                        'event_type'                 => 'Half Day',
                    );
                }
                $array[] = $s;
            }
        }

        echo json_encode($eventdata);
    }

    public function getevents()
    {
        $userdata = $this->customlib->getUserData();
        $result   = $this->calendar_model->getEvents();
        if (!empty($result)) {

            foreach ($result as $key => $value) {

                $event_type = $value["event_type"];

                if ($event_type == 'private') {

                    $event_for = $userdata["id"];
                } else if ($event_type == 'sameforall') {

                    $event_for = $userdata["role_id"];
                } else if ($event_type == 'public') {

                    $event_for = "0";
                } else if ($event_type == 'task') {

                    $event_for = $userdata["id"];
                }
                if ($event_type == 'task') {

                    if (($event_for == $value["event_for"]) && ($value["role_id"] == $userdata["role_id"])) {
                        $eventdata[] = array('title' => $value["event_title"],
                            'start'                      => $value["start_date"],
                            'end'                        => $value["end_date"],
                            'description'                => $value["event_description"],
                            'id'                         => $value["id"],
                            'backgroundColor'            => $value["event_color"],
                            'borderColor'                => $value["event_color"],
                            'event_type'                 => $value["event_type"],
                        );
                    }
                } else {
                    if ($event_for == $value["event_for"]) {
                        $eventdata[] = array('title' => $value["event_title"],
                            'start'                      => $value["start_date"],
                            'end'                        => $value["end_date"],
                            'description'                => $value["event_description"],
                            'id'                         => $value["id"],
                            'backgroundColor'            => $value["event_color"],
                            'borderColor'                => $value["event_color"],
                            'event_type'                 => $value["event_type"],
                        );
                    } elseif ($event_type == 'protected') {

                    }

                }
            }

            echo json_encode($eventdata);
        }
    }

}
