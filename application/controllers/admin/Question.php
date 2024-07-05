<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Question extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->sch_setting_detail = $this->setting_model->getSetting();

    }
    public function read($id)
    {
        if (!$this->rbac->hasPrivilege('question_bank', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/question');
        $question                    = $this->question_model->get($id);
        $data['question']            = $question;
        $data['question_type']       = $this->config->item('question_type');
        $data['question_level']      = $this->config->item('question_level');
        $data['question_true_false'] = $this->config->item('question_true_false');
        $questionOpt                 = $this->customlib->getQuesOption();
        $data['questionOpt']         = $questionOpt;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/question/read', $data);
        $this->load->view('layout/footer', $data);
    }

    public function index($offset = 0)
    {
        if (!$this->rbac->hasPrivilege('question_bank', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/question');
        $data                   = array();
        $class                  = $this->class_model->get();
        $data['classlist']      = $class;
        $subjectlist            = $this->subject_model->get();
        $data['subjectlist']    = $subjectlist;
        $data['question_type']  = $this->config->item('question_type');
        $data['question_level'] = $this->config->item('question_level');
        $questionList           = $this->question_model->getquestioncreatedstaff();
        $data['staff_list']     = $questionList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/question/question', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getQuestionByID()
    {
        $id = $this->input->post('recordid');
        $question_result = $this->question_model->get($id);
        echo json_encode(array('status' => 1, 'result' => $question_result));
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./backend/import/import_question_sample_file.csv";
        $data     = file_get_contents($filepath);
        $name     = 'import_question_sample_file.csv';
        force_download($name, $data);
    }

    public function bulkdelete()
    {
        $question_array  = $this->input->post('recordid');
        $question_result = $this->question_model->bulkdelete($question_array);
        echo json_encode(array('status' => 1, 'message' => $this->lang->line('delete_message')));
    }

    public function uploadfile()
    {
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'subject_id' => form_error('subject_id'),
                'class_id'   => form_error('class_id'),
                'section_id' => form_error('section_id'),
                'file'       => form_error('file'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $insert_array = array();
            //====================
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {

                $fileName = $_FILES["file"]["tmp_name"];
                if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {
                    $file = fopen($fileName, "r");
                    $flag = true;
                    while (($column = fgetcsv($file, 10000, ",")) !== false) {
                        if ($flag) {
                            $flag = false;
                            continue;
                        }
                        if (trim($column['0']) != "" && trim($column['1']) != "" && trim($column['2']) != "") {
                            $insert_array[] = array(
                                'staff_id'      => $this->customlib->getStaffID(),
                                'subject_id'    => $this->input->post('subject_id'),
                                'class_id'      => $this->input->post('class_id'),
                                'section_id'    => $this->input->post('section_id'),
                                'question_type' => trim($column['0']),
                                'level'         => trim($column['1']),
                                'question'      => trim($column['2']),
                                'opt_a'         => trim($column['3']),
                                'opt_b'         => trim($column['4']),
                                'opt_c'         => trim($column['5']),
                                'opt_d'         => trim($column['6']),
                                'opt_e'         => trim($column['7']),
                                'correct'       => trim($column['8']),
                            );
                        }
                    }
                }

                if (!empty($insert_array)) {
                    $this->question_model->add_question_bulk($insert_array);
                }
                $array = array('status' => '1', 'error' => '', 'message' => count($insert_array) . ' ' . $this->lang->line('questions_are_successfully_imported'));
                echo json_encode($array);
            }
            //=============
        }
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('csv_validate');
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0) {

            $file_type         = $_FILES["file"]['type'];
            $file_size         = $_FILES["file"]["size"];
            $file_name         = $_FILES["file"]["name"];
            $allowed_extension = $image_validate['allowed_extension'];
            $ext               = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_mime_type = $image_validate['allowed_mime_type'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['file']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $image_validate['upload_size']) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_upload', $this->lang->line('please_choose_a_file_to_upload'));
            return false;
        }
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('question_bank', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('subject_id', $this->lang->line('subject'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('question', $this->lang->line('question'), 'trim|required');
        $this->form_validation->set_rules('question_type', $this->lang->line('question_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('question_level', $this->lang->line('question_level'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        if ($this->input->post('question_type') == "singlechoice") {
            $this->form_validation->set_rules('opt_a', $this->lang->line('option_a'), 'trim|required');
            $this->form_validation->set_rules('opt_b', $this->lang->line('option_b'), 'trim|required');
            $this->form_validation->set_rules('correct', $this->lang->line('answer'), 'trim|required');
        } elseif ($this->input->post('question_type') == "true_false") {
            $this->form_validation->set_rules('correct_true_false', $this->lang->line('answer'), 'trim|required|xss_clean');
        } elseif ($this->input->post('question_type') == "multichoice") {
            $this->form_validation->set_rules('opt_a', $this->lang->line('option_a'), 'trim|required');
            $this->form_validation->set_rules('opt_b', $this->lang->line('option_b'), 'trim|required');
            $this->form_validation->set_rules('ans[]', $this->lang->line('answer'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {

            $msg = array(
                'subject_id'     => form_error('subject_id'),
                'question'       => form_error('question'),
                'question_type'  => form_error('question_type'),
                'question_level' => form_error('question_level'),
                'class_id'       => form_error('class_id'),
            );

            if ($this->input->post('question_type') == "singlechoice") {
                $msg['opt_a']   = form_error('opt_a');
                $msg['opt_b']   = form_error('opt_b');
                $msg['correct'] = form_error('correct');
            } elseif ($this->input->post('question_type') == "true_false") {
                $msg['correct_true_false'] = form_error('correct_true_false');
            } elseif ($this->input->post('question_type') == "multichoice") {
                $msg['opt_a'] = form_error('opt_a');
                $msg['opt_b'] = form_error('opt_b');
                $msg['ans']   = form_error('ans[]');
            }

            $array = array('status' => 0, 'error' => $msg, 'message' => '');

        } else {

            $insert_data = array(
                'subject_id'    => $this->input->post('subject_id'),
                'question'      => $this->input->post('question'),
                'question_type' => $this->input->post('question_type'),
                'level'         => $this->input->post('question_level'),
                'class_id'      => $this->input->post('class_id'),
                'staff_id'      => $this->customlib->getStaffID(),
            );

            $section_id = $this->input->post('section_id');
            if (isset($section_id) && $section_id != "") {
                $insert_data['section_id'] = $this->input->post('section_id');
            }

            if ($this->input->post('question_type') == "singlechoice") {
                $insert_data['opt_a']   = $this->input->post('opt_a');
                $insert_data['opt_b']   = $this->input->post('opt_b');
                $insert_data['opt_c']   = $this->input->post('opt_c');
                $insert_data['opt_d']   = $this->input->post('opt_d');
                $insert_data['opt_e']   = $this->input->post('opt_e');
                $insert_data['correct'] = $this->input->post('correct');
            } elseif ($this->input->post('question_type') == "true_false") {
                $insert_data['opt_a']   = "";
                $insert_data['opt_b']   = "";
                $insert_data['opt_c']   = "";
                $insert_data['opt_d']   = "";
                $insert_data['opt_e']   = "";
                $insert_data['correct'] = $this->input->post('correct_true_false');
            } elseif ($this->input->post('question_type') == "multichoice") {
                $insert_data['opt_a']   = $this->input->post('opt_a');
                $insert_data['opt_b']   = $this->input->post('opt_b');
                $insert_data['opt_c']   = $this->input->post('opt_c');
                $insert_data['opt_d']   = $this->input->post('opt_d');
                $insert_data['opt_e']   = $this->input->post('opt_e');
                $insert_data['correct'] = json_encode($this->input->post('ans'));
            } elseif ($this->input->post('question_type') == "descriptive") {
                $insert_data['opt_a']   = "";
                $insert_data['opt_b']   = "";
                $insert_data['opt_c']   = "";
                $insert_data['opt_d']   = "";
                $insert_data['opt_e']   = "";
                $insert_data['correct'] = "";
            }

            $id = $this->input->post('recordid');
            if ($id != 0) {
                $insert_data['id'] = $id;
            }

            $this->question_model->add($insert_data);
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function getRecord($id)
    {
        $result            = $this->question_model->get_result($id);
        $result['options'] = $this->question_model->get_option($id);
        $result['ans']     = $this->question_model->get_answer($id);
        echo json_encode($result);
    }

    public function addform()
    {
        $data                        = array();
        $data['classList']           = $this->class_model->get();
        $subject_result              = $this->subject_model->get();
        $data['subjectlist']         = $subject_result;
        $data['question_true_false'] = $this->config->item('question_true_false');
        $data['question_type']       = $this->config->item('question_type');
        $data['question_level']      = $this->config->item('question_level');
        $questionOpt                 = $this->customlib->getQuesOption();
        $data['questionOpt']         = $questionOpt;
        $data['recordid']            = $this->input->post('recordid');
        $page                        = $this->load->view('admin/question/_addform', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function editform()
    {
        $data                        = array();
        $data['recordid']            = $this->input->post('recordid');
        $question_result             = $this->question_model->get($data['recordid']);
        $data['question_result']     = $question_result;
        $data['classList']           = $this->class_model->get();
        $data['sectionList']         = $this->section_model->getClassBySection($question_result->class_id);
        $subject_result              = $this->subject_model->get();
        $data['subjectlist']         = $subject_result;
        $data['question_true_false'] = $this->config->item('question_true_false');
        $data['question_type']       = $this->config->item('question_type');
        $data['question_level']      = $this->config->item('question_level');
        $questionOpt                 = $this->customlib->getQuesOption();
        $data['questionOpt']         = $questionOpt;
        $page = $this->load->view('admin/question/_editform', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('question_bank', 'can_delete')) {
            access_denied();
        }
        $this->question_model->remove($id);
        redirect('admin/question', 'refresh');
    }

    public function getimages()
    {
        $keyword         = "";
        $page            = $this->input->post('page');
        $keyword         = $this->input->post('query');
        $per_page_record = 12;
        if ($page > 1) {
            $start = (($page - 1) * $per_page_record);
            $page  = $page;
        } else {
            $start = 0;
        }
        $file_type = "image";
        $result       = $this->cms_media_model->fetch_details($per_page_record, $start, $keyword, $file_type);
        $result_count = $this->cms_media_model->count_all($keyword, $file_type);

        $data['result']       = $result;
        $data['result_count'] = $result_count;
        $data['pagination']   = $this->getpagination($result_count, $per_page_record, $page);
        $page = $this->load->view('admin/question/_getimages', $data, true);
        echo json_encode(array('page' => $page, 'count' => $data['result_count'], 'pagination' => $data['pagination']));
    }

    public function getpagination($total_data, $limit, $page)
    {
        $output = "";
        $output .= '<ul class="pagination">';
        $total_links   = ceil($total_data / $limit);
        $previous_link = '';
        $next_link     = '';
        $page_link     = '';

        $page_array = array();
        if ($total_links > 4) {
            if ($page < 5) {
                for ($count = 1; $count <= 5; $count++) {
                    $page_array[] = $count;
                }
                $page_array[] = '...';
                $page_array[] = $total_links;
            } else {
                $end_limit = $total_links - 5;
                if ($page > $end_limit) {
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for ($count = $end_limit; $count <= $total_links; $count++) {
                        $page_array[] = $count;
                    }
                } else {
                    $page_array[] = 1;
                    $page_array[] = '...';
                    for ($count = $page - 1; $count <= $page + 1; $count++) {
                        $page_array[] = $count;
                    }
                    $page_array[] = '...';
                    $page_array[] = $total_links;
                }
            }
        } else {

            for ($count = 1; $count <= $total_links; $count++) {
                $page_array[] = $count;
            }
        }

        for ($count = 0; $count < count($page_array); $count++) {
            if ($page == $page_array[$count]) {
                $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">' . $page_array[$count] . ' <span class="sr-only">(current)</span></a>
    </li>
    ';

                $previous_id = $page_array[$count] - 1;
                if ($previous_id > 0) {
                    $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $previous_id . '">Previous</a></li>';
                } else {
                    $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
                }
                $next_id = $page_array[$count] + 1;
                if ($next_id >= $total_links) {
                    $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
                } else {
                    $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $next_id . '">Next</a></li>';
                }
            } else {
                if ($page_array[$count] == '...') {
                    $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
                } else {
                    $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="' . $page_array[$count] . '">' . $page_array[$count] . '</a></li>
      ';
                }
            }
        }

        $output .= $previous_link . $page_link . $next_link;
        $output .= '</ul>';

        return $output;
    }

    public function getDatatable()
    {
        $getStaffRole            = $this->customlib->getStaffRole();
        $staffrole               = json_decode($getStaffRole);
        $superadmin_restriction   = $this->session->userdata['admin']['superadmin_restriction'];
        
        
        $class_id              = $this->input->post('class_id');
        $section_id            = $this->input->post('section_id');
        $subject               = $this->input->post('subject');
        $search_question_type  = $this->input->post('question_type');
        $search_question_level = $this->input->post('question_level');
        $created_by            = $this->input->post('created_by');
        
        $question_type  = $this->config->item('question_type');
        $question_level = $this->config->item('question_level');
        $question_dt = $this->question_model->getAllRecord($class_id, $section_id, $subject, $search_question_type, $search_question_level,$created_by);
        $question_dt = json_decode($question_dt);
        $dt_data = array();
        $recordsTotal_flter = "";
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata["role_id"];
        if (!empty($question_dt->data)) {
            foreach ($question_dt->data as $key => $value) {

                $delete       = "'" . $this->lang->line("delete_confirm") . "'";
                $delete_title = "'" . $this->lang->line("delete") . "'";
                $editbtn      = "";
                $deletebtn    = "";
                $del_checkbox = "";

                if ($this->rbac->hasPrivilege('question_bank', 'can_delete')) {
                    $deletebtn    = '<a href="' . base_url() . 'admin/question/delete/' . $value->id . '" class="btn btn-default btn-xs"  data-toggle="tooltip" title=' . $delete_title . ' onclick="return confirm(' . $delete . ')"><i class="fa fa-remove"></i></a>';
                    $del_checkbox = "<input type='checkbox' name='question_" . $value->id . "' data-question-id='" . $value->id . "' value='" . $value->id . "'>";
                }

                if ($this->rbac->hasPrivilege('question_bank', 'can_view')) {

                    $viewbtn = '<a target="_blank" href="' . site_url('admin/question/read/' . $value->id) . '" class="btn btn-default btn-xs"  data-toggle="tooltip" title=' . $this->lang->line("view") . ' ><i class="fa fa-eye"></i></a>';
                }
                
                if ($this->rbac->hasPrivilege('question_bank', 'can_edit')) {                
                    $editbtn = '<button type="button" class="btn btn-default btn-xs question-btn-edit" data-toggle="tooltip" id="load" data-recordid="' . $value->id . '" title="' . $this->lang->line("edit") . '" ><i class="fa fa-pencil"></i></button>';
                }
                
                $code = '';
                if($value->code){
                    $code = ' ('.$value->code.')';
                }
                $row = array();

                $row[] = $del_checkbox;
                $row[] = $value->id;
                $row[] = $value->name .' '. $code ;
                $row[] = ($value->question_type != "") ? $question_type[$value->question_type] : "";
                $row[] = ($value->level != "") ? $question_level[$value->level] : "";
                $row[] = readmorelink($value->question, site_url('admin/question/read/' . $value->id));                
                
                if($superadmin_restriction == 'disabled'){
                    if($staffrole->id == 7){
                        $row[] = $value->staff_name. ' ' .$value->staff_surname. ' (' .$value->employee_id. ')';
                    }else{
                        if($value->created_role != 7){
                            $row[] = $value->staff_name. ' ' .$value->staff_surname. ' (' .$value->employee_id. ')'; 
                        }else{
                            $row[] = '';
                        } 
                    }
                }else{
                    $row[] = $value->staff_name. ' ' .$value->staff_surname. ' (' .$value->employee_id. ')';
                }              
                
                $row[] = $viewbtn . ' ' . $editbtn . ' ' . $deletebtn;

                if ($role_id == 2) {
                    $my_section = array();
                    if ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '1') {
                        $my_class = $this->class_model->get();
                        foreach ($my_class as $class_key => $class_value) {
                            $section_id = $this->teacher_model->get_teacherrestricted_modesections($this->customlib->getStaffID(), $class_value['id']);
                            foreach ($section_id as $section_idkey => $section_idvalue) {
                                $my_section[] = $section_idvalue['section_id'];
                            }

                            if (in_array($value->section_id, $my_section, true) && $class_value['id'] == $value->class_id) {
                                $dt_data[] = $row;

                            } elseif (($class_value['id'] == $value->class_id) && $value->section_id == '0') {
                                $dt_data[] = $row;

                            }
                        }

                    } elseif ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '0') {
                        $my_class = $this->class_model->get();
                        foreach ($my_class as $class_key => $class_value) {
                            $section_id = $this->teacher_model->get_teacherrestricted_modesections($this->customlib->getStaffID(), $class_value['id']);
                            foreach ($section_id as $section_idkey => $section_idvalue) {
                                $my_section[] = $section_idvalue['section_id'];
                            }

                            if (in_array($value->section_id, $my_section, true) && $class_value['id'] == $value->class_id) {
                                $dt_data[] = $row;

                            } elseif (($class_value['id'] == $value->class_id) && $value->section_id == '0') {
                                $dt_data[] = $row;

                            }
                        }

                    } elseif ($this->sch_setting_detail->class_teacher == 'no' && $this->sch_setting_detail->my_question == '1') {
                        if ($this->customlib->getStaffID() == $value->staff_id) {
                            $dt_data[] = $row;

                        }
                    } else {
                        $dt_data[] = $row;

                    }

                } else {
                    $dt_data[] = $row;

                }
            }
        }

        $json_data = array(
            "draw"            => intval($question_dt->draw),
            "recordsTotal"    => intval($question_dt->recordsTotal),
            "recordsFiltered" => intval($question_dt->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function questionsearchvalidation()
    {
        $class_id       = $this->input->post('class');
        $section_id     = $this->input->post('section');
        $subject        = $this->input->post('subject');
        $question_type  = $this->input->post('question_type');
        $question_level = $this->input->post('question_level');
        $created_by = $this->input->post('created_by');
        $srch_type      = $this->input->post('search_type');         

        $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id, 'subject' => $subject, 'question_type' => $question_type, 'question_level' => $question_level, 'created_by' => $created_by);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);        

    }

}
