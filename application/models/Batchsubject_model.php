<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Batchsubject_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getExamSubjects($id = null)
    {
        $subject_condition = 0;
        $userdata          = $this->customlib->getUserData();
        $role_id           = $userdata["role_id"];

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {

                $my_classes = $this->teacher_model->my_classes($userdata['id']);

                if (!empty($my_classes)) {
                    $subject_condition = 0;
                } else {
                    $subject_condition = 1;
                    $my_subjects       = $this->teacher_model->get_examsubjects($userdata['id']);
                }
            }
        }

        $this->db->select('exam_group_class_batch_exam_subjects.*,subjects.name as `subject_name`,subjects.code as `subject_code`,subjects.type as `subject_type`')->from('exam_group_class_batch_exam_subjects');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $id);
        if ($subject_condition == 1 && (!empty($my_subjects))) {
            $this->db->where_in('subjects.id', $my_subjects);
        }
        $this->db->order_by('exam_group_class_batch_exam_subjects.date_from', 'ASC');
        $this->db->order_by('exam_group_class_batch_exam_subjects.time_from', 'ASC');
        $query  = $this->db->get();
        $result = $query->result();

        return $result;
    }

    public function getExamstudentSubjects($id = null)
    {
        $this->db->select('exam_group_class_batch_exam_subjects.*,subjects.name as `subject_name`,subjects.code as `subject_code`,subjects.type as `subject_type`')->from('exam_group_class_batch_exam_subjects');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $this->db->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $id);
        $this->db->order_by('exam_group_class_batch_exam_subjects.date_from', 'asc');
        $this->db->order_by('exam_group_class_batch_exam_subjects.time_from', 'asc');
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function examGroupExamResult($class_id, $batch_id, $exam_group_class_batch_exams_id)
    {
        $exam_students = $this->attempt_exam_students($class_id, $batch_id, $exam_group_class_batch_exams_id);
        if (!empty($exam_students)) {

            foreach ($exam_students as $exam_result_student_key => $exam_result_student_value) {
                $sql                                                   = "SELECT `exam_group_class_batch_exam_subjects`.*,exam_group_exam_results.id as exam_group_exam_result_id,exam_group_exam_results.exam_group_student_id,exam_group_exam_results.exam_group_class_batch_exam_subject_id,exam_group_exam_results.attendence,exam_group_exam_results.get_marks,exam_group_exam_results.note FROM `exam_group_class_batch_exam_subjects` LEFT JOIN exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id= exam_group_class_batch_exam_subjects.id and exam_group_exam_results.exam_group_student_id=" . $exam_result_student_value->id . " WHERE exam_group_class_batch_exams_id=" . $exam_group_class_batch_exams_id . " ORDER BY `exam_group_class_batch_exams_id` DESC";
                $query                                                 = $this->db->query($sql);
                $exam_students[$exam_result_student_key]->marks_result = $query->result();
            }
        }

        return $exam_students;
    }

    public function attempt_exam_students($class_id, $batch_id, $exam_group_class_batch_exams_id)
    {
        $sql   = "SELECT exam_group_students.*,student.admission_no , student.id as `student_id`, student.roll_no,student.admission_date,student.firstname,student.middlename, student.lastname,student.image, student.mobileno, student.email ,student.state , student.city , student.pincode,student.father_name,student.dob,student.gender FROM `exam_group_students` INNER JOIN exam_groups on exam_groups.id= exam_group_students.exam_group_id INNER join (SELECT exam_groups.id FROM `exam_group_class_batch_exam_subjects` INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=exam_group_class_batch_exams.id inner JOIN exam_groups on exam_groups.id= exam_group_class_batch_exams.exam_group_id  where exam_group_class_batch_exams_id=" . $exam_group_class_batch_exams_id . " GROUP by exam_group_class_batch_exams_id ORDER BY `exam_group_class_batch_exams_id`) as exam_group on exam_group.id=exam_group_students.exam_group_id INNER JOIN (SELECT students.* from student_session INNER JOIN students on students.id=student_session.student_id WHERE student_session.class_id=" . $class_id . " and students.batch_id=" . $batch_id . " GROUP BY student_session.student_id) as student on student.id=exam_group_students.student_id";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get($id = null)
    {
        $this->db->select('class_batches.*,classes.class,sections.section,batch.name')->from('class_batches');
        $this->db->join('class_sections', 'class_batches.class_section_id = class_sections.id');
        $this->db->join('batch', 'batch.id = class_batches.batch_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        if ($id != null) {
            $this->db->where('class_batches.id', $id);
        } else {
            $this->db->order_by('class_batches.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            $result = $query->row();
        } else {
            $result = $query->result();
            foreach ($result as $key => $value) {
                $value->batch_subjects = $this->getClassBatchSubjects($value->id);
            }
            return $result;
        }
    }

    public function getClassBatchSubjects($id = null)
    {
        $this->db->select('class_batch_subjects.*,name as `subject_name`');
        $this->db->from('class_batch_subjects');
        $this->db->join('subjects', 'subjects.id = class_batch_subjects.subject_id');
        $this->db->where('class_batch_subjects.class_batch_id', $id);
        $this->db->order_by('class_batch_subjects.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function getByID($id = null)
    {
        $this->db->select('class_batch_subjects.*,class_batches.class_section_id as `class_section_id`,class_batches.batch_id, `class_sections`.`class_id`,`class_sections`.`section_id`');
        $this->db->from('class_batch_subjects');
        $this->db->join('class_batches', 'class_batches.id = class_batch_subjects.class_batch_id');
        $this->db->join('class_sections', 'class_sections.id = class_batches.class_section_id');
        $this->db->where('class_batch_subjects.id', $id);
        $this->db->order_by('class_batch_subjects.id', 'asc');
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_begin();
        $record         = $this->getByID($id);
        $class_batch_id = $record->class_batch_id;
        $this->db->where('id', $id);
        $this->db->delete('class_batch_subjects'); //class record delete.
        $this->db->select('*');
        $this->db->where('class_batch_subjects.class_batch_id', $class_batch_id);
        $query = $this->db->get('class_batch_subjects');
        if ($query->num_rows() <= 0) {
            $this->db->where('id', $class_batch_id);
            $this->db->delete('class_batches'); //class record delete.
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return true;
    }

    public function removeGroup($id)
    {
        $this->db->trans_begin();
        $this->db->where('id', $id);
        $this->db->delete('class_batches'); //class record delete.
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return true;
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $insert_id       = "";
        $subject_id      = $data['subject_id'];
        $is_exam         = $data['is_exam'];
        $batchsubject_id = $this->batchsubject_exists($data['class_section_id'], $data['batch_id']);
        if (!$batchsubject_id) {
            unset($data['subject_id']);
            unset($data['is_exam']);
            $this->db->insert('class_batches', $data);
            $insert_id = $this->db->insert_id();
        } else {
            $insert_id = $batchsubject_id;
        }

        if (isset($data['id'])) {
            $subject_array = array(
                'id'             => $data['id'],
                'class_batch_id' => $insert_id,
                'subject_id'     => $subject_id,
                'is_exam'        => $is_exam,
            );
            $this->db->where('id', $data['id']);
            $insert_id = $this->db->update('class_batch_subjects', $subject_array);
        } else {
            $subject_array = array(
                'class_batch_id' => $insert_id,
                'subject_id'     => $subject_id,
                'is_exam'        => $is_exam,
            );
            $insert_id = $this->db->insert('class_batch_subjects', $subject_array);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function batchsubject_exists($class_section_id, $batch_id)
    {
        $this->db->where('class_section_id', $class_section_id);
        $this->db->where('batch_id', $batch_id);
        $query = $this->db->get('class_batches');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        }
        return false;
    }

    public function check_data_exists($data)
    {
        $this->db->where('class', $data);

        $query = $this->db->get('class_batches');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function class_exists($str)
    {
        $class = $this->security->xss_clean($str);
        $res   = $this->check_data_exists($class);

        if ($res) {
            $pre_class_id = $this->input->post('pre_class_id');
            if (isset($pre_class_id)) {
                if ($res->id == $pre_class_id) {
                    return true;
                }
            }
            $this->form_validation->set_message('class_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function getBatchClass($id = null)
    {
        $this->db->select('class_batches.*,classes.id as class_id,classes.class')->from('class_batches');
        $this->db->join('class_sections', 'class_batches.class_section_id = class_sections.id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->order_by('class_batches.id');
        $this->db->group_by('classes.id');
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getBatchSectionByClass($classid)
    {
        $this->db->select('class_batches.*,classes.id as class_id,class_sections.id as class_section_id,classes.class,sections.section')->from('class_batches');
        $this->db->join('class_sections', 'class_batches.class_section_id = class_sections.id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->order_by('class_batches.id');
        $this->db->where('classes.id', $classid);
        $this->db->group_by('class_batches.class_section_id');
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getBatchByClassSection($class_section_id)
    {
        $this->db->select('class_batches.*,classes.id as class_id,class_sections.id as class_section_id,classes.class,sections.section,batch.name as `batch_name`')->from('class_batches');
        $this->db->join('class_sections', 'class_batches.class_section_id = class_sections.id');
        $this->db->join('batch', 'batch.id = class_batches.batch_id');
        $this->db->join('classes', 'classes.id = class_sections.class_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id');
        $this->db->order_by('class_batches.id');
        $this->db->where('class_batches.class_section_id', $class_section_id);
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getBatchByClass($class_id)
    {
        $this->db->select('student_session.*,students.firstname,students.middlename,students.batch_id,batch.name as `batch_name`')->from('student_session');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('batch', 'batch.id = students.batch_id');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->group_by('students.batch_id');
        $this->db->order_by('students.id');
        $query  = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function getExamSubject($exam_subject_id)
    {
        $sql   = "SELECT exam_group_class_batch_exam_subjects.*,subjects.name as `subject_name`,subjects.code FROM `exam_group_class_batch_exam_subjects` INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.id=" . $this->db->escape_str($exam_subject_id);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function valid_batchsubject()
    {
        $class_section_id = $this->input->post('section_id');
        $batch_id         = $this->input->post('batch_id');
        $subject_id       = $this->input->post('subject_id');
        $id               = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_batch_subject_exists($class_section_id, $subject_id, $batch_id, $id)) {
            $this->form_validation->set_message('check_batchsubjectexists', 'Record combination already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_batch_subject_exists($class_section_id, $subject_id, $batch_id, $id)
    {
        if ($class_section_id != "" && $subject_id != "" && $batch_id) {
            $sql   = "SELECT * FROM `class_batches` INNER JOIN class_batch_subjects on class_batch_subjects.class_batch_id=class_batches.id and class_batches.class_section_id=" . $this->db->escape_str($class_section_id) . " and batch_id=" . $this->db->escape_str($batch_id) . " WHERE class_batch_subjects.subject_id=" . $this->db->escape_str($subject_id) . " and class_batch_subjects.id !=" . $id;
            $query = $this->db->query($sql);
            if ($query->num_rows() > 0) {
                return $query->row();
            } else {
                return false;
            }
        }
        return false;
    }

}
