<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Content extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('enc_lib');
        $this->load->model(array('contenttype_model', 'uploadcontent_model', 'sharecontent_model'));
    }

    function list() {

        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'content/index');
        $data = array();
        $this->load->view('layout/student/header');
        $this->load->view('user/content/list', $data);
        $this->load->view('layout/student/footer');

    }

    public function getsharelist()
    {
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $role                  = $this->customlib->getUserRole();
        if ($role == "student") {

            $m = $this->sharecontent_model->getStudentsharelist($this->customlib->getStudentSessionUserID(), $student_current_class->class_id, $student_current_class->section_id);

        } elseif ($role == "parent") {

            $m = $this->sharecontent_model->getParentsharelist($this->customlib->getUsersID(), $student_current_class->class_id, $student_current_class->section_id);
        }
        
        $superadmin_visible =    $this->Setting_model->get();        
        $superadmin_restriction =   $superadmin_visible[0]['superadmin_restriction'];
        
        $m = json_decode($m);

        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $viewbtn   = '';
                $title     = $value->title;
                $row       = array();
                $row[]     = $title;
                $viewbtn   = "<a href='" . site_url('user/content/view/') . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('view') . "'><i class='fa fa-eye'></i></a>";
                $row[]     = $this->customlib->dateformat($value->share_date);
                $row[]     = $this->customlib->dateformat($value->valid_upto);
                
                if($superadmin_restriction == 'disabled' && $value->role_id == 7){
                        $row[]     =  '';
                }else{
                        $row[]     = $value->name .' '. $value->surname . ' (' . $value->employee_id . ')';
                }               
                
                $row[]     = $viewbtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function view($id)
    {
        $data['title']      = 'Upload Content';
        $data['title_list'] = 'Upload Content List';
        $data['content']    = $this->sharecontent_model->getShareContentWithDocuments($id);
        $superadmin_visible =    $this->Setting_model->get();        
        $data['superadmin_restriction'] =   $superadmin_visible[0]['superadmin_restriction'];
        
        $this->load->view('layout/student/header');
        $this->load->view('user/content/view', $data);
        $this->load->view('layout/student/footer');
    }

    public function index()
    {
        $data['title']      = 'Upload Content';
        $data['title_list'] = 'Upload Content List';
        $list               = $this->content_model->get();
        $data['list']       = $list;
        $ght                = $this->customlib->getcontenttype();
        $data['ght']        = $ght;
        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $this->load->view('layout/student/header');
        $this->load->view('user/content/createcontent', $data);
        $this->load->view('layout/student/footer');
    }

    public function download($file)
    {
        $this->media_storage->filedownload($this->uri->segment(7), "./uploads/school_content/material");

    }

    public function assignment()
    {
        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'content/assignment');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student               = $this->student_model->get($student_id);
        $data['title_list']    = 'List of Assignment';
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $list                  = $this->content_model->getListByCategoryforUser($student_current_class->class_id, $student_current_class->section_id, "assignments");
        $data['list']          = $list;
        $this->load->view('layout/student/header');
        $this->load->view('user/content/assignment', $data);
        $this->load->view('layout/student/footer');
    }

    public function studymaterial()
    {
        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'content/studymaterial');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student               = $this->student_model->get($student_id);
        $data['title_list']    = 'List of Assignment';
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $list                  = $this->content_model->getListByCategoryforUser($student_current_class->class_id, $student_current_class->section_id, "study_material");
        $data['list']          = $list;
        $this->load->view('layout/student/header');
        $this->load->view('user/content/studymaterial', $data);
        $this->load->view('layout/student/footer');
    }

    public function syllabus()
    {
        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'content/syllabus');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student               = $this->student_model->get($student_id);
        $data['title_list']    = 'List of Syllabus';
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $list                  = $this->content_model->getListByCategoryforUser($student_current_class->class_id, $student_current_class->section_id, "syllabus");
        $data['list']          = $list;
        $this->load->view('layout/student/header');
        $this->load->view('user/content/syllabus', $data);
        $this->load->view('layout/student/footer');
    }

    public function other()
    {
        $this->session->set_userdata('top_menu', 'Downloads');
        $this->session->set_userdata('sub_menu', 'content/other');
        $student_id            = $this->customlib->getStudentSessionUserID();
        $student               = $this->student_model->get($student_id);
        $data['title_list']    = 'List of Other Download';
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $list                  = $this->content_model->getListByCategoryforUser($student_current_class->class_id, $student_current_class->section_id, "other_download");
        $data['list']          = $list;
        $this->load->view('layout/student/header');
        $this->load->view('user/content/other', $data);
        $this->load->view('layout/student/footer');
    }

}
