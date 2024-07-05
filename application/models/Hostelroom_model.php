<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelroom_model extends MY_Model
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
        $this->db->select()->from('hostel_rooms');
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
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('hostel_rooms');
        $message   = DELETE_RECORD_CONSTANT . " On hostel rooms id " . $id;
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

    public function getRoomByHoselID($hostel_id)
    {
        $this->db->select('hostel_rooms.*,room_types.room_type');
        $this->db->from('hostel_rooms');
        $this->db->join('room_types', 'hostel_rooms.room_type_id = room_types.id');
        $this->db->where('hostel_rooms.hostel_id', $hostel_id);
        $query = $this->db->get();
        return $query->result();
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
            $this->db->update('hostel_rooms', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  hostel rooms id " . $data['id'];
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
                //return $return_value;
            }
        } else {
            $this->db->insert('hostel_rooms', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On hostel rooms id " . $insert_id;
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
            return $insert_id;
        }
    }

    public function lists()
    {
        $this->db->select('hostel_rooms.*,b.hostel_name,c.room_type');
        $this->db->from('hostel_rooms');
        $this->db->join('hostel b', 'b.id=hostel_rooms.hostel_id');
        $this->db->join('room_types c', 'c.id=hostel_rooms.room_type_id');
        $listroomtype = $this->db->get();
        return $listroomtype->result_array();
    }

    public function studentHostelDetails($carray = null)
    {
        $userdata = $this->customlib->getUserData();
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            if (!empty($carray)) {
                $this->datatables->where_in("student_session.class_id", $carray);
            } else {
                $this->datatables->where_in("student_session.class_id", "");
            }
        }

        $sql = "select students.firstname,students.middlename,students.id as sid,students.guardian_phone,students.admission_no,classes.class,sections.section,students.lastname,students.mobileno,hostel_rooms.*,hostel.hostel_name,room_types.room_type from students join student_session on  students.id = student_session.student_id join sections on sections.id = student_session.section_id join classes on classes.id = student_session.class_id join hostel_rooms on  hostel_rooms.id = students.hostel_room_id join hostel on hostel.id = hostel_rooms.hostel_id join room_types on  room_types.id = hostel_rooms.room_type_id where students.is_active= 'yes' ";

        $this->datatables->query($sql)
            ->query_where_enable(true)
            ->orderable('class,admission_no,students.firstname,mobileno,guardian_phone,hostel_name,room_no,room_type,cost_per_bed')
            ->searchable('class,admission_no,students.firstname,mobileno,guardian_phone,hostel_name,room_no,room_type,cost_per_bed')
            ->sort("students.firstname", "asc");
        return $this->datatables->generate('json');
    }

    public function searchHostelDetails($section_id, $class_id, $hostel_name = "")
    {
        if (!empty($hostel_name)) {
            $condition = "student_session.section_id ='" . $section_id . "' and student_session.class_id='" . $class_id . "' and hostel.hostel_name ='" . $hostel_name . "' and students.is_active='yes' ";
        } else {
            $condition = "student_session.section_id ='" . $section_id . "' and student_session.class_id='" . $class_id . "'  and students.is_active='yes' ";
        }

        $sql = "select students.firstname,students.middlename,students.id as sid, students.admission_no,students.guardian_phone,classes.class,sections.section,students.lastname,students.mobileno,hostel_rooms.*,hostel.hostel_name,room_types.room_type from students join student_session on students.id = student_session.student_id join sections on sections.id = student_session.section_id join classes on classes.id = student_session.class_id join  hostel_rooms on hostel_rooms.id = students.hostel_room_id join  hostel on hostel.id = hostel_rooms.hostel_id join room_types on room_types.id = hostel_rooms.room_type_id where " . $condition;
        $this->datatables->query($sql)
            ->query_where_enable(true)
            ->orderable('class,admission_no,students.firstname,mobileno,guardian_phone,hostel_name,room_no,room_type,cost_per_bed')
            ->searchable('class,admission_no,students.firstname,mobileno,guardian_phone,hostel_name,room_no,room_type,cost_per_bed')
            ->sort("students.firstname", "asc");
        return $this->datatables->generate('json');
    }

}
