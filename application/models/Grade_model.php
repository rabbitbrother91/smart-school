<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Grade_model extends MY_Model
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
    public function getGradeDetails()
    {
        $grade_types     = array();
        $grade_type_list = $this->config->item('exam_type');

        if (!empty($grade_type_list)) {
            foreach ($grade_type_list as $exm_type_key => $exm_type_value) {
                $grade_types[] = array(
                    'exam_key'          => $exm_type_key,
                    'exm_type_value'    => $exm_type_value,
                    'exam_grade_values' => $this->getfeeTypeByGroup($exm_type_key),
                );
            }
        }
        return $grade_types;
    }

    public function getfeeTypeByGroup($exm_type_key)
    {
        $this->db->select()->from('grades');
        $this->db->where('grades.exam_type', $exm_type_key);
        $this->db->order_by('grades.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get($id = null)
    {
        $this->db->select()->from('grades');
        if ($id != null) {
            $this->db->where('grades.id', $id);
        } else {
            $this->db->order_by('grades.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getByExamType($exam_type = null)
    {
        $this->db->select()->from('grades');
        $this->db->where('grades.exam_type', $exam_type);
        $this->db->order_by('grades.id');
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
        $this->db->where('id', $id);
        $this->db->delete('grades');
        $message   = DELETE_RECORD_CONSTANT . " On grades id " . $id;
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
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('grades', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On grades id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('grades', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On grades id " . $id;
            $action    = "Update";
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

    public function check_Exits_group($data)
    {
        $this->db->select('*');
        $this->db->from('feemasters');
        $this->db->where('session_id', $this->current_session);
        $this->db->where('feetype_id', $data['feetype_id']);
        $this->db->where('class_id', $data['class_id']);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

}
