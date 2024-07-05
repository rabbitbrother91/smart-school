<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class OfflinePayment_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function get($id = null)
    {
        $this->db->select('offline_fees_payments.*,fee_groups_feetype.due_date,feetype.type,feetype.code,feetype.is_system,fee_groups.name as `fee_group_name`,transport_feemaster.month,transport_feemaster.due_date as `transport_feemaster_due_date`,pickup_point.name as `pickup_point`,transport_route.route_title,classes.id AS `class_id`,student_session.id as student_session_id,students.id as `student_id`,classes.class,sections.id AS `section_id`,sections.section,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`, students.cast')->from('offline_fees_payments');
        $this->db->join('fee_groups_feetype', 'offline_fees_payments.fee_groups_feetype_id = fee_groups_feetype.id', 'left');
        $this->db->join('fee_groups', 'fee_groups_feetype.fee_groups_id = fee_groups.id', 'left');
        $this->db->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id', 'left');
        $this->db->join('student_transport_fees', 'offline_fees_payments.student_transport_fee_id = student_transport_fees.id', 'left');
        $this->db->join('transport_feemaster', 'student_transport_fees.transport_feemaster_id = transport_feemaster.id', 'left');
        $this->db->join('route_pickup_point', 'student_transport_fees.route_pickup_point_id = route_pickup_point.id', 'left');
        $this->db->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left');
        $this->db->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left');
        $this->db->join('student_session', 'student_session.id = offline_fees_payments.student_session_id');
        $this->db->join('students', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('categories', 'students.category_id = categories.id', 'left');
        if ($id != null) {
            $this->db->where('offline_fees_payments.id', $id);
        } else {
            $this->db->order_by('offline_fees_payments.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('offline_fees_payments', $data);
        } else {
            $this->db->insert('offline_fees_payments', $data);
            return $this->db->insert_id();
        }
    }

    public function getPaymentlistByUser($student_session_id)
    {
        $this->datatables
            ->select('offline_fees_payments.*,student_fees_master.id as `student_fees_master_id`,fee_groups_feetype.due_date,feetype.type,feetype.code,fee_groups.name as `fee_group_name`,transport_feemaster.month,transport_feemaster.due_date as `transport_feemaster_due_date`,pickup_point.name as `pickup_point`,transport_route.route_title,classes.id AS `class_id`,student_session.id as student_session_id,students.id as `student_id`,classes.class,sections.id AS `section_id`,sections.section,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`, students.cast')
            ->join("student_fees_master", "student_fees_master.id=offline_fees_payments.student_fees_master_id", "left")
            ->join("fee_groups_feetype", "fee_groups_feetype.id=offline_fees_payments.fee_groups_feetype_id", "left")
            ->join("student_transport_fees", "student_transport_fees.id=offline_fees_payments.student_transport_fee_id", "left")

            ->join('fee_groups', 'fee_groups_feetype.fee_groups_id = fee_groups.id', 'left')
            ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id', 'left')
            ->join('transport_feemaster', 'student_transport_fees.transport_feemaster_id = transport_feemaster.id', 'left')
            ->join('route_pickup_point', 'student_transport_fees.route_pickup_point_id = route_pickup_point.id', 'left')
            ->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left')
            ->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left')
            ->join('student_session', 'student_session.id = offline_fees_payments.student_session_id')
            ->join('students', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')
            ->where('offline_fees_payments.student_session_id', $student_session_id)

            ->searchable('offline_fees_payments.id,offline_fees_payments.payment_date,offline_fees_payments.bank_from,offline_fees_payments.bank_account_transferred,offline_fees_payments.reference,offline_fees_payments.amount,offline_fees_payments.submit_date,offline_fees_payments.approve_date')
            ->orderable('offline_fees_payments.id,offline_fees_payments.payment_date,offline_fees_payments.submit_date,offline_fees_payments.amount,offline_fees_payments.is_active,offline_fees_payments.approve_date,offline_fees_payments.invoice_id')
            ->sort('offline_fees_payments.submit_date', 'desc')

            ->from('offline_fees_payments');
        return $this->datatables->generate('json');

    }

    public function getPaymentlist()
    {

        $this->datatables
            ->select('offline_fees_payments.*,student_fees_master.id as `student_fees_master_id`,fee_groups_feetype.due_date,feetype.type,feetype.code,fee_groups.name as `fee_group_name`,transport_feemaster.month,transport_feemaster.due_date as `transport_feemaster_due_date`,pickup_point.name as `pickup_point`,transport_route.route_title,classes.id AS `class_id`,student_session.id as student_session_id,students.id as `student_id`,classes.class,sections.id AS `section_id`,sections.section,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,     students.dob ,students.current_address,    students.permanent_address,IFNULL(students.category_id, 0) as `category_id`,IFNULL(categories.category, "") as `category`, students.cast')
            ->join("student_fees_master", "student_fees_master.id=offline_fees_payments.student_fees_master_id", "left")
            ->join("fee_groups_feetype", "fee_groups_feetype.id=offline_fees_payments.fee_groups_feetype_id", "left")
            ->join("student_transport_fees", "student_transport_fees.id=offline_fees_payments.student_transport_fee_id", "left")

            ->join('fee_groups', 'fee_groups_feetype.fee_groups_id = fee_groups.id', 'left')
            ->join('feetype', 'fee_groups_feetype.feetype_id = feetype.id', 'left')
            ->join('transport_feemaster', 'student_transport_fees.transport_feemaster_id = transport_feemaster.id', 'left')
            ->join('route_pickup_point', 'student_transport_fees.route_pickup_point_id = route_pickup_point.id', 'left')
            ->join('pickup_point', 'route_pickup_point.pickup_point_id = pickup_point.id', 'left')
            ->join('transport_route', 'route_pickup_point.transport_route_id = transport_route.id', 'left')
            ->join('student_session', 'student_session.id = offline_fees_payments.student_session_id')
            ->join('students', 'student_session.student_id = students.id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('categories', 'students.category_id = categories.id', 'left')

            ->searchable('offline_fees_payments.id,students.admission_no,students.firstname,classes.class,offline_fees_payments.payment_date,offline_fees_payments.submit_date,offline_fees_payments.amount,offline_fees_payments.is_active,offline_fees_payments.approve_date,offline_fees_payments.invoice_id,offline_fees_payments.id')
            ->orderable('offline_fees_payments.id,students.admission_no,students.firstname,classes.class,offline_fees_payments.payment_date,offline_fees_payments.submit_date,offline_fees_payments.amount,offline_fees_payments.is_active,offline_fees_payments.approve_date,offline_fees_payments.invoice_id,offline_fees_payments.id')

            ->sort('offline_fees_payments.submit_date', 'desc')

            ->from('offline_fees_payments');
        return $this->datatables->generate('json');

    }

    public function update($data)
    {

        $amount = ($data['amount']);
        $fine   = ($data['fine']);

        if (isset($data['id']) && $data['is_active']) {
            unset($data['amount']);
            unset($data['fine']);
            $this->db->trans_start();
            $this->db->trans_strict(false);

            $fees_detail = $this->get($data['id']);

            if ($data['is_active'] == 1) {

                $fee_transaction = [];

                if (IsNullOrEmptyString($fees_detail->student_transport_fee_id)) {

                    $fee_transaction['fee_category'] = "fees";
                } else {
                    $fee_transaction['fee_category'] = "transport";
                }

                $staff_record = $this->staff_model->get($this->customlib->getStaffID());

                $collected_by                                = $this->customlib->getAdminSessionUserName() . "(" . $staff_record['employee_id'] . ")";
                $fee_transaction['student_fees_master_id']   = $fees_detail->student_fees_master_id;
                $fee_transaction['fee_groups_feetype_id']    = $fees_detail->fee_groups_feetype_id;
                $fee_transaction['student_transport_fee_id'] = $fees_detail->student_transport_fee_id;

                $json_array = array(
                    'amount'          => $amount,
                    'amount_discount' => 0,
                    'amount_fine'     => $fine,
                    'date'            => $fees_detail->payment_date,
                    'description'     => 'Amount credited through offline bank payment Request ID : ' . $fees_detail->id,
                    'collected_by'    => $collected_by,
                    'payment_mode'    => 'bank_payment',
                    'received_by'     => $staff_record['id'],
                );

                $fee_transaction['amount_detail'] = $json_array;

                $return_array = $this->studentfeemaster_model->fee_deposit($fee_transaction, null, null);

                $return = json_decode($return_array);

                $data['invoice_id'] = ($return->invoice_id . "/" . $return->sub_invoice_id);
            }

            $this->db->where('id', $data['id']);
            $this->db->update('offline_fees_payments', $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === false) {

                $this->db->trans_rollback();
                return false;
            } else {

                $this->db->trans_commit();

                return true;
            }

        } else {
            $this->db->where('id', $data['id']);
            $this->db->update('offline_fees_payments', $data);
        }
    }

}
