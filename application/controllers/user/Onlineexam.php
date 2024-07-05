<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineexam extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->config->load("mailsms");
        $this->load->library("datatables");
    }

    public function index()
    {
        $data = array();
        $this->session->set_userdata('top_menu', 'Onlineexam');
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $onlineexam            = $this->onlineexam_model->getStudentexam($student_session_id);
        $data['onlineexam']    = $onlineexam;
        $this->load->view('layout/student/header');
        $this->load->view('user/onlineexam/onlineexamlist', $data);
        $this->load->view('layout/student/footer');
    }

    public function view($id)
    {
        $data = array();
        $this->session->set_userdata('top_menu', 'Onlineexam');
        $data['sch_setting']         = $this->sch_setting_detail;
        $role                        = $this->customlib->getUserRole();
        $data['role']                = $role;
        $student_current_class       = $this->customlib->getStudentCurrentClsSection();
        $student_session_id          = $student_current_class->student_session_id;
        $online_exam_validate        = $this->onlineexam_model->examstudentsID($student_session_id, $id);
        $student                     = $this->student_model->getByStudentSession($student_session_id);
        $data['question_true_false'] = $this->config->item('question_true_false');
        $exam                        = $this->onlineexam_model->getexamdetails($id);
        $data['exam']                = $exam;
        $data['student']             = $student;
        $questionOpt                 = $this->customlib->getQuesOption();
        $data['questionOpt']         = $questionOpt;
        if (!empty($online_exam_validate)) {
            $data['question_result'] = $this->onlineexamresult_model->getResultByStudent($online_exam_validate->id, $online_exam_validate->onlineexam_id);
            $data['result_prepare']  = $this->onlineexamresult_model->checkResultPrepare($online_exam_validate->id);
        }
        $data['online_exam_validate'] = $online_exam_validate;
        $filetype                     = $this->filetype_model->get();
        $data['allowed_extension']    = array_map('trim', array_map('strtolower', explode(',', $filetype->file_extension)));
        $data['allowed_mime_type']    = array_map('trim', array_map('strtolower', explode(',', $filetype->file_mime)));
        $data['allowed_upload_size']  = $filetype->file_size;
        $this->load->view('layout/student/header');
        $this->load->view('user/onlineexam/view', $data);
        $this->load->view('layout/student/footer');
    }

    function print() {
        $data                        = array();
        $data['sch_setting']         = $this->sch_setting_detail;
        $exam_id                     = $this->input->post('exam_id');
        $role                        = $this->customlib->getUserRole();
        $data['role']                = $role;
        $student_current_class       = $this->customlib->getStudentCurrentClsSection();
        $student_session_id          = $student_current_class->student_session_id;
        $online_exam_validate        = $this->onlineexam_model->examstudentsID($student_session_id, $exam_id);
        $data['question_true_false'] = $this->config->item('question_true_false');
        $exam                        = $this->onlineexam_model->printstudentexamdetails($exam_id);
        $data['exam']                = $exam;
        $questionOpt                 = $this->customlib->getQuesOption();
        $data['questionOpt']         = $questionOpt;
        $student                     = $this->student_model->getByStudentSession($student_session_id);
        $data['student']             = $student;

        if (!empty($online_exam_validate)) {

            $data['question_result'] = $this->onlineexamresult_model->getResultByStudent($online_exam_validate->id, $online_exam_validate->onlineexam_id);
            $data['result_prepare']  = $this->onlineexamresult_model->checkResultPrepare($online_exam_validate->id);

        }
        $data['online_exam_validate'] = $online_exam_validate;
        $data['page']                 = $this->load->view('user/onlineexam/_print', $data, true);
        echo json_encode(array('status' => 1, 'page' => $data['page']));
    }

    public function save()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $total_rows = $this->input->post('total_rows');
            if (!empty($total_rows)) {
                $save_result = array();
                foreach ($total_rows as $row_key => $row_value) {
                    if (($_POST['question_type_' . $row_value]) == "singlechoice") {

                        if (isset($_POST['radio' . $row_value])) {
                            $save_result[] = array(
                                'onlineexam_student_id'  => $this->input->post('onlineexam_student_id'),
                                'onlineexam_question_id' => $this->input->post('question_id_' . $row_value),
                                'select_option'          => $_POST['radio' . $row_value],
                                'attachment_name'        => "",
                                'attachment_upload_name' => "",
                            );
                        }
                    } elseif (($_POST['question_type_' . $row_value]) == "true_false") {
                        # code...
                        if (isset($_POST['radio' . $row_value])) {
                            $save_result[] = array(
                                'onlineexam_student_id'  => $this->input->post('onlineexam_student_id'),
                                'onlineexam_question_id' => $this->input->post('question_id_' . $row_value),
                                'select_option'          => $_POST['radio' . $row_value],
                                'attachment_name'        => "",
                                'attachment_upload_name' => "",
                            );
                        }
                    } elseif (($_POST['question_type_' . $row_value]) == "multichoice") {
                        # code...
                        if (isset($_POST['checkbox' . $row_value])) {
                            $save_result[] = array(
                                'onlineexam_student_id'  => $this->input->post('onlineexam_student_id'),
                                'onlineexam_question_id' => $this->input->post('question_id_' . $row_value),
                                'select_option'          => json_encode($_POST['checkbox' . $row_value]),
                                'attachment_name'        => "",
                                'attachment_upload_name' => "",
                            );
                        }
                    } elseif (($_POST['question_type_' . $row_value]) == "descriptive") {
                        # code...
                        if (isset($_POST['answer' . $row_value]) || (isset($_FILES["attachment" . $row_value]) && !empty($_FILES["attachment" . $row_value]['name']))) {
                            $inst_array = array(
                                'onlineexam_student_id'  => $this->input->post('onlineexam_student_id'),
                                'onlineexam_question_id' => $this->input->post('question_id_' . $row_value),
                                'select_option'          => $_POST['answer' . $row_value],
                            );

                            $file_name        = "";
                            $upload_file_name = "";
                            if (isset($_FILES["attachment" . $row_value]) && !empty($_FILES["attachment" . $row_value]['name'])) {
                                $file_name        = $_FILES["attachment" . $row_value]["name"];
                                $fileInfo         = pathinfo($_FILES["attachment" . $row_value]["name"]);
                                $upload_file_name = time() . uniqid(rand()) . '.' . $fileInfo['extension'];
                                move_uploaded_file($_FILES["attachment" . $row_value]["tmp_name"], "./uploads/onlinexam_images/" . $upload_file_name);
                            }
                            $inst_array['attachment_name']        = $file_name;
                            $inst_array['attachment_upload_name'] = $upload_file_name;
                            $save_result[]                        = $inst_array;
                        }
                    }
                }

                $this->onlineexamresult_model->add($save_result);
                $this->onlineexam_model->updateExamResult($this->input->post('onlineexam_student_id'));
                redirect('user/onlineexam', 'refresh');
            }
        } else {

        }
    }

    public function getExamForm()
    {
        $data            = array();
        $question_status = 0;
        $recordid        = $this->input->post('recordid');
        $exam            = $this->onlineexam_model->getexamdetails($recordid);
        $data['exam']    = $exam;

        $data['questions'] = $this->onlineexam_model->getExamQuestions($recordid, $exam->is_random_question);

        $student_current_class         = $this->customlib->getStudentCurrentClsSection();
        $student_session_id            = $student_current_class->student_session_id;
        $onlineexam_student            = $this->onlineexam_model->examstudentsID($student_session_id, $exam->id);
        $data['onlineexam_student_id'] = $onlineexam_student;
        $getStudentAttemts             = $this->onlineexam_model->getStudentAttemts($onlineexam_student->id);
        $data['question_status']       = 0;
        $data['exam_duration']         = $exam->duration;

        if (strtotime(date('Y-m-d H:i:s')) >= strtotime(date($exam->exam_to))) {
            $question_status         = 1;
            $data['question_status'] = 1;
        } else if ($exam->attempt > $getStudentAttemts) {
            $this->onlineexam_model->addStudentAttemts(array('onlineexam_student_id' => $onlineexam_student->id));
        } else {
            $question_status         = 1;
            $data['question_status'] = 1;
        }

        $questionOpt         = $this->customlib->getQuesOption();
        $data['questionOpt'] = $questionOpt;
        $pag_content         = $this->load->view('user/onlineexam/_searchQuestionByExamID', $data, true);

        $total_remaining_seconds = round((strtotime($exam->exam_to) - strtotime(date('Y-m-d H:i:s'))) / 3600 * 60 * 60, 1);
        $exam_duration           = ($total_remaining_seconds < getSecondsFromHMS($exam->duration)) ? getHMSFromSeconds($total_remaining_seconds) : $exam->duration;

        echo json_encode(array('status' => 0, 'exam' => $exam, 'duration' => $exam_duration, 'page' => $pag_content, 'question_status' => $question_status, 'total_question' => count($data['questions'])));
    }

    public function downloadattachment($doc)
    {
        $this->load->helper('download');
        $filepath = "./uploads/onlinexam_images/" . $doc;
        $data     = file_get_contents($filepath);
        $name     = $doc;
        force_download($name, $data);
    }

    public function getexamlist()
    {
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $questionList          = $this->onlineexam_model->getstudentexamlist($student_session_id);
        $m                     = json_decode($questionList);
        $currency_symbol       = $this->customlib->getSchoolCurrencyFormat();
        $dt_data               = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $viewbtn = '';

                $title = "<a href='#' data-toggle='popover' class='detail_popover'>" . $value->exam . "</a>";

                $title = "<a href='#' data-toggle='popover' class='detail_popover'>" . $value->exam . "</a>";

                if ($value->description == "") {
                    $description = "<div class='fee_detail_popover' style='display: none'><p class='text text-danger'>" . $this->lang->line('no_description') . "</div></p>";
                } else {
                    $description = "<div class='fee_detail_popover' style='display: none'><p class='text text-danger'>" . $value->description . "</div></p>";
                }
                if ($value->is_quiz) {
                    $is_quiz = "<i class='fa fa-check-square-o'></i><span style='display:none'>" . $this->lang->line('yes') . "</span>";
                } else {
                    $is_quiz = "<i class='fa fa-exclamation-circle'></i><span style='display:none'>" . $this->lang->line('no') . "</span>";
                }

                $viewbtn = " <a href=" . base_url() . 'user/onlineexam/view/' . $value->id . " class='btn btn-default btn-xs' data-toggle='tooltip'  title=" . $this->lang->line('view') . " '   ><i class='fa fa fa-eye'></i></a>";

                $row   = array();
                $row[] = $title . $description;
                $row[] = $is_quiz;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->exam_from, false);
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->exam_to, false);
                $row[] = $value->duration;
                $row[] = $value->attempt;
                $row[] = $value->counter;

                if ($value->publish_result) {
                    $row[] = $this->lang->line('result_published');
                } else {
                    $row[] = $this->lang->line('available');
                }
                $row[] = $viewbtn;

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

    public function getclosedexamlist()
    {
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        $student_session_id    = $student_current_class->student_session_id;
        $questionList          = $this->onlineexam_model->getstudentclosedexamlist($student_session_id);
        $m                     = json_decode($questionList);
        $currency_symbol       = $this->customlib->getSchoolCurrencyFormat();
        $dt_data               = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $viewbtn = '';

                $title = "<a href='#' data-toggle='popover' class='detail_popover'>" . $value->exam . "</a>";

                $title = "<a href='#' data-toggle='popover' class='detail_popover'>" . $value->exam . "</a>";

                if ($value->description == "") {
                    $description = "<div class='fee_detail_popover' style='display: none'><p class='text text-danger'>" . $this->lang->line('no_description') . "</div></p>";
                } else {
                    $description = "<div class='fee_detail_popover' style='display: none'><p class='text text-danger'>" . $value->description . "</div></p>";
                }
                if ($value->is_quiz) {
                    $is_quiz = "<i class='fa fa-check-square-o'></i><span style='display:none'>" . $this->lang->line('yes') . "</span>";
                } else {
                    $is_quiz = "<i class='fa fa-exclamation-circle'></i><span style='display:none'>" . $this->lang->line('no') . "</span>";
                }

                $viewbtn = " <a href=" . base_url() . 'user/onlineexam/view/' . $value->id . " class='btn btn-default btn-xs' data-toggle='tooltip'  title=" . $this->lang->line('view') . " '   ><i class='fa fa fa-eye'></i></a>";
                $row     = array();
                $row[]   = $title . $description;
                $row[]   = $is_quiz;
                $row[]   = $this->customlib->dateyyyymmddToDateTimeformat($value->exam_from, false);
                $row[]   = $this->customlib->dateyyyymmddToDateTimeformat($value->exam_to, false);
                $row[]   = $value->duration;
                $row[]   = $value->attempt;
                $row[]   = $value->counter;

                if ($value->publish_result) {
                    $row[] = $this->lang->line('result_published');
                } else {
                    $row[] = $this->lang->line('available');
                }
                $row[] = $viewbtn;

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
}
