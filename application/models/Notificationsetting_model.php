<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Notificationsetting_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        $this->db->select()->from('notification_setting');
        if ($id != null) {
            $this->db->where('notification_setting.id', $id);
        } else {
            $this->db->order_by('notification_setting.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function add($data) {
        $this->db->select()->from('notification_setting');
        $this->db->where('notification_setting.type', $data['type']);
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            $result = $q->row();

            $this->db->where('id', $result->id);
            $this->db->update('notification_setting', $data);
        } else {
            $this->db->insert('notification_setting', $data);
            return $this->db->insert_id();
        }
    }

    public function update($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $data['id']);
        $this->db->update('notification_setting', $data);
        $message = UPDATE_RECORD_CONSTANT . " On notification setting id " . $data['id'];
        $action = "Update";
        $record_id = $data['id'];
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function updatebatch($update_array) {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($update_array) && !empty($update_array)) {

            $this->db->update_batch('notification_setting', $update_array, 'id');
        }
        foreach ($update_array as $ua) {
            $message = UPDATE_RECORD_CONSTANT . " On notification setting id " . $ua['id'];
            $action = "Update";
            $record_id = $ua['id'];
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}
