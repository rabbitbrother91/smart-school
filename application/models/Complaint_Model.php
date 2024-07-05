<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class complaint_Model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session      = $this->setting_model->getCurrentSession();
        $this->current_session_name = $this->setting_model->getCurrentSessionName();
        $this->start_month          = $this->setting_model->getStartMonth();
    }

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('complaint', $data);
        $query     = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On  Complain id " . $query;
        $action    = "Insert";
        $record_id = $query;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
        return $query;
    }

    public function image_add($complaint_id, $image)
    {
        $array = array('id' => $complaint_id);
        $this->db->set('image', $image);
        $this->db->where($array);
        $this->db->update('complaint');
    }

    public function complaint_list($id = null)
    {
        $this->db->select()->from('complaint');
        if ($id != null) {
            $this->db->where('complaint.id', $id);
        } else {
            $this->db->order_by('complaint.id', "desc");
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function image_delete($id, $img_name)
    {
        $file = "./uploads/front_office/complaints/" . $img_name;
        unlink($file);
        $this->db->where('id', $id);
        $this->db->delete('complaint');
        $controller_name = $this->uri->segment(2);
    }

    public function compalaint_update($id, $data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('complaint', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On Complaint id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function delete($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('complaint');
        $message   = DELETE_RECORD_CONSTANT . " On Complaint id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getComplaintType()
    {
        $this->db->select('*');
        $this->db->from('complaint_type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getComplaintSource()
    {
        $this->db->select('*');
        $this->db->from('source');
        $query = $this->db->get();
        return $query->result_array();
    }

}
