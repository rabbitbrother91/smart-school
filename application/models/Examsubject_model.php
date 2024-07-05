<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examsubject_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add($insert_array, $update_array, $not_be_del, $exam_id)
    {
        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $this->db->insert('exam_group_class_batch_exam_subjects', $insert_array[$insert_key]);
                $not_be_del[] = $this->db->insert_id();
            }
        }
        if (!empty($update_array)) {
            $this->db->update_batch('exam_group_class_batch_exam_subjects', $update_array, 'id');
        }

        if (!empty($not_be_del)) {
            $this->db->where('exam_group_class_batch_exams_id', $exam_id);
            $this->db->where_not_in('id', $not_be_del);
            $this->db->delete('exam_group_class_batch_exam_subjects');
        }
    }

}
