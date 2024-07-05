<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Class_model extends MY_Model
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
    public function getAll($id = null)
    {
        $this->db->select()->from('classes');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            $classlist = $query->row_array();
        } else {
            $classlist = $query->result_array();
        }

        return $classlist;
    }

    public function get($id = null, $classteacher = null)
    {
        $userdata = $this->customlib->getUserData();
        $role_id  = $userdata["role_id"];
        $carray   = array();
        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if ($userdata["class_teacher"] == 'yes') {
             
                $classlist = $this->teacher_model->get_teacherrestricted_mode($userdata["id"]);
            }
        } else {
         
            $this->db->select()->from('classes');
            if ($id != null) {
                $this->db->where('id', $id);
            } else {
                $this->db->order_by('id');
            }
            $query = $this->db->get();
            if ($id != null) {
                $classlist = $query->row_array();
            } else {
                $classlist = $query->result_array();
            }
        }

        return $classlist;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('classes'); //class record delete.
        $this->db->where('class_id', $id);
        $this->db->delete('class_sections'); //class_sections record delete.
        $message   = DELETE_RECORD_CONSTANT . " On classes id " . $id;
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
            $this->db->update('classes', $data);
        } else {
            $this->db->insert('classes', $data);
        }
    }

    public function check_data_exists($data)
    {
        $this->db->where('class', $data);

        $query = $this->db->get('classes');
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

    public function check_classteacher_exists($class, $section, $teacher)
    {
        $this->db->where(array('class_id' => $class, 'section_id' => $section, 'session_id' => $this->current_session));

        $query = $this->db->get('class_teacher');
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function class_teacher_exists($str)
    {
        $class    = $this->input->post('class');
        $section  = $this->input->post('section');
        $teachers = $this->input->post('teachers');

        $res = $this->check_classteacher_exists($class, $section, $teachers);

        if ($res) {
            $prev_class_id   = $this->input->post('prev_class_id');
            $prev_section_id = $this->input->post('prev_section_id');
            if (isset($prev_class_id) && isset($prev_section_id)) {
                if ($prev_class_id == $class && $prev_section_id == $section) {
                    return true;
                }
            }
            $this->form_validation->set_message('class_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function getClassTeacher()
    {
        $query = $this->db->query('SELECT class_teacher.*,classes.class,sections.section FROM `class_teacher` INNER JOIN classes on classes.id=class_teacher.class_id INNER JOIN sections on sections.id=class_teacher.section_id where class_teacher.session_id="' . $this->current_session . '" GROUP BY class_teacher.class_id , class_teacher.section_id ORDER by length(classes.class), classes.class');
        $result = $query->result_array();
        return $result;
    }

    public function get_section($id)
    {
        return $this->db->select('sections.id,sections.section')->from('class_sections')->join('sections', 'class_sections.section_id=sections.id')->where('class_id', $id)->get()->result_array();
    }

}
