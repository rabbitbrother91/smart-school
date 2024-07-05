<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sidemenu extends Admin_Controller
{

    public $custom_fields_list = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sidebarmenu_model');

    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/sidebar_menu');
        $data = array();
        $this->load->view('layout/header');
        $this->load->view('admin/sidemenu/index', $data);
        $this->load->view('layout/footer');
    }

    public function add_menu()
    {
        if (!$this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('menu', $this->lang->line('menu'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('lang_key', $this->lang->line('lang_key'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('activate_menu', 'Active Menu Array key', 'required|trim|xss_clean');
        $this->form_validation->set_rules('icon', 'Icon', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'menu'          => form_error('menu'),
                'lang_key'      => form_error('lang_key'),
                'activate_menu' => form_error('activate_menu'),
                'icon'          => form_error('icon'),
            );
            $array = array('status' => 0, 'error' => $msg);
            echo json_encode($array);
        } else {
            $sidebar      = 0;
            $sidebar_view = $this->input->post('sidebar_view');
            if (isset($sidebar_view)) {
                $sidebar = 1;
            }

            $menu_id = $this->input->post('menu_id');
            if ($menu_id == "" || $menu_id == 0) {
                $menu_id = 0;
            }

            $insert_array = array(
                'id'                 => $menu_id,
                'lang_key'           => $this->input->post('lang_key'),
                'menu'               => $this->input->post('menu'),
                'icon'               => $this->input->post('icon'),
                'activate_menu'      => $this->input->post('activate_menu'),
                'access_permissions' => $this->input->post('access_permissions'),
                'sidebar_display'    => $sidebar,
                'level'              => 0,
            );

            $resultlist = $this->sidebarmenu_model->add($insert_array);
            $array      = array('status' => '1', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function add_sub_menu()
    {
        if (!$this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('menu', $this->lang->line('menu'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('url', $this->lang->line('url'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('activate_controller', 'controller', 'required|trim|xss_clean');
        $this->form_validation->set_rules('activate_methods', 'methods', 'required|trim|xss_clean');
        $this->form_validation->set_rules('lang_key', 'Language Key', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'menu'                => form_error('menu'),
                'lang_key'            => form_error('lang_key'),
                'activate_controller' => form_error('activate_controller'),
                'activate_methods'    => form_error('activate_methods'),
                'url'                 => form_error('url'),
            );
            $array = array('status' => 0, 'error' => $msg);
            echo json_encode($array);
        } else {
            $sidebar = 0;

            $submenu_id = $this->input->post('submenu_id');
            if ($submenu_id == "" || $submenu_id == 0) {
                $submenu_id = 0;
            }
            $sidebar_view = $this->input->post('sidebar_view');
            if (isset($sidebar_view)) {
                $sidebar = 1;
            }
            $insert_array = array(

                'id'                  => $submenu_id,
                'sidebar_menu_id'     => $this->input->post('menu_id'),
                'url'                 => $this->input->post('url'),
                'lang_key'            => $this->input->post('lang_key'),
                'menu'                => $this->input->post('menu'),
                'activate_controller' => $this->input->post('activate_controller'),
                'activate_methods'    => $this->input->post('activate_methods'),
                'access_permissions'  => $this->input->post('access_permissions'),
                'addon_permission'    => $this->input->post('addon_permission'),
                'level'               => 1,
            );
            $resultlist = $this->sidebarmenu_model->addSubMenu($insert_array);
            $array      = array('status' => '1', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function ajax_list_menu()
    {
        $data                 = array();
        $data['menus']        = $this->sidebarmenu_model->getMenuwithSubmenus(0);
        $data['active_menus'] = $this->sidebarmenu_model->getMenuwithSubmenus(1);
        $list  = $this->load->view('admin/sidemenu/_ajax_list_menu', $data, true);
        $array = array('status' => '1', 'error' => '', 'page' => $list);
        echo json_encode($array);
    }

    public function getmenu()
    {
        $data    = array();
        $menu_id = $this->input->post('menu_id');
        $menu    = $this->sidebarmenu_model->get($menu_id);
        $array   = array('status' => '1', 'error' => '', 'menu' => $menu);
        echo json_encode($array);
    }

    public function getsubmenu()
    {
        $data       = array();
        $submenu_id = $this->input->post('submenu_id');
        $sub_menu   = $this->sidebarmenu_model->getSubmenuById($submenu_id);
        $array      = array('status' => '1', 'error' => '', 'sub_menu' => $sub_menu);
        echo json_encode($array);
    }

    public function menu_updateorder()
    {
        if (!$this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {
            access_denied();
        }
        
        $items = $this->input->post('items');

        if (!empty($items)) {
            $updateorder        = array();
            $i                  = 1;
            $id_not_to_be_reset = array();
            foreach ($items as $item_key => $item_value) {
                $updateorder[]        = array('id' => $item_value, 'level' => $i, 'sidebar_display' => 1);
                $id_not_to_be_reset[] = $item_value;
                $i++;
            }

            $this->sidebarmenu_model->update_menu_order($updateorder, $id_not_to_be_reset);
        } else {
            $this->sidebarmenu_model->update_menu_order(array(), array(0));
        }

        $array = array('status' => '1', 'msg' => $this->lang->line('update_message'));
        echo json_encode($array);
    }

    public function submenu_updateorder()
    {
        if (!$this->rbac->hasPrivilege('sidebar_menu', 'can_view')) {
            access_denied();
        }
        $belong_to = $this->input->post('belong_to');
        $items     = $this->input->post('items');

        if (!empty($items)) {
            $updateorder = array();
            $i           = 1;
            foreach ($items as $item_key => $item_value) {
                $updateorder[] = array('id' => $item_value, 'level' => $i);
                $i++;
            }

            $this->sidebarmenu_model->update_submenu_order($updateorder);
        }

        $array = array('status' => '1', 'msg' => $this->lang->line('update_message'));
        echo json_encode($array);
    }
}
