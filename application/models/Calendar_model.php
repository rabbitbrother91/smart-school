<?php

class Calendar_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function saveEvent($data)
    {
        if (isset($data["id"])) {
            $this->db->where("id", $data["id"])->update("events", $data);
        } else {
            $this->db->insert("events", $data);
        }
    }

    public function getEvents($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("events");
            return $query->row_array();
        } else {
            $query = $this->db->get("events");
            return $query->result_array();
        }
    }

    public function getStudentEvents($id = null)
    {
        $cond  = "(event_type = 'public' or event_type = 'task') and role_id IS NULL ";
        $query = $this->db->where($cond)->get("events");
        return $query->result_array();
    }

    public function deleteEvent($id)
    {
        $this->db->where("id", $id)->delete("events");
    }

    public function getTask($id, $role_id, $limit = null, $offset = null)
    {
        $query = $this->db->where(array('event_type' => 'task', 'event_for' => $id, 'role_id' => $role_id))->order_by("is_active,start_date", "asc")->limit($limit, $offset)->get("events");

        return $query->result_array();
    }

    public function countEventByUser($user_id)
    {
        $query = $this->db->where(array("event_type" => "task", 'event_for' => $user_id))->get("events");
        return $query->num_rows();
    }

    public function countrows($id, $role_id)
    {
        $query = $this->db->where(array('event_type' => 'task', 'event_for' => $id, 'role_id' => $role_id))->order_by("is_active,start_date", "asc")->get("events");
        return $query->num_rows();
    }

    public function countincompleteTask($id,$role_id=null)
    {
        $query = $this->db->where("event_type", "task")->where("is_active", "no")->where("event_for", $id)->where('role_id', $role_id)->where("start_date", date("Y-m-d"))->get("events");
        return $query->num_rows();
    }

    public function getincompleteTask($id,$role_id=null)
    {
        $query = $this->db->where("event_type", "task")->where("is_active", "no")->where("event_for", $id)->where('role_id', $role_id)->where("start_date", date("Y-m-d"))->order_by("start_date", "asc")->get("events");
        return $query->result_array();
    }

    public function geteventreminder($reminder_date)
    {
        $query = $this->db->where("date_format(start_date,'%Y-%m-%d')", $reminder_date)->get("events");
        return $query->result_array();
    }

    public function getstaffandstudentemail()
    {
        $this->db->select("email");
        $this->db->distinct();
        $this->db->from("staff");
        $this->db->where('staff.is_active', 1);
        $this->db->get();
        $query1 = $this->db->last_query();

        $this->db->select("email");
        $this->db->distinct();
        $this->db->from("students");
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->get();
        $query2 = $this->db->last_query();

        $query = $this->db->query($query1 . " UNION " . $query2);

        return $query->result_array();
    }

}
