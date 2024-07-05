<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expensehead_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDatatableExpenseHead()
    {
        $sql = "SELECT * FROM `expense_head`  ";
        $this->datatables->query($sql)
            ->searchable('expense_head.exp_category')
            ->orderable('`expense_head`.`id`,`expense_head`.`exp_category`')
            ->sort('expense_head.id', 'asc');
        return $this->datatables->generate('json');
    }

    public function get($id = null)
    {
        $this->db->select()->from('expense_head');
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

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('expense_head');
        $message   = DELETE_RECORD_CONSTANT . " On  expense head id " . $id;
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

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('expense_head', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  expense head id " . $data['id'];
            $action    = "Update";
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
                //return $return_value;
            }
        } else {
            $this->db->insert('expense_head', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  expense head id " . $id;
            $action    = "Insert";
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
    }

    public function searchexpensegroup($start_date, $end_date, $head_id = null)
    {
        $this->datatables
            ->select('expenses.id,expenses.date,expenses.name,expenses.invoice_no,expenses.amount, expense_head.exp_category,expenses.exp_head_id,expenses.amount as total_amount')
            ->searchable('expense_head.exp_category,expenses.id,expenses.name,expenses.date,expenses.invoice_no,expenses.amount')
            ->orderable('expense_head.exp_category,expenses.id,expenses.name,expenses.date,expenses.invoice_no')
            ->join('expense_head', 'expenses.exp_head_id = expense_head.id')
            ->where('expenses.date >=', $start_date)
            ->where('expenses.date <=', $end_date)
            ->from('expenses');
        if ($head_id != null) {
            $this->datatables->where('expenses.exp_head_id', $head_id);
        }
        $this->datatables->sort('expenses.exp_head_id', 'desc');
        return $this->datatables->generate('json');
    }

}
