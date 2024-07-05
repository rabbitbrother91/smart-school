<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subjectgroup_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($classid = null) {
        $this->db->select('class_sections.id,class_sections.section_id,sections.section');
        $this->db->from('class_sections');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.class_id', $classid);
        $this->db->order_by('class_sections.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update($data) {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('subject_group_subjects', $data);
        }
    }

    public function check_data_exists($data) {
        $this->db->where('name', $data);
        $this->db->where('session_id', $this->current_session);

        $query = $this->db->get('subject_groups');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function class_exists($str) {

        $name = $this->security->xss_clean($str);
        $res = $this->check_data_exists($name);

        if ($res) {
            $id = $this->input->post('id');
            if (isset($id)) {
                if ($res->id == $id) {
                    return true;
                }
            }
            $this->form_validation->set_message('class_exists', $this->lang->line('already_exists'));
            return false;
        } else {
            return true;
        }
    }

    public function edit($data, $delete_sections, $add_sections, $delete_subjects, $add_subjects) {
        $this->db->trans_begin();
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('subject_groups', $data);
            $subject_group_id = $data['id'];
        }

        if (!empty($add_sections)) {
            $section_group_array = array();
            foreach ($add_sections as $section_group_key => $section_group_value) {

                $sections_array = array(
                    'subject_group_id' => $subject_group_id,
                    'class_section_id' => $section_group_value,
                    'session_id' => $this->setting_model->getCurrentSession(),
                );

                $section_group_array[] = $sections_array;
            }
            $this->db->insert_batch('subject_group_class_sections', $section_group_array);
        }
        if (!empty($add_subjects)) {
            $subject_group_subject_Array = array();
            foreach ($add_subjects as $sub_group_key => $sub_group_value) {

                $vehicle_array = array(
                    'subject_group_id' => $subject_group_id,
                    'subject_id' => $sub_group_value,
                    'session_id' => $this->setting_model->getCurrentSession(),
                );

                $subject_group_subject_Array[] = $vehicle_array;
            }
            $this->db->insert_batch('subject_group_subjects', $subject_group_subject_Array);
        }
        if (!empty($delete_sections)) {
            $this->db->where('subject_group_id', $data['id']);
            $this->db->where_in('class_section_id', $delete_sections);
            $this->db->delete('subject_group_class_sections');
        }
        if (!empty($delete_subjects)) {
            $this->db->where('subject_group_id', $data['id']);
            $this->db->where_in('subject_id', $delete_subjects);
            $this->db->delete('subject_group_subjects');
        }
    
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function add($data, $subject_group, $section_group) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('subject_groups', $data);
            $subject_group_id = $data['id'];

            $message = UPDATE_RECORD_CONSTANT . " On subject groups id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('subject_groups', $data);
            $subject_group_id = $this->db->insert_id();

            $message = INSERT_RECORD_CONSTANT . " On subject groups id " . $subject_group_id;
            $action = "Insert";
            $record_id = $subject_group_id;
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

        $subject_group_subject_Array = array();
        foreach ($subject_group as $sub_group_key => $sub_group_value) {

            $vehicle_array = array(
                'subject_group_id' => $subject_group_id,
                'subject_id' => $sub_group_value,
                'session_id' => $this->setting_model->getCurrentSession(),
            );

            $subject_group_subject_Array[] = $vehicle_array;
        }
        $this->db->insert_batch('subject_group_subjects', $subject_group_subject_Array);

        $section_group_array = array();
        foreach ($section_group as $section_group_key => $section_group_value) {

            $sections_array = array(
                'subject_group_id' => $subject_group_id,
                'class_section_id' => $section_group_value,
                'session_id' => $this->setting_model->getCurrentSession(),
            );

            $section_group_array[] = $sections_array;
        }
        $this->db->insert_batch('subject_group_class_sections', $section_group_array);
    }

    public function getDetailbyClassSection($class_id, $section_id) {
        $this->db->select('class_sections.*,subject_group_subjects.class,sections.section')->from('class_sections');
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->join('subject_group_subjects', 'subject_group_subjects.id = class_sections.class_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->where('class_sections.section_id', $section_id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getByID($id = null) {
        $this->db->select('subject_groups.*')->from('subject_groups');
        $this->db->where('subject_groups.session_id', $this->current_session);

        if ($id != null) {
            $this->db->where('subject_groups.id', $id);
        } else {
            $this->db->order_by('subject_groups.id', 'DESC');
        }

        $query = $this->db->get();
        $subject_groups = $query->result();
        if (!empty($subject_groups)) {
            foreach ($subject_groups as $subject_group_key => $subject_group_value) {
                $subject_groups[$subject_group_key]->group_subject = $this->getGroupsubjects($subject_group_value->id);
                $subject_groups[$subject_group_key]->sections = $this->getClassSectionByGroup($subject_group_value->id);
            }
        }
        return $subject_groups;
    }

    public function getClassSectionByGroup($subject_group_id) {

        $sql = "SELECT subject_group_class_sections.*,classes.id as `class_id`,classes.class,sections.id as `section_id`,sections.section FROM `subject_group_class_sections` INNER JOIN class_sections on class_sections.id=subject_group_class_sections.class_section_id INNER JOIN classes on classes.id=class_sections.class_id INNER join sections on sections.id=class_sections.section_id WHERE subject_group_class_sections.session_id=" . $this->db->escape($this->current_session) . " and subject_group_id=" . $this->db->escape($subject_group_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getGroupsubjects($subject_group_id ,$session_id=NULL) {
        $class_id = "";
        $subject_groupid_condition = "";
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
       $session_id=IsNullOrEmptyString($session_id) ? $this->current_session :$session_id;

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {


                $get_class = $this->teacher_model->get_classbysubject_group_id($subject_group_id);
                if (!empty($get_class)) {
                    $class_id = $get_class[0]['class_id'];
                }
                $my_classes = $this->teacher_model->my_classes($userdata['id']);
                if (!empty($my_classes)) {
                    if (in_array($class_id, $my_classes)) {

                        $subject_groupid_condition = "";
                    }
                } else {



                    $my_subjects = $this->teacher_model->get_subjectby_staffid($userdata['id']);
                    $subject_groupid_condition = " and subject_group_subjects.id in(" . $my_subjects['subject'] . ")";
                }
            }
        }

        $sql = "SELECT subject_group_subjects.*,subjects.name,subjects.code,subjects.type FROM `subject_group_subjects` INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id WHERE subject_group_id =" . $this->db->escape($subject_group_id) . " and session_id =" . $this->db->escape($session_id) . "" . $subject_groupid_condition;
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function remove($id) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('subject_groups');
        $message = DELETE_RECORD_CONSTANT . " On subject groups id " . $id;
        $action = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function getSubjectgroupbyTeacherid($staff_id) {
        return $this->db->select('GROUP_CONCAT(subject_group_id) as subject_group_ids')->from('subject_timetable')->where('staff_id', $staff_id)->group_by('staff_id')->get()->result_array();
    }



    public function getGroupByClassandSection($class_id, $section_id,$session_id=NULL) {
        $return = true;
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];
        $subject_groupid_condition = "";
        $session_id=IsNullOrEmptyString($session_id) ? $this->current_session :$session_id;

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {


                $subject_groupid = $this->subjectgroup_model->getSubjectgroupbyTeacherid($userdata['id']);

                $my_classes = $this->teacher_model->my_classes($userdata['id']);

                if (in_array($class_id, $my_classes)) {

                    $subject_groupid_condition = "";
                } else {

                    if (!empty($subject_groupid)) {

                        $subject_groupid_condition = " and subject_groups.id in(" . $subject_groupid[0]['subject_group_ids'] . ")";
                    } else {

                        $return = false;
                    }
                }
            }
        }

        if ($return) {
            $sql = "SELECT subject_groups.name, subject_group_class_sections.* from subject_group_class_sections INNER JOIN class_sections on class_sections.id=subject_group_class_sections.class_section_id INNER JOIN subject_groups on subject_groups.id=subject_group_class_sections.subject_group_id WHERE class_sections.class_id=" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id) . " and subject_groups.session_id=" . $this->db->escape($session_id) . " " . $subject_groupid_condition . " ORDER by subject_groups.id DESC";
            $query = $this->db->query($sql);

            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getClassandSectionTimetable($class_id, $section_id) {

        $sql = "SELECT subject_group_class_sections.*,subject_group_subjects.id as `subject_group_id`,subject_group_subjects.subject_id,subjects.name,subjects.code,subject_timetable.day,subject_timetable.staff_id,subject_timetable.time_from,subject_timetable.time_to,subject_timetable.room_no,staff.name as `staff_name`,staff.surname FROM `class_sections` INNER JOIN subject_group_class_sections on subject_group_class_sections.class_section_id=class_sections.id INNER JOIN subject_group_subjects on subject_group_subjects.subject_group_id=subject_group_class_sections.subject_group_id INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id INNER JOIN subject_timetable on subject_timetable.subject_group_subject_id=subject_group_subjects.id inner JOIN staff on staff.id= subject_timetable.staff_id WHERE class_sections.class_id=" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id) . " and subject_group_class_sections.session_id=" . $this->db->escape($this->current_session);

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function check_section_exists($str) {
        $sections = $this->input->post('sections');
        if (!isset($sections)) {
            return true;
        }
        $id = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }

        if ($this->check_section_data_exists($sections, $id)) {
          
            $this->form_validation->set_message('check_section_exists', $this->lang->line('subjects_already_assigned'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_section_data_exists($sections, $id) {

        $this->db->where('session_id', $this->current_session);
        $this->db->where_in('class_section_id', $sections);
        $this->db->where('subject_group_id !=', $id);

        $query = $this->db->get('subject_group_class_sections');
        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function getsubject($class_id,$section_id) {
        return $this->db->select('subject_group_subjects.id,subjects.name,subjects.code')
        ->from('subject_timetable')
        ->join("subject_group_subjects", "subject_group_subjects.subject_group_id = subject_timetable.subject_group_id")
        ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
        ->where('subject_timetable.class_id', $class_id)
        ->where('subject_timetable.section_id', $section_id)
        ->group_by('subjects.id')
        ->get()->result_array();
    }

    public function getAllsubjectByClassSection($class_id,$section_id){
        $sql = "SELECT subject_group_class_sections.*,subject_groups.name as subject_group_name,subject_group_subjects.id as subject_group_subject_id,subjects.id as subject_id,subjects.name as subject_name,subjects.code as subject_code FROM `subject_group_class_sections` INNER JOIN class_sections on subject_group_class_sections.class_section_id=class_sections.id INNER JOIN subject_groups on subject_groups.id=subject_group_class_sections.subject_group_id  INNER JOIN subject_group_subjects on subject_group_subjects.subject_group_id=subject_groups.id INNER JOIN subjects on subjects.id=subject_group_subjects.subject_id WHERE  class_sections.class_id=" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id) . " and subject_group_class_sections.session_id=" . $this->db->escape($this->current_session);

        $query = $this->db->query($sql);
        return $query->result();
    }

}
