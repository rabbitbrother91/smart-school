<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Calendar extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->library('customlib');
        $this->load->model("calendar_model");
        $this->load->library('pagination');
    }

    public function index()
    {
        $userdata                  = $this->customlib->getLoggedInUserData();
        $event_colors              = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"]      = $event_colors;
        $config['base_url']        = base_url() . 'user/calendar/index';
        $config['total_rows']      = $this->calendar_model->countrows($userdata["id"], 0);
        $config['per_page']        = 10;
        $config["full_tag_open"]   = '<ul class="pagination">';
        $config["full_tag_close"]  = '</ul>';
        $config["first_link"]      = "&laquo;";
        $config["first_tag_open"]  = "<li>";
        $config["first_tag_close"] = "</li>";
        $config["last_link"]       = "&raquo;";
        $config["last_tag_open"]   = "<li>";
        $config["last_tag_close"]  = "</li>";
        $config['next_link']       = '&gt;';
        $config['next_tag_open']   = '<li>';
        $config['next_tag_close']  = '<li>';
        $config['prev_link']       = '&lt;';
        $config['prev_tag_open']   = '<li>';
        $config['prev_tag_close']  = '<li>';
        $config['cur_tag_open']    = '<li class="active"><a href="#">';
        $config['cur_tag_close']   = '</a></li>';
        $config['num_tag_open']    = '<li>';
        $config['num_tag_close']   = '</li>';
        $this->pagination->initialize($config);
        $tasklist         = $this->calendar_model->getTask($userdata["id"], null, 10, $this->uri->segment(4));
        $data["tasklist"] = $tasklist;
        $data["title"]    = $this->lang->line("event_calendar");
        $this->load->view("layout/student/header.php");
        $this->load->view("user/eventcalendar.php", $data);
        $this->load->view("layout/student/footer.php");
    }

    public function getevents()
    {
        $userdata = $this->customlib->getLoggedInUserData();
        $result   = $this->calendar_model->getStudentEvents();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $event_type = $value["event_type"];
                $status = 1;
                if ($event_type == "task") {
                    $event_for = $userdata["id"];
                    if ($event_for == $value["event_for"]) {
                        $status = 1;
                    } else {
                        $status = 0;
                    }
                }

                if ($status == 1) {
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
            }

            echo json_encode($eventdata);
        }
    }

    public function addtodo()
    {
        $this->form_validation->set_rules('task_title', $this->lang->line('task_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('task_date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'task_title' => form_error('task_title'),
                'task_date'  => form_error('task_date'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $userdata          = $this->customlib->getLoggedInUserData();
            $event_title       = $this->input->post("task_title");
            $event_description = '';
            $event_type        = 'task';
            $event_color       = '#000';
            $date              = $this->input->post('task_date');
            $start_date        = date('Y-m-d H:i:s', $this->customlib->datetostrtotime($this->input->post('task_date')));
            $eventid           = $this->input->post("eventid");
            if (!empty($eventid)) {

                $eventdata = array('event_title' => $event_title,
                    'event_description'              => $event_description,
                    'start_date'                     => $start_date,
                    'end_date'                       => $start_date,
                    'event_type'                     => $event_type,
                    'event_color'                    => $event_color,
                    'event_for'                      => $userdata["id"],
                    'id'                             => $eventid,
                );
                $msg = $this->lang->line('update_message');
            } else {
                $eventdata = array('event_title' => $event_title,
                    'event_description'              => $event_description,
                    'start_date'                     => $start_date,
                    'end_date'                       => $start_date,
                    'event_type'                     => $event_type,
                    'event_color'                    => $event_color,
                    'is_active'                      => "no",
                    'event_for'                      => $userdata["id"],
                );
                $msg = $this->lang->line('success_message');
            }
            $this->calendar_model->saveEvent($eventdata);
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function gettaskbyid($id)
    {
        $result = $this->calendar_model->getEvents($id);
        echo json_encode($result);
    }

    public function markcomplete($id)
    {
        $status = $this->input->post("active");
        $eventdata = array('is_active' => $status, 'id' => $id);
        if (!empty($id)) {
            $this->calendar_model->saveEvent($eventdata);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('mark_completed_successfully'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('cannot_mark_complete_this_event'));
        }
        echo json_encode($array);
    }

    public function delete_event($id)
    {
        if (!empty($id)) {
            $result = $this->calendar_model->deleteEvent($id);
            $array  = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('delete_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('cannot_delete_this_event'));
        }
        echo json_encode($array);
    }

}
