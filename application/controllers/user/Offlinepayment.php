<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Offlinepayment extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model(array('offlinePayment_model'));
    }    

    public function getBankPayments()
    {
        $data=[];    
    
        $page = $this->load->view('user/offlinepayment/_getbankpayment', $data, true);
        $array                    = array('status' => '1', 'error' => '', 'page' => $page);
            echo json_encode($array);
    }

    // public function list() {
         // $this->session->set_userdata('top_menu', 'Offlinepayment');
        // $data['title']    = 'Exam List';
        // $payments         = $this->offlinePayment_model->get();
        // $data['payments'] = $payments;
        // $this->load->view('layout/student/header', $data);
        // $this->load->view('user/offlinepayment/list', $data);
        // $this->load->view('layout/student/footer', $data);
    // }

    public function index()
    {      
        $this->session->set_userdata('top_menu', 'fees');
        $this->session->set_userdata('sub_menu', 'student/getFees');
        
        $sessionData   = $this->customlib->getLoggedInUserData();
        $payment_session=$this->session->userdata("params");
        $data['title'] = 'Change Username';
        $this->form_validation->set_rules('payment_date', $this->lang->line('date_of_payment'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bank_from', $this->lang->line('payment_mode'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('bank_account_transferred', $this->lang->line('payment_from'), 'trim|required|xss_clean');
      
        $this->form_validation->set_rules('amount', $this->lang->line('amount_paid'), 'trim|required|xss_clean');
          $this->form_validation->set_rules('attachment', $this->lang->line('file'), 'callback_handle_upload');
        if ($this->form_validation->run() == true) {

            $student_current_class       = $this->customlib->getStudentCurrentClsSection();
            $student_session_id          = $student_current_class->student_session_id;

                $insert_data=[];
                $insert_data['student_session_id']=$student_session_id;
                $insert_data['payment_date']=$this->customlib->dateFormatToYYYYMMDD($this->input->post('payment_date'));
                $insert_data['bank_from']=$this->input->post('bank_from');
                $insert_data['bank_account_transferred']=$this->input->post('bank_account_transferred');
                $insert_data['reference']=$this->input->post('reference');
                $insert_data['amount']=convertCurrencyFormatToBaseAmount($this->input->post('amount'));
                $insert_data['student_fees_master_id']=($payment_session['student_fees_master_id'] !=0) ? $payment_session['student_fees_master_id']:NULL;
                $insert_data['fee_groups_feetype_id']=($payment_session['fee_groups_feetype_id'] !=0) ? $payment_session['fee_groups_feetype_id'] :NULL;
                $insert_data['student_transport_fee_id']=($payment_session['student_transport_fee_id'] !=0) ? $payment_session['student_transport_fee_id'] : NULL ;
                $insert_data['submit_date']=date('Y-m-d H:i:s');

            $img_name = $this->media_storage->fileupload("attachment", "./uploads/offline_payments/");

            $insert_data['attachment']=$img_name;

            $this->offlinePayment_model->add($insert_data);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('thank_you_for_the_payment_we_will_review_and_update_your_payment') . '</div>');
            redirect('user/user/getfees');

        }
        
        $data['setting'] = $this->setting_model->getSetting();
        
        $this->data['id']       = $sessionData['id'];
        $this->data['username'] = $sessionData['username'];
        $this->load->view('layout/student/header', $data);
        $this->load->view('user/offlinepayment/index', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function handle_upload($str, $is_required)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["attachment"]) && !empty($_FILES["attachment"]['name']) && $_FILES["attachment"]["size"] > 0) {

            $file_type = $_FILES["attachment"]['type'];
            $file_size = $_FILES["attachment"]["size"];
            $file_name = $_FILES["attachment"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES["attachment"]['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } 
            return true;

    }

    public function getlistbyuser()
    {
        $student_current_class       = $this->customlib->getStudentCurrentClsSection();
        $student_session_id          = $student_current_class->student_session_id;

        $questionList = $this->offlinePayment_model->getPaymentlistByUser($student_session_id);
        $m            = json_decode($questionList);

        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                 if ($value->is_active == 0) {
                    $status = '<span class="label label-warning">'.$this->lang->line('pending').'</span>';
                } elseif ($value->is_active == 1) {
                    $status = '<span class="label label-success">'.$this->lang->line('approved').'</span>';
                } elseif ($value->is_active == 2) {
                    $status = '<span class="label label-danger">'.$this->lang->line('rejected').'</span>';
                }
                $viewbtn = '';

                $viewbtn = " <button type='button' data-toggle='tooltip' class='btn btn-default btn-xs getbankdetail' data-recordid=" . $value->id . "  title=" . $this->lang->line('view') . " data-loading-text='<i class=" . '" fa fa-spinner fa-spin"' . "  ></i>'><i class='fa fa-reorder'></i></button>";

               $row   = array();
                $row[] = $value->id;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->payment_date)); 
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->submit_date, false);
                $row[] = amountFormat($value->amount);
                $row[] = $status;
                $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->approve_date, false);
                $row[] = $value->invoice_id;
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

    // public function getlist()
    // {
        // $questionList = $this->offlinePayment_model->getPaymentlist();
        // $m            = json_decode($questionList);

        // $dt_data = array();
        // if (!empty($m->data)) {
            // foreach ($m->data as $key => $value) {
                 // if ($value->is_active == 0) {
                    // $status = '<span class="label label-warning">'.$this->lang->line('pending').'</span>';
                // } elseif ($value->is_active == 1) {
                    // $status = '<span class="label label-success">'.$this->lang->line('approved').'</span>';
                // } elseif ($value->is_active == 2) {
                    // $status = '<span class="label label-danger">'.$this->lang->line('rejected').'</span>';
                // }
                // $viewbtn = '';

                // $viewbtn = " <button type='button' data-toggle='tooltip' class='btn btn-default btn-xs download_exam' data-recordid=" . $value->id . "  title=" . $this->lang->line('print') . " data-loading-text='<i class=" . '" fa fa-spinner fa-spin"' . "  ></i>'><i class='fa fa-reorder'></i></button>";

               // $row   = array();
                // $row[] = $this->customlib->dateformat($value->payment_date);
                // $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->submit_date, false);
                // $row[] = amountformat($value->amount);
                // $row[] = $status;
                // $row[] = $this->customlib->dateyyyymmddToDateTimeformat($value->approve_date, false);
                // $row[] = $viewbtn;

                // $dt_data[] = $row;
            // }
        // }

        // $json_data = array(
            // "draw"            => intval($m->draw),
            // "recordsTotal"    => intval($m->recordsTotal),
            // "recordsFiltered" => intval($m->recordsFiltered),
            // "data"            => $dt_data,
        // );
        // echo json_encode($json_data);
    // }

    public function getpayment()
    {
        $data=[];
        $recordid          = $this->input->post('recordid');
        $data['payment']          = $this->offlinePayment_model->get($recordid);
        $page = $this->load->view('user/offlinepayment/_getpayment', $data, true);
        $array             = array('status' => '1', 'error' => '', 'page' => $page);
            echo json_encode($array);
    }

    public function download($id)
    {
        $payment = $this->offlinePayment_model->get($id);
        $this->media_storage->filedownload($payment->attachment, "./uploads/offline_payments/");
    }

}