<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Homework_model extends MY_model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"]) && $data["id"] > 0) {
            $this->db->where("id", $data["id"])->update("homework", $data);
            $message   = UPDATE_RECORD_CONSTANT . " On homework id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {

            $this->db->insert("homework", $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On homework id " . $insert_id;
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

    public function get($id = null)
    {
        $class  = $this->class_model->get();
        $carray = array();
        foreach ($class as $key => $value) {
            $carray[] = $value['id'];
            $sections = $this->section_model->getClassBySection($value['id']);

            foreach ($sections as $sec => $secdata) {
                $section_array[] = $secdata['section_id'];
            }
        }

        if (!empty($id)) {
            $this->db->select("`homework`.*,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments");
            $this->db->join("classes", "classes.id = homework.class_id");
            $this->db->join("sections", "sections.id = homework.section_id");
            $this->db->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id");
            $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
            $this->db->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id");
            $this->db->where('homework.session_id', $this->current_session);
            $this->db->where("homework.id", $id);

            $query = $this->db->get("homework");
            return $query->row_array();
        } else {

            $this->db->select("`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments");
            $this->db->join("classes", "classes.id = homework.class_id");
            $this->db->join("sections", "sections.id = homework.section_id");
            $this->db->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id");
            $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
            $this->db->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id");
            $this->db->where('homework.session_id', $this->current_session);
            if (!empty($carray)) {
                $this->db->where_in('classes.id', $carray);
            }
            if (!empty($section_array)) {
                $this->db->where_in('sections.id', $section_array);
            }
            $query = $this->db->get("homework");
            return $query->result_array();
        }
    }

    public function get_homeworkDocById($homework_id)
    {
        $this->datatables
            ->select('students.*,submit_assignment.docs,submit_assignment.message,submit_assignment.student_id')
            ->join('students', 'students.id=submit_assignment.student_id', 'inner')
            ->searchable('students.firstname,submit_assignment.message," "')
            ->orderable('students.firstname,submit_assignment.message," "')
            ->from('submit_assignment')
            ->where(array('submit_assignment.homework_id' => $homework_id));
        return $this->datatables->generate('json');
    }

    public function get_homeworkDocByIdStdid($homework_id, $student_id)
    {
        $query = $this->db->select('students.*,submit_assignment.docs,submit_assignment.message')->from('submit_assignment')->join('students', 'students.id=submit_assignment.student_id', 'inner')->where(array('submit_assignment.homework_id' => $homework_id, 'submit_assignment.student_id' => $student_id))->get();
        return $query->result_array();
    }

    public function search_homework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_id)) && (!empty($subject_group_id))) {
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id, 'subject_group_subjects.id' => $subject_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_group_id))) {
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id));
        } else if ((!empty($class_id)) && (empty($section_id)) && (empty($subject_id))) {
            $this->db->where(array('homework.class_id' => $class_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (empty($subject_id))) {
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        }

        $this->db->select("`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subjects.code as subject_code,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments");
        $this->db->join("classes", "classes.id = homework.class_id");
        $this->db->join("sections", "sections.id = homework.section_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id");
        $this->db->where('subject_groups.session_id', $this->current_session);
        $this->db->order_by('homework.homework_date', 'DESC');
        $query = $this->db->get("homework");
        return $query->result_array();
    }

    public function search_dthomework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_id)) && (!empty($subject_group_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id, 'subject_group_subjects.id' => $subject_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_group_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id));
        } else if ((!empty($class_id)) && (empty($section_id)) && (empty($subject_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (empty($subject_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        }

        $this->datatables->select('`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subjects.code as subject_code,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,staff_roles.role_id')
            ->searchable('classes.class,sections.section,subject_groups.name,subjects.name,homework_date,submit_date,evaluation_date,staff.name')
            ->join("classes", "classes.id = homework.class_id")
            ->join("sections", "sections.id = homework.section_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id")
            ->join("staff", "homework.created_by=staff.id")
            ->join("staff_roles", "staff_roles.staff_id=staff.id")
            ->orderable('classes.class,sections.section,subject_groups.name,subjects.name,homework_date,submit_date,evaluation_date,staff.name')
            ->where('subject_groups.session_id', $this->current_session)
            ->where('homework.submit_date >=', date("Y-m-d"))
            ->sort('homework.homework_date', 'DESC')
            ->from('homework');
        return $this->datatables->generate('json');
    }

    public function search_closehomework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_id)) && (!empty($subject_group_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id, 'subject_group_subjects.id' => $subject_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_group_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id));
        } else if ((!empty($class_id)) && (empty($section_id)) && (empty($subject_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (empty($subject_id))) {
            $this->datatables->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        }

        $this->datatables->select('`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subjects.code as subject_code,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,staff_roles.role_id')
            ->searchable('classes.class,sections.section,subject_groups.name,subjects.name,homework_date,submit_date,evaluation_date,staff.name')
            ->join("classes", "classes.id = homework.class_id")
            ->join("sections", "sections.id = homework.section_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id")
            ->join("staff", "homework.created_by=staff.id")
            ->join("staff_roles", "staff_roles.staff_id=staff.id")
            ->orderable('" ",classes.class,sections.section,subject_groups.name,subjects.name,homework_date,submit_date,evaluation_date,staff.name')
            ->where('subject_groups.session_id', $this->current_session)
            ->where('homework.submit_date <', date("Y-m-d"))
            ->sort('homework.homework_date', 'DESC')
            ->from('homework');
        return $this->datatables->generate('json');
    }

    public function getRecord($id = null)
    {
        $query = $this->db->select("homework.*,classes.class,sections.section,subjects.name,subjects.code,subject_groups.name as subject_group, staff.id as created_staff_id, staff.employee_id as created_employee_id, staff.name as created_staff_name, staff.surname as created_staff_surname, staff_roles.role_id as created_staff_roleid")
            ->join("classes", "classes.id = homework.class_id")
            ->join("sections", "sections.id = homework.section_id")
            ->join('subject_group_subjects', 'homework.subject_group_subject_id=subject_group_subjects.id')
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id", "left")
            ->join('subject_groups', 'subject_group_subjects.subject_group_id=subject_groups.id')
            ->join('staff', 'staff.id=homework.created_by')
            ->join('staff_roles', 'staff_roles.staff_id=staff.id')
            ->where("homework.id", $id)
            ->get("homework");
        return $query->row_array();
    }

    public function getStudents($id)
    {
        $sql = "SELECT IFNULL(homework_evaluation.id,0) as homework_evaluation_id,homework_evaluation.note,homework_evaluation.marks,student_session.*,students.firstname,students.middlename,students.lastname,students.admission_no from student_session inner JOIN (SELECT homework.id as homework_id,homework.class_id,homework.section_id,homework.session_id FROM `homework` WHERE id= " . $this->db->escape($id) . " ) as home_work on home_work.class_id=student_session.class_id and home_work.section_id=student_session.section_id and home_work.session_id=student_session.session_id inner join students on students.id=student_session.student_id and students.is_active='yes' left join homework_evaluation on homework_evaluation.student_session_id=student_session.id and students.is_active='yes' and homework_evaluation.homework_id=" . $this->db->escape($id) . "   order by students.id desc";    
        $query = $this->db->query($sql);
        $studentlist = $query->result_array();
        foreach ($studentlist as $key => $student_list_value) {
            $studentlist[$key]['assignmentlist'] = $this->get_homeworkassignmentById($id, $student_list_value['student_id']);
        }

        return $studentlist;

    }

    public function get_homeworkassignmentById($homework_id, $student_id)
    {
        $this->db->select('submit_assignment.id as submit_assignment_id ,submit_assignment.docs,submit_assignment.message,submit_assignment.student_id');
        $this->db->join('students', 'students.id=submit_assignment.student_id');
        $this->db->from('submit_assignment');
        $this->db->where('submit_assignment.homework_id', $homework_id);
        $this->db->where('submit_assignment.student_id', $student_id);
        $result = $this->db->get();
        return $result->result_array();
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("homework");
        $this->db->where("homework_id", $id)->delete("homework_evaluation");
        $this->db->where("homework_id", $id)->delete("submit_assignment");
        $message   = DELETE_RECORD_CONSTANT . " On homework id " . $id;
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

    public function addEvaluation($insert_prev, $insert_array, $homework_id, $evaluation_date, $evaluated_by, $update_array)
    {
        
        $homework = array('evaluation_date' => $evaluation_date, 'evaluated_by' => $evaluated_by);
        $this->db->where("id", $homework_id)->update("homework", $homework);

        if (!empty($insert_array)) {

            foreach ($insert_array as $insert_student_key => $insert_student_value) {

                $insert_student = array(
                    'homework_id'        => $homework_id,
                    'student_session_id' => $insert_student_value['student_session_id'],
                    'note'               => $insert_student_value['note'],
                    'marks'              => $insert_student_value['marks'],
                    'student_id'         => $insert_student_value['student_id'],
                    'date'               => $evaluation_date,
                    'status'             => 'Complete',
                );

                $this->db->insert("homework_evaluation", $insert_student);
                $insert_prev[] = $this->db->insert_id();
            }
        }

        if (!empty($update_array)) {
            foreach ($update_array as $parameter_key => $parameter_value) {

                foreach ($parameter_value as $row) {
                    $update_student = array(
                        'note'  => $row['note'],
                        'marks' => $row['marks'],

                    );
                }
                $this->db->where("id", $parameter_key)->update("homework_evaluation", $update_student);
            }
        }
        $this->db->where('homework_id', $homework_id);
        $this->db->where_not_in('id', $insert_prev);
        $this->db->delete('homework_evaluation');
        
    }

    public function searchHomeworkEvaluation($class_id, $section_id, $subject_id)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_id))) {
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'homework.subject_id' => $subject_id));
        } else if ((!empty($class_id)) && (empty($section_id)) && (empty($subject_id))) {
            $this->db->where(array('homework.class_id' => $class_id));
        } else if ((!empty($class_id)) && (!empty($section_id)) && (empty($subject_id))) {
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        }

        $query = $this->db->select('homework.*,classes.class,sections.section,subjects.name')
            ->join('classes', 'classes.id = homework.class_id')
            ->join('sections', 'sections.id = homework.section_id')
            ->join('subjects', 'subjects.id = homework.subject_id')
            ->where_in('homework.id', 'select homework_evaluation.homework_id from homework_evaluation join homework on (homework_evaluation.homework_id = homework.id) group by homework_evaluation.homework_id')
            ->get('homework');
        return $query->result_array();
    }

    public function getEvaluationReport($id)
    {
        $query = $this->db->select("homework.*,homework_evaluation.student_id,homework_evaluation.id as evalid,homework_evaluation.date,homework_evaluation.status,classes.class,subjects.name,sections.section,(select count(*) as total from submit_assignment sa where sa.homework_id=homework.id) as assignments")->join("classes", "classes.id = homework.class_id")->join("sections", "sections.id = homework.section_id")->join("subjects", "subjects.id = homework.subject_id")->join("homework_evaluation", "homework.id = homework_evaluation.homework_id")->where("homework.id", $id)->get("homework");
        return $query->result_array();
    }

    public function getEvaStudents($id, $class_id, $section_id)
    {
        $query = $this->db->select("students.*,homework_evaluation.student_id,homework_evaluation.date,homework_evaluation.status,classes.class,subjects.name,sections.section")->join("classes", "classes.id = homework.class_id")->join("sections", "sections.id = homework.section_id")->join("subjects", "subjects.id = homework.subject_id")->join("homework_evaluation", "homework.id = homework_evaluation.homework_id")->join("students", "students.id = homework_evaluation.student_id", "left")->where("homework.id", $id)->get("homework");
        return $query->result_array();
    }

    public function delete_evaluation($prev_students)
    {
        if (!empty($prev_students)) {
            $this->db->where_in("id", $prev_students)->delete("homework_evaluation");
        }
    }

    public function count_students($class_id, $section_id)
    {
        $query = $this->db->select("student_session.student_id")->join("student_session", "students.id = student_session.student_id")->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id, 'students.is_active' => "yes", 'student_session.session_id' => $this->current_session))->group_by("student_session.student_id")->get("students");
        return $query->num_rows();
    }

    public function count_evalstudents($id, $class_id, $section_id)
    {
        $array['homework.id']         = $id;
        $array['homework.session_id'] = $this->current_session;
        $array['students.is_active']  = 'yes';
        $query = $this->db->select("count(*) as total")->join("homework_evaluation", "homework_evaluation.homework_id = homework.id")->join('student_session', 'student_session.id=homework_evaluation.student_session_id')->join('students', 'students.id=student_session.student_id')->where($array)->get("homework");
        return $query->row_array();
    }

    public function get_homeworkDoc($student_id)
    {
        return $this->db->select('*')->from('submit_assignment')->where('student_id', $student_id)->get()->result_array();
    }

    public function get_homeworkDocByhomework_id($homework_id)
    {
        return $this->db->select('*')->from('submit_assignment')->where('homework_id', $homework_id)->get()->result_array();
    }

    public function getStudentHomeworkWithStatus($class_id, $section_id, $student_session_id)
    {
        $sql   = "SELECT `homework`.*,IFNULL(homework_evaluation.id,0) as homework_evaluation_id,homework_evaluation.note,homework_evaluation.marks as evaluation_marks, `classes`.`class`, `sections`.`section`, `subject_group_subjects`.`subject_id`, `subject_group_subjects`.`id` as `subject_group_subject_id`, `subjects`.`name` as `subject_name`, `subjects`.`code` as `subject_code`, `subject_groups`.`id` as `subject_groups_id`, `subject_groups`.`name` FROM `homework` LEFT JOIN homework_evaluation on homework_evaluation.homework_id=homework.id and homework_evaluation.student_session_id=" . $this->db->escape($student_session_id) . "  JOIN `classes` ON `classes`.`id` = `homework`.`class_id` JOIN `sections` ON `sections`.`id` = `homework`.`section_id` JOIN `subject_group_subjects` ON `subject_group_subjects`.`id` = `homework`.`subject_group_subject_id` JOIN `subjects` ON `subjects`.`id` = `subject_group_subjects`.`subject_id` JOIN `subject_groups` ON `subject_group_subjects`.`subject_group_id`=`subject_groups`.`id` WHERE `homework`.`class_id` = " . $this->db->escape($class_id) . " AND `homework`.`section_id` = " . $this->db->escape($section_id) . " AND `homework`.`session_id` = " . $this->current_session . " and submit_date >= '" . date('Y-m-d') . "'  order by homework.homework_date desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getstudentclosedhomeworkwithstatus($class_id, $section_id, $student_session_id)
    {
        $sql   = "SELECT `homework`.*,IFNULL(homework_evaluation.id,0) as homework_evaluation_id,homework_evaluation.note,homework_evaluation.marks as evaluation_marks, `classes`.`class`, `sections`.`section`, `subject_group_subjects`.`subject_id`, `subject_group_subjects`.`id` as `subject_group_subject_id`, `subjects`.`name` as `subject_name`, `subjects`.`code` as `subject_code`,  `subject_groups`.`id` as `subject_groups_id`, `subject_groups`.`name` FROM `homework` LEFT JOIN homework_evaluation on homework_evaluation.homework_id=homework.id and homework_evaluation.student_session_id=" . $this->db->escape($student_session_id) . "  JOIN `classes` ON `classes`.`id` = `homework`.`class_id` JOIN `sections` ON `sections`.`id` = `homework`.`section_id` JOIN `subject_group_subjects` ON `subject_group_subjects`.`id` = `homework`.`subject_group_subject_id` JOIN `subjects` ON `subjects`.`id` = `subject_group_subjects`.`subject_id` JOIN `subject_groups` ON `subject_group_subjects`.`subject_group_id`=`subject_groups`.`id` WHERE `homework`.`class_id` = " . $this->db->escape($class_id) . " AND `homework`.`section_id` = " . $this->db->escape($section_id) . " AND `homework`.`session_id` = " . $this->current_session . " and submit_date < '" . date('Y-m-d') . "' order by homework.homework_date desc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getStudentHomework($class_id, $section_id)
    {
        $this->db->select("`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments");
        $this->db->join("classes", "classes.id = homework.class_id");
        $this->db->join("sections", "sections.id = homework.section_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id");
        $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
        $this->db->where('homework.session_id', $this->current_session);
        $query = $this->db->get("homework");
        return $query->result_array();       
    }

    public function get_HomeworkSubject($subjectgroup_id)
    {
        return $this->db->select('subjects.name as subject,subjects.code')->from('subject_group_subjects')->join('subjects', 'subject_group_subjects.subject_id=subjects.id')->where('subject_group_subjects.subject_group_id', $subjectgroup_id)->get()->result_array();
    }

    public function upload_docs($data)
    {
        $this->db->where('homework_id', $data['homework_id']);
        $this->db->where('student_id', $data['student_id']);
        $q = $this->db->get('submit_assignment');
        if ($q->num_rows() > 0) {
            $this->db->where('homework_id', $data['homework_id']);
            $this->db->where('student_id', $data['student_id']);
            $this->db->update('submit_assignment', $data);
        } else {
            $this->db->insert('submit_assignment', $data);
        }
    }

    public function get_upload_docs($array)
    {
        return $this->db->select('*')->from('submit_assignment')->where($array)->get()->result_array();
    }

    public function getEvaluationReportForStudent($id, $student_id)
    {
        $query = $this->db->select("homework.*,homework_evaluation.student_id,homework_evaluation.id as evalid,homework_evaluation.date,homework_evaluation.status,homework_evaluation.student_id,classes.class,sections.section")->join("classes", "classes.id = homework.class_id")->join("sections", "sections.id = homework.section_id")->join("homework_evaluation", "homework.id = homework_evaluation.homework_id")->where("homework.id", $id)->get("homework");      
        $result = $query->result_array();
        foreach ($result as $key => $value) {
            if ($value["student_id"] == $student_id) {
                return $value;
            } else {
                $data = array('date' => $value["date"], 'status' => 'Incomplete');
                return $data;
            }
        }
    }

    public function get_homeworkDocBystudentId($homework_id, $student_id)
    {
        $where = array('submit_assignment.homework_id' => $homework_id, 'submit_assignment.student_id' => $student_id);
        $query = $this->db->select('students.*,submit_assignment.docs,submit_assignment.message')->from('submit_assignment')->join('students', 'students.id=submit_assignment.student_id', 'inner')->where($where)->get();
        return $query->result_array();
    }

    public function check_assignment($homework_id, $student_id)
    {
        $status = $this->db->select('*')->from('submit_assignment')->where(array('homework_id' => $homework_id, 'student_id' => $student_id))->get()->num_rows();
        return $status;
    }

    public function adddailyassignment($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data["id"]) && $data["id"] > 0) {
            $this->db->where("id", $data["id"])->update("daily_assignment", $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Daily Assignment id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert("daily_assignment", $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Daily Assignment id " . $insert_id;
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

    public function getdailyassignment($student_id, $student_session_id)
    {
        return $this->db->select('daily_assignment.*,subjects.name as subject_name,`subjects`.`code` as `subject_code`')
            ->from('daily_assignment')
            ->join('student_session', 'student_session.student_id=daily_assignment.student_session_id', 'left')
            ->join('subject_group_subjects', 'subject_group_subjects.id=daily_assignment.subject_group_subject_id', 'left')
            ->join('subjects', 'subjects.id=subject_group_subjects.subject_id')
            ->where('daily_assignment.student_session_id', $student_session_id)
            ->or_where('student_session.student_id', $student_id)
            ->order_by('daily_assignment.id', 'desc')
            ->group_by('daily_assignment.id')
            ->get()
            ->result_array();
    }

    public function getsingledailyassignment($assignment_id)
    {
        return $this->db->select('daily_assignment.*')->from('daily_assignment')->where('daily_assignment.id', $assignment_id)->get()->row_array();
    }

    public function deletedailyassignment($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("id", $id)->delete("daily_assignment");
        $message   = DELETE_RECORD_CONSTANT . " On Daily Assignment id " . $id;
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

    public function searchdailyassignment($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $date)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($date))) {
            $this->datatables->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id, 'daily_assignment.date' => $date, 'subject_group_subjects.subject_group_id' => $subject_group_id, 'subject_group_subjects.id' => $subject_group_subject_id));
        }

        $this->datatables->select('daily_assignment.*,staff.name,staff.surname,staff.employee_id,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.id as student_id,students.admission_no as student_admission_no,subjects.name as subject_name,subjects.code as subject_code')
            ->searchable('students.firstname,classes.class,sections.section,daily_assignment.title,daily_assignment.date,daily_assignment.description')
            ->join("student_session", "student_session.id = daily_assignment.student_session_id")
            ->join("classes", "classes.id = student_session.class_id")
            ->join("sections", "sections.id = student_session.section_id")
            ->join("students", "students.id = student_session.student_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = daily_assignment.subject_group_subject_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("staff", "staff.id = `daily_assignment`.`evaluated_by`", "left")
            ->orderable('students.firstname,classes.class,sections.section,daily_assignment.description,daily_assignment.remark,daily_assignment.date,daily_assignment.evaluation_date')
            ->sort('students.firstname,classes.class,sections.section,daily_assignment.title,daily_assignment.date,daily_assignment.description', 'DESC')
            ->from('daily_assignment');
        return $this->datatables->generate('json');
    }   

    public function checkstatus($homework_id, $student_id)
    {
        return $this->db->select('count(submit_assignment.id) as record_count')->from('submit_assignment')
            ->where('submit_assignment.homework_id', $homework_id)->where('submit_assignment.student_id', $student_id)->get()->row_array();
    }

    public function getStudentlist($id)
    {
        $sql = "SELECT IFNULL(homework_evaluation.id,0) as homework_evaluation_id,homework_evaluation.note,homework_evaluation.marks,student_session.*,students.firstname,students.middlename,students.lastname,students.admission_no from student_session inner JOIN (SELECT homework.id as homework_id,homework.class_id,homework.section_id,homework.session_id FROM `homework` WHERE id= " . $this->db->escape($id) . " ) as home_work on home_work.class_id=student_session.class_id and home_work.section_id=student_session.section_id and home_work.session_id=student_session.session_id inner join students on students.id=student_session.student_id and students.is_active='yes' left join homework_evaluation on homework_evaluation.student_session_id=student_session.id and students.is_active='yes' and homework_evaluation.homework_id=" . $this->db->escape($id) . "";
        $this->datatables->query($sql)
            ->searchable('firstname,marks,homework_evaluation.note," "')
            ->orderable('firstname,marks,homework_evaluation.note," "')
            ->sort('students.id', 'DESC')
            ->query_where_enable(true);

        return $this->datatables->generate('json');
    }

    public function assignmentrecord($assigment_id)
    {
        $this->db->select('daily_assignment.*,staff.name,staff.surname,staff.employee_id,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.id as student_id,students.admission_no as student_admission_no,subjects.name as subject_name,subjects.code as subject_code');
        $this->db->join("student_session", "student_session.id = daily_assignment.student_session_id");
        $this->db->join("classes", "classes.id = student_session.class_id");
        $this->db->join("sections", "sections.id = student_session.section_id");
        $this->db->join("students", "students.id = student_session.student_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = daily_assignment.subject_group_subject_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("staff", "staff.id = `daily_assignment`.`evaluated_by`", "left");
        $this->db->from('daily_assignment');
        $this->db->where('daily_assignment.id', $assigment_id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function dailyassignmentreport($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $condition = null)
    {
        if ((!empty($class_id)) && (!empty($section_id))) {
            $this->datatables->where(array('student_session.class_id' => $class_id, 'student_session.section_id' => $section_id, 'subject_group_subjects.subject_group_id' => $subject_group_id, 'subject_group_subjects.id' => $subject_group_subject_id));
        }

        if ($condition != null) {
            $this->datatables->where($condition);
        }

        $this->datatables->select('daily_assignment.*,staff.name,staff.surname,staff.employee_id,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.id as student_id,students.admission_no as student_admission_no,subjects.name as subject_name,count(students.id) as total_student')
            ->searchable('students.firstname,classes.class,sections.section,daily_assignment.title,daily_assignment.date,daily_assignment.description')
            ->join("student_session", "student_session.id = daily_assignment.student_session_id")
            ->join("classes", "classes.id = student_session.class_id")
            ->join("sections", "sections.id = student_session.section_id")
            ->join("students", "students.id = student_session.student_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = daily_assignment.subject_group_subject_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("staff", "staff.id = `daily_assignment`.`evaluated_by`", "left")
            ->orderable('students.firstname,classes.class,sections.section,daily_assignment.description,daily_assignment.remark,daily_assignment.date,daily_assignment.evaluation_date')
            ->group_by('students.id')
            ->sort('students.firstname,classes.class,sections.section,daily_assignment.title,daily_assignment.date,daily_assignment.description', 'DESC')
            ->from('daily_assignment');

        return $this->datatables->generate('json');
    }

    public function assignmentdetails($student_id, $condition, $subject_group_id)
    {
        $this->db->select('daily_assignment.*,staff.name,staff.surname,staff.employee_id,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.id as student_id,students.admission_no as student_admission_no,subjects.name as subject_name,subjects.code as subject_code');
        $this->db->join("student_session", "student_session.id = daily_assignment.student_session_id");
        $this->db->join("classes", "classes.id = student_session.class_id");
        $this->db->join("sections", "sections.id = student_session.section_id");
        $this->db->join("students", "students.id = student_session.student_id");
        $this->db->join("subject_group_subjects", "subject_group_subjects.id = daily_assignment.subject_group_subject_id");
        $this->db->join("subjects", "subjects.id = subject_group_subjects.subject_id");
        $this->db->join("staff", "staff.id = `daily_assignment`.`evaluated_by`", "left");
        $this->db->where('students.id', $student_id);
        $this->db->where('daily_assignment.subject_group_subject_id', $subject_group_id);
        $this->db->where($condition);
        $this->db->from('daily_assignment');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function check_valid_marks($str)
    {
        $this->form_validation->set_message('check_valid_marks', $this->lang->line('enter_valid_marks'));
        return false;

    }
    
    public function search_dthomeworkreport($class_id, $section_id, $subject_group_id, $subject_id)
    {
        if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_id)) && (!empty($subject_group_id))) {
            
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id, 'subject_group_subjects.id' => $subject_id));
            
        } else if ((!empty($class_id)) && (!empty($section_id)) && (!empty($subject_group_id))) {
            
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id, 'subject_groups.id' => $subject_group_id));
            
        } else if ((!empty($class_id)) && (empty($section_id)) && (empty($subject_id))) {
            
            $this->db->where(array('homework.class_id' => $class_id));
            
        } else if ((!empty($class_id)) && (!empty($section_id)) && (empty($subject_id))) {
            
            $this->db->where(array('homework.class_id' => $class_id, 'homework.section_id' => $section_id));
            
        }

        $this->db->select('`homework`.*,classes.class,sections.section,subject_group_subjects.subject_id,subject_group_subjects.id as `subject_group_subject_id`,subjects.name as subject_name,subjects.code as subject_code,subject_groups.id as subject_groups_id,subject_groups.name,(select count(*) as total from submit_assignment where submit_assignment.homework_id=homework.id) as assignments,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,staff_roles.role_id,       
        (SELECT COUNT(*) FROM student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=classes.id and student_session.section_id=sections.id and students.is_active="yes"  and student_session.session_id='.$this->current_session.')  as student_count
        
        ')
            
            ->join("classes", "classes.id = homework.class_id")
            ->join("sections", "sections.id = homework.section_id")
            ->join("subject_group_subjects", "subject_group_subjects.id = homework.subject_group_subject_id")
            ->join("subjects", "subjects.id = subject_group_subjects.subject_id")
            ->join("subject_groups", "subject_group_subjects.subject_group_id=subject_groups.id")
            ->join("staff", "homework.created_by=staff.id")
            ->join("staff_roles", "staff_roles.staff_id=staff.id")            
            ->where('subject_groups.session_id', $this->current_session)             
            ->order_by('homework.homework_date', 'DESC')
            ->from('homework');            
             
        $result = $this->db->get();
        return $result->result_array();      
         
    }
    
    public function get_submitted_homework($homework_id)
    {
        $this->db
            ->select('students.*,submit_assignment.docs,submit_assignment.message,submit_assignment.student_id,classes.class,sections.section')
            ->join('students', 'students.id=submit_assignment.student_id', 'inner')
            ->join("student_session", "student_session.student_id = submit_assignment.student_id")
            ->join("classes", "classes.id = student_session.class_id")
            ->join("sections", "sections.id = student_session.section_id")
            ->from('submit_assignment')
            ->where(array('submit_assignment.homework_id' => $homework_id))
            ->where('student_session.session_id', $this->current_session)
            ->where('students.is_active', 'yes');
            
        $result = $this->db->get();
        return $result->result_array();
    }
    
    public function get_not_submitted_homework($class_id,$section_id,$homework_id)
    {
        $this->db
            ->select('students.*,classes.class,sections.section')             
            ->join("student_session", "student_session.student_id = students.id")
            ->join("classes", "classes.id = student_session.class_id")
            ->join("sections", "sections.id = student_session.section_id")
            ->from('students')
            ->where('student_session.class_id', $class_id)
            ->where('student_session.session_id', $this->current_session)
            ->where('student_session.section_id', $section_id)
            ->where('students.is_active', 'yes');           
            
            $this->db->where("students.id NOT IN (select submit_assignment.student_id from submit_assignment where homework_id = $homework_id and students.id = student_id) ");
            
        $result = $this->db->get();
        return $result->result_array();
    }  

    
}