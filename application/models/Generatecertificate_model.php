<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Generatecertificate_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getcertificatebyid($certificate)
    {
        $this->db->select('*');
        $this->db->from('certificates');
        $this->db->where('id', $certificate);
        $query = $this->db->get();
        return $query->result();
    }

}
