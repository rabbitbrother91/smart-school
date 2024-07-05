<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feegrouptype_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('fee_groups_feetype');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getAll()
    {
        $query = "SELECT fee_groups_feetype.*,fee_groups.name as `fee_group_name` FROM `fee_groups_feetype` INNER JOIN fee_groups on fee_groups_feetype.fee_groups_id=fee_groups.id group BY fee_groups_id";

        $query                = $this->db->query($query);
        $fee_group_type_array = $query->result();

        if (!empty($fee_group_type_array)) {
            foreach ($fee_group_type_array as $key => $value) {
                $value->feetypes = $this->getfeeTypeByGroup($value->fee_groups_id);
            }
        }
        return $fee_group_type_array;
    }

    public function getSingleFeeGroup($id)
    {
        $query = "SELECT fee_groups_feetype.*,fee_groups.name as `fee_group_name` FROM `fee_groups_feetype` INNER JOIN fee_groups on fee_groups_feetype.fee_groups_id=fee_groups.id WHERE fee_groups_feetype.id=$id";
        $query                = $this->db->query($query);
        $fee_group_type_array = $query->result();

        if (!empty($fee_group_type_array)) {
            foreach ($fee_group_type_array as $key => $value) {
                $value->feetypes = $this->getfeeTypeByGroup($value->fee_groups_id);
            }
        }
        return $fee_group_type_array;
    }

   public function getFeeGroupByIDAndStudentSessionID($id,$student_session_id)
    {
        $query = "SELECT fee_groups_feetype.*,fee_groups.name as `fee_group_name`,feetype.type,feetype.code,student_fees_master.is_system,student_fees_master.amount as `balance_fee_master_amount` FROM `fee_groups_feetype` INNER JOIN fee_groups on fee_groups_feetype.fee_groups_id=fee_groups.id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id INNER JOIN fee_session_groups on fee_groups_feetype.fee_session_group_id=fee_session_groups.id  INNER JOIN student_fees_master on student_fees_master.fee_session_group_id=fee_groups_feetype.fee_session_group_id WHERE fee_groups_feetype.id=". $this->db->escape($id)." and student_fees_master.student_session_id=" . $this->db->escape($student_session_id);
        $query                = $this->db->query($query);
        $fee_group_type_array = $query->row();
        return $fee_group_type_array;
    }



    public function getFeeGroupByID($id)
    {
        $query                = "SELECT fee_groups_feetype.*,fee_groups.name as `fee_group_name`,feetype.type,feetype.code FROM `fee_groups_feetype` INNER JOIN fee_groups on fee_groups_feetype.fee_groups_id=fee_groups.id INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id WHERE fee_groups_feetype.id=" . $this->db->escape($id);
        $query                = $this->db->query($query);
        $fee_group_type_array = $query->row();
        return $fee_group_type_array;
    }

    public function getfeeTypeByGroup($id = null)
    {
        $this->db->select('fee_groups_feetype.*,feetype.type,feetype.code');
        $this->db->from('fee_groups_feetype');
        $this->db->join('feetype', 'feetype.id=fee_groups_feetype.feetype_id');
        $this->db->where('fee_groups_feetype.fee_groups_id', $id);
        $this->db->order_by('fee_groups_feetype.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->select()->from('fee_groups_feetype');
        $this->db->where('id', $id);
        $query                = $this->db->get();
        $result               = $query->row();
        $fee_session_group_id = $result->fee_session_group_id;

        $this->db->where('fee_session_group_id', $fee_session_group_id);
        $num_rows = $this->db->count_all_results('fee_groups_feetype');
        if ($num_rows == 1) {
            $this->db->where('id', $fee_session_group_id);
            $this->db->delete('fee_session_groups');
        }
        $this->db->where('id', $id);
        $this->db->delete('fee_groups_feetype');
        $message   = DELETE_RECORD_CONSTANT . " On fee groups fee type id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add1($data)
    {
        $class_section = $this->input->post('cls_sec');
        $this->db->trans_begin();
        $data_insert = array(
            'fee_groups_id' => $this->input->post('fee_groups_id'),
            'feetype_id'    => $this->input->post('feetype_id'),
            'amount'        => $this->input->post('amount'),
            'session_id'    => $this->current_session,
        );
        $this->db->insert('fee_groups_feetype', $data_insert);
        $fee_group_type_id = $this->db->insert_id();
        $array             = array();
        foreach ($class_section as $clssec_key => $clssec_value) {
            $sub_array = array('fee_groups_feetype' => $fee_group_type_id, 'class_section_id' => $clssec_value);
            $array[]   = $sub_array;
        }

        $this->db->insert_batch('fee_class_section_group', $array);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('fee_groups_feetype', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  fee groups fee type id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('fee_groups_feetype', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On fee groups fee type id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }

    }

    public function getFeeTypeDueDateReminder($date)
    {
        $query                = "SELECT fee_groups_feetype.*,feetype.type,feetype.code,fee_groups.name as fee_group_name FROM `fee_groups_feetype` INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id INNER JOIN fee_groups on fee_groups_feetype.fee_groups_id=fee_groups.id WHERE due_date= " . $this->db->escape($date);
        $query                = $this->db->query($query);
        $fee_group_type_array = $query->result();
        return $fee_group_type_array;
    }

    public function getFeeTypeStudents($fee_session_group_id, $fee_groups_feetype_id)
    {
        $query = "SELECT student_fees_master.*,student_fees_deposite.student_fees_master_id,student_fees_deposite.fee_groups_feetype_id,student_fees_deposite.amount_detail, students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`,   students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_email,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`app_key`,`students`.`parent_app_key`,`students`.`gender` FROM `student_fees_master` LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id = " . $this->db->escape($fee_groups_feetype_id) . " INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE students.is_active='yes' and student_fees_master.fee_session_group_id =" . $this->db->escape($fee_session_group_id);
        $query                = $this->db->query($query);
        $fee_group_type_array = $query->result();
        return $fee_group_type_array;
    }

}
