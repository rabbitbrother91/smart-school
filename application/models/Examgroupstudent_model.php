<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examgroupstudent_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function searchExamGroupStudentAttempted1($exam_group_id, $class_id, $batch_id)
    {
        $sql   = "select IFNULL(exam_group_students.id, 0) as `exam_group_student_id`,students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname, students.middlename, students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`,   students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender`,students.batch_id,batch.name,student_session.* from student_session INNER join students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` inner join batch on students.batch_id=batch.id INNER JOIN exam_group_students on exam_group_students.exam_group_id=" . $this->db->escape($exam_group_id) . " and exam_group_students.student_id =students.id WHERE student_session.class_id=" . $this->db->escape($class_id) . " and students.batch_id=" . $this->db->escape($batch_id) . " GROUP BY students.id ORDER BY students.id asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchExamGroupStudentAttempted($exam_group_id, $exam_id, $class_id, $section_id, $session_id)
    {
        $sql   = "select IFNULL(exam_group_students.id, 0) as `exam_group_student_id`,students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender`,student_session.* from student_session INNER join students on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_ids=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($session_id) . " JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN exam_group_students on exam_group_students.exam_group_id=" . $this->db->escape($exam_group_id) . " and exam_group_students.student_id =students.id ORDER BY students.id asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchExamStudentsByExam($exam_id)
    {
        $sql = "SELECT  exam_group_class_batch_exam_students.id as `exam_group_class_batch_exam_student_id`,exam_group_class_batch_exam_students.rank,exam_group_class_batch_exam_students.roll_no as `exam_roll_no`,exam_group_class_batch_exam_students.teacher_remark,students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_email,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender`,`students`.`app_key`,`students`.`parent_app_key`,exam_group_class_batch_exam_students.rank,sections.section as student_section FROM `exam_group_class_batch_exam_students` INNER JOIN student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id INNER join students on students.id=student_session.student_id  INNER JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` INNER JOIN `sections` ON `student_session`.`section_id` = `sections`.`id`  LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE exam_group_class_batch_exam_id=" . $this->db->escape($exam_id) . " AND students.is_active='yes' order by exam_group_class_batch_exam_students.rank asc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id)
    {
        $sql   = "SELECT  exam_group_class_batch_exam_students.id as `exam_group_class_batch_exam_student_id`,exam_group_class_batch_exam_students.rank,exam_group_class_batch_exam_students.roll_no as `exam_roll_no`,students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender` FROM `exam_group_class_batch_exam_students` INNER JOIN student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id INNER join students on students.id=student_session.student_id  INNER JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE exam_group_class_batch_exam_id=" . $this->db->escape($exam_id) . " AND students.is_active='yes' AND student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($session_id)."order by students.firstname asc";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function searchExamGroupStudents($exam_group_id, $class_id, $section_id, $session_id)
    {
        $sql   = "select IFNULL(exam_group_students.id, 0) as `exam_group_student_id`,students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,`classes`.`class`,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender`,student_session.* from student_session INNER join students on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($session_id) . " JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` LEFT JOIN exam_group_students on exam_group_students.exam_group_id=" . $this->db->escape($exam_group_id) . " and exam_group_students.student_id =students.id ORDER BY students.id asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function add($data_insert, $data_delete, $exam_group_id)
    {
        $this->db->trans_begin();
        if (!empty($data_insert)) {
            foreach ($data_insert as $student_key => $student_value) {
                $this->db->where('exam_group_id', $student_value['exam_group_id']);
                $this->db->where('student_id', $student_value['student_id']);
                $q = $this->db->get('exam_group_students');
                if ($q->num_rows() == 0) {
                    $this->db->insert('exam_group_students', $data_insert[$student_key]);
                }
            }
        }
        if (!empty($data_delete)) {
            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->where_in('student_id', $data_delete);
            $this->db->delete('exam_group_students');
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id)
    {
        $sql = "SELECT IFNULL(exam_group_exam_results.id, 0) as exam_group_exam_result_id,IFNULL(exam_group_exam_results.attendence,'') as `exam_group_exam_result_attendance`,IFNULL(exam_group_exam_results.get_marks,'') as `exam_group_exam_result_get_marks`,IFNULL(exam_group_exam_results.note,'') as `exam_group_exam_result_note`,exam_group_class_batch_exam_students.id as `exam_group_class_batch_exam_students_id`,exam_group_class_batch_exam_students.roll_no as `exam_roll_no`,exam_group_class_batch_exam_subjects.*,subjects.name,subjects.code,subjects.type,students.admission_no , students.roll_no,students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender`,exam_group_class_batch_exams.use_exam_roll_no FROM `exam_group_class_batch_exam_subjects` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id INNER JOIN exam_group_class_batch_exam_students on exam_group_class_batch_exam_students.exam_group_class_batch_exam_id=exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id INNER join student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id LEFT join exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id and exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id  INNER JOIN students on students.id=student_session.student_id LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id`  WHERE students.is_active='yes' AND exam_group_class_batch_exam_subjects.id=" . $this->db->escape($exam_subject_id) . " and  student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($session_id) . " ORDER BY students.id asc";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function add_result($insert_array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        if (!empty($insert_array)) {
            foreach ($insert_array as $student_key => $student_value) {
                $student_value['exam_group_class_batch_exam_subject_id'];
                $student_value['exam_group_class_batch_exam_student_id'];
                $this->db->where('exam_group_class_batch_exam_subject_id', $student_value['exam_group_class_batch_exam_subject_id']);
                $this->db->where('exam_group_class_batch_exam_student_id', $student_value['exam_group_class_batch_exam_student_id']);
                $q = $this->db->get('exam_group_exam_results');
                if ($q->num_rows() > 0) {
                    $update_result = $q->row();
                    $this->db->where('id', $update_result->id);
                    $this->db->update('exam_group_exam_results', $student_value);
                } else {
                    $this->db->insert('exam_group_exam_results', $student_value);
                }
            }
        }

        $this->db->trans_complete(); # Completing transaction

        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            # Everything is Perfect.
            # Committing data to the database.
            $this->db->trans_commit();
            return true;
        }
    }

    public function searchStudentByClassSectionSession($class_id, $section_id, $session_id)
    {
        $sql = "SELECT students.admission_no , students.id as `student_id`, students.roll_no,students.admission_date,students.firstname, students.middlename,students.lastname,students.image, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion,students.dob ,students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender` FROM `students` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER join student_session on students.id=student_session.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($session_id) . " ORDER BY students.id asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function searchStudentExams($student_session_id, $is_active = false, $is_publish = false)
    {
        $inner_sql = "";
        if ($is_active) {
            $inner_sql = "and exam_group_class_batch_exams.is_active=1 ";
        }
        if ($is_publish) {
            $inner_sql .= "and exam_group_class_batch_exams.is_publish=1 ";
        }
        $sql = "SELECT exam_group_class_batch_exam_students.*,exam_group_class_batch_exams.exam_group_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.date_from,exam_group_class_batch_exams.date_to,exam_group_class_batch_exams.description,exam_groups.name,exam_groups.exam_type,exam_group_class_batch_exams.passing_percentage FROM `exam_group_class_batch_exam_students` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_class_batch_exam_students.exam_group_class_batch_exam_id  INNER JOIN exam_groups on exam_groups.id=exam_group_class_batch_exams.exam_group_id WHERE student_session_id=" . $this->db->escape($student_session_id) . $inner_sql . " ORDER BY id asc";
        $query        = $this->db->query($sql);
        $student_exam = $query->result();
        if (!empty($student_exam)) {
            foreach ($student_exam as $student_exam_key => $student_exam_value) {
                $student_exam_value->exam_result = $this->examresult_model->getStudentExamResults($student_exam_value->exam_group_class_batch_exam_id, $student_exam_value->exam_group_id, $student_exam_value->id, $student_exam_value->student_id);
            }
        }
        return $student_exam;
    }

    public function studentExams($student_session_id)
    {
        $sql = "SELECT exam_group_class_batch_exam_students.*,exam_group_class_batch_exams.id as `exam_group_class_batch_exam_id`,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.description FROM `exam_group_class_batch_exam_students` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exam_students.exam_group_class_batch_exam_id=exam_group_class_batch_exams.id WHERE student_session_id=" . $this->db->escape($student_session_id) . " and exam_group_class_batch_exams.is_active=1";
        $query        = $this->db->query($sql);
        $student_exam = $query->result();
        return $student_exam;
    }
    public function updateExamStudent($data)
    {
        $this->db->update_batch('exam_group_class_batch_exam_students', $data, 'id');
    }

    public function getexamresult($student_session_id, $exam_id, $is_active = false, $is_publish = false)
    {
        $inner_sql = "";
        if ($is_active) {
            $inner_sql = "and exam_group_class_batch_exams.is_active=1 ";
        }
        if ($is_publish) {
            $inner_sql .= "and exam_group_class_batch_exams.is_publish=1 ";
        }
        $sql = "SELECT exam_group_class_batch_exam_students.*,exam_group_class_batch_exams.exam_group_id,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.passing_percentage,exam_group_class_batch_exams.date_from,exam_group_class_batch_exams.date_to,exam_group_class_batch_exams.description,exam_groups.name,exam_groups.exam_type FROM `exam_group_class_batch_exam_students` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_class_batch_exam_students.exam_group_class_batch_exam_id  INNER JOIN exam_groups on exam_groups.id=exam_group_class_batch_exams.exam_group_id WHERE exam_group_class_batch_exam_students.exam_group_class_batch_exam_id = " . $this->db->escape($exam_id) . " and  student_session_id=" . $this->db->escape($student_session_id) . $inner_sql . " ORDER BY id asc";

        $query        = $this->db->query($sql);
        $student_exam = $query->result();

        if (!empty($student_exam)) {
            foreach ($student_exam as $student_exam_key => $student_exam_value) {
                $student_exam_value->exam_result = $this->examresult_model->getStudentExamResults($student_exam_value->exam_group_class_batch_exam_id, $student_exam_value->exam_group_id, $student_exam_value->id, $student_exam_value->student_id);
            }
        }
        return $student_exam;
    }

}
