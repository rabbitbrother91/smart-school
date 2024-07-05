<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendencetype_model extends CI_Model
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
        $this->db->select()->from('attendence_type');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('is_active', 'yes');
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAttType($id = null)
    {
        $this->db->select()->from('attendence_type');
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

    public function getstdAttType($id)
    {
        $this->db->select()->from('attendence_type');
        $this->db->where_not_in('id', $id);
        $this->db->order_by('id');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('attendence_type', $data);
        } else {
            $this->db->insert('attendence_type', $data);
        }
    }

    public function getStaffAttendanceType($id = null)
    {
        $this->db->select('staff_attendance_type.*')->from('staff_attendance_type');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('is_active', 'yes');
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getStudentAttendence($date, $student_session_id)
    {
        $sql = "SELECT attendence_type.type FROM `student_attendences` INNER JOIN attendence_type ON attendence_type.id=student_attendences.attendence_type_id where  student_attendences.`student_session_id`=" . $this->db->escape($student_session_id) . " and student_attendences.date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getStudentAttendenceRange($date, $student_session_id)
    {
        $sql = "SELECT attendence_type.type,attendence_type.key_value,student_attendences.* FROM `student_attendences`
INNER JOIN attendence_type ON attendence_type.id=student_attendences.attendence_type_id where  student_attendences.`student_session_id`=" . $this->db->escape($student_session_id) . " and student_attendences.date >= " . $this->db->escape($date['start']) . " and student_attendences.date <= " . $this->db->escape($date['end']);
        $query = $this->db->query($sql);
        return $query->result();
    }

}
