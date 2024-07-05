<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Class_section_time_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
         $this->current_session = $this->setting_model->getCurrentSession();
      
    }


  public function add($insert_data, $update_data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (!empty($insert_data)) {
            $this->db->insert_batch('class_section_times', $insert_data);
        }
        if (!empty($update_data)) {
            $this->db->update_batch('class_section_times', $update_data, 'id');
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }

    }


      public function getAttendanceNotSubmittedByTime($date,$time)
    {
     $sql="SELECT student_session.id as `student_session_id` FROM `class_section_times` JOIN `class_sections` ON `class_sections`.`id` = `class_section_times`.`class_section_id` and time >= TIME('11:00:00')  JOIN `classes` ON `classes`.`id` = `class_sections`.`class_id` JOIN `sections` ON `sections`.`id` = `class_sections`.`section_id` INNER JOIN  student_session  on student_session.class_id=classes.id and student_session.section_id=sections.id LEFT OUTER JOIN student_attendences on student_attendences.student_session_id=student_session.id and student_attendences.date=".$this->db->escape($date)." WHERE student_session.session_id=".$this->db->escape($this->current_session)." and student_attendences.student_session_id IS NULL ORDER BY `sections`.`section` ASC";
        $query = $this->db->query($sql);
        $result= $query->result();
        return $result;
    }




    public function allClassSections()
    {
        $classes = $this->class_model->get();
        if (!empty($classes)) {
            foreach ($classes as $class_key => $class_value) {
                $classes[$class_key]['sections'] = $this->getTime($class_value['id']);
            }
        }
        return $classes;
    }

    public function getTime($class_id){

          $query = "SELECT class_sections.*,sections.section,IFNULL(class_section_times.id,0) as `class_section_times_id`,IFNULL(class_section_times.time,0) as time from class_sections INNER JOIN sections on sections.id=class_sections.section_id left JOIN class_section_times on class_section_times.class_section_id=class_sections.id WHERE class_id=".$this->db->escape($class_id)." order by sections.section asc";
        $query = $this->db->query($query);
        $result= $query->result();
        return $result;
    }


 
}
