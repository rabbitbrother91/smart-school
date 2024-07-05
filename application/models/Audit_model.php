<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Audit_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($limit = null, $offset = null)
    {
        $this->db->select('logs.*, CONCAT_WS("",staff.name,staff.surname," (",staff.employee_id,")") as name')->from('logs');
        $this->db->join('staff', 'staff.id = logs.user_id');
        $this->db->order_by('logs.id', 'asc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllRecord()
    {
        $this->datatables
            ->select('logs.*, CONCAT_WS("",staff.name,staff.surname," (",staff.employee_id,")") as name')
            ->join('staff', 'staff.id = logs.user_id')
            ->searchable('message, name, ip_address, action, platform, agent')
            ->orderable('message, name, ip_address, action, platform, agent')
            ->sort('logs.id','desc')
            ->from('logs');
        return $this->datatables->generate('json');
    }

    public function count()
    {
        $query = $this->db->select('count(*) as total')->get('logs')->row_array();
        return $query['total'];
    }

    public function audittrail_delete()
    {
        $this->db->truncate('logs');
    }

}
