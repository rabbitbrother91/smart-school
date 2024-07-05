<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examresult_model extends CI_Model
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
        $this->db->select()->from('exam_results');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('exam_results');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_results', $data);
        } else {
            $this->db->insert('exam_results', $data);
            return $this->db->insert_id();
        }
    }

    public function add_exam_result($data)
    {
        $this->db->where('exam_schedule_id', $data['exam_schedule_id']);
        $this->db->where('student_id', $data['student_id']);
        $q      = $this->db->get('exam_results');
        $result = $q->row();
        if ($q->num_rows() > 0) {
            $this->db->where('id', $result->id);
            $this->db->update('exam_results', $data);
            if ($result->get_marks != $data['get_marks']) {
                return $result->id;
            }
        } else {
            $this->db->insert('exam_results', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return false;
    }

    public function get_exam_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            $obj             = new stdClass();
            $obj->attendence = 'pre';
            $obj->get_marks  = "0.00";
            return $obj;
        }
    }

    public function get_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {

        }
    }

    public function checkexamresultpreparebyexam($exam_id, $class_id, $section_id)
    {
        $query = $this->db->query("SELECT count(*) `counter` FROM `exam_results`,exam_schedules,student_session WHERE exam_results.exam_schedule_id=exam_schedules.id and student_session.student_id=exam_results.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and exam_schedules.session_id=" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id=" . $this->db->escape($exam_id));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        return $query->result_array();
    }

    public function getStudentExamResultByStudent($exam_id, $student_id, $exam_schedule)
    {
        $sql = "SELECT exam_schedules.id as `exam_schedules_id`,exam_results.id as `exam_results_id`,exam_schedules.exam_id,exam_schedules.date_of_exam,exam_schedules.full_marks,exam_schedules.passing_marks,exam_results.student_id,exam_results.get_marks,students.firstname,students.middlename,students.lastname,students.guardian_phone,students.email ,exams.name as `exam_name` FROM `exam_schedules` INNER JOIN exams on exams.id=exam_schedules.exam_id INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN students on students.id=exam_results.student_id WHERE exam_schedules.session_id =" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id =" . $this->db->escape($exam_id) . " and exam_results.student_id =" . $this->db->escape($student_id) . " and exam_schedules.id in (" . $exam_schedule . ") ORDER BY `exam_results`.`id` ASC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getExamResults($exam_id, $post_exam_group_id, $students)
    {
        $result           = array('exam_connection' => 0, 'students' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection  = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection           = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;
        foreach ($students as $student_key => $student_value) {
            $student = $this->examstudent_model->getExamStudentByID($student_value);

            $student['exam_result'] = array();
            if ($exam_connection) {
                foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {

                    $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student['student_id'], $exam_connection_value->exam_group_class_batch_exams_id);
                    if(!empty($exam_group_class_batch_exam_student)){
                        
                        $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);
    
                        $student['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
    
                        $student['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id] = $this->getStudentResultByExam($exam_connection_value->exam_group_class_batch_exams_id, $exam_group_class_batch_exam_student->id);
    
                        $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
                    }

                }
                $result['students'][] = $student;
            } else {
                $student['exam_roll_no'] = $student['roll_no'];
                $student['exam_result']  = $this->getStudentResultByExam($exam_id, $student['id']);
                $result['students'][]    = $student;
            }
        }

        return $result;
    }

    public function updaterank($update_array,$exam_group_class_batch_exam_id)
    {     
        if (!empty($update_array)) {
            $data_update = array('is_rank_generated' => 1);   
            $this->db->where('id', $exam_group_class_batch_exam_id);
            $this->db->update('exam_group_class_batch_exams', $data_update);
            $this->db->update_batch('exam_group_class_batch_exam_students', $update_array, 'id');
        }
       
    }

    public function getStudentResultByExam($exam_id, $student_id)
    {
        $sql   = "SELECT exam_group_class_batch_exam_subjects.*,exam_group_exam_results.id as `exam_group_exam_results_id`,exam_group_exam_results.attendence,exam_group_exam_results.get_marks,exam_group_exam_results.note,subjects.name,subjects.code,exam_group_class_batch_exam_students.rank FROM `exam_group_class_batch_exam_subjects` inner JOIN exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id INNER JOIN exam_group_class_batch_exam_students on exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id and exam_group_class_batch_exam_students.id=" . $this->db->escape($student_id) . " INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $this->db->escape($exam_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentExamResults($exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id)
    {
        $student          = $this->examstudent_model->getExamStudentByID($exam_group_class_batch_exam_student_id);
        $result           = array('student' => $student, 'exam_connection' => 0, 'result' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection  = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection           = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;
        if ($exam_connection) {
            $new_array = array();

            foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {

                $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student_id, $exam_connection_value->exam_group_class_batch_exams_id);

                $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);

                if (!empty($exam_group_class_batch_exam_student->id)) {

                    $result['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
                    $result['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id]
                    = $this->getStudentResultByExam($exam_connection_value->exam_group_class_batch_exams_id, $exam_group_class_batch_exam_student->id);

                }
                $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
            }

        } else {

            $result['exam_connection_list']    = $exam_connections;
            $result['student']['exam_roll_no'] = $student['roll_no'];
            $result['result']                  = $this->getStudentResultByExam($exam_id, $exam_group_class_batch_exam_student_id);
        }

        return $result;
    }

}
