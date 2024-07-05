<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class syllabus_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session      = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month          = $this->setting_model->getStartMonth();
        $this->superadmin_visible   = $this->setting_model->get();
    }

    public function getmysubjects($class_id, $section_id)
    {
        $sql   = "SELECT subject_group_subjects.id as subject_group_subjects_id,subject_group_class_sections.id as subject_group_class_sections_id,subjects.name,subjects.code,subjects.id as subject_id FROM `class_sections` join subject_group_class_sections on subject_group_class_sections.class_section_id=class_sections.id join subject_group_subjects on subject_group_subjects.subject_group_id=subject_group_class_sections.subject_group_id join subjects on subject_group_subjects.subject_id=subjects.id WHERE subject_group_class_sections.session_id=" . $this->current_session . " and class_sections.class_id=" . $this->db->escape($class_id) . " and class_sections.section_id=" . $this->db->escape($section_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_subjectstatus($id, $subject_group_class_sections_id)
    {
        $sql   = "SELECT COUNT(CASE WHEN topic.status = 0 then 1 ELSE NULL END) as 'incomplete', COUNT(CASE WHEN topic.status = 1 then 1 ELSE NULL END) as 'complete',count('*') as total FROM `lesson` inner join topic on lesson.id=topic.lesson_id WHERE lesson.subject_group_class_sections_id=" . $this->db->escape($subject_group_class_sections_id) . " and `subject_group_subject_id`=" . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_studentsyllabus($data)
    {
        $sql   = "SELECT class_sections.id as class_section_id,subject_group_class_sections.id as subject_group_class_section_id FROM `class_sections` inner join subject_group_class_sections on subject_group_class_sections.class_section_id=class_sections.id WHERE `subject_group_class_sections`.`session_id`=" . $this->current_session . " and  `class_id`=" . $this->db->escape($data->class_id) . " and `section_id`=" . $this->db->escape($data->section_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_subject_syllabus($data, $staff_id)
    {
        $this->db->select('subject_syllabus.*,subject_groups.name as sgname,subjects.name as subname,subjects.code as scode,sections.section as sname,classes.class as cname,lesson.name as lessonname,topic.name as topic_name')->from('subject_syllabus');
        $this->db->where("subject_syllabus.id", $data['id']);
        if ($data['role_id'] != 7) {
            $this->db->where('subject_syllabus.created_for', $staff_id);
        }
        $this->db->where('subject_syllabus.session_id', $this->current_session);
        $this->db->join("topic", "topic.id = subject_syllabus.topic_id");
        $this->db->join("lesson", "lesson.id = topic.lesson_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");
        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->group_by("lesson.subject_group_subject_id");
        $this->db->group_by("topic.lesson_id");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_subject_syllabus_student($data)
    {
        $this->db->select('subject_syllabus.*,subject_groups.name as sgname,subjects.name as subname,subjects.code as scode,sections.section as sname,classes.class as cname,lesson.name as lessonname,topic.name as topic_name')->from('subject_syllabus');
        $this->db->join("topic", "topic.id = subject_syllabus.topic_id");
        $this->db->join("lesson", "lesson.id = topic.lesson_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");
        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->where('subject_syllabus.id', $data['subject_syllabus_id']);
        $this->db->where('subject_syllabus.session_id', $this->current_session);
        $this->db->group_by("subject_syllabus.id");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function check_subject_syllabus_student($data)
    {
        $this->db->select('subject_syllabus.*,subject_groups.name as sgname,subjects.name as subname,subjects.code as scode,sections.section as sname,classes.class as cname,lesson.name as lessonname,topic.name as topic_name')->from('subject_syllabus');
        $this->db->join("topic", "topic.id = subject_syllabus.topic_id");
        $this->db->join("lesson", "lesson.id = topic.lesson_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");
        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->where("lesson.subject_group_subject_id", $data['subject_group_subject_id']);
        $this->db->where("lesson.subject_group_class_sections_id", $data['subject_group_class_section_id']);
        $this->db->where('subject_syllabus.date', $data['subject_syllabus_id']);
        $this->db->where('subject_syllabus.time_from', $data['time_from']);
        $this->db->where('subject_syllabus.time_to', $data['time_to']);
        $this->db->where('subject_syllabus.session_id', $this->current_session);
        $this->db->group_by("subject_syllabus.id");
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_subject_syllabus_student_byDate($data)
    {
        $this->db->select('subject_syllabus.*,subject_groups.name as sgname,subjects.name as subname,subjects.code as scode,sections.section as sname,classes.class as cname,lesson.name as lessonname,topic.name as topic_name')->from('subject_syllabus');
        $this->db->join("topic", "topic.id = subject_syllabus.topic_id");
        $this->db->join("lesson", "lesson.id = topic.lesson_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_group_class_sections", "subject_group_class_sections.id = lesson.subject_group_class_sections_id", 'inner');
        $this->db->join("class_sections", "class_sections.id = subject_group_class_sections.class_section_id");
        $this->db->join("sections", "sections.id = class_sections.section_id");
        $this->db->join("classes", "classes.id = class_sections.class_id");
        $this->db->where("lesson.subject_group_class_sections_id", $data['subject_group_class_section_id']);
        $this->db->where('subject_syllabus.date', $data['date']);
        $this->db->where('subject_syllabus.session_id', $this->current_session);
        $this->db->group_by("subject_syllabus.id");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_subject_syllabusdatabyid($id)
    {
        $this->db->select('subject_syllabus.*,lesson.subject_group_subject_id as subject_group_subject_id,lesson.subject_group_class_sections_id,lesson.id as lesson_id')->from('subject_syllabus')->join('topic', 'topic.id=subject_syllabus.topic_id')->join('lesson', 'lesson.id=topic.lesson_id');
        $this->db->where("subject_syllabus.id", $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_subject_syllabusdata($subject_group_subject_id, $date, $role_id, $staff_id, $time_from, $time_to, $subject_group_class_sections_id)
    {
        $this->db->select('count(*) as total,subject_syllabus.id')
            ->from('subject_syllabus')->join('topic', 'topic.id=subject_syllabus.topic_id', 'inner')
            ->join('lesson', 'topic.lesson_id=lesson.id', 'inner')
            ->where("lesson.subject_group_subject_id", $subject_group_subject_id)
            ->where("subject_syllabus.date", $date)
            ->where("subject_syllabus.time_from", $time_from)
            ->where("subject_syllabus.time_to", $time_to)
            ->where('lesson.subject_group_class_sections_id', $subject_group_class_sections_id);

        $this->db->where('subject_syllabus.created_for', $staff_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_subjectteachersreport($subject_group_subject_id, $subject_group_class_sections_id)
    {
        $this->db->select('GROUP_CONCAT(subject_syllabus.id) as subject_syllabus_id,CONCAT_WS(" ",staff.name,staff.surname,"(",staff.employee_id,")") as name,count(subject_syllabus.id) as total_priodes,subjects.name as subject_name,subjects.code')
            ->from('subject_syllabus')->join('topic', 'topic.id=subject_syllabus.topic_id')->join('lesson', 'lesson.id=topic.lesson_id')
            ->join('staff', 'staff.id=subject_syllabus.created_for');
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = lesson.subject_group_subject_id");
        $this->db->join("subject_groups", "subject_groups.id = subject_group_subjects.subject_group_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->where("lesson.subject_group_subject_id", $subject_group_subject_id)
            ->where("lesson.subject_group_class_sections_id", $subject_group_class_sections_id);
        $this->db->group_by("subject_syllabus.created_for");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_subjectsyllabussreport($subject_group_subject_id, $subject_group_class_sections_id)
    {
        return $this->db->select('id,name')->from('lesson')->where('subject_group_subject_id', $subject_group_subject_id)->where('subject_group_class_sections_id', $subject_group_class_sections_id)->get()->result_array();
    }

    public function get_topicbylessonid($lesson_id)
    {
        return $this->db->select('topic.id,topic.name,status,complete_date')->from('topic')->join('lesson', 'lesson.id=topic.lesson_id')->where('lesson_id', $lesson_id)->get()->result_array();
    }

    public function get_subjectsyllabusbyid($id)
    {
        return $this->db->select('subject_syllabus.*,lesson.name as lesson_name,topic.name as topic_name')->from('subject_syllabus')->join('topic', 'topic.id=subject_syllabus.topic_id')->join('lesson', 'lesson.id=topic.lesson_id')->where('subject_syllabus.id', $id)->get()->row_array();
    }

    public function delete_subject_syllabus($id)
    {
        $this->db->where("id", $id)->delete('subject_syllabus');
    }

    public function addmessage($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert("lesson_plan_forum", $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On Lesson Plan Forum id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);

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

    public function getmessage($subject_syllabus_id = null)
    {
        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole    = json_decode($getStaffRole);

        $this->db->select("lesson_plan_forum.id as fourm_id,lesson_plan_forum.message,lesson_plan_forum.created_date,lesson_plan_forum.type,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,staff.image as staff_image,staff.gender,students.firstname,students.middlename,students.lastname,students.image as student_image,students.admission_no,staff.id as staff_id,students.gender as students_gender");
        $this->db->join("staff", "staff.id = lesson_plan_forum.staff_id", 'left');
        $this->db->join("students", "students.id = lesson_plan_forum.student_id", 'left');

        if ($subject_syllabus_id != null) {
            $this->db->where("lesson_plan_forum.subject_syllabus_id", $subject_syllabus_id);
        }
        $this->db->order_by("lesson_plan_forum.id", 'desc');
        $query  = $this->db->get("lesson_plan_forum");
        $result = $query->result_array();

        if ($this->superadmin_visible[0]['superadmin_restriction'] == 'disabled') {
            if ($staffrole->id != 7) {
                foreach ($result as $key => $value) {
                    if ($value['type'] == 'staff') {
                        $staff_id    = $value['staff_id'];
                        $staffresult = $this->staff_model->getAll($staff_id);
                        if ($staffresult['role_id'] != 7) {
                            $result1[] = $value;
                        }
                    } else {
                        $result1[] = $value;
                    }
                }
            } else {
                $result1 = $result;
            }
        } else {
            $result1 = $result;
        }

        return $result1;

    }

    public function getstudentmessage($subject_syllabus_id = null)
    {
        $this->db->select("lesson_plan_forum.id as fourm_id,lesson_plan_forum.message,lesson_plan_forum.created_date,lesson_plan_forum.type,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,staff.image as staff_image,staff.gender,students.firstname,students.middlename,students.lastname,students.image as student_image,students.admission_no,staff.id as staff_id,students.id as student_id,students.gender as students_gender");
        $this->db->join("staff", "staff.id = lesson_plan_forum.staff_id", 'left');
        $this->db->join("students", "students.id = lesson_plan_forum.student_id", 'left');

        if ($subject_syllabus_id != null) {
            $this->db->where("lesson_plan_forum.subject_syllabus_id", $subject_syllabus_id);
        }
        $this->db->order_by("lesson_plan_forum.id", 'desc');
        $query  = $this->db->get("lesson_plan_forum");
        $result = $query->result_array();

        if ($this->superadmin_visible[0]['superadmin_restriction'] == 'disabled') {

            foreach ($result as $key => $value) {
                if ($value['type'] == 'staff') {
                    $staff_id    = $value['staff_id'];
                    $staffresult = $this->staff_model->getAll($staff_id);
                    if ($staffresult['role_id'] != 7) {
                        $result1[] = $value;
                    }
                } else {
                    $result1[] = $value;
                }
            }

        } else {
            $result1 = $result;
        }

        return $result1;

    }
    
    public function deletemessage($id)
    {
        $this->db->where("id", $id)->delete('lesson_plan_forum');
    }

}
