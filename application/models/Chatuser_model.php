<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chatuser_model extends CI_Model
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
    public function searchForUser($keyword, $chat_user_id, $login_id, $user_type = 'staff')
    {
        if ($user_type == 'staff') {
            $sql = "SELECT staff.id as `staff_id`,Null as `student_id`, staff.name, staff.surname , Null as `first_name`,Null as `middle_name`,Null as `last_name`,staff.image FROM `staff` WHERE staff.name LIKE '%" . $keyword . "%'  and is_active= 1 and id NOT IN(SELECT chat_users.staff_id FROM `chat_users` inner JOIN (SELECT chat_connections.id, CASE  WHEN chat_user_one =" . $chat_user_id . " THEN chat_user_two ELSE chat_connections.chat_user_one END as 'chat_user_id' FROM `chat_connections` WHERE  (chat_user_one=" . $chat_user_id . " or chat_user_two=" . $chat_user_id . ")) as chat_connections on chat_connections.chat_user_id=chat_users.id WHERE staff_id IS NOT NULL) and staff.id != " . $login_id . " Union  SELECT Null as `staff_id`,students.id as `student_id`,Null as `name`,Null as `surname`,students.firstname,students.middlename,students.lastname,students.image FROM `students` WHERE (students.firstname LIKE '%" . $keyword . "%' or students.middlename LIKE '%" . $keyword . "%' or students.lastname LIKE '%" . $keyword . "%')  and students.is_active='yes' and students.id NOT IN(SELECT chat_users.student_id FROM `chat_users` inner JOIN (SELECT chat_connections.id, CASE  WHEN chat_user_one =" . $chat_user_id . " THEN chat_user_two ELSE chat_connections.chat_user_one END as 'chat_user_id' FROM `chat_connections` WHERE  (chat_user_one=" . $chat_user_id . " or chat_user_two=" . $chat_user_id . ")) as chat_connections on chat_connections.chat_user_id=chat_users.id WHERE student_id IS NOT NULL)";
        } else if ($user_type == 'student') {

            $sql = "SELECT staff.id as `staff_id`,Null as `student_id`,staff.name, staff.surname ,staff.image FROM `staff` WHERE staff.name LIKE '%" . $keyword . "%' and is_active= 1 and id NOT IN(SELECT chat_users.staff_id FROM `chat_users` inner JOIN (SELECT chat_connections.id, CASE  WHEN chat_user_one =" . $chat_user_id . " THEN chat_user_two ELSE chat_connections.chat_user_one END as 'chat_user_id' FROM `chat_connections` WHERE  (chat_user_one=" . $chat_user_id . " or chat_user_two=" . $chat_user_id . ")) as chat_connections on chat_connections.chat_user_id=chat_users.id WHERE staff_id IS NOT NULL)";
        }

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function myUser($staff_id, $chat_connection_id, $user_type = 'staff')
    {
        if ($user_type == 'staff') {
            $sql = " SELECT * from chat_connections WHERE chat_connections.chat_user_one= (SELECT id FROM `chat_users` WHERE staff_id=" . $staff_id . ") or chat_connections.chat_user_two = (SELECT id FROM `chat_users` WHERE staff_id=" . $staff_id . ") ORDER BY `chat_connections`.`id` DESC";
        } else if ($user_type == 'student') {
            $sql = " SELECT * from chat_connections WHERE chat_connections.chat_user_one= (SELECT id FROM `chat_users` WHERE student_id=" . $staff_id . ") or chat_connections.chat_user_two = (SELECT id FROM `chat_users` WHERE student_id=" . $staff_id . ") ORDER BY `chat_connections`.`id` DESC";
        }

        $query = $this->db->query($sql);

        $chat_users = $query->result();
        foreach ($chat_users as $chat_users_key => $chat_users_value) {
            $messages                       = $this->getLastMessages($chat_users_value->id);
            $messages                       = $this->getLastMessages($chat_users_value->id);
            $chat_users_value->{'messages'} = $messages;

            $chat_user_id = $chat_users_value->chat_user_one;
            if ($chat_users_value->chat_user_one == $chat_connection_id) {
                $chat_user_id = $chat_users_value->chat_user_two;
            }

            $chat_users_value->{'user_details'} = $this->getChatUserDetail($chat_user_id);
        }
        $return_result = array(
            'chat_users'             => $chat_users,
            'chat_user_notification' => $this->getChatNotification($chat_connection_id),
        );

        return json_encode($return_result);
    }

    public function getLastMessages($chat_connection_id)
    {
        $sql = "SELECT * FROM chat_messages WHERE id=(SELECT max(id) FROM `chat_messages` WHERE chat_connection_id=" . $chat_connection_id . ")";

        $query         = $this->db->query($sql);
        $chat_messages = $query->row();
        return $chat_messages;
    }

    public function getChatUserDetail($chat_user_id)
    {
        $sql = "SELECT chat_users.id as `chat_user_id`, chat_users.user_type,chat_users.student_id,staff_id ,students.firstname,students.middlename,students.lastname,staff.name,staff.surname,(CASE WHEN staff_id IS NULL THEN (SELECT
students.image as `image` FROM students WHERE students.id=chat_users.student_id) ELSE (SELECT staff.image as `image` FROM staff WHERE staff.id=chat_users.staff_id) END) as image FROM `chat_users` left JOIN students on students.id=chat_users.student_id left JOIN staff on staff.id=chat_users.staff_id WHERE chat_users.id=" . $chat_user_id;
        $query     = $this->db->query($sql);
        $chat_user = $query->row();

        return $chat_user;
    }

    public function myChatAndUpdate($chat_connection_id, $chat_user_id)
    {
        $update_read = array('is_read' => 1);
        $this->db->where('chat_connection_id', $chat_connection_id);
        $this->db->where('chat_user_id', $chat_user_id);
        $this->db->update('chat_messages', $update_read);
        $sql           = "SELECT * FROM `chat_messages` WHERE chat_connection_id=" . $chat_connection_id;
        $query         = $this->db->query($sql);
        $chat_messages = $query->result();
        return $chat_messages;
    }

    public function getMyID($id, $user_type = 'staff')
    {
        if ($user_type == 'staff') {
            $sql = "SELECT * FROM `chat_users` WHERE staff_id=" . $id . " and user_type='staff'";
        }
        if ($user_type == 'student') {
            $sql = "SELECT * FROM `chat_users` WHERE student_id=" . $id . " and user_type='student'";
        }
        $query         = $this->db->query($sql);
        $chat_messages = $query->row();
        return $chat_messages;
    }

    public function getChatNotification($chat_user_id)
    {
        $sql               = "SELECT COUNT(*) as `no_of_notification`, chat_connection_id FROM `chat_messages`   WHERE chat_connection_id in (SELECT chat_connections.id FROM `chat_connections` WHERE chat_user_one=" . $chat_user_id . " or chat_user_two=" . $chat_user_id . ") and chat_user_id=" . $chat_user_id . "  and is_read = 0 GROUP by chat_connection_id ORDER BY `chat_connection_id` ASC";
        $query             = $this->db->query($sql);
        $chat_notification = $query->result();
        return $chat_notification;
    }

    public function getChatConnectionByID($id)
    {
        $this->db->select()->from('chat_connections');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function addMessage($insert)
    {
        $this->db->insert('chat_messages', $insert);
        return $this->db->insert_id();
    }

    public function getUpdatedchat($chat_connection_id, $last_chat_id, $chat_user_id)
    {
        $update_read = array('is_read' => 1);
        $this->db->where('chat_connection_id', $chat_connection_id);
        $this->db->where('chat_user_id', $chat_user_id);
        $this->db->update('chat_messages', $update_read);
        $sql           = "SELECT * FROM `chat_messages` WHERE chat_connection_id=" . $chat_connection_id . " and id > " . $last_chat_id . " ORDER BY `chat_messages`.`chat_connection_id` ASC";
        $query         = $this->db->query($sql);
        $chat_messages = $query->result();
        return $chat_messages;
    }

    public function addNewUser($first_entry, $insert_data, $id, $insert_message, $panel = "staff")
    {
        $chat_connections = array('chat_user_one' => '', 'chat_user_two' => '');
        $this->db->where('staff_id', $first_entry['staff_id']);
        $this->db->where('user_type', $first_entry['user_type']);
        $q = $this->db->get('chat_users');
        if ($insert_data['user_type'] == 'staff') {
            $this->db->where('staff_id', $insert_data['staff_id']);
            $this->db->where('user_type', $insert_data['user_type']);
            $q1 = $this->db->get('chat_users');
        } elseif ($insert_data['user_type'] == 'student') {
            $this->db->where('student_id', $insert_data['student_id']);
            $this->db->where('user_type', $insert_data['user_type']);
            $q1 = $this->db->get('chat_users');
        }

        if ($q->num_rows() > 0 && $q1->num_rows() > 0) {
            $chat_connections['chat_user_one'] = $q->row()->id;
            $chat_connections['chat_user_two'] = $q1->row()->id;
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else if ($q->num_rows() == 0 && $q1->num_rows() > 0) {

            $this->db->insert('chat_users', $first_entry);
            $chat_connections['chat_user_one'] = $this->db->insert_id();
            $chat_connections['chat_user_two'] = $q1->row()->id;
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);

            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else if ($q->num_rows() > 0 && $q1->num_rows() == 0) {
            $chat_connections['chat_user_one'] = $q->row()->id;
            if ($panel == "staff") {
                $insert_data['create_staff_id'] = $id;
            }
            $this->db->insert('chat_users', $insert_data);
            $chat_connections['chat_user_two'] = $this->db->insert_id();
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else {
            $this->db->insert('chat_users', $first_entry);
            $chat_connections['chat_user_one'] = $this->db->insert_id();
            if ($panel == "staff") {
                $insert_data['create_staff_id'] = $id;
            }
            $this->db->insert('chat_users', $insert_data);
            $chat_connections['chat_user_two'] = $this->db->insert_id();
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id = $this->db->insert_id();

            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);

            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        }
    }

    public function addNewUserForStudent($first_entry, $insert_data, $id, $insert_message, $panel = "student")
    {
        $chat_connections = array('chat_user_one' => '', 'chat_user_two' => '');
        $this->db->where('student_id', $first_entry['student_id']);
        $this->db->where('user_type', $first_entry['user_type']);
        $q = $this->db->get('chat_users');
        $this->db->where('staff_id', $insert_data['staff_id']);
        $this->db->where('user_type', $insert_data['user_type']);
        $q1 = $this->db->get('chat_users');
        if ($q->num_rows() > 0 && $q1->num_rows() > 0) {
            $chat_connections['chat_user_one'] = $q->row()->id;
            $chat_connections['chat_user_two'] = $q1->row()->id;
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else if ($q->num_rows() > 0 && $q1->num_rows() == 0) {
            $chat_connections['chat_user_one'] = $q->row()->id;
            if ($panel == "student") {
                $insert_data['create_student_id'] = $id;
            }
            $this->db->insert('chat_users', $insert_data);
            $chat_connections['chat_user_two'] = $this->db->insert_id();
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else if ($q->num_rows() == 0 && $q1->num_rows() > 0) {

            $chat_connections['chat_user_two'] = $q1->row()->id;
            $this->db->insert('chat_users', $first_entry);
            $chat_connections['chat_user_one'] = $this->db->insert_id();
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        } else {
            $this->db->insert('chat_users', $first_entry);
            $chat_connections['chat_user_one'] = $this->db->insert_id();
            if ($panel == "student") {
                $insert_data['create_student_id'] = $id;
            }
            $this->db->insert('chat_users', $insert_data);
            $chat_connections['chat_user_two'] = $this->db->insert_id();
            $this->db->insert('chat_connections', $chat_connections);
            $new_user_chat_connection_id          = $this->db->insert_id();
            $insert_message['chat_user_id']       = $chat_connections['chat_user_two'];
            $insert_message['chat_connection_id'] = $new_user_chat_connection_id;
            $this->db->insert('chat_messages', $insert_message);
            return json_encode(array('new_user_id' => $chat_connections['chat_user_two'], 'new_user_chat_connection_id' => $new_user_chat_connection_id));
        }
    }

    public function mynewuser($user_id, $userlist)
    {
        $ids = "";
        if (!empty($userlist)) {
            $ids = join("','", $userlist);
            $ids = " and id NOT IN ('$ids') ";
        }

        $sql        = "SELECT * FROM `chat_connections` WHERE (chat_user_one =" . $user_id . " or chat_user_two=" . $user_id . ")" . $ids . "ORDER BY `chat_connections`.`id` ASC";
        $query      = $this->db->query($sql);
        $chat_users = $query->result();
        foreach ($chat_users as $chat_users_key => $chat_users_value) {
            $messages                       = $this->getLastMessages($chat_users_value->id);
            $messages                       = $this->getLastMessages($chat_users_value->id);
            $chat_users_value->{'messages'} = $messages;

            $chat_user_id = $chat_users_value->chat_user_one;
            if ($chat_users_value->chat_user_one == $user_id) {
                $chat_user_id = $chat_users_value->chat_user_two;
            }

            $chat_users_value->{'user_details'} = $this->getChatUserDetail($chat_user_id);
        }
        $return_result = array(
            'chat_users'             => $chat_users,
            'chat_user_notification' => $this->getChatNotification($user_id),
        );

        return json_encode($return_result);
    }

}
