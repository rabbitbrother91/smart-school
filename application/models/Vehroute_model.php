<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vehroute_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select('vehicle_routes.*,route_pickup_point.fees,transport_route.id as transport_id,transport_route.route_title')->from('vehicle_routes');
        $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id');
        $this->db->join('route_pickup_point', 'route_pickup_point.transport_route_id = transport_route.id');

        if ($id != null) {
            $this->db->where('vehicle_routes.route_id', $id);
        } else {
            $this->db->order_by('vehicle_routes.id', 'DESC');
        }

        $query = $this->db->get();
        if ($id != null) {
            $vehicle_routes = $query->result_array();

            $array = array();
            if (!empty($vehicle_routes)) {
                foreach ($vehicle_routes as $vehicle_key => $vehicle_value) {
                    $vec_route              = new stdClass();
                    $vec_route->id          = $vehicle_value['id'];
                    $vec_route->route_title = $vehicle_value['route_title'];
                    $vec_route->route_id    = $vehicle_value['route_id'];
                    $vec_route->fees        = $vehicle_value['fees'];
                    $vec_route->vehicles    = $this->getVechileByRoute($vehicle_value['route_id']);
                    $array[]                = $vec_route;
                }
            }
            return $array;
        } else {
            $vehicle_routes = $query->result_array();
            
 
            $array = array();
            if (!empty($vehicle_routes)) {
                foreach ($vehicle_routes as $vehicle_key => $vehicle_value) {

                    $vec_route                         = new stdClass();
                    $vec_route->id                     = $vehicle_value['id'];
                    $vec_route->route_title            = $vehicle_value['route_title'];
                    $vec_route->route_id               = $vehicle_value['route_id'];
                    $vec_route->fees                   = $vehicle_value['fees'];
                    $vec_route->vehicles               = $this->getVechileByRoute($vehicle_value['route_id']);
                    
                    
                    $array[$vehicle_value['route_id']] = $vec_route;
                    
                    
                }
                 
            } 
            return $array;
        } 
    }

    public function get_pickup_pointbyvehrouteid($reh_route_id)
    {
        $this->db->select('pickup_point.name as pickup_point_name')->from('route_pickup_point');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->where('route_pickup_point.transport_route_id', $reh_route_id);
        $query                 = $this->db->get();
        return $vehicle_routes = $query->result_array();
    }

    public function getVechileByRoute($route_id)
    {
     
        $this->db->select('vehicle_routes.id as vec_route_id,vehicles.*')->from('vehicle_routes');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id');
        $this->db->where('vehicle_routes.route_id', $route_id);
        $this->db->order_by('vehicle_routes.id', 'DESC');
        $query                 = $this->db->get();
        return $vehicle_routes = $query->result();
    }

    public function getVechileDetailByVecRouteID($id)
    {
        $this->db->select('vehicle_routes.id as vec_route_id,vehicles.*,transport_route.route_title')->from('vehicle_routes');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id');
        $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id');
        $this->db->where('vehicle_routes.id', $id);
        $query                 = $this->db->get();
        return $vehicle_routes = $query->row();
    }

    public function getRouteVehiclesList($id = null)
    {

        $listroute = $this->route_model->listroute($id);

        if ($id != null) {

            $vehicles = $this->getVechileByRoute($listroute["id"]);
            if (!empty($vehicles)) {

                $listroute['vehicles'] = $vehicles;
            }

        } else {

            $new = array();
            if (!empty($listroute)) {

                foreach ($listroute as $route_key => $route_value) {

                    $vehicles = $this->getVechileByRoute($route_value["id"]);
                    if (!empty($vehicles)) {

                        $listroute[$route_key]['vehicles'] = $vehicles;
                    } else {
                        unset($listroute[$route_key]);
                    }
                }
            }
        }

        return $listroute;
    }

    public function listroute()
    {

        $listroute = $this->route_model->listroute();
        $new       = array();
        if (!empty($listroute)) {
            foreach ($listroute as $route_key => $route_value) {
                $vehicles                          = $this->getVechileByRoute($route_value['id']);
                $listroute[$route_key]['vehicles'] = $vehicles;
            }
        }

        return $listroute;
    }

    public function remove($route_id, $array)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('route_id', $route_id);
        $this->db->where_in('vehicle_id', $array);
        $this->db->delete('vehicle_routes');
        $message   = DELETE_RECORD_CONSTANT . " On vehicle routes id " . $route_id;
        $action    = "Delete";
        $record_id = $route_id;
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

    public function removeByroute($route_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('route_id', $route_id);
        $this->db->delete('vehicle_routes');
        $message   = DELETE_RECORD_CONSTANT . " On vehicle routes id " . $route_id;
        $action    = "Delete";
        $record_id = $route_id;
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
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('vehicle_routes', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  vehicle routes id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert_batch('vehicle_routes', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On vehicle routes id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }       
    }

    public function route_exists($str)
    {
        $route_id     = $this->security->xss_clean($str);
        $pre_route_id = $this->input->post('pre_route_id');
        if (isset($pre_route_id)) {
            if ($route_id == $pre_route_id) {
                return true;
            }
        }

        if ($this->check_data_exists($route_id)) {
            $this->form_validation->set_message('route_exists', 'Record already exists');
            return false;
        } else {
            return true;
        }
    }

    public function check_data_exists($route_id)
    {
        $this->db->where('route_id', $route_id);

        $query = $this->db->get('vehicle_routes');
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

}
