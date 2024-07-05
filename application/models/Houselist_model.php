<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Houselist_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null, $is_active = null)
    {
        $this->db->select()->from('school_houses');
        if ($id != null) {
            $this->db->where('school_houses.id', $id);
        } else {
            $this->db->order_by('school_houses.id');
        }

        if ($is_active != null) {
            $this->db->where("is_active", $is_active);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('school_houses');
    }

}
