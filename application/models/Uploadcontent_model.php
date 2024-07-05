<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Uploadcontent_model extends MY_Model
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
        $this->db->select()->from('upload_contents');
        if ($id != null) {
            $this->db->where('id', $id);
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

    public function total_record($staff_id)
    {

        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole    = json_decode($getStaffRole);

        $this->db->select("count(upload_contents.id) as number,sum(upload_contents.file_size) as `file_size`");

        if ($staffrole->id != "7") {
            $this->db->where('upload_by', $staff_id);
        }

        $query = $this->db->get('upload_contents');

        return $query->row();

    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function getByIdArray($array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->select('upload_contents.*')->from('upload_contents');
        $this->db->where_in('id', $array);
        $query = $this->db->get();
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $query->result();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove_array($array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where_in('id', $array);
        $this->db->delete('upload_contents');

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

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('upload_contents');
        $message   = DELETE_RECORD_CONSTANT . " On upload_contents id " . $id;
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
            return true;
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
            $this->db->update('upload_contents', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  upload_contents id " . $data['id'];
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
            $this->db->insert('upload_contents', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  upload_contents id " . $id;
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
            return $id;
        }
    }
     
    public function getlimitwithsearch($staff_id, $limit = null, $start = null, $where_condition = array())
    {

        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole    = json_decode($getStaffRole);
        $query        = $this->db->select('upload_contents.*,staff.name as `staff_name`,staff.surname as `surname`,staff.employee_id as `employee_id`,content_types.name as `content_type`');
        if (!empty($where_condition)) {
            $query->group_start(); // Open bracket
            $query->like('img_name', $where_condition['search']);
            $query->or_like('vid_title', $where_condition['search']);
            $query->or_like('real_name', $where_condition['search']);
            $query->or_like('content_types.name', $where_condition['search']);
            $query->or_like('staff.name', $where_condition['search']);
            $query->or_like('staff.surname', $where_condition['search']);
            $query->or_like('staff.employee_id', $where_condition['search']);
            $query->group_end(); // Close bracket
        }
        $query->join('staff', 'staff.id=upload_contents.upload_by');
        $query->join('content_types', 'content_types.id=upload_contents.content_type_id');
        $query->from('upload_contents');

        if ($staffrole->id != "7") {
            $query->where('upload_by', $staff_id);
        }

        $num_rows = $query->count_all_results('', false);

        if (!is_null($limit) && !is_null($start)) {
            $query->limit($limit, $start);
        }

        $query->order_by("id", "desc");
        $query = $query->get();
        return ['count' => $num_rows, 'total_rows' => $query->result()];

    }

    public function countwithsearch($where_condition = array())
    {
        if (!empty($where_condition)) {
            $this->db->like('img_name', $where_condition['search']);
        }
        $this->db->from('upload_contents');
        return $num_rows = $this->db->count_all_results();
    }

}
