<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timeline_model extends CI_Model
{

    public function add($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("student_timeline", $data);
        } else {
            $this->db->insert("student_timeline", $data);
            return $this->db->insert_id();
        }
    }

    public function add_staff_timeline($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("staff_timeline", $data);
        } else {
            $this->db->insert("staff_timeline", $data);
            return $this->db->insert_id();
        }
    }

    public function getStudentTimeline($id, $status = '')
    {
        if (!empty($status)) {
            $this->db->where("status", "yes");
        }
        $query = $this->db->where("student_id", $id)->order_by("timeline_date", "asc")->get("student_timeline");
        return $query->result_array();
    }

    public function getStaffTimeline($id, $status = '')
    {
        if (!empty($status)) {
            $this->db->where("status", $status);
        }
        $query = $this->db->where("staff_id", $id)->order_by("timeline_date", "asc")->get("staff_timeline");
        return $query->result_array();
    }

    public function delete_timeline($id)
    {
        $this->db->where("id", $id)->delete("student_timeline");
    }

    public function delete_staff_timeline($id)
    {
        $this->db->where("id", $id)->delete("staff_timeline");
    }

    public function getstudentsingletimeline($id)
    {
        $this->db->select("*");
        $this->db->from("student_timeline");
        $this->db->where("id", $id);
        $result = $this->db->get();
        return $result->row_array();
    }

    public function getstaffsingletimeline($id)
    {
        $this->db->select("*");
        $this->db->from("staff_timeline");
        $this->db->where("id", $id);
        $result = $this->db->get();
        return $result->row_array();
    }

}
