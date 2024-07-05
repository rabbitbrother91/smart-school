<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Routepickuppoint_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select('route_pickup_point.*,pickup_point.name')->from('route_pickup_point');
            $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id');
        if ($id != null) {
            $this->db->where('route_pickup_point.id', $id);
        } else {
            $this->db->order_by('route_pickup_point.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

}
