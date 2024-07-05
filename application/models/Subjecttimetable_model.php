<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Subjecttimetable_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($delete_array, $insert_array, $update_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        if (!empty($delete_array)) {
            $this->db->where_in('id', $delete_array);
            $this->db->delete('subject_timetable');
        }

        if (isset($update_array) && !empty($update_array)) {
            $this->db->update_batch('subject_timetable', $update_array, 'id');
        }

        if (isset($insert_array) && !empty($insert_array)) {
            $this->db->insert_batch('subject_timetable', $insert_array);
            $count = count($insert_array);
            $id    = $this->db->insert_id();
            $loop  = $id - $count;
            for ($x = $id; $x > $loop; $x--) {
                $message   = INSERT_RECORD_CONSTANT . " On  subject timetable id " . $x;
                $action    = "Insert";
                $record_id = $x;
                $this->log($message, $record_id, $action);
            }
        }

        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get($id)
    {
        $this->db->select('subject_timetable.*,subject_group_subjects.subject_id,subjects.name,subjects.code,subjects.type');
        $this->db->from('subject_timetable');
        $this->db->join('subject_group_subjects', 'subject_timetable.subject_group_subject_id = subject_group_subjects.id');
        $this->db->join('staff', 'staff.id = subject_timetable.staff_id');
        $this->db->join('subjects', 'subjects.id = subject_group_subjects.subject_id');
        $this->db->where('subject_timetable.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function getBySubjectGroupDayClassSection($subject_group_id, $day, $class_id, $section_id)
    {
        $this->db->select('subject_timetable.*');
        $this->db->from('subject_timetable');
        $this->db->join('subject_group_subjects', 'subject_timetable.subject_group_subject_id = subject_group_subjects.id');
        $this->db->join('staff', 'staff.id = subject_timetable.staff_id');
        $this->db->where('subject_timetable.class_id', $class_id);
        $this->db->where('subject_timetable.section_id', $section_id);
        $this->db->where('subject_timetable.day', $day);
        $this->db->where('subject_timetable.subject_group_id', $subject_group_id);
        $this->db->where('staff.is_active', 1);
        $this->db->order_by('subject_timetable.start_time', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getSubjectByClassandSectionDay($class_id, $section_id, $day)
    {
        $subject_condition = "";
        $userdata          = $this->customlib->getUserData();

        $role_id = $userdata["role_id"];
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {

                $my_classes = $this->teacher_model->my_classes($userdata['id']);

                if (!empty($my_classes)) {
                    if (in_array($class_id, $my_classes)) {
                        $subject_condition = "";
                    } else {
                        $my_subjects = $this->teacher_model->get_subjectby_classid($class_id, $section_id, $userdata['id']);
                        $subject_condition = " and subject_group_subjects.id in(" . $my_subjects['subject'] . ")";
                    }
                } else {
                    $my_subjects = $this->teacher_model->get_subjectby_classid($class_id, $section_id, $userdata['id']);
                    $subject_condition = " and subject_group_subjects.id in(" . $my_subjects['subject'] . ")";
                }
            }
        }
        $subject_condition = $subject_condition . " and staff.is_active=1 order by subject_timetable.start_time asc";

        $sql = "SELECT `subject_group_subjects`.`subject_id`,subjects.name as `subject_name`,subjects.code,subjects.type,staff.name,staff.surname,staff.employee_id,`subject_timetable`.* FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id`inner JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id   WHERE `subject_timetable`.`class_id` = " . $class_id . " AND `subject_timetable`.`section_id` = " . $section_id . " AND `subject_timetable`.`day` = " . $this->db->escape($day) . " AND `subject_timetable`.`session_id` = " . $this->current_session . "" . $subject_condition;

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getparentSubjectByClassandSectionDay($class_id, $section_id, $day)
    {
        $sql   = "SELECT `subject_group_subjects`.`subject_id`,subjects.name as `subject_name`,subjects.code,subjects.type,staff.name,staff.surname,staff.employee_id,staff.image,staff.gender,`subject_timetable`.*,class_sections.id as class_section_id ,subject_group_class_sections.id as `subject_group_class_sections_id` FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id`inner JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id inner JOIN class_sections on class_sections.class_id=subject_timetable.class_id and class_sections.section_id=subject_timetable.section_id INNER JOIN subject_group_class_sections on subject_group_class_sections.class_section_id=class_sections.id WHERE `subject_timetable`.`class_id` = " . $this->db->escape($class_id) . " AND `subject_timetable`.`section_id` = " . $this->db->escape($section_id) . " AND `subject_timetable`.`day` = " . $this->db->escape($day) . " AND `subject_timetable`.`session_id` = " . $this->current_session . " and staff.is_active=1 and subject_group_class_sections.session_id=" . $this->current_session . " order by subject_timetable.start_time asc";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getTeacherByClassandSection($class_id, $section_id)
    {
        $condition  = " and staff.is_active='1'";
        $condition1 = " and st.is_active='1'";
        if ($class_id != '') {
            $condition .= " and `subject_timetable`.`class_id` = " . $class_id . "";
            $condition1 .= " and `ct1`.`class_id` = " . $class_id . "";
        }

        if ($section_id != '') {
            $condition .= " AND `subject_timetable`.`section_id` = " . $section_id . "";
            $condition1 .= " AND `ct1`.`section_id` = " . $section_id . "";
        }

        $sql = "SELECT 'subject' type, ct.staff_id as class_teacher,`subject_group_subjects`.`subject_id` as subject_id,subjects.name as `subject_name`,subjects.code as code,subjects.type as type,staff.name,staff.surname,staff.email,staff.contact_no,staff.employee_id,`subject_timetable`.staff_id as staff_id,staff.image,staff.gender,`subject_timetable`.time_from as time_from,`subject_timetable`.day as day,`subject_timetable`.room_no as room_no,`subject_timetable`.time_to as time_to ,`subject_timetable`.start_time as start_time ,sec.section as section_name,cl.class as class_name FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id` left JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id LEFT JOIN classes cl on cl.id=subject_timetable.class_id LEFT JOIN sections as sec on sec.id=subject_timetable.section_id left join class_teacher ct on (ct.class_id=cl.id and ct.staff_id=staff.id and ct.section_id=sec.id) WHERE 1=1 " . $condition . " AND `subject_timetable`.`session_id` = '" . $this->current_session . "'         
        UNION SELECT 'class' type, ct1.staff_id as class_teacher,'' as subject_id,'' as `subject_name`,'' as code,'' as type,st.name,st.surname,st.email,st.contact_no,st.employee_id,st.id as staff_id,st.image,st.gender,'' as time_from,'' as time_to,'' as start_time,'' as day,'' as room_no,secs.section as section_name,cls.`class` as class_name FROM class_teacher ct1 inner join staff st on st.id=ct1.staff_id INNER join classes cls on cls.id=ct1.class_id inner join sections secs on secs.id=ct1.section_id where 1=1 AND ct1.`session_id` = '" . $this->current_session . "' " . $condition1 . " ";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function getSubjectByClassandSection($class_id, $section_id)
    {
        $condition = '';
        if ($class_id != '') {
            $condition .= " and `subject_timetable`.`class_id` = " . $class_id . "";
        }

        if ($section_id != '') {
            $condition .= " AND `subject_timetable`.`section_id` = " . $section_id . "";
        }

        $sql = "SELECT ct.staff_id as class_teacher,`subject_group_subjects`.`subject_id`,subjects.name as `subject_name`,subjects.code,subjects.type,staff.name,staff.surname,staff.employee_id,`subject_timetable`.*,sec.section as section_name,cl.class as class_name FROM `subject_timetable` JOIN `subject_group_subjects` ON `subject_timetable`.`subject_group_subject_id` = `subject_group_subjects`.`id`inner JOIN subjects on subject_group_subjects.subject_id = subjects.id INNER JOIN staff on staff.id=subject_timetable.staff_id LEFT JOIN classes cl on cl.id=subject_timetable.class_id LEFT JOIN sections as sec on sec.id=subject_timetable.section_id left join class_teacher ct on (ct.class_id=cl.id and ct.staff_id=staff.id and ct.section_id=sec.id) WHERE 1=1 " . $condition . " AND `subject_timetable`.`session_id` = " . $this->current_session . " AND `staff`.`is_active` =1 ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getSyllabussubject($staff_id, $day_value, $class_section_array)
    {
        if (!empty($class_section_array)) {
            $this->db->group_start();
            foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                    $this->db->or_group_start();
                    $this->db->where('subject_timetable.class_id', $class_sectionkey);
                    $this->db->where('subject_timetable.section_id', $class_sectionvaluevalue);
                    $this->db->group_end();

                }
            }
            $this->db->group_end();
        }

        $result = $this->db->select('`classes`.`class`,`sections`.`section`,`subject_group_subjects`.`subject_id`,`sub`.`name` as `subject_name`,`sub`.`code` as `subject_code`,`subject_timetable`.*,class_sections.id as class_section_id')->from('subject_timetable')->join('class_sections', 'class_sections.class_id=subject_timetable.class_id and class_sections.section_id=subject_timetable.section_id')->join('classes', 'classes.id = `class_sections`.`class_id`')->join('sections', '`sections`.`id`=`class_sections`.`section_id`')->join('subject_group_subjects', '`subject_group_subjects`.`id`=`subject_timetable`.`subject_group_subject_id`')->join('subjects as sub', '`sub`.`id`=`subject_group_subjects`.`subject_id`')->where('subject_timetable.session_id', $this->current_session)->where('subject_group_subjects.session_id', $this->current_session)->where('subject_timetable.day', $day_value)->where('subject_timetable.staff_id', $staff_id)->get()->result();

        return $result;

    }

    public function getByStaffandDay($staff_id, $day_value)
    {
        $sql   = "SELECT `classes`.`class`,`sections`.`section`,`subject_group_subjects`.`subject_id`,`sub`.`name` as `subject_name`,`sub`.`code` as `subject_code`,`subject_timetable`.* FROM `subject_timetable` INNER JOIN `classes` on classes.id = `subject_timetable`.`class_id` INNER JOIN sections on `sections`.`id`=`subject_timetable`.`section_id` INNER JOIN `subject_group_subjects` on `subject_group_subjects`.`id`=`subject_timetable`.`subject_group_subject_id` INNER JOIN `subjects` as `sub` on `sub`.`id`=`subject_group_subjects`.`subject_id`  WHERE subject_timetable.staff_id=" . $this->db->escape($staff_id) . " and subject_timetable.session_id =" . $this->current_session . " and subject_timetable.day=" . $this->db->escape($day_value) . "order by subject_timetable.start_time";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getByStaffClassTeachersubjects($staff_id)
    {
        $sql = "select GROUP_CONCAT(subject_timetable.subject_group_subject_id) as subject_group_subject_id from class_teacher inner join subject_timetable on class_teacher.class_id=subject_timetable.class_id  and class_teacher.section_id=subject_timetable.section_id WHERE  class_teacher.staff_id=" . $this->db->escape($staff_id) . " and subject_timetable.session_id =" . $this->current_session;

        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getByTeacherSubjects($staff_id)
    {
        $sql   = "SELECT GROUP_CONCAT(subject_timetable.subject_group_subject_id) as subject_group_subject_id FROM `subject_timetable`   WHERE subject_timetable.staff_id=" . $this->db->escape($staff_id) . " and subject_timetable.session_id =" . $this->current_session;
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function canAddExamMarks($staff_id, $class_id, $section_id, $subject_id)
    {
        $class_teacher = $this->db->select('*')->from('class_teacher')->where('class_id', $class_id)->where('section_id', $section_id)->where('staff_id', $staff_id)->get()->num_rows();
        if ($class_teacher > 0) {
            return 1;
        } else {
            $subject_teacher = $this->db->select('*')->from('subject_timetable')->join('subject_group_subjects', 'subject_timetable.subject_group_subject_id=subject_group_subjects.id')->where('class_id', $class_id)->where('section_id', $section_id)->where('staff_id', $staff_id)->where('subject_group_subjects.subject_id', $subject_id)->get()->num_rows();

            if ($subject_teacher > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }
    
    public function getByTeacherSubjectandDay($staff_id, $day_value) {

        $sql = "SELECT GROUP_CONCAT(subject_timetable.id order by subject_timetable.start_time) as timetable_id FROM `subject_timetable`   WHERE subject_timetable.staff_id=" . $this->db->escape($staff_id) . " and subject_timetable.session_id =" . $this->current_session . " and subject_timetable.day=" . $this->db->escape($day_value)." order by subject_timetable.start_time";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function getByStaffClassTeacherandDay($staff_id, $day_value) {

        $sql = "select GROUP_CONCAT(subject_timetable.id) as timetable_id from class_teacher inner join subject_timetable on class_teacher.class_id=subject_timetable.class_id  and class_teacher.section_id=subject_timetable.section_id WHERE  subject_timetable.staff_id=" . $this->db->escape($staff_id) . " and subject_timetable.session_id =" . $this->current_session." order by subject_timetable.start_time";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

}
