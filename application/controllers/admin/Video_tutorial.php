<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Video_tutorial extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('video_tutorial', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'download_center');
        $this->session->set_userdata('sub_menu', 'video_tutorial/index');
        $data['Video_tutorial_list'] = $this->video_tutorial_model->get();
        $data['classlist']           = $this->class_model->get();
        $this->load->view('layout/header');
        $this->load->view('admin/video_tutorial/index', $data);
        $this->load->view('layout/footer');
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('video_tutorial', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id[]', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('video_link', $this->lang->line('video_link'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id[]'),
                'title'      => form_error('title'),
                'video_link' => form_error('video_link'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $sectionarray = $this->input->post('section_id');

            $url     = $this->input->post('video_link');
            $youtube = "https://www.youtube.com/oembed?url=" . $url . "&format=json";
            $curl    = curl_init($youtube);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return   = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode == 200) {
                $img_array = array();

                $dir_path               = "uploads/video_tutorial/youtube_video/";
                $thumb_path             = "uploads/video_tutorial/youtube_video/thumb/";
                $config['thumb_path']   = $thumb_path;
                $config['dir_path']     = $dir_path;
                $config['thumb_width']  = 300;
                $config['thumb_height'] = 200;

                $this->load->library('imageResize', $config);

                $upload_response = $this->imageresize->resizeVideoImg($return);

                if ($upload_response) {
                    $upload_response = json_decode($upload_response);

                    $data = array(
                        'video_link'  => $url,
                        'title'       => $this->input->post('title'),
                        'vid_title'   => $upload_response->vid_title,
                        'img_name'    => $upload_response->store_name,
                        'thumb_name'  => $upload_response->thumb_name,
                        'thumb_path'  => $upload_response->thumb_path,
                        'dir_path'    => $upload_response->dir_path,
                        'description' => $this->input->post('description'),
                        'created_by'  => $this->customlib->getStaffID(),
                        'created_at'  => date('Y-m-d H:i:s'),
                    );

                    $insert_id = $this->video_tutorial_model->add($data);

                    if (!empty($sectionarray)) {
                        foreach ($sectionarray as $sectionarray_value) {
                            $sectiondata = array(
                                'video_tutorial_id' => $insert_id,
                                'class_section_id'  => $sectionarray_value,
                                'created_date'      => date('Y-m-d'),
                            );
                            // This is used to add section by class
                            $this->video_tutorial_model->addsections($sectiondata);
                        }
                    }

                    $msg   = $this->lang->line('success_message');
                    $array = array('status' => 'success', 'error' => '', 'message' => $msg);
                } else {
                    $error_msg = array(
                        'video_link' => $this->lang->line('please_try_again'),
                    );

                    $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
                }
            } else {
                $msg = array(
                    'video_link_error' => $this->lang->line('please_fill_correct_video_link'),
                );
                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');

            }
        }
        echo json_encode($array);
    }

    public function get()
    {
        $videotutorialid           = $this->input->post('videotutorialid');
        $videotutoriallist         = $this->video_tutorial_model->get($videotutorialid);
        $data['videotutoriallist'] = $videotutoriallist;
        $data['classlist']         = $this->class_model->get();
        $data['classid']           = $this->video_tutorial_model->getclassid($videotutorialid);
        $page                      = $this->load->view('admin/video_tutorial/edit', $data, true);
        echo json_encode(array('page' => $page));
    }

    public function edit()
    {
        if (!$this->rbac->hasPrivilege('video_tutorial', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('edit_section_id[]', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('video_link', $this->lang->line('video_link'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'class_id'        => form_error('class_id'),
                'edit_section_id' => form_error('edit_section_id[]'),
                'title'           => form_error('title'),
                'video_link'      => form_error('video_link'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $sectionarray = $this->input->post('edit_section_id');
            $id=$this->input->post('id');
            $video_directory=$this->video_tutorial_model->get($id);
     
            $this->media_storage->filedelete($video_directory->img_name, $video_directory->dir_path);
            $this->media_storage->filedelete($video_directory->thumb_name, $video_directory->thumb_path);


            //================
            $img_array = array();

            $dir_path               = "./uploads/video_tutorial/youtube_video/";
            $thumb_path             = "./uploads/video_tutorial/youtube_video/thumb/";
            $config['thumb_path']   = $thumb_path;
            $config['dir_path']     = $dir_path;
            $config['thumb_width']  = 300;
            $config['thumb_height'] = 200;

            $this->load->library('imageResize', $config);

            $url     = $this->input->post('video_link');
            $youtube = "https://www.youtube.com/oembed?url=" . $url . "&format=json";
            $curl    = curl_init($youtube);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $return   = curl_exec($curl);
            $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpcode == 200) {

                $upload_response = $this->imageresize->resizeVideoImg($return);

                if ($upload_response) {
                    $upload_response = json_decode($upload_response);

                    $data = array(
                        'id'          => $id,
                        'video_link'  => $url,
                        'title'       => $this->input->post('title'),
                        'vid_title'   => $upload_response->vid_title,
                        'img_name'    => $upload_response->store_name,
                        'thumb_name'  => $upload_response->thumb_name,
                        'thumb_path'  => $upload_response->thumb_path,
                        'dir_path'    => $upload_response->dir_path,
                        'description' => $this->input->post('description'),
                        'created_by'  => $this->customlib->getStaffID(),
                        'created_at'  => date('Y-m-d H:i:s'),
                    );
                  
                    $this->video_tutorial_model->add($data);

                }
            }
            //==================

            $class_sections_data = $this->video_tutorial_model->selectedsection($this->input->post('id'));

            if (!empty($class_sections_data)) {
                foreach ($class_sections_data as $key => $value) {
                    $this->video_tutorial_model->delete($this->input->post('id'), $value['class_section_id']);
                }
            }

            if (!empty($sectionarray)) {
                foreach ($sectionarray as $sectionarray_value) {
                    $sectiondata = array(
                        'video_tutorial_id' => $this->input->post('id'),
                        'class_section_id'  => $sectionarray_value,
                        'created_date'      => date('Y-m-d'),
                    );
                    // This is used to add section by class
                    $this->video_tutorial_model->addsections($sectiondata);
                }
            }

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function getPage($page)
    {
        $keyword          = $this->input->get('keyword');
        $class_id         = $this->input->get('class_id');
        $class_section_id = $this->input->get('class_section_id');

        $this->load->library("pagination");
        $config    = array();
        $count_all = $this->video_tutorial_model->count_all($keyword, $class_id, $class_section_id);

        $config['base_url']         = "#";
        $config['per_page']         = 30;
        $config['num_links']        = 5;
        $config['total_rows']       = $count_all;
        $config['full_tag_open']    = '<ul class="pagination">';
        $config['full_tag_close']   = '</ul>';
        $config["first_tag_open"]   = '<li>';
        $config["first_tag_close"]  = '</li>';
        $config["last_tag_open"]    = '<li>';
        $config["last_tag_close"]   = '</li>';
        $config['use_page_numbers'] = true;
        $config['next_link']        = $this->lang->line('next');
        $config['next_tag_open']    = '<li class="next page">';
        $config['next_tag_close']   = '</li>';
        $config['prev_link']        = $this->lang->line('previous');
        $config['prev_tag_open']    = '<li class="prev page">';
        $config['prev_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="active pagination-disabled"><a href="" >';
        $config['cur_tag_close']    = '</a></li>';
        $config['num_tag_open']     = '<li class="page">';
        $config['num_tag_close']    = '</li>';

        $this->pagination->initialize($config);

        if ($page == 'undefined') {
            $page = 1;
        }

        $start = ($page - 1) * $config["per_page"];

        $result = $this->video_tutorial_model->fetch_details($config["per_page"], $start, $keyword, $class_id, $class_section_id);

        $img_data    = array();
        $check_empty = 0;
        if (!empty($result)) {
            $check_empty = 1;
            foreach ($result as $res_key => $res_value) {

                $div        = $this->genratediv($res_value);
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

    public function genratediv($result)
    {
        $sectionlist = $this->video_tutorial_model->selectedsection($result->id);
        foreach ($sectionlist as $key => $sectionlist_value) {
            $result->sectionlist[$key] = $sectionlist_value['section'];
        }

        $employee_id = '';
        if ($result->staff_employee_id != "") {
            $employee_id = ' (' . $result->staff_employee_id . ')';
        }

        $sectionlist = implode(",", $result->sectionlist);
        $file        = $this->media_storage->getImageURL($result->thumb_path . $result->thumb_name);
        $file_src    = $result->video_link;

        $output = '';
        $output .= "<div class='col-sm-3 col-md-2 col-xs-6 img_div_modal image_div div_record_" . $result->id . "'>";
        $output .= "<div class='fadeoverlay'>";
        $output .= "<div class='fadeheight'>";
        $output .= "<img class='' data-fid='" . $result->id . "' data-content_name='" . $result->img_name . "' src='" . $file . "'>";
        $output .= "</div>";
        $output .= "<div class='overlay3'>";

        $output .= "<a  href='#' class='uploadcheckbtn' data-backdrop='static' data-keyboard='false' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#detail'  data-role_name='" . $result->staff_name . ' ' . $result->staff_surname . $employee_id . "' data-sectionlist='" . $sectionlist . "' data-class='" . $result->class . "'  data-image='" . $file . "' data-source='" . $file_src . "' data-title='" . $result->title . "' data-description='" . $result->description . "'><i class='fa fa-navicon' title='" . $this->lang->line('view') . "'></i></a>";

        if ($this->rbac->hasPrivilege('video_tutorial', 'can_edit')) {
            $output .= "<a href='#' class='uploadclosebtn' data-id='" . $result->id . "' data-toggle='modal' data-target='#editvideotutorialmodal' data-backdrop='static' data-keyboard='false'><i class=' fa fa-pencil' title='" . $this->lang->line('edit') . "'></i></a>";
        }

        if ($this->rbac->hasPrivilege('video_tutorial', 'can_delete')) {
            $output .= "<a href='#' class='uploadcheckbtn' data-backdrop='static' data-keyboard='false' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o' title='" . $this->lang->line('delete') . "'></i></a>";
        }

        $output .= "<p class='processing'>" . $this->lang->line('processing') . "</p>";
        $output .= "</div>";
        $output .= "</div>";
        $output .= "<p class='fadeoverlay-para'>" . $result->title . "</p>";
        $output .= "</div>";
        return $output;
    }

    public function delete()
    {
        if (!$this->rbac->hasPrivilege('video_tutorial', 'can_delete')) {
            access_denied();
        }
        $record_id = $this->input->post('record_id');
        $record    = $this->video_tutorial_model->get($record_id);

        if ($record) {

            $destination_path = "uploads/video_tutorial/youtube_video/" . $record->img_name;
            $thumb_path       = "uploads/video_tutorial/youtube_video/thumb/" . $record->thumb_name;
            $this->video_tutorial_model->remove($record_id);
            unlink($destination_path);
            unlink($thumb_path);

            echo json_encode(array('status' => 1, 'msg' => $this->lang->line('delete_message')));

        } else {
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('please_try_again')));
        }
    }

    public function searchvalidation()
    {
        $class_id    = $this->input->post('search_class_id');
        $section_id  = $this->input->post('search_section_id');
        $srch_type   = $this->input->post('search_type');
        $search_text = $this->input->post('search_text');

        if ($srch_type == 'search_filter') {
            $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'class_section_id' => $section_id);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        } else {
            $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'class_section_id' => $section_id, 'search_text' => $search_text);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }

    }

    public function getsection()
    {
        $multisection    = array();
        $classid         = $this->input->post('class_id');
        $videotutorialid = $this->input->post('videotutorialid');
        $multipalsection = $this->video_tutorial_model->selectedsection($videotutorialid);
        foreach ($multipalsection as $key => $value) {
            $multisection[] = $value['class_section_id'];
        }
        $multipalsection = $multisection;
        $sectionlist     = $this->section_model->getClassBySection($classid);

        echo json_encode(array('sectionlist' => $sectionlist, 'multipalsection' => $multipalsection));
    }
}
