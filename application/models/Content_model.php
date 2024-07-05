<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Content_model extends MY_Model
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
        $this->db->select('contents.*,classes.class,sections.section,(select GROUP_CONCAT(role) FROM content_for WHERE content_id=contents.id) as role,class_sections.id as `aa`')->from('contents');
        $this->db->join('class_sections', 'contents.cls_sec_id = class_sections.id', 'left outer');
        $this->db->join('classes', 'class_sections.class_id = classes.id', 'left outer');
        $this->db->join('sections', 'class_sections.section_id = sections.id', 'left outer');
        if ($id != null) {
            $this->db->where('contents.id', $id);
        }
        $this->db->order_by('contents.id', "desc");
        $this->db->limit(10);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getContentByRole($id = null, $role = null)
    {
        $inner_sql = "";

        if ($role == "student") {
            $inner_sql = " WHERE (role='student' and created_by='" . $id . "' ) or (created_by=0 and role='" . $role . "')";
        } elseif ($role == "Teacher") {
            $inner_sql = " WHERE (role='Teacher' and created_by='" . $id . "' ) or (created_by=0 and role='" . $role . "')";
        }
        $query = "SELECT contents.*,(select GROUP_CONCAT(role) FROM content_for WHERE content_id=contents.id) as role,class_sections.id as `class_section_id`,classes.class,sections.section  FROM `content_for`  INNER JOIN contents on contents.id=content_for.content_id left JOIN class_sections on class_sections.id=contents.cls_sec_id left join classes on classes.id=class_sections.class_id LEFT JOIN sections on sections.id=class_sections.section_id" . $inner_sql . " GROUP by contents.id";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getListByCategory($category)
    {
        $this->db->select('contents.*,classes.class,sections.section')->from('contents');
        $this->db->join('classes', 'contents.class_id = classes.id', 'left');
        $this->db->join(' class_sections', 'contents.cls_sec_id =  class_sections.id', 'left');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->where('contents.type', $category);
        $this->db->order_by('contents.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getListByCategoryforUser($class_id, $section_id, $category = '')
    {

        if (empty($class_id)) {
            $class_id = "0";
        }

        if (empty($section_id)) {
            $section_id = "0";
        }
        $query = "SELECT contents.*,class_sections.id as `class_section_id`,classes.class,sections.section FROM `content_for` INNER JOIN contents on content_for.content_id=contents.id left JOIN class_sections on class_sections.id=contents.cls_sec_id left join classes on classes.id=class_sections.class_id LEFT JOIN sections on sections.id=class_sections.section_id WHERE  (role='student' and contents.type='" . $category . "' and contents.is_public='yes') or (classes.id =" . $class_id . " and sections.id=" . $section_id . " and role='student' and contents.type='" . $category . "')";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getListByforUser($class_id, $section_id)
    {

        if (empty($class_id)) {
            $class_id = "0";
        }

        if (empty($section_id)) {
            $section_id = "0";
        }
        
        $query = "SELECT contents.*,class_sections.id as `class_section_id`,classes.class,sections.section FROM `content_for` INNER JOIN contents on content_for.content_id=contents.id left JOIN class_sections on class_sections.id=contents.cls_sec_id left join classes on classes.id=class_sections.class_id LEFT JOIN sections on sections.id=class_sections.section_id WHERE  (role='student' and contents.is_public='yes') or (classes.id =" . $class_id . " and sections.id=" . $section_id . " and role='student')";
        $query = $this->db->query($query);
        return $query->result_array();
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
        $this->db->delete('contents');
        $message   = DELETE_RECORD_CONSTANT . " On contents id " . $id;
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

    public function search_by_content_type($text)
    {
        $this->db->select()->from('contents');
        $this->db->or_like('contents.content_type', $text);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data, $content_role = array())
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('contents', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  contents id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('contents', $data);
            $insert_id = $this->db->insert_id();
            if (isset($content_role) && !empty($content_role)) {
                $total_rec = count($content_role);
                for ($i = 0; $i < $total_rec; $i++) {
                    $content_role[$i]['content_id'] = $insert_id;
                }
                $this->db->insert_batch('content_for', $content_role);
            }
            $message   = INSERT_RECORD_CONSTANT . " On contents id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
         
    }

}
