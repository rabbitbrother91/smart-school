<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class langpharses_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get($id)
    {
        $query = "SELECT lang_keys.id,lang_keys.key,IFNULL(lang_pharses.id, 0 ) as pharsesid,lang_pharses.pharses FROM lang_keys LEFT JOIN lang_pharses ON lang_keys.id=lang_pharses.key_id and lang_pharses.lang_id=" . $this->db->escape($id) . " order by lang_keys.key asc";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getByLangAfter($id, $after)
    {
        $query = "SELECT lang_keys.id,lang_keys.key,IFNULL(lang_pharses.id, 0 ) as pharsesid,lang_pharses.pharses FROM lang_keys LEFT JOIN lang_pharses ON lang_keys.id=lang_pharses.key_id and lang_pharses.lang_id=" . $this->db->escape($id) . " where lang_keys.id >" . $this->db->escape($after) . " order by lang_keys.key asc";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('lang_pharses');
    }

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('lang_pharses', $data);
        } else {
            $this->db->insert('lang_pharses', $data);
        }
    }

    public function deletepharses($lang_id)
    {
        $this->db->where('lang_id', $lang_id);
        $this->db->delete('lang_pharses');
    }

}
