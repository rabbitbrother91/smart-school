<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Video_tutorial extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library('imageResize');
    }

    public function index()
    {

        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'video_tutorial/index');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $data['class_id']      = $student_current_class->class_id;
        $data['section_id']    = $student_current_class->section_id;
        $this->load->view('layout/student/header');
        $this->load->view('user/video_tutorial/index', $data);
        $this->load->view('layout/student/footer');
    }

    public function getPage($page)
    {
        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $superadmin_visible =    $this->Setting_model->get();
        
        $superadmin_restriction =   $superadmin_visible[0]['superadmin_restriction'];
       
        $this->load->library("pagination");
        $config             = array();
        $config["base_url"] = "#";
        $config["total_rows"]       = count($this->video_tutorial_model->getvideotutorial('', '', $class_id, $section_id));
        $config["per_page"]         = 30;
        $config["uri_segment"]      = 5;
        $config["use_page_numbers"] = true;
        $config["full_tag_open"]    = '<ul class="pagination">';
        $config["full_tag_close"]   = '</ul>';
        $config["first_tag_open"]   = '<li>';
        $config["first_tag_close"]  = '</li>';
        $config["last_tag_open"]    = '<li>';
        $config["last_tag_close"]   = '</li>';
        $config['next_link']        = $this->lang->line('next');
        $config['next_tag_open']    = '<li class="next page">';
        $config['next_tag_close']   = '</li>';
        $config['prev_link']        = $this->lang->line('previous');
        $config['prev_tag_open']    = '<li class="prev page">';
        $config['prev_tag_close']   = '</li>';
        $config["cur_tag_open"]     = "<li class='active'><a href='#'>";
        $config["cur_tag_close"]    = "</a></li>";
        $config["num_tag_open"]     = "<li>";
        $config["num_tag_close"]    = "</li>";
        $config["num_links"]        = 1;  
        

        $this->pagination->initialize($config);

        if ($page == 'undefined') {
            $page = 1;
        }

        $start = ($page - 1) * $config["per_page"];

        $result      = $this->video_tutorial_model->getvideotutorial($config["per_page"], $start, $class_id, $section_id);
        $img_data    = array();
        $check_empty = 0;
        if (!empty($result)) {
            $check_empty = 1;
            foreach ($result as $res_key => $res_value) {
                $div        = $this->genratediv($res_value,$superadmin_restriction);
                $img_data[] = $div;
            }
        }

        $output = array(
            'pagination_link' => $this->pagination->create_links(),
            'result_status'   => $check_empty,
            'result'          => $img_data,
        );
        echo json_encode($output);
    }

    public function genratediv($result,$superadmin_restriction)
    {
        $file     = base_url() . $result['thumb_path'] . $result['thumb_name'] . img_time();
        $file_src = $result['video_link'];

        $employee_id = '';
        if ($result['staff_employee_id'] != "") {
            $employee_id = ' (' . $result['staff_employee_id'] . ')';
        }    
        
        if($superadmin_restriction == 'disabled' && $result['role_id'] == 7){
                $staff_name =  ''; 
        }else{
                $staff_name =   $result['staff_name'] . ' ' . $result['staff_surname'] . $employee_id ;
        }

        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_" . $result['id'] . "'>";
        $output .= "<div class='fadeoverlay'>";
        $output .= "<div class='fadeheight'>";
        $output .= "<img class='' data-fid='" . $result['id'] . "' data-content_name='" . $result['img_name'] . "' src='" . $file . "'>";
        $output .= "</div>";
        $output .= "<i class='fa fa-youtube-play videoicon'></i>";
        $output .= "<div class='overlay3'>";
        $output .= "<a href='#' ata-toggle='tooltip' title =" . $this->lang->line('view') . " class='uploadcheckbtn' data-backdrop='static' data-keyboard='false' data-record_id='" . $result['id'] . "' data-toggle='modal' data-target='#detail' 
        
        data-role_name='" . $staff_name . "' 
        
        data-image='" . $file . "' data-source='" . $file_src . "' data-title='" . $result['title'] . "' data-description='" . $result['description'] . "'><i class='fa fa-navicon'></i></a>";
        $output .= "<p class='processing'>" . $this->lang->line('processing') . "</p>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "<p class='fadeoverlay-para'>" . $result['title'] . "</p>";
        $output .= "</div>";

        return $output;
    }
}
