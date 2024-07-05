<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineexamresult_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getDescriptionRecord($per_page, $start, $where,$exam_id)
    {
        $this->db->select('`onlineexam_student_results`.*, onlineexam_questions.marks  as `question_marks`,onlineexam_questions.neg_marks  as `question_neg_marks`,`onlineexam_questions`.`question_id`, `questions`.`question`, `questions`.`question_type`,onlineexam_students.student_session_id, student_session.student_id,students.firstname,students.middlename,students.lastname,students.mobileno, students.email,students.admission_no , students.guardian_name , students.guardian_relation,students.guardian_phone,students.guardian_address, students.roll_no,students.admission_date,classes.class,sections.section')->from('onlineexam_student_results');
        $this->db->join('onlineexam_questions', 'onlineexam_questions.id = onlineexam_student_results.onlineexam_question_id');
        $this->db->join('questions', 'questions.id = onlineexam_questions.question_id');
        $this->db->join('onlineexam_students', 'onlineexam_students.id = onlineexam_student_results.onlineexam_student_id');
        $this->db->join('student_session', 'student_session.id = onlineexam_students.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('questions.question_type', 'descriptive');
        $this->db->where('onlineexam_questions.onlineexam_id', $exam_id);
        if (!empty($where)) {

            if (isset($where['class_id']) && $where['class_id'] != "") {
                $this->db->where('student_session.class_id', $where['class_id']);
            }
            if (isset($where['section_id']) && $where['section_id'] != "") {
                $this->db->where('student_session.section_id', $where['section_id']);
            }
            if (isset($where['question_id']) && $where['question_id'] != "") {
                $this->db->where('questions.id', $where['question_id']);
            }
        }
        $this->db->limit($per_page, $start);
        $this->db->order_by('onlineexam_student_results.id');
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result = $query->result();

            //======
            $this->db->from('onlineexam_student_results');
            $this->db->join('onlineexam_questions', 'onlineexam_questions.id = onlineexam_student_results.onlineexam_question_id');
            $this->db->join('questions', 'questions.id = onlineexam_questions.question_id');
            $this->db->join('onlineexam_students', 'onlineexam_students.id = onlineexam_student_results.onlineexam_student_id');
            $this->db->join('student_session', 'student_session.id = onlineexam_students.student_session_id');
            $this->db->join('students', 'students.id = student_session.student_id');
            $this->db->join('classes', 'classes.id = student_session.class_id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->where('questions.question_type', 'descriptive');
            $this->db->where('onlineexam_questions.onlineexam_id', $exam_id);
            if (!empty($where)) {

                if (isset($where['class_id']) && $where['class_id'] != "") {
                    $this->db->where('student_session.class_id', $where['class_id']);
                }
                if (isset($where['section_id']) && $where['section_id'] != "") {
                    $this->db->where('student_session.section_id', $where['section_id']);
                }
                if (isset($where['question_id']) && $where['question_id'] != "") {
                    $this->db->where('questions.id', $where['question_id']);
                }
            }
            $num_results = $this->db->count_all_results();
            //======

            return json_encode(array('total_row' => $num_results, 'total_result' => $result));
        } else {
            $result = $query->result();
            return json_encode(array('total_row' => 0, 'total_result' => $result));
        }

    }

    public function add($data_insert)
    {

        $this->db->trans_begin();

        if (!empty($data_insert)) {

            $this->db->insert_batch('onlineexam_student_results', $data_insert);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function update($data_update)
    {

        $this->db->trans_begin();

        if (!empty($data_update)) {
            $this->db->where('id', $data_update['id']);
            $this->db->update('onlineexam_student_results', $data_update);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getResultByStudent($onlineexam_student_id, $exam_id)
    { 
        $query = "SELECT onlineexam_questions.*,subjects.name as subject_name,subjects.code as subject_code,onlineexam_student_results.id as `onlineexam_student_result_id`,questions.question,questions.question_type,onlineexam_student_results.marks as `score_marks`,questions.opt_a, questions.opt_b,questions.opt_c,questions.opt_d,questions.opt_e,questions.correct,onlineexam_student_results.select_option,onlineexam_student_results.remark,onlineexam_student_results.attachment_name,onlineexam_student_results.attachment_upload_name FROM `onlineexam_questions` left JOIN onlineexam_student_results on onlineexam_student_results.onlineexam_question_id=onlineexam_questions.id and onlineexam_student_results.onlineexam_student_id=" . $this->db->escape($onlineexam_student_id) . " INNER JOIN questions on questions.id=onlineexam_questions.question_id INNER JOIN subjects on subjects.id=questions.subject_id WHERE onlineexam_questions.onlineexam_id=" . $this->db->escape($exam_id);
        $query = $this->db->query($query);
        return $query->result();
    }
   
    public function getStudentByExam($exam_id, $class_id, $section_id)
    {
        $query = "SELECT student_session.*,onlineexam.id as `exam_id`,onlineexam.exam,onlineexam.attempt,onlineexam_students.id as `onlineexam_student_id`,onlineexam_students.rank,onlineexam_students.is_attempted, (select count(*) from onlineexam_attempts WHERE onlineexam_attempts.onlineexam_student_id = onlineexam_students.id ) as `total_counter`,`classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,students.middlename, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `student_session` INNER JOIN onlineexam_students on onlineexam_students.student_session_id=student_session.id INNER JOIN students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN onlineexam on onlineexam_students.onlineexam_id=onlineexam.id WHERE class_id =" . $this->db->escape($class_id) . "  and section_id=" . $this->db->escape($section_id) . " and student_session.session_id=" . $this->db->escape($this->current_session) . " AND onlineexam_students.onlineexam_id =" . $this->db->escape($exam_id)." ";

        $this->datatables->query($query)
        ->searchable('students.admission_no,students.firstname,classes.class,onlineexam.attempt')
        ->orderable('students.admission_no,students.firstname,classes.class,onlineexam.attempt,null,null') 
        ->query_where_enable(TRUE)
        ->sort('onlineexam_students.is_attempted', 'desc') ;
        return $this->datatables->generate('json');
    }

    public function checkResultPrepare($onlineexam_student_id)
    {
        $this->db->select()->from('onlineexam_student_results');
        $this->db->where('onlineexam_student_id', $onlineexam_student_id);
        $query = $this->db->get();
        return $query->result();
    } 

    public function onlineexamrank($onlineexam_student_id, $exam_id)
    {    

        $query = "SELECT onlineexam_student_results.onlineexam_student_id,SUM(CASE WHEN questions.correct = onlineexam_student_results.select_option THEN 1 ELSE 0 END) AS correct_answer,SUM(CASE WHEN questions.correct != onlineexam_student_results.select_option THEN 1 ELSE 0 END) AS incorrect_answer,COUNT(*) as total_questions FROM `onlineexam_questions` left JOIN onlineexam_student_results on onlineexam_student_results.onlineexam_question_id=onlineexam_questions.id and onlineexam_student_results.onlineexam_student_id=" . $this->db->escape($onlineexam_student_id) . " INNER JOIN questions on questions.id=onlineexam_questions.question_id INNER JOIN subjects on subjects.id=questions.subject_id WHERE onlineexam_questions.onlineexam_id=" . $this->db->escape($exam_id);

        $query = $this->db->query($query);

        return $query->result_array();

    }

}
