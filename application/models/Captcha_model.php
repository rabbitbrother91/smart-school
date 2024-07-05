<?php

class Captcha_model extends CI_Model
{

    public function getSetting()
    {
        $this->db->select('*');
        $this->db->from('captcha');
        $query = $this->db->get();
        return $query->result();
    }

    public function getStatus($name)
    {
        $this->db->select('*');
        $this->db->where("name", $name);
        $this->db->from('captcha');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function update_status($data)
    {
        $this->db->where('name', $data["name"]);
        $this->db->update('captcha', $data);
    }

}
