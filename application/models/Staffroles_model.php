<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staffroles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getStaffRoles($staffid) {
        $this->db->select('staff_roles.*,roles.name');
        $this->db->from('staff_roles');
        $this->db->join('roles', 'roles.id=staff_roles.role_id', 'inner');
        $this->db->where('staff_roles.staff_id', $staffid);
        $query = $this->db->get();
        return $query->result();
    }

}
