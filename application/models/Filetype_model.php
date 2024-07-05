<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Filetype_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    public function get($id = null)
    {
        $this->db->select()->from('filetypes');
        $query = $this->db->get();
        return $query->row();
    }

    public function add($data)
    {
        $q = $this->db->get('filetypes');
        if ($q->num_rows() > 0) {
            $row = $q->row();
            $this->db->where('id', $row->id);
            $this->db->update('filetypes', $data);
        } else {
            $this->db->insert('filetypes', $data);
        }
    }

}
