<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineexamquestion_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getByExamID($exam_id, $limit, $start, $where_search)
    {
        
        $this->db->select('questions.*,subjects.name as subject_name,subjects.code as subject_code, IFNULL(onlineexam_questions.id,0) as `onlineexam_question_id`,IFNULL(onlineexam_questions.marks,1.00) as `onlineexam_question_marks`,IFNULL(onlineexam_questions.neg_marks,"0.25") as `onlineexam_question_neg_marks`')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('onlineexam_questions', '(onlineexam_questions.question_id = questions.id AND onlineexam_questions.onlineexam_id=' . $this->db->escape($exam_id) . ')', 'LEFT');
        
        if (!empty($where_search)) {
            if (isset($where_search['subject']) && $where_search['subject'] != "") {
                $this->db->where('subjects.id', $where_search['subject']);
            }
            if (isset($where_search['keyword']) && $where_search['keyword'] != "") {
                $this->db->like('question', $where_search['keyword']);
            }
            if (isset($where_search['question_level']) && $where_search['question_level'] != "") {
                $this->db->where('level', $where_search['question_level']);
            }
            if (isset($where_search['question_type']) && $where_search['question_type'] != "") {
                $this->db->where('question_type', $where_search['question_type']);
            }
            if (isset($where_search['class_id']) && $where_search['class_id'] != "") {
                $this->db->where('class_id', $where_search['class_id']);
            }
            if (isset($where_search['section_id']) && $where_search['section_id'] != "") {
                $this->db->where('section_id', $where_search['section_id']);
            }
           if ($where_search['is_quiz'] == 1) {
                $this->db->where('questions.question_type !=','descriptive');
            }

        }else{

        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];

        if(isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")){
                   $carray = array();
        $class_list=array();
       
            if ($userdata["class_teacher"] == 'yes') {

                $carray = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
            }
 

        foreach ($carray as $key => $value) {
          $class_list[]=$value['id'];
        } 
        if(!empty($class_list)){
             $this->db->where_in('questions.class_id',$class_list);
        }
        
                }
        }
        $this->db->order_by('questions.id');
        $this->db->limit($limit, $start);

        $query = $this->db->get();

        return $query->result();

    }

    public function getExamQuestions($exam_id)
    {
        $this->db->select('questions.*,subjects.id as `subject_id`,subjects.name as subject_name,subjects.code as subject_code, IFNULL(onlineexam_questions.id,0) as `onlineexam_question_id`,IFNULL(onlineexam_questions.marks,1) as `onlineexam_question_marks`,onlineexam_questions.neg_marks')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('onlineexam_questions', '(onlineexam_questions.question_id = questions.id AND onlineexam_questions.onlineexam_id=' . $this->db->escape($exam_id) . ')');
        $this->db->order_by('questions.id');
        $query = $this->db->get();
        return $query->result();

    }

       public function getExamQuestionSubjects($exam_id)
    {
        $this->db->select('subjects.id as `subject_id`,subjects.name as subject_name, IFNULL(onlineexam_questions.id,0) as `onlineexam_question_id`,IFNULL(onlineexam_questions.marks,1) as `onlineexam_question_marks`')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('onlineexam_questions', '(onlineexam_questions.question_id = questions.id AND onlineexam_questions.onlineexam_id=' . $this->db->escape($exam_id) . ')');
        $this->db->group_by('subjects.id');
        $this->db->order_by('questions.id');
        $query = $this->db->get();
        return $query->result();

    }

    public function getCountByExamID($exam_id, $where_search)
    {
        $this->db->select('questions.*,subjects.name as subject_name')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        if (!empty($where_search)) {
            if (isset($where_search['subject']) && $where_search['subject'] != "") {
                $this->db->where('subjects.id', $where_search['subject']);
            }
            if (isset($where_search['keyword']) && $where_search['keyword'] != "") {
                $this->db->like('question', $where_search['keyword']);
            }
            if (isset($where_search['question_level']) && $where_search['question_level'] != "") {
                $this->db->where('level', $where_search['question_level']);
            }
            if (isset($where_search['question_type']) && $where_search['question_type'] != "") {
                $this->db->where('question_type', $where_search['question_type']);
            }
            if (isset($where_search['class_id']) && $where_search['class_id'] != "") {
                $this->db->where('class_id', $where_search['class_id']);
            }
            if (isset($where_search['section_id']) && $where_search['section_id'] != "") {
                $this->db->where('section_id', $where_search['section_id']);
            }
              if ($where_search['is_quiz'] == 1) {
                $this->db->where('questions.question_type !=','descriptive');
            }


        }
        $query = $this->db->get();
        return $query->num_rows();

    }

    public function getByExamNoLimit($exam_id, $question_type)
    {

        $this->db->select('questions.*,subjects.name as subject_name, IFNULL(onlineexam_questions.id,0) as `onlineexam_question_id`,IFNULL(onlineexam_questions.marks,1) as `onlineexam_question_marks`')->from('questions');
        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('onlineexam_questions', '(onlineexam_questions.question_id = questions.id AND onlineexam_questions.onlineexam_id=' . $this->db->escape($exam_id) . ')');
        $this->db->where('questions.question_type', $question_type);
        $this->db->order_by('questions.id');
        $query = $this->db->get();
        return $query->result();

    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('onlineexam_questions');
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

}
