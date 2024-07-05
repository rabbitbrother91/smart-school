<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Currency_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('currencies.*,IFNULL(sch_settings.currency, 0) as `currency_id`')->from('currencies');
        $this->db->join('sch_settings', 'currencies.id=sch_settings.currency', 'left');

        if ($id != null) {
            $this->db->where('currencies.id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('currencies', $data);
        } else {
            $this->db->insert('currencies', $data);
        }
    }

    public function update_currency($setting_data)
    {
        $this->db->where('id', $setting_data['id']);
        $this->db->update('sch_settings', $setting_data);
    }

}
