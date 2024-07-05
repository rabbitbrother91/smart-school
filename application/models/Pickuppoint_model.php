<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pickuppoint_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select()->from('pickup_point');
        if ($id != null) {
            $this->db->where('pickup_point.id', $id);
        } else {
            $this->db->order_by('pickup_point.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('transport_route_id', $id);
        $this->db->delete('route_pickup_point');
        $message   = DELETE_RECORD_CONSTANT . " On transport route id " . $id;
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

    public function remove_point($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('pickup_point');
        $message   = DELETE_RECORD_CONSTANT . " On transport route id " . $id;
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

    public function remove_pickupfromroute($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('route_pickup_point');
        $message   = DELETE_RECORD_CONSTANT . " On transport route id " . $id;
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

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('route_pickup_point', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  transport route id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $insert_id = $data['id'];
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
            $this->db->insert('route_pickup_point', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On transport route id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function listpickup_point()
    {
        $this->datatables->select('*')->from('pickup_point')->searchable('name,latitude,longitude,id')
            ->orderable('name,latitude,longitude,id');
        return $this->datatables->generate('json');
    }

    public function dropdownpickup_point()
    {
        return $this->db->select('*')->from('pickup_point')->get()->result_array();
    }

    public function getpickup_pointbyid($id)
    {
        $this->db->select('*')->from('route_pickup_point')->where('route_pickup_point.id', $id);
        $getpickup_pointbyid = $this->db->get();
        return $getpickup_pointbyid->row_array();
    }

    public function route_pickup_point()
    {
        $this->db->select('route_pickup_point.transport_route_id,pickup_point.name as pickup_point,transport_route.route_title')->from('route_pickup_point')->join('transport_route', 'route_pickup_point.transport_route_id=transport_route.id')->join('pickup_point', 'pickup_point.id=route_pickup_point.pickup_point_id')->group_by('route_pickup_point.transport_route_id');
        $route_pickup_point = $this->db->get();

        $result = $route_pickup_point->result_array();
        foreach ($result as $key => $value) {
            $result[$key]['point_list'] = $this->getPickupPointByRouteID($value['transport_route_id']);
        }

        return $result;
    }

    public function get_routelist()
    {
        $route_list = $this->db->select('transport_route.id as routes_id,transport_route.route_title')->from('transport_route')->get()->result_array();

        return $route_list;
    } 

    public function getPickupPointByRouteID($id)
    {
        $this->db->select('route_pickup_point.*,pickup_point.name as pickup_point,transport_route.route_title')->from('route_pickup_point')->join('transport_route', 'route_pickup_point.transport_route_id=transport_route.id')->join('pickup_point', 'pickup_point.id=route_pickup_point.pickup_point_id')->where('route_pickup_point.transport_route_id', $id)->order_by('order_number', 'asc');
        $route_pickup_point = $this->db->get();

        return $route_pickup_point->result_array();
    }

    public function add_pickup_point($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id']) && !empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('pickup_point', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  transport route id " . $data['id'];
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
            $this->db->insert('pickup_point', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On transport route id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
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
            return $insert_id;
        }
    }

    public function reorder($id)
    {
        $sn = 1;
        foreach ($id as $key => $value) {
            $order['id']           = $value;
            $order['order_number'] = $sn;
            $this->add($order);
            $sn++;
        }
        return $this->getpickup_pointbyid($id['0']);

    } 
 
    public function getPickupPointsByvehrouteId($vehroute_id)
    {
        $sql   = "SELECT vehicle_routes.*,transport_route.route_title,transport_route.id as `transport_route_id`,route_pickup_point.id as `route_pickup_point_id`,route_pickup_point.fees,route_pickup_point.destination_distance,pickup_point.name FROM `vehicle_routes` INNER JOIN transport_route on transport_route.id=vehicle_routes.route_id INNER JOIN route_pickup_point on route_pickup_point.transport_route_id=transport_route.id  INNER JOIN pickup_point on pickup_point.id= route_pickup_point.pickup_point_id WHERE vehicle_routes.id=" . $this->db->escape($vehroute_id) . " ORDER by route_pickup_point.order_number asc";
        $query = $this->db->query($sql);

        return $query->result();
    }

    public function reorder_pickup_point($route_id){
        $sql= "select route_pickup_point.*,pickup_point.name as pickup_point_name from route_pickup_point join pickup_point on pickup_point.id=route_pickup_point.pickup_point_id where transport_route_id=" . $this->db->escape($route_id) ." ORDER by route_pickup_point.order_number asc";
        $query = $this->db->query($sql);
        return $query->result();
    }

}
