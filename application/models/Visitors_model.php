<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class visitors_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session      = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month          = $this->setting_model->getStartMonth();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('visitors_book', $data);
        $query     = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On  visitors  id " . $query;
        $action    = "Insert";
        $record_id = $query;
        $this->log($message, $record_id, $action);

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {

            return $record_id;
        }
    }

    public function getPurpose()
    {
        $this->db->select('*');
        $this->db->from('visitors_purpose');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function visitors_list($id = null)
    {
        $this->db->select('visitors_book.*,classes.class,sections.section,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id,student_session.class_id,student_session.section_id,students.id as students_id,students.admission_no,students.firstname as student_firstname,students.middlename as student_middlename,students.lastname as student_lastname,roles.id as role_id')->from('visitors_book');
        if ($id != null) {
            $this->db->where('visitors_book.id', $id);
        } else {
            $this->db->order_by('visitors_book.id', 'desc');
        }
        $this->db->join('student_session', 'student_session.id=visitors_book.student_session_id', 'left');
        $this->db->join('students', 'students.id=student_session.student_id', 'left');
        $this->db->join('classes', 'student_session.class_id=classes.id', 'left');
        $this->db->join('sections', 'sections.id=student_session.section_id', 'left');
        $this->db->join('staff', 'staff.id=visitors_book.staff_id', 'left');
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", 'left');
        $this->db->join("roles", "staff_roles.role_id = roles.id", 'left');

        if ($id == null) {
            if ($this->session->has_userdata('admin')) {
                $getStaffRole = $this->customlib->getStaffRole();
                $staffrole    = json_decode($getStaffRole);

                $superadmin_visible = $this->customlib->superadmin_visible();
                if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                    $this->db->where("role_id !=", 7);
                    $this->db->or_where("role_id =", null);
                }
            }
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('visitors_book');
        $message   = DELETE_RECORD_CONSTANT . " On  visitors  id " . $id;
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

    public function update($id, $data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('visitors_book', $data);

        $message   = UPDATE_RECORD_CONSTANT . " On  visitors id " . $id;
        $action    = "Update";
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

    public function image_add($visitor_id, $image)
    {
        $array = array('id' => $visitor_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('visitors_book');
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
    }

    public function image_update($visitor_id, $image)
    {
        $array = array('id' => $visitor_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('visitors_book');
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
    }

    public function image_delete($id, $img_name)
    {
        $file = "./uploads/front_office/visitors/" . $img_name;
        unlink($file);
        $this->db->where('id', $id);
        $this->db->delete('visitors_book');
        $controller_name = $this->uri->segment(2);
        $this->session->set_flashdata('msg', '<div class="alert alert-success"> ' . ucfirst($controller_name) . '' . $this->lang->line('success_message') . '</div>');
        redirect('admin/' . $controller_name);
    }

    public function getstudent($class_id, $section_id)
    {
        $this->db->select('student_session.id,students.firstname,students.middlename,students.lastname,students.id as student_id, students.admission_no');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id=student_session.student_id', 'left');
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->where('student_session.session_id', $this->current_session);        
        $result = $this->db->get();
        return $result->result_array();
    }

    public function visitorbystudentid($student_session_id)
    {
        $this->db->select('visitors_book.*')->from('visitors_book');
        $this->db->where('visitors_book.student_session_id', $student_session_id);
        $this->db->order_by('visitors_book.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function visitorbystaffid($staff_id)
    {
        $this->db->select('visitors_book.*')->from('visitors_book');
        $this->db->where('visitors_book.staff_id', $staff_id);
        $this->db->order_by('visitors_book.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

}
