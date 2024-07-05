<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Alumni_model extends MY_Model
{

    public function get_alumnidetail($student_id)
    {
        return $this->db->select('*')->from('alumni_students')->where('student_id', $student_id)->get()->row_array();
    }

    public function get()
    {
        return $this->db->select('*')->from('alumni_students')->get()->result_array();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('alumni_students', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Alumni Student id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('alumni_students', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Alumni Student id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $record_id;
        }
    }

    public function add_event($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('alumni_events', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Alumni Event id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            
            
        } else {
            $this->db->insert('alumni_events', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Alumni Event id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
             
            
        }
        
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

    public function getevents()
    {
        return $this->db->select('*')->from('alumni_events')->order_by('alumni_events.from_date', 'desc')->get()->result_array();
    }

    public function get_eventbydate($date)
    {
        return $this->db->select('*')->from('alumni_events')->where('from_date', $date)->get()->result_array();
    }

    public function get_eventbyid($id)
    {
        return $this->db->select('*')->from('alumni_events')->where('id', $id)->get()->row_array();
    }

    public function get_eventbydaterange($start, $end)
    {
        $sql   = "select * from alumni_events where (alumni_events.from_date >= " . $this->db->escape($start) . " and alumni_events.from_date <= " . $this->db->escape($end) . ") or (alumni_events.to_date >= " . $this->db->escape($start) . " and alumni_events.to_date <= " . $this->db->escape($end) . ")";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function delete_event($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('alumni_events');

        $message   = DELETE_RECORD_CONSTANT . " On Alumni Event  id " . $id;
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
            return $record_id;
        }
    }

    public function deletestudent($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('student_id', $id);
        $this->db->delete('alumni_students');

        $message   = DELETE_RECORD_CONSTANT . " On  alumni students  id " . $id;
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
            return $record_id;
        }
    }

    public function alumniMail($class_id = null, $session = null, $section = null)
    {
        $this->db->select('alumni_students.*');
        $this->db->join('student_session', 'student_session.student_id = alumni_students.student_id');
        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
            $this->db->where('student_session.session_id', $session);
            $this->db->where('student_session.section_id', $section);
            $this->db->where('student_session.is_alumni', 1);
        }
        $this->db->from('alumni_students');
        $query = $this->db->get();
        return $query->result_array();
    }

}
