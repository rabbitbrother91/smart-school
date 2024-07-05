<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Offlinepayment extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model(array('offlinePayment_model', 'setting_model'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('offline_bank_payments', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/offlinepayment');
        $payments         = $this->offlinePayment_model->get();
        $data['payments'] = $payments;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/offlinepayment/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getlist()
    {
        $questionList = $this->offlinePayment_model->getPaymentlist();
        $m            = json_decode($questionList);

        $dt_data = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $viewbtn      = '';
                $sch_setting  = $this->setting_model->getSetting();
                $student_name = $this->customlib->getFullname($value->firstname, $value->middlename, $value->lastname, $sch_setting->middlename, $sch_setting->lastname);

                if ($value->is_active == 0) {
                    $status = '<span class="label label-warning">'.$this->lang->line('pending').'</span>';
                } elseif ($value->is_active == 1) {
                    $status = '<span class="label label-success">'.$this->lang->line('approved').'</span>';
                } elseif ($value->is_active == 2) {
                    $status = '<span class="label label-danger">'.$this->lang->line('rejected').'</span>';
                }
                $viewbtn = " <button type='button' data-toggle='tooltip' class='btn btn-default btn-xs download_exam' data-recordid=" . $value->id . "  title=" . $this->lang->line('view') . " data-loading-text='<i class=" . '" fa fa-spinner fa-spin"' . "  ></i>'><i class='fa fa-reorder'></i></button>";

                $row   = array();
                $row[] = $value->id;
                $row[] = $value->admission_no;
                $row[] = $student_name;
                $row[] = $value->class . "(" . $value->section . ")";
                $row[] = $this->customlib->dateformat($value->payment_date);
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

    public function getpayment()
    {
        $data                  = [];
        $recordid              = $this->input->post('recordid');
        $data['sch_setting']   = $this->setting_model->getSetting();
        $category_list         = $this->category_model->get();
        $data['category_list'] = $category_list;

        $payment         = $this->offlinePayment_model->get($recordid);
        $data['payment'] = $payment;

        $amount_to_paid = array(
            'amount' => convertBaseAmountCurrencyFormat($payment->amount),
            'fine'   => 0,
        );

        if (IsNullOrEmptyString($payment->student_transport_fee_id)) {

            $fee_detail = $this->studentfeemaster_model->getFeesByStudentFeeMasterAndFeetype($payment->student_fees_master_id, $payment->fee_groups_feetype_id);

            $total_fine_amount_paid = 0;

            if (isJSON($fee_detail->amount_detail)) {
                $fees_details = (json_decode($fee_detail->amount_detail));
                if (!empty($fees_details)) {
                    foreach ($fees_details as $fees_detail_key => $fees_detail_value) {
                        $total_fine_amount_paid += $fees_detail_value->amount_fine;
                    }
                }
            }

            if (($fee_detail->due_date != "0000-00-00" && $fee_detail->due_date != null) && (strtotime($fee_detail->due_date) < strtotime($payment->payment_date))) {

                $amount_to_paid['amount'] = convertBaseAmountCurrencyFormat($payment->amount - ($fee_detail->fine_amount - $total_fine_amount_paid));
                $amount_to_paid['fine']   = convertBaseAmountCurrencyFormat(($fee_detail->fine_amount - $total_fine_amount_paid));

            }

        } else {
            $fee_detail             = $this->studentfeemaster_model->studentTransportDeposit($payment->student_transport_fee_id);
            $total_fine_amount_paid = 0;

            if (isJSON($fee_detail->amount_detail)) {
                $fees_details = (json_decode($fee_detail->amount_detail));
                if (!empty($fees_details)) {
                    foreach ($fees_details as $fees_detail_key => $fees_detail_value) {
                        $total_fine_amount_paid += $fees_detail_value->amount_fine;
                    }
                }
            }

            if (($fee_detail->due_date != "0000-00-00" && $fee_detail->due_date != null) && (strtotime($fee_detail->due_date) < strtotime($payment->payment_date))) {
                $fees_fine_amount = IsNullOrEmptyString($fee_detail->fine_percentage) ? $fee_detail->fine_amount : percentageAmount($fee_detail->fees, $fee_detail->fine_percentage);

                $amount_to_paid['amount'] = convertBaseAmountCurrencyFormat($payment->amount - ($fees_fine_amount - $total_fine_amount_paid));
                $amount_to_paid['fine']   = convertBaseAmountCurrencyFormat(($fees_fine_amount - $total_fine_amount_paid));

            }

        }

        $data['amount_to_paid'] = $amount_to_paid;
        $page                   = $this->load->view('admin/offlinepayment/_getpayment', $data, true);
        $array                  = array('status' => '1', 'error' => '', 'page' => $page);
        echo json_encode($array);
    }

    public function download($id)
    {
        $payment = $this->offlinePayment_model->get($id);     
        $this->media_storage->filedownload($payment->attachment, "./uploads/offline_payments/");
    }

    public function update()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('payment_status', $this->lang->line('payment_status'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('fine', $this->lang->line('fine'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'payment_status' => form_error('payment_status'),
                'amount'         => form_error('amount'),
                'fine'           => form_error('fine'),

            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            $offline_fees_payment_id = $this->input->post('offline_fees_payment_id');
            
            $update_data             = array(
                'amount'       => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'fine'         => convertCurrencyFormatToBaseAmount($this->input->post('fine')),
                'reply'        => $this->input->post('reply'),
                'is_active'    => $this->input->post('payment_status'),
                'id'           => $this->input->post('offline_fees_payment_id'),
                'approve_date' => date('Y-m-d H:i:s'),
                'approved_by'  => $this->customlib->getStaffID(),
            );

            $payment = $this->offlinePayment_model->update($update_data);
       

            $array = array('status' => '1', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

}
