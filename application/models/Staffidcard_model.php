<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Staffidcard_model extends MY_model {

    public function staffidcardlist() {
        $this->db->select('*');
        $this->db->from('staff_id_card');
        $query = $this->db->get();
        return $query->result();
    }

     public function addstaffidcard($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_id_card', $data);
            $message      = UPDATE_RECORD_CONSTANT." On  id card id ".$data['id'];
            $action       = "Update";
            $record_id    = $data['id'];
            $this->log($message, $record_id, $action);
            //======================Code End==============================
            $this->db->trans_complete(); # Completing transaction
            /*Optional*/
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;
            }
        } else {
            $this->db->insert('staff_id_card', $data);
            $insert_id = $this->db->insert_id();
            $message      = INSERT_RECORD_CONSTANT." On id card id ".$insert_id;
            $action       = "Insert";
            $record_id    = $insert_id;
            $this->log($message, $record_id, $action);
            //======================Code End==============================
            $this->db->trans_complete(); # Completing transaction
            /*Optional*/
            if ($this->db->trans_status() === false) {
                # Something went wrong.
                $this->db->trans_rollback();
                return false;

            }
            return $insert_id;
        }
    }
    public function idcardbyid($id) {
        $this->db->select('*');
        $this->db->from('staff_id_card');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get($id) {
        $this->db->select('*');
        $this->db->from('staff_id_card');
        $this->db->where('status = 1');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($id) {
		$this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('staff_id_card');
		$message      = DELETE_RECORD_CONSTANT." On id card id ".$id;
        $action       = "Delete";
        $record_id    = $id;
        $this->log($message, $record_id, $action);
		//======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /*Optional*/
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        }
    }
}

?>