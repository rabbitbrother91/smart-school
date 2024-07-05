<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cms_page_content_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->load->config('ci-blog');
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('front_cms_page_contents');
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

    public function getContentByPage($page_id = null)
    {
        $this->db->select()->from('front_cms_page_contents');
        $this->db->where('page_id', $page_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('front_cms_page_contents');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('front_cms_page_contents', $data);
        } else {
            $this->db->insert('front_cms_page_contents', $data);
            return $this->db->insert_id();
        }
    }

    public function batch_insert($data)
    {
        $this->db->insert_batch('front_cms_page_contents', $data);
    }

    public function insertOrUpdate($data)
    {
        $this->db->where('page_id', $data['page_id']);
        $q = $this->db->get('front_cms_page_contents');

        if ($q->num_rows() > 0) {
            $this->db->where('page_id', $data['page_id']);
            $this->db->update('front_cms_page_contents', $data);
        } else {
            $this->db->insert('front_cms_page_contents', $data);
        }
    }

    public function deleteByPageID($page_id)
    {
        $this->db->where('page_id', $page_id);
        $this->db->delete('front_cms_page_contents');
    }

}
