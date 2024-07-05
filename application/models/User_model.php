<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('users', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  users id " . $data['id'];
            $action    = "Update";
           $insert_id = $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('users', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On users id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);             
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    public function addNewParent($data_parent_login, $student_data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        $this->db->insert('users', $data_parent_login);
        $insert_id                 = $this->db->insert_id();
        $student_data['parent_id'] = $insert_id;
        $this->student_model->add($student_data);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function checkLogin($data)
    {

        $resultdata    = $this->setting_model->getSetting();
        $student_login = json_decode($resultdata->student_login);
        $parent_login  = json_decode($resultdata->parent_login);

        $this->db->select('users.id as id, username, password,role,users.is_active as is_active,lang_id');
        $this->db->from('users');
        $this->db->join('students', 'students.id = users.user_id');         
        $this->db->where('username', $data['username']);
        if (!empty($student_login) && in_array("admission_no", $student_login)) {
            $this->db->or_where('students.admission_no', $data['username']);
        }
        if (!empty($student_login) && in_array("mobile_number", $student_login)) {
            $this->db->or_where('students.mobileno', $data['username']);
        }
        if (!empty($student_login) && in_array("email", $student_login)) {
            $this->db->or_where('students.email', $data['username']);
        }

        $this->db->limit(1);
        $query  = $this->db->get();
        $result = $query->result();
        if ($query->num_rows() == 1 && $result[0]->password == $data['password']) {
            return $result;
        } else {
            $this->db->select('users.id as id, username, password,role,users.is_active as is_active,lang_id');
            $this->db->from('users');
            $this->db->join('students', 'students.parent_id = users.id');
            $this->db->where('username', $data['username']);
            if (!empty($parent_login) && in_array("mobile_number", $parent_login)) {
                $this->db->or_where('students.guardian_phone', $data['username']);
            }
            if (!empty($parent_login) && in_array("email", $parent_login)) {
                $this->db->or_where('students.guardian_email', $data['username']);
            }
           
            $this->db->limit(1);
            $query   = $this->db->get();
            $result1 = $query->result();

            if ($query->num_rows() == 1 && $result1[0]->password == $data['password']) {
                return $result1;
            } else {
                return false;
            }
        }
    }
 
    public function checkLoginParent($data)
    {
        $resultdata   = $this->setting_model->getSetting();
        $parent_login = json_decode($resultdata->parent_login);
        $this->db->select('users.*,languages.language,students.admission_no,students.admission_no ,students.guardian_name, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image,students.father_pic,students.mother_pic,students.guardian_pic,students.guardian_relation, students.mobileno, students.email ,students.state , students.city , students.pincode , students.religion, students.dob ,students.current_address, students.permanent_address ,students.gender, students.guardian_phone, students.guardian_email,students.guardian_is,languages.is_rtl,IFNULL(currencies.short_name,0) as currency_name,IFNULL(currencies.symbol,0) as symbol,IFNULL(currencies.base_price,0) as base_price,IFNULL(currencies.id,0) as `currency`');
        $this->db->from('users');
        $this->db->join('students', 'students.parent_id = users.id');
        $this->db->join('languages', 'users.lang_id = languages.id', 'left');
        $this->db->join('currencies', 'currencies.id=users.currency_id', 'left');
        $this->db->where('username', $data['username']);

        if (!empty($parent_login) && in_array("mobile_number", $parent_login)) {
            $this->db->or_where('students.guardian_phone', $data['username']);
        }
        if (!empty($parent_login) && in_array("email", $parent_login)) {
            $this->db->or_where('students.guardian_email', $data['username']);
        }

        $this->db->where('password', ($data['password']));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function read_user_information($users_id) {
        $this->db->select('users.*,languages.language,students.firstname, students.middlename,students.image,students.lastname,students.guardian_name,students.gender,students.admission_no,students.email,IFNULL(currencies.short_name,0) as currency_name,IFNULL(currencies.symbol,0) as symbol,IFNULL(currencies.base_price,0) as base_price,IFNULL(currencies.id,0) as `currency`');
        $this->db->from('users');
        $this->db->join('students', 'students.id = users.user_id');
        $this->db->join('languages', 'languages.id = users.lang_id', 'left');
          $this->db->join('currencies', 'currencies.id=users.currency_id', 'left');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.id', $users_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
    
    public function read_teacher_information($users_id)
    {
        $this->db->select('users.*,teachers.name');
        $this->db->from('users');
        $this->db->join('teachers', 'teachers.id = users.user_id');
        $this->db->where('users.id', $users_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function read_accountant_information($users_id)
    {
        $this->db->select('users.*,accountants.name');
        $this->db->from('users');
        $this->db->join('accountants', 'accountants.id = users.user_id');
        $this->db->where('users.id', $users_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function read_librarian_information($users_id)
    {
        $this->db->select('users.*,librarians.name');
        $this->db->from('users');
        $this->db->join('librarians', 'librarians.id = users.user_id');
        $this->db->where('users.id', $users_id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function checkOldUsername($data)
    {
        $this->db->where('id', $data['user_id']);
        $this->db->where('username', $data['username']);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkOldPass($data)
    {
        $this->db->where('id', $data['user_id']);
        $this->db->where('password', $data['current_pass']);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserNameExist($data)
    {
        $this->db->where('role', $data['role']);
        $this->db->where('username', $data['new_username']);
        $query = $this->db->get('users');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveNewPass($data)
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function changeStatus($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $query     = $this->db->update('users', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On users id " . $data['id'];
        $action    = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        $this->db->trans_complete(); # Completing transaction
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function saveNewUsername($data)
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function read_user()
    {
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function read_single_child($child_id)
    {
        $this->db->select('*');
        $this->db->where('users.id', $child_id);
        $this->db->from('users');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getLoginDetails($student_id)
    {
        $sql   = "SELECT * FROM (select * from users where find_in_set('$student_id',childs) <> 0 union SELECT * FROM `users` WHERE `user_id` = " . $this->db->escape($student_id) . " AND `role` != 'teacher' AND `role` != 'librarian' AND `role` != 'accountant') a order by a.role desc";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentLoginDetails($student_id)
    {
        $sql   = "SELECT users.* FROM users WHERE id in (select students.parent_id from users INNER JOIN students on students.id =users.user_id WHERE users.user_id=" . $this->db->escape($student_id) . " AND users.role ='student') UNION select users.* from users INNER JOIN students on students.id =users.user_id WHERE users.user_id=" . $this->db->escape($student_id) . " AND users.role ='student'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getTeacherLoginDetails($teacher_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $teacher_id);
        $this->db->where('role', 'teacher');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getLibrarianLoginDetails($librarian_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $librarian_id);
        $this->db->where('role', 'librarian');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function getAccountantLoginDetails($accountant_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $accountant_id);
        $this->db->where('role', 'accountant');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function updateVerCode($data)
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByEmail($table, $role, $email)
    {
        $this->db->select($table . '.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`');
        $this->db->from($table);
        $this->db->join('users', 'users.user_id = ' . $table . '.id', 'left');
        $this->db->where('users.role', $role);
        if ($role == 'parent') {
            $this->db->where($table . '.guardian_email', $email);
        } else {
            $this->db->where($table . '.email', $email);
        }
        $query = $this->db->get();
        if ($email != null) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getParentByEmail($table, $role, $email)
    {
        $this->db->select($table . '.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`');
        $this->db->from($table);
        $this->db->join('users', 'users.id = ' . $table . '.parent_id', 'left');
        $this->db->where('users.role', $role);
        if ($role == 'parent') {
            $this->db->where($table . '.guardian_email', $email);
        } else {
            $this->db->where($table . '.email', $email);
        }
        $query = $this->db->get();
        if ($email != null) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getUserValidCode($table, $role, $code)
    {
        $this->db->select($table . '.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`');
        $this->db->from($table);
        $this->db->join('users', 'users.user_id = ' . $table . '.id', 'left');
        $this->db->where('users.role', $role);
        $this->db->where('users.verification_code', $code);

        $query = $this->db->get();
        if ($code != null) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getParentUserValidCode($table, $role, $code)
    {
        $this->db->select($table . '.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`');
        $this->db->from($table);
        $this->db->join('users', 'users.id = ' . $table . '.parent_id', 'left');
        $this->db->where('users.role', $role);
        $this->db->where('users.verification_code', $code);

        $query = $this->db->get();
        if ($code != null) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function forgotPassword($usertype, $email)
    {
        $result = false;
        if ($usertype == 'student') {
            $table  = "students";
            $role   = "student";
            $result = $this->getUserByEmail($table, $role, $email);
        } elseif ($usertype == 'parent') {
            $table  = "students";
            $role   = "parent";
            $result = $this->getParentByEmail($table, $role, $email);
        } elseif ($usertype == 'teacher') {

            $table  = "teachers";
            $role   = "teacher";
            $result = $this->getUserByEmail($table, $role, $email);
        } elseif ($usertype == 'accountant') {
            $table  = "accountants";
            $role   = "accountant";
            $result = $this->getUserByEmail($table, $role, $email);
        } elseif ($usertype == 'librarian') {
            $table  = "librarians";
            $role   = "librarian";
            $result = $this->getUserByEmail($table, $role, $email);
        }
        return $result;
    }

    public function getUserByCodeUsertype($usertype, $code)
    {
        $result = false;

        if ($usertype == 'student') {
            $table  = "students";
            $role   = "student";
            $result = $this->getUserValidCode($table, $role, $code);
        } elseif ($usertype == 'parent') {
            $table  = "students";
            $role   = "parent";
            $result = $this->getParentUserValidCode($table, $role, $code);
        } elseif ($usertype == 'teacher') {

            $table  = "teachers";
            $role   = "teacher";
            $result = $this->getUserValidCode($table, $role, $code);
        } elseif ($usertype == 'accountant') {
            $table  = "accountants";
            $role   = "accountant";
            $result = $this->getUserValidCode($table, $role, $code);
        } elseif ($usertype == 'librarian') {
            $table  = "librarians";
            $role   = "librarian";
            $result = $this->getUserValidCode($table, $role, $code);
        }
        return $result;
    }

    public function getUserLoginDetails($student_id)
    {
        $sql   = "SELECT users.* FROM users WHERE user_id =" . $student_id . " and role = 'student'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function getParentLoginDetails($student_id)
    {
        $sql   = "SELECT users.* FROM `users` join students on students.parent_id = users.id WHERE students.id = " . $student_id . " and role = 'parent'";
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function student_information($users_id)
    {
        $this->db->select('users.*,students.admission_no,students.firstname,students.lastname,users.password,students.mobileno,students.email,students.guardian_phone,students.guardian_email,students.parent_id,student_session.id as student_session_id');
        $this->db->from('users');
        $this->db->join('students', 'students.id = users.user_id');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('users.user_id', $users_id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_studentdefaultClass($student_id)
    {
        $sql   = "SELECT class_sections.id from student_session join class_sections on class_sections.class_id=student_session.class_id and class_sections.section_id=student_session.section_id WHERE student_session.student_id=" . $student_id;
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    
    public function getById($id) {
        $this->db->select('users.*,languages.language');
        $this->db->from('users');
        $this->db->join('languages', 'languages.id = users.lang_id', 'left');
        $this->db->where('users.id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

}
