<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cms_menu_model extends CI_Model
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
    public function get($id = null)
    {
        $this->db->select()->from('front_cms_menus');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('front_cms_menus', $data);
        } else {
            $this->db->insert('front_cms_menus', $data);
            return $this->db->insert_id();
        }
    }

    public function getBySlug($slug = null)
    {
        $this->db->select()->from('front_cms_menus');
        if ($slug != null) {
            $this->db->where('slug', $slug);
        }
        $query  = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function findMenuExists($menu)
    {
        $this->db->select()->from('front_cms_menus');
        $this->db->where('menu', $menu);
        $query  = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function valid_check_exists($str)
    {
        $menu = $this->input->post('menu');
        $id   = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_data_exists($menu, $id)) {
            $this->form_validation->set_message('check_exists', 'Menu already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($menu, $id)
    {
        $this->db->where('menu', $menu);
        $this->db->where('id !=', $id);
        $query = $this->db->get('front_cms_menus');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function removeBySlug($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->delete('front_cms_menus');
    }

}
