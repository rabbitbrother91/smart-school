<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studenttransportfee_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data_insert, $student_session_id, $remove_ids, $route_pickup_point_id)
    {

        $new_inserted = array();
        $this->db->trans_begin();

        $this->db->where('route_pickup_point_id !=', $route_pickup_point_id);
        $this->db->where('student_session_id', $student_session_id);
        $this->db->delete('student_transport_fees');

        if (!empty($remove_ids)) {

            $this->db->where_in('id', $remove_ids);
            $this->db->where('student_session_id', $student_session_id);
            $this->db->delete('student_transport_fees');
        }

        if (!empty($data_insert)) {
            foreach ($data_insert as $insert_key => $insert_value) {
                $this->db->insert('student_transport_fees', $insert_value);

            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function update($data_insert, $student_session_id)
    {

        $new_inserted = array();
        $this->db->trans_begin();

        if (!empty($data_insert)) {
            $row_insert = array();
            foreach ($data_insert as $insert_key => $insert_value) {
                # code...
                $this->db->where('student_session_id', $student_session_id);
                $this->db->where('route_pickup_point_id', $insert_value['route_pickup_point_id']);
                $this->db->where('transport_feemaster_id', $insert_value['transport_feemaster_id']);
                $q = $this->db->get('student_transport_fees');

                if ($q->num_rows() > 0) {
                    $row_insert[] = $q->row()->id;
                } else {

                    $this->db->insert('student_transport_fees', $data_insert[$insert_key]);
                    $row_insert[] = $this->db->insert_id();
                }
            }
            $this->db->where('student_session_id', $student_session_id);
            $this->db->where_not_in('id', $row_insert);
            $this->db->delete('student_transport_fees');

        } else {

            $this->db->where('student_session_id', $student_session_id);
            $this->db->delete('student_transport_fees');
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getTransportFeeByStudentSession($student_session_id, $route_pickup_point_id)
    {

        if ($student_session_id != null && $route_pickup_point_id != null) {

            $sql = "SELECT transport_feemaster.*,student_transport_fees.id as student_transport_fee_id FROM `transport_feemaster` LEFT JOIN student_transport_fees on transport_feemaster.id = student_transport_fees.transport_feemaster_id and student_transport_fees.route_pickup_point_id=" . $route_pickup_point_id . " and student_transport_fees.student_session_id=" . $student_session_id . " WHERE transport_feemaster.session_id=" . $this->current_session . " ORDER by transport_feemaster.id";

        } elseif ($student_session_id != null && $route_pickup_point_id == null) {
            $sql = "SELECT transport_feemaster.*,IFNULL(student_transport_fees.id,0) as student_transport_fee_id FROM `transport_feemaster` LEFT JOIN student_transport_fees on transport_feemaster.id = student_transport_fees.transport_feemaster_id and student_transport_fees.student_session_id=" . $student_session_id . " WHERE transport_feemaster.session_id=" . $this->current_session . " ORDER by transport_feemaster.id";
        }

        $query = $this->db->query($sql);
        return $query->result_array();

    }

     public function getTransportFeeByMonthStudentSession($student_session_id, $route_pickup_point_id,$month)
    {

        if ($student_session_id != null && $route_pickup_point_id != null) {

            $sql = "SELECT transport_feemaster.*,student_transport_fees.id as student_transport_fee_id FROM `transport_feemaster` LEFT JOIN student_transport_fees on transport_feemaster.id = student_transport_fees.transport_feemaster_id and student_transport_fees.route_pickup_point_id=" . $route_pickup_point_id . " and student_transport_fees.student_session_id=" . $student_session_id . " WHERE transport_feemaster.session_id=" . $this->current_session . " and transport_feemaster.month='" . $month . "' ORDER by transport_feemaster.id";

        } elseif ($student_session_id != null && $route_pickup_point_id == null) {
            $sql = "SELECT transport_feemaster.*,IFNULL(student_transport_fees.id,0) as student_transport_fee_id FROM `transport_feemaster` LEFT JOIN student_transport_fees on transport_feemaster.id = student_transport_fees.transport_feemaster_id and student_transport_fees.student_session_id=" . $student_session_id . " WHERE transport_feemaster.session_id=" . $this->current_session . " and transport_feemaster.month='". $month . "' ORDER by transport_feemaster.id";
        }

        $query = $this->db->query($sql);
        return $query->row_array();

    }

    public function getTransportFeeMasterByStudentTransportID($student_transport_fee_id)
    {

        $this->db->select('transport_feemaster.*,route_pickup_point.fees as `amount`');
        $this->db->join('transport_feemaster', 'transport_feemaster.id=student_transport_fees.transport_feemaster_id');
        $this->db->join('route_pickup_point', 'route_pickup_point.id=student_transport_fees.route_pickup_point_id');
        $this->db->where('student_transport_fees.id', $student_transport_fee_id);
        $q = $this->db->get('student_transport_fees');

        return $q->row();

    }


    public function getTransportDepositAmountBetweenDate($start_date, $end_date)
    {
        $this->db->select('`student_fees_deposite`.*')
        ->from('student_fees_deposite')
        ->join('student_transport_fees', 'student_transport_fees.id=student_fees_deposite.student_transport_fee_id')
        ->join('transport_feemaster', 'transport_feemaster.id=student_transport_fees.transport_feemaster_id')
        ->where('transport_feemaster.session_id', $this->current_session);
        $this->db->order_by('student_fees_deposite.id');
        $query        = $this->db->get();
        $result_value = $query->result();
        $return_array = array();
        if (!empty($result_value)) {
            $st_date = strtotime($start_date);
            $ed_date = strtotime($end_date);
            foreach ($result_value as $key => $value) {
                $return = $this->findObjectById($value, $st_date, $ed_date);

                if (!empty($return)) {
                    foreach ($return as $r_key => $r_value) {
                        $a                    = array();
                        $a['amount']          = $r_value->amount;
                        $a['date']            = $r_value->date;
                        $a['amount_discount'] = $r_value->amount_discount;
                        $a['amount_fine']     = $r_value->amount_fine;
                        $a['description']     = $r_value->description;
                        $a['payment_mode']    = $r_value->payment_mode;
                        $a['inv_no']          = $r_value->inv_no;
                        $return_array[]       = $a;
                    }
                }
            }
        }

        return $return_array;
    }

    public function findObjectById($array, $st_date, $ed_date)
    {
        $ar = json_decode($array->amount_detail);

        $array = array();
        for ($i = $st_date; $i <= $ed_date; $i += 86400) {
            $find = date('Y-m-d', $i);
            foreach ($ar as $row_key => $row_value) {
                if ($row_value->date == $find) {
                    $array[] = $row_value;
                }
            }
        }

        return $array;
    }
    


}
