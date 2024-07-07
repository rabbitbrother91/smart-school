<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Student_helper
{

    private $CI;
    private $sch_setting;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->library('form_validation');
        $this->CI->load->library('customlib');
        $this->CI->load->model('setting_model');
        $this->CI->load->model('student_model');
        $this->sch_setting = $this->CI->setting_model->getSetting();
    }

    public function get_students_validation($class_id, $section_id, $category_id, $gender, $rte, $srch_type)
    {
        if ($srch_type == 'search_filter') {
            $this->CI->form_validation->set_rules('class_id', $this->CI->lang->line('class'), 'trim|required|xss_clean');
            if ($this->CI->form_validation->run() == true) {
                $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id, 'category_id' => $category_id, 'gender' => $gender, 'rte' => $rte);
                $array  = array('status' => 1, 'error' => '', 'params' => $params);
            } else {
                $error             = array();
                $error['class_id'] = $this->CI->form_validation->error('class_id');
                $array             = array('status' => 0, 'error' => $error);
            }
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
        }
        return $array;
    }
}
