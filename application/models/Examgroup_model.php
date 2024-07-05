<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examgroup_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('exam_groups.*,(select count(*) from exam_group_class_batch_exams WHERE exam_group_class_batch_exams.exam_group_id=exam_groups.id) as `counter`')->from('exam_groups');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getExamByID($id = null)
    {
        $sql = "SELECT exam_groups.name as `exam_group_name`,exam_groups.exam_type as `exam_group_type`,exam_groups.id as `exam_group_id`,exam_group_class_batch_exams.*,sessions.session FROM `exam_group_class_batch_exams` INNER JOIN exam_groups on exam_groups.id= exam_group_class_batch_exams.exam_group_id INNER JOIN sessions on sessions.id = exam_group_class_batch_exams.session_id WHERE exam_group_class_batch_exams.id=" . $this->db->escape($id);
        $query = $this->db->query($sql);
        return $query->row();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->delete('exam_groups'); //class record delete.
        $message   = DELETE_RECORD_CONSTANT . " On exam groups id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return true;
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_groups', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  exam groups id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('exam_groups', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On exam groups id " . $id;
            $action    = "Insert";
            $record_id = $id;
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
            //return $return_value;
        }
    }

    public function delete_exam($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('exam_group_class_batch_exams');
        $message   = DELETE_RECORD_CONSTANT . " On exam groups exams name id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    public function add_exam($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_group_class_batch_exams', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  exam group exams name id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->trans_start(); # Starting Transaction
            $this->db->trans_strict(false); # See Note 01. If you wish can remove as well

            $exam_group = $this->examgroup_model->get($data['exam_group_id']);
            $this->db->insert('exam_group_class_batch_exams', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On exam group exams name id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);            
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
    }

    public function getExamByExamGroup($id, $is_active = false)
    {
        $this->db->select('exam_group_class_batch_exams.*,sessions.session,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('sessions', 'sessions.id = exam_group_class_batch_exams.session_id');
        if ($is_active) {
            $this->db->where('exam_group_class_batch_exams.is_active', $is_active);
        }
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->order_by('exam_group_class_batch_exams.exam_group_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function getBacklogExam($parent_exam_id)
    {
        $this->db->select()->from('exam_group_class_batch_exams');
        $this->db->where('parent_exam_id', $parent_exam_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function getExamGroupDetailByID($id)
    {
        $this->db->select()->from('exam_groups');
        $this->db->where('id', $id);
        $query = $this->db->get();
        if ($query->num_rows() >= 1) {
            $result        = $query->row();
            $result->exams = $this->getExamByExamGroup($result->id);
            return $result;
        }
        return false;
    }

    public function verifyExamConnection($exam_array)
    {
        $sql = "SELECT exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id,exam_group_class_batch_exam_subjects.subject_id,count(subject_id) as subject_count FROM `exam_group_class_batch_exam_subjects` WHERE exam_group_class_batch_exams_id in(" . implode(",", $exam_array) . ") GROUP by subject_id,exam_group_class_batch_exams_id";

        $query  = $this->db->query($sql);
        $result = $query->result();
        $sub_array   = array();
        $exams_array = array();
        $ex_array    = array();
        $no_record   = 0;
        if (!empty($result)) {
            $no_record = 1;
            foreach ($result as $result_key => $result_value) {
                $exams_array[$result_value->exam_group_class_batch_exams_id]                         = $result_value->exam_group_class_batch_exams_id;
                $ex_array[$result_value->exam_group_class_batch_exams_id][$result_value->subject_id] = $result_value->subject_count;
            }
        }
        return array('sub_array' => $sub_array, 'exams_array' => $exams_array, 'exam_subject_array' => $ex_array, 'no_record' => $no_record);
    }

    public function getExamByExamGroupConnection($id = null)
    {
        $this->db->select('exam_group_class_batch_exams.*,IFNULL(exam_group_exam_connections.id,0) as `exam_group_exam_connection_id`,IFNULL(exam_group_exam_connections.exam_weightage,"0.00") as exam_weightage,(select COUNT(*) from exam_group_class_batch_exam_subjects WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id) as `total_subjects`')->from('exam_group_class_batch_exams');
        $this->db->join('exam_group_exam_connections', 'exam_group_exam_connections.exam_group_id = exam_group_class_batch_exams.exam_group_id and exam_group_exam_connections.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id', 'left');
        $this->db->where('exam_group_class_batch_exams.exam_group_id', $id);
        $this->db->order_by('exam_group_class_batch_exams.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getExamGroupConnectionList($exam_group_id = null)
    {
        $this->db->select('exam_group_exam_connections.*')->from('exam_group_exam_connections');
        $this->db->where('exam_group_exam_connections.exam_group_id', $exam_group_id);
        $this->db->order_by('exam_group_exam_connections.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function connectExam($insert_array, $exam_group_id)
    {
        $not_be_delted = array();
        if (!empty($insert_array)) {

            foreach ($insert_array as $array_key => $array_value) {
                $this->db->where('exam_group_id', $array_value['exam_group_id']);
                $this->db->where('exam_group_class_batch_exams_id', $array_value['exam_group_class_batch_exams_id']);
                $q = $this->db->get('exam_group_exam_connections');

                if ($q->num_rows() == 0) {
                    $this->db->insert('exam_group_exam_connections', $insert_array[$array_key]);
                    $not_be_delted[] = $array_value['exam_group_class_batch_exams_id'];
                } else {
                    $id                              = $q->row()->id;
                    $exam_group_class_batch_exams_id = $q->row()->exam_group_class_batch_exams_id;
                    $this->db->where('id', $id);
                    $this->db->update('exam_group_exam_connections', $insert_array[$array_key]);
                    $not_be_delted[] = $exam_group_class_batch_exams_id;
                }
            }
        }

        if (!empty($not_be_delted)) {

            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->where_not_in('exam_group_class_batch_exams_id', $not_be_delted);
            $this->db->delete('exam_group_exam_connections');
        } else {
            $this->db->where('exam_group_id', $exam_group_id);
            $this->db->delete('exam_group_exam_connections');
        }
    }

    public function deleteExamGroupConnection($exam_group_id)
    {
        $this->db->where('exam_group_id', $exam_group_id);
        $this->db->delete('exam_group_exam_connections');
    }

    public function getExamGroupByStudent($student_id, $active = 1)
    {
        $this->db->select('exam_group_students.*,exam_groups.name,exam_groups.exam_type,exam_groups.exam_type')->from('exam_group_students');
        $this->db->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id');
        $this->db->where('student_session_id', $student_id);
        $this->db->where('exam_groups.is_active', $active);
        $query = $this->db->get();
        return $query->result();
    }

    public function getExamGroupByStudentSession($student_session_id, $active = 1)
    {
        $this->db->select('exam_group_students.*,exam_groups.name,exam_groups.exam_type,exam_groups.exam_type')->from('exam_group_students');
        $this->db->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('exam_groups.is_active', $active);
        $query = $this->db->get();
        $exam_groups  = $query->result();
        $exam_results = array();
        if (!empty($exam_groups)) {
            foreach ($exam_groups as $exam_group_key => $exam_group_value) {
                $exam_groups[$exam_group_key]->exam_group_connection = $this->getExamGroupConnection($exam_group_value->exam_group_id);
                $exam_groups[$exam_group_key]->exam_results          = $this->getExamGroupExamsResultByStudentID($exam_group_value->exam_group_id, $student_session_id);
            }
            return $exam_groups;
        }
        return false;
    }

    public function getExamResultStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {
        $sql   = "SELECT `exam_group_class_batch_exam_subjects`.*,IFNULL(exam_group_student.id, 0) as exam_group_exam_result_id,exam_group_student.get_marks,exam_group_student.attendence,exam_group_student.note,subjects.id as `subject_id`,subjects.`name`,subjects.`code` FROM `exam_group_class_batch_exam_subjects` LEFT join (SELECT exam_group_exam_results.* FROM `exam_group_students` INNER JOIN exam_group_exam_results on exam_group_exam_results.exam_group_student_id = exam_group_students.id WHERE exam_group_students.exam_group_id=" . $this->db->escape($exam_group_id) . " and exam_group_students.student_session_id =" . $this->db->escape($student_id) . " ORDER BY `exam_group_id`) as `exam_group_student` on exam_group_student.exam_group_class_batch_exam_subject_id =exam_group_class_batch_exam_subjects.id INNER join subjects on subjects.id= exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $this->db->escape($exam_group_exam_id) . " ORDER BY `exam_group_class_batch_exams_id`";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {
        $this->db->select('exam_groups.*')->from('exam_groups');
        $this->db->where('id', $exam_group_id);
        $query = $this->db->get();
        $exam_group               = $query->row();
        $exam_group->exam_results = $this->getExamResultStudent($exam_group_exam_id, $exam_group_id, $student_id);
        return $exam_group;
    }

    public function getExamGroupExamsResultByStudentID($exam_group_id, $student_id)
    {
        $exam_group_exams = $this->getExamByExamGroup($exam_group_id, 1);
        if (!empty($exam_group_exams)) {
            foreach ($exam_group_exams as $exam_key => $exam_value) {
                $exam_group_exams[$exam_key]->exam_results = $this->getExamResultStudent($exam_value->id, $exam_value->exam_group_id, $student_id);
            }
        }
        return $exam_group_exams;
    }

    public function getExamGroupConnection($exam_group_id)
    {
        $result_array                     = array();
        $sql                              = "SELECT exam_group_exam_connections.*,exam_group_class_batch_exams.id as `exam_group_class_batch_exam_id`,exam_group_class_batch_exams.exam,exam_group_class_batch_exams.description FROM `exam_group_exam_connections` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id = exam_group_exam_connections.exam_group_class_batch_exams_id WHERE exam_group_exam_connections.exam_group_id=" . $exam_group_id;
        $query                            = $this->db->query($sql);
        $result                           = $query->result();
        $result_array['exam_connections'] = $result;
        if (!empty($result)) {
            $sql_inner                        = "SELECT exam_group_exam_connections.*,exam_group_class_batch_exam_subjects.id as exam_group_class_batch_exam_subject_id,exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id,exam_group_class_batch_exam_subjects.subject_id,exam_group_class_batch_exam_subjects.credit_hours,exam_group_class_batch_exam_subjects.date_from,exam_group_class_batch_exam_subjects.date_from,exam_group_class_batch_exam_subjects.date_to,exam_group_class_batch_exam_subjects.room_no,exam_group_class_batch_exam_subjects.max_marks,exam_group_class_batch_exam_subjects.max_marks,subjects.name,subjects.code FROM `exam_group_exam_connections`INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_exam_connections.exam_group_class_batch_exams_id INNER JOIN exam_group_class_batch_exam_subjects on exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id = exam_group_class_batch_exams.id  INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_exam_connections.exam_group_id=" . $exam_group_id . " GROUP BY exam_group_class_batch_exam_subjects.subject_id";
            $query                            = $this->db->query($sql_inner);
            $result_array['connect_subjects'] = $query->result();
        }

        return $result_array;
    }

    public function getExamGroupByClassSection($class_id, $section_id, $session_id)
    {
        $result_array = array();
        $sql          = "SELECT student_session.*,exam_group_students.exam_group_id,exam_groups.name FROM `student_session` INNER join exam_group_students on exam_group_students.student_id=student_session.student_id INNER JOIN exam_groups on exam_groups.id=exam_group_students.exam_group_id WHERE class_id= " . $this->db->escape($class_id) . " and section_id=" . $this->db->escape($section_id) . " and session_id=" . $this->db->escape($session_id) . " GROUP BY exam_group_students.exam_group_id";
        $query        = $this->db->query($sql);

        $result = $query->result();
        return $result;
    }

}
