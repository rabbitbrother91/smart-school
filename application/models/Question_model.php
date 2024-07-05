<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Question_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('questions', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  questions id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
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
        } else {
            $this->db->insert('questions', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  questions id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
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
            return $id;
        }
    }

    public function get($id = null)
    {
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];

        $this->db->select('questions.*,subjects.name,subjects.code,classes.class as `class_name`,sections.section as `section_name`')->from('questions');

        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('classes', 'classes.id = questions.class_id', 'left');
        $this->db->join('sections', 'sections.id = questions.section_id', 'left');

        if ($id != null) {
            $this->db->where('questions.id', $id);
        } else {
            $this->db->order_by('questions.id');
        }

        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }

    }

    public function getall($limit = null, $offset = null)
    {
        $this->db->select('questions.*,subjects.name,classes.class as `class_name`,sections.section as `section_name`')->from('questions');
        $this->db->join('subjects', 'subjects.id = questions.subject_id');
        $this->db->join('classes', 'classes.id = questions.class_id', 'left');
        $this->db->join('sections', 'sections.id = questions.section_id', 'left');
        $this->db->limit($limit, $offset);
        $this->db->order_by('questions.id');
        $query = $this->db->get();
        return $query->result();
    }


    public function getAllRecord($class_id,$section_id,$subject,$question_type,$question_level,$created_by)
    {

        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
 
        if ($role_id == 2) {
            $my_section = array();
            if ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '1') {
                $my_class = $this->class_model->get();

                foreach ($my_class as $class_key => $class_value) {
                    $my_class_id[] = $class_value['id'];
                }
                $this->datatables->where_in('questions.class_id', $my_class_id);

            } elseif ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '0') {

                $my_class = $this->class_model->get();
                foreach ($my_class as $class_key => $class_value) {

                    $my_class_id[] = $class_value['id'];

                }

                $this->datatables->where_in('questions.class_id', $my_class_id);
                $this->datatables->where('questions.staff_id', $this->customlib->getStaffID());

            } elseif ($this->sch_setting_detail->class_teacher == 'no' && $this->sch_setting_detail->my_question == '1') {

                $this->datatables->where('questions.staff_id', $this->customlib->getStaffID());

            }

        }

        if (!empty($class_id)){
            $this->datatables->where('questions.class_id', $class_id);
        }

        if(!empty($section_id)){
            $this->datatables->where('questions.section_id', $section_id);
        }

        if(!empty($subject)){
            $this->datatables->where('questions.subject_id', $subject);
        }

        if(!empty($question_type)){
            $this->datatables->where('questions.question_type', $question_type);
        }

        if(!empty($question_level)){
            $this->datatables->where('questions.level', $question_level);
        }
        
        if(!empty($created_by)){
            $this->datatables->where('questions.staff_id', $created_by);
        }


        $this->datatables->select('questions.*,subjects.name,subjects.code,classes.class as class_name,sections.section as section_name,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id,staff_roles.role_id as created_role');
        $this->datatables->join('subjects', 'subjects.id = questions.subject_id');
        $this->datatables->join('classes', 'classes.id = questions.class_id', 'left');
        $this->datatables->join('sections', 'sections.id = questions.section_id', 'left');
        $this->datatables->join('staff', 'staff.id = questions.staff_id', 'left');
        $this->datatables->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');

        $this->datatables->searchable('questions.id,subjects.name,questions.question_type,questions.level,questions.question,classes.class,questions.staff_id');
        $this->datatables->orderable('questions.id,subjects.name,questions.question_type,questions.level,questions.question,classes.class,questions.staff_id');
        $this->datatables->from('questions');
        $this->datatables->sort('questions.id','desc');
        return $this->datatables->generate('json');
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('questions');
        $message   = DELETE_RECORD_CONSTANT . " On questions id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
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

    public function image_add($id, $image)
    {

        $this->db->where('id', $id);
        $this->db->update('questions', $image);

    }

    public function bulkdelete($question_array)
    {

        $this->db->where_in('id', $question_array);
        $this->db->delete('questions');

    }

    public function add_option($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('question_options', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On question_options id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            $return_value = $data['id'];
        } else {
            $this->db->insert('question_options', $data);
            $message   = INSERT_RECORD_CONSTANT . " On question_options id " . $this->db->insert_id();
            $action    = "Insert";
            $record_id = $this->db->insert_id();
            $this->log($message, $record_id, $action);
            $return_value = $this->db->insert_id();
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $return_value;
        }
    }

    public function add_question_answers($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('question_answers', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On question_answers id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
            $return_value = $data['id'];
        } else {
            $this->db->insert('question_answers', $data);
            $message   = INSERT_RECORD_CONSTANT . " On question_answers id " . $this->db->insert_id();
            $action    = "Insert";
            $record_id = $this->db->insert_id();
            $this->log($message, $record_id, $action);
            $return_value = $this->db->insert_id();
        }

        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $return_value;
        }
    }

    public function add_question_bulk($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert_batch('questions', $data);
        $message   = 'Questions ' . IMPORT_RECORD_CONSTANT . " (" . count($data) . ")";
        $action    = "Import";
        $record_id = null;
        $this->log($message, $record_id, $action);
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

    public function get_result($id)
    {
        return $this->db->select('*')->from('questions')->join('question_answers', 'question.id=question_answers.question_id')->get()->row_array();

    }
    public function get_option($id)
    {
        return $this->db->select('id,option')->from('question_options')->where('question_id', $id)->get()->result_array();
    }

    public function get_answer($id)
    {
        return $this->db->select('option_id as answer_id')->from('question_answers')->where('question_id', $id)->get()->row_array();
    }

    public function count()
    {
        $query = $this->db->select('count(*) as total')->get('questions')->row_array();
        return $query['total'];
    }
    
    public function getquestioncreatedstaff()
    {
        $this->superadmin_visible = $this->customlib->superadmin_visible();
        $getStaffRole     = $this->customlib->getStaffRole();
        if(isset($getStaffRole)){
            $this->staffrole   =   json_decode($getStaffRole); 
        }        
        
        $this->db->select('staff.id,staff.name,staff.surname,staff.employee_id');
        $this->db->from('questions');        
        $this->db->join('staff', 'staff.id = questions.staff_id', 'left'); 
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        
        if ($this->session->has_userdata('admin')) {
            if($this->staffrole->id != 7){
                if ($this->superadmin_visible == 'disabled' ) {
                    $this->db->where("roles.id !=", 7);             
                } 
            }
        }        
        
        $this->db->order_by('staff.id');
        $this->db->group_by('staff.name');
        $query = $this->db->get();
        return $query->result();
    }
}
