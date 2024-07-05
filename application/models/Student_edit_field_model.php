<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student_edit_field_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function add($record) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

        $this->db->where('name', $record['name']);
        $q = $this->db->get('student_edit_fields');

        if ($q->num_rows() > 0) {
            $results = $q->row();
            $this->db->where('id', $results->id);
            $this->db->update('student_edit_fields', $record);
            $message = UPDATE_RECORD_CONSTANT . " On  student edit fields id " . $results->id;
            $action = "Update";
            $record_id = $insert_id = $results->id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_edit_fields', $record);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On student edit fields id " . $insert_id;
            $action = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function get() {
        $this->db->select('*');
        $this->db->from('student_edit_fields');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function checkprofilesettingfieldexist($fieldname)
    {
        $this->db->where('name', $fieldname);
        $this->db->select('status');
        $this->db->from('student_edit_fields');
        $query  = $this->db->get();
        $result = $query->row_array();
        if(!empty($result)){
        return $result['status'];
        }
    }

}
