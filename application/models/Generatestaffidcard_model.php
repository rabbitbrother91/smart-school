<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generatestaffidcard_model extends CI_model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getstaffidcard()
    {
        $this->db->select('*');
        $this->db->from('staff_id_card');
        $query = $this->db->get();
        return $query->result();
    }

    public function getidcardbyid($idcard)
    {
        $this->db->select('*');
        $this->db->from('staff_id_card');
        $this->db->where('id', $idcard);
        $query = $this->db->get();
        return $query->result();
    }

    public function getEmployee($array, $active)
    {
        $this->db->select("staff.*,staff_designation.designation,department.department_name as department,roles.name as user_type")->from('staff');
        $this->db->join('staff_designation', "staff_designation.id = staff.designation", "left");
        $this->db->join('staff_roles', "staff_roles.staff_id = staff.id", "left");
        $this->db->join('roles', "roles.id = staff_roles.role_id", "left");
        $this->db->join('department', "department.id = staff.department", "left");
        $this->db->where("staff.is_active", $active);
        $this->db->where_in("staff.id", $array);
        $query = $this->db->get();
        return $query->result();
    }
}
