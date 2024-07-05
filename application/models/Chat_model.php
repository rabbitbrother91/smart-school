<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chat_model extends Admin_Model
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
    public function seen($sender_id, $receiver_id, $sender_type, $receiver_type, $data)
    {
        $this->db->where('sender_id', $sender_id);
        $this->db->where('receiver_id', $receiver_id);
        $this->db->where('sender_type', $sender_type);
        $this->db->where('receiver_type', $receiver_type);
        $this->db->update('chat', $data);
    }

    public function get_chat($sender_id, $receiver_id)
    {
        $this->db->select('*')->from('chat');
        $this->db->where("(`sender_id` = '" . $sender_id . "' or `receiver_id` = '" . $sender_id . "') AND (`sender_id` = '" . $receiver_id . "' or `receiver_id` ='" . $receiver_id . "')");
        $this->db->order_by('id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */

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
            $this->db->update('chat', $data);
        } else {
            $this->db->insert('chat', $data);
            return $this->db->insert_id();
        }
    }

    public function conversation_staff($sender_id)
    {
        $this->db->select('chat.receiver_id,staff.name,staff.surname, chat.sender_type,chat.receiver_type,roles.name as role,staff.image ')->from('chat')->join('staff', 'staff.id=chat.receiver_id', 'inner')->join('staff_roles', 'staff.id=staff_roles.staff_id', 'inner')->join('roles', 'staff_roles.role_id=roles.id', 'inner');

        $this->db->where("(sender_id='" . $sender_id . "' or sender_type=1  and receiver_id='" . $sender_id . "' or receiver_type=1)");
        $this->db->group_by('receiver_id');
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation_student($sender_id)
    {
        $this->db->select('chat.receiver_id, students.firstname,students.middlename,students.lastname, chat.sender_type,chat.receiver_type,"Student" as role,students.image as image')->from('chat')->join('students', 'students.id=chat.receiver_id', 'inner');
        $this->db->where("(sender_id='" . $sender_id . "' or sender_type=2  and receiver_id='" . $sender_id . "' or receiver_type=2)");
        $this->db->group_by('receiver_id');
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation_parent($sender_id)
    {
        $this->db->select('chat.receiver_id, students.father_name, chat.sender_type,chat.receiver_type,"Parent" as role,"no_image.png" as image')->from('chat')->join('students', 'students.parent_id=chat.receiver_id', 'inner');
        $this->db->where("(sender_id='" . $sender_id . "' or sender_type=3  and receiver_id='" . $sender_id . "' or receiver_type=3)");
        $this->db->group_by('receiver_id');
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation_seen($sender_id)
    {
        $this->db->select('chat.receiver_id,staff.name,staff.surname, chat.sender_type,chat.receiver_type,roles.name as role,staff.image ')->from('chat')->join('staff', 'staff.id=chat.receiver_id', 'inner')->join('staff_roles', 'staff.id=staff_roles.staff_id', 'inner')->join('roles', 'staff_roles.role_id=roles.id', 'inner');
        $this->db->where("(sender_id='" . $sender_id . "' OR receiver_id='" . $sender_id . "')");       
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation($sender_id)
    {
        $this->db->select('chat.receiver_id,chat.sender_type,chat.receiver_type,')->from('chat');
        $this->db->where("(sender_id='" . $sender_id . "' OR receiver_id='" . $sender_id . "')");       
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation_forstudent($sender_id)
    {
        $this->db->select('chat.receiver_id,staff.name,staff.surname, chat.sender_type,chat.receiver_type,roles.name as role,staff.image ')->from('chat')->join('staff', 'staff.id=chat.receiver_id', 'inner')->join('staff_roles', 'staff.id=staff_roles.staff_id', 'inner')->join('roles', 'staff_roles.role_id=roles.id', 'inner');

        $this->db->where("(sender_id='" . $sender_id . "' or sender_type=2  and receiver_id='" . $sender_id . "' or receiver_type=2)");
        $this->db->group_by('receiver_id');
        $this->db->order_by('chat.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function conversation_forparent($parent_id)
    {
        $this->db->select('chat.receiver_id,staff.name,chat.message,chat.created_at,staff.surname, chat.sender_type,chat.receiver_type,roles.name as role,staff.image ')->from('chat')->join('staff', 'staff.id=chat.receiver_id', 'inner')->join('staff_roles', 'staff.id=staff_roles.staff_id', 'inner')->join('roles', 'staff_roles.role_id=roles.id', 'inner');

        $this->db->where("(sender_id='" . $parent_id . "' or sender_type=3  and receiver_id='" . $parent_id . "' or receiver_type=3)");
        $this->db->group_by('receiver_id');
        $this->db->order_by('chat.created_at', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_staff($staff)
    {
        $this->db->select('staff.name ,staff.id,roles.name as role')->from('staff')->join('staff_roles', 'staff.id=staff_roles.staff_id', 'inner')->join('roles', 'staff_roles.role_id=roles.id', 'inner');
        $this->db->like('staff.name', $staff);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_student($student)
    {
        $this->db->select('students.firstname ,students.middlename, students.lastname,students.id,"Student" as role')->from('students');
        $this->db->like('students.firstname', $student);
        $this->db->or_like('students.lastname', $student);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_parent($name)
    {
        $this->db->select('students.father_name,students.parent_id, "Parent" as role')->from('students');
        $this->db->like('students.father_name', $name);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function receiver_name($receiver_id, $type)
    {
        if ($type == '1') {
            $this->db->select('staff.name,staff.surname,staff.image,roles.name as role')->from('staff')->join('staff_roles', 'staff_roles.staff_id=staff.id', 'inner')->join('roles', 'roles.id=staff_roles.role_id', 'inner')->where('staff.id', $receiver_id);
        } elseif ($type == '3') {
            $this->db->select('students.father_name as name," " as surname," " as image,"Parent" as role')->from('students')->where('students.parent_id', $receiver_id);
        } elseif ($type == '2') {
            $this->db->select('students.firstname as name,students.middlename,students.lastname as surname,students.image as image,"Student" as role')->from('students')->where('students.id', $receiver_id);
        }

        $query = $this->db->get();
        return $query->row_array();
    }

}
