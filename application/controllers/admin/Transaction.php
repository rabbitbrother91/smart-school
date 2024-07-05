<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transaction extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function searchtransaction()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $data['title']       = 'Search Expense';
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['search_type'] = $search_type = '';
        $search = $this->input->post('search_type');
        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = $search_type = 'this_year';
        }

        $dateformat = $this->customlib->getSchoolDateFormat();

        $date_from = $dates['from_date'];
        $date_to   = $dates['to_date'];

        $data['collection_title'] = $this->lang->line('collection_report_from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['income_title']     = $this->lang->line('income_report_from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['expense_title']    = $this->lang->line('expense_report_from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $data['payroll_title']    = $this->lang->line('payroll_report_from') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($date_from)) . " To " . date($this->customlib->getSchoolDateFormat(), strtotime($date_to));
        $date_from                = date('Y-m-d', strtotime($date_from));
        $date_to                  = date('Y-m-d', strtotime($date_to));
        $expenseList              = $this->expense_model->search("", $date_from, $date_to);

        $result = $this->payroll_model->getbetweenpayrollReport($date_from, $date_to);

        $incomeList          = $this->income_model->search("", $date_from, $date_to);
        $feeList             = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to);
        $data['expenseList'] = $expenseList;
        $data['incomeList']  = $incomeList;
        $data['feeList']     = $feeList;
        $data['payrollList'] = $result;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/searchtransaction', $data);
        $this->load->view('layout/footer', $data);
    }

    

}
