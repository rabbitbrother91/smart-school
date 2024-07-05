<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('timetables');
    }

    public function add($data)
    {
        if (($data['id']) != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('timetables', $data);
        } else {
            $this->db->insert('timetables', $data);
            return $this->db->insert_id();
        }
    }

    public function get($data)
    {
        $query = $this->db->get_where('timetables', $data);
        return $query->result_array();
    }

}
