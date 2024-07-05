<?php

class Leaverequest extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->config->load("payroll");
        $this->load->library('media_storage');

        $this->load->model("staff_model");
        $this->load->model("leaverequest_model");
        $this->contract_type    = $this->config->item('contracttype');
        $this->marital_status   = $this->config->item('marital_status');
        $this->staff_attendance = $this->config->item('staffattendance');
        $this->payroll_status   = $this->config->item('payroll_status');
        $this->payment_mode     = $this->config->item('payment_mode');
        $this->status           = $this->config->item('status');
        $this->load->library('mailsmsconf');
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function leaverequest()
    {
        if (!$this->rbac->hasPrivilege('approve_leave_request', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/leaverequest/leaverequest');
        $leave_request         = $this->leaverequest_model->staff_leave_request();
        $data["leave_request"] = $leave_request;
        $LeaveTypes            = $this->staff_model->getLeaveType();
        $userdata              = $this->customlib->getUserData();
        $data["leavetype"]     = $LeaveTypes;
        $staffRole             = $this->staff_model->getStaffRole();
        $data["staffrole"]     = $staffRole;
        $data["status"]        = $this->status;

        $this->load->view("layout/header", $data);
        $this->load->view("admin/staff/staffleaverequest", $data);
        $this->load->view("layout/footer", $data);
    }

    public function countLeave($id)
    {
        $lid               = $this->input->post("lid");
        $alloted_leavetype = $this->leaverequest_model->allotedLeaveType($id);

        $i    = 0;
        $html = "<select  name='leave_type' id='leave_type' class='form-control'><option value=''>" . $this->lang->line('select') . "</option>";
        $data = array();

        foreach ($alloted_leavetype as $key => $value) {
            $count_leaves[]            = $this->leaverequest_model->countLeavesData($id, $value["leave_type_id"]);
            $data[$i]['type']          = $value["type"];
            $data[$i]['id']            = $value["leave_type_id"];
            $data[$i]['alloted_leave'] = $value["alloted_leave"];
            $data[$i]['approve_leave'] = $count_leaves[$i]['approve_leave'];

            $i++;
        }

        foreach ($data as $dkey => $dvalue) {
            if (!empty($dvalue["alloted_leave"])) {
                if ($lid == $dvalue["id"]) {
                    $a = "selected";
                } else {
                    $a = "";
                }

                if ($dvalue["alloted_leave"] == "") {

                    $available = $dvalue["approve_leave"];
                } else {
                    $available = $dvalue["alloted_leave"] - $dvalue["approve_leave"];
                }
                if ($available > 0) {

                    $html .= "<option value=" . $dvalue["id"] . " $a>" . $dvalue["type"] . " (" . $available . ")" . "</option>";
                }
            }
        }

        $html .= "</select>";
        echo $html;
    }

    public function leaveStatus()
    {
        if ((!$this->rbac->hasPrivilege('approve_leave_request', 'can_edit'))) {
            access_denied();
        }
        $leave_request_id = $this->input->post("leave_request_id");
        $status           = $this->input->post("status");
        $adminRemark      = $this->input->post("detailremark");
        $data             = array('status' => $status, 'admin_remark' => $adminRemark);
        $this->leaverequest_model->changeLeaveStatus($data, $leave_request_id);
        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function remove($id, $staff_id)
    {
        $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
        $row       = $this->leaverequest_model->get_staff_leave($id);
        if ($row['document_file'] != '') {
            $this->media_storage->filedelete($row['document_file'], $uploaddir);
        }
        $this->leaverequest_model->leave_remove($id);
    }

    public function leaveRecord()
    {
        $id                   = $this->input->post("id");
        $result               = $this->staff_model->getLeaveRecord($id);
        $leave_from           = date("m/d/Y", strtotime($result->leave_from));
        $result->leavefrom    = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->leave_from));
        $result->date         = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->date));
        $leave_to             = date("m/d/Y", strtotime($result->leave_to));
        $result->leaveto      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($result->leave_to));
        $result->days         = $this->dateDifference($leave_from, $leave_to);
        $result->leave_status = $this->lang->line($result->status);
        echo json_encode($result);
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        $interval  = date_diff($datetime1, $datetime2);
        return $interval->format($differenceFormat) + 1;
    }

    public function addLeave()
    {
        $role         = $this->input->post("role");
        $empid        = $this->input->post("empname");
        $applied_date = $this->input->post("applieddate");
        $leavetype    = $this->input->post("leave_type");
        $reason       = $this->input->post("reason");
        $remark       = $this->input->post("remark");
        $status       = $this->input->post("addstatus");
        $request_id   = $this->input->post("leaverequestid");
        $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('empname', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('applieddate', $this->lang->line('applied_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_from_date', $this->lang->line('leave_from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_to_date', $this->lang->line('leave_to_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_type', $this->lang->line('available_leave'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_type', $this->lang->line('leave_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'role'            => form_error('role'),
                'empname'         => form_error('empname'),
                'applieddate'     => form_error('applieddate'),
                'leavedates'      => form_error('leavedates'),
                'leave_type'      => form_error('leave_type'),
                'leave_from_date' => form_error('leave_from_date'),
                'leave_to_date'   => form_error('leave_to_date'),
                'userfile'        => form_error('userfile'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $leavefrom    = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_from_date')));
            $leaveto      = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_to_date')));
            $applied_by   = $this->customlib->getStaffID();
            $leave_days   = $this->dateDifference($leavefrom, $leaveto);
            $staff_id     = $empid;
            $my_laeve     = $this->leaverequest_model->myallotedLeaveType($staff_id, $leavetype);
            $total_remain = $my_laeve['alloted_leave'] - $my_laeve['total_applied'];
            if ($total_remain >= $leave_days) {

                if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $document = $this->media_storage->fileupload("userfile", $uploaddir);
                } else {
                    $document = '';
                }

                if (!empty($request_id)) {

                    $data = array(
                        'id'              => $request_id,
                        'staff_id'        => $staff_id,
                        'date'            => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                        'leave_type_id'   => $leavetype,
                        'leave_days'      => $leave_days,
                        'leave_from'      => $leavefrom,
                        'leave_to'        => $leaveto,
                        'employee_remark' => $reason,
                        'status'          => $status,
                        'admin_remark'    => $remark,
                        'applied_by'      => $applied_by,
                        'document_file'   => $document,
                    );
                } else {

                    $data = array('staff_id' => $staff_id, 'date' => date("Y-m-d", $this->customlib->datetostrtotime($applied_date)), 'leave_days' => $leave_days, 'leave_type_id' => $leavetype, 'leave_from' => $leavefrom, 'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                }

                $this->leaverequest_model->addLeaveRequest($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $msg = array(
                    'applieddate' => $this->lang->line('selected_leave_days') . " > " . $this->lang->line('available_leaves'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }

        }
        echo json_encode($array);
    }

    public function add_staff_leave()
    {
        $userdata     = $this->customlib->getUserData();
        $applied_date = $this->input->post("applieddate");
        $leavetype    = $this->input->post("leave_type");
        $reason       = $this->input->post("reason");
        $remark       = '';
        $status       = 'pending';
        $request_id   = $this->input->post("leaverequestid");
        $this->form_validation->set_rules('applieddate', $this->lang->line('applied_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_from_date', $this->lang->line('leave_from_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_to_date', $this->lang->line('leave_to_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('leave_type', $this->lang->line('available_leave'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('userfile', $this->lang->line('file'), 'callback_handle_upload[userfile]');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'applieddate'     => form_error('applieddate'),
                'leave_from_date' => form_error('leave_from_date'),
                'leave_to_date'   => form_error('leave_to_date'),
                'leave_type'      => form_error('leave_type'),
                'userfile'        => form_error('userfile'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $leavefrom = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_from_date')));
            $leaveto   = date("Y-m-d", $this->customlib->datetostrtotime($this->input->post('leave_to_date')));

            $staff_id     = $userdata["id"];
            $applied_by   = $this->customlib->getStaffID();
            $leave_days   = $this->dateDifference($leavefrom, $leaveto);
            $my_laeve     = $this->leaverequest_model->myallotedLeaveType($staff_id, $leavetype);
            $total_remain = $my_laeve['alloted_leave'] - $my_laeve['total_applied'];

            if ($total_remain >= $leave_days) {

                if (isset($_FILES["userfile"]) && !empty($_FILES['userfile']['name'])) {
                    $uploaddir = './uploads/staff_documents/' . $staff_id . '/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
                    $document = $this->media_storage->fileupload("userfile", $uploaddir);
                } else {
                    $document = '';
                }

                if (!empty($request_id)) {
                    $data = array('id' => $request_id,
                        'staff_id'         => $staff_id,
                        'date'             => date('Y-m-d', $this->customlib->datetostrtotime($applied_date)),
                        'leave_type_id'    => $leavetype,
                        'leave_days'       => $leave_days,
                        'leave_from'       => $leavefrom,
                        'leave_to'         => $leaveto,
                        'employee_remark'  => $reason,
                        'status'           => $status,
                        'admin_remark'     => $remark,
                        'applied_by'       => $applied_by,
                        'document_file'    => $document,
                    );
                } else {

                    $data = array('staff_id' => $staff_id, 'date' => date("Y-m-d", $this->customlib->datetostrtotime($applied_date)), 'leave_days' => $leave_days, 'leave_type_id' => $leavetype, 'leave_from' => $leavefrom, 'leave_to' => $leaveto, 'employee_remark' => $reason, 'status' => $status, 'admin_remark' => $remark, 'applied_by' => $applied_by, 'document_file' => $document);
                }

                $this->leaverequest_model->addLeaveRequest($data);

                $message_title = $this->lang->line('staff_leave');
                $message       = $this->input->post('reason') . '<br>' . $this->lang->line('apply_date') . ': ' . $this->input->post("applieddate") . '<br>' . $this->lang->line('from_date') . ': ' . $this->input->post('leave_from_date') . '<br>' . $this->lang->line('to_date') . ': ' . $this->input->post('leave_to_date');

                $this->mailer->send_mail($this->sch_setting_detail->staff_notification_email, $message_title, $message, $_FILES, '');

                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $msg = array(
                    'applieddate' => $this->lang->line('selected_leave_days') . " > " . $this->lang->line('available_leaves'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            }
        }
        echo json_encode($array);
    }

    public function handle_upload($str, $var)
    {

        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
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

            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;

    }
    
    public function downloadleaverequestdoc($staff_id, $id)
    {
        $doc = $this->leaverequest_model->get_staff_leave($id);        
        $this->media_storage->filedownload($doc['document_file'], "./uploads/staff_documents/$staff_id");

    }

}
