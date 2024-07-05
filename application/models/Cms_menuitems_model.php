<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cms_menuitems_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select()->from('front_cms_menu_items');
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

    public function getBySlug($slug = null)
    {
        $this->db->select()->from('front_cms_menu_items');
        if ($slug != null) {
            $this->db->where('slug', $slug);
        }
        $query  = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function removeBySlug($slug)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('slug', $slug);
        $this->db->delete('front_cms_menu_items');
        $message   = DELETE_RECORD_CONSTANT . " On menu id " . $slug;
        $action    = "Delete";
        $record_id = $slug;
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

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('front_cms_menu_items');
        $message   = DELETE_RECORD_CONSTANT . " On menu id " . $id;
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
            $this->db->update('front_cms_menu_items', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Menu id " . $data['id'];
            $action    = "Update";
            $record_id = $insert_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('front_cms_menu_items', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Menu id " . $insert_id;
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

    public function getMenus($menu_id, $parent = 0, $spacing = '', $user_tree_array = '')
    {
        $parent_menu = array();
        $sub_menu    = array();
        $this->db->select('front_cms_menu_items.*,front_cms_pages.slug as `page_slug`,front_cms_pages.url as `page_url`,front_cms_pages.is_homepage')->from('front_cms_menu_items');
        $this->db->join('front_cms_pages', 'front_cms_pages.id = front_cms_menu_items.page_id', 'left');
        $this->db->where('menu_id', $menu_id);
        $this->db->order_by('parent_id ASC, weight ASC');
        $query  = $this->db->get();
        $result = $query->result();

        foreach ($result as $r_key => $obj) {
            if(isset($obj->ext_url_link)){ 
                if (substr($obj->ext_url_link, -16) == "online_admission") {
                    $obj->page_slug = substr($obj->ext_url_link, -16);
                }
                if (substr($obj->ext_url_link, -10) == "examresult") {
                    $obj->page_slug = substr($obj->ext_url_link, -10);
                }
                if(substr($obj->ext_url_link, -13)== "online_course"){
                    $obj->page_slug=substr($obj->ext_url_link, -13);
                }
                if(substr($obj->ext_url_link, -8)== "cbseexam"){
                    $obj->page_slug=substr($obj->ext_url_link, -8);
                }
            }

            if ($obj->parent_id == 0) {
                $parent_menu[$obj->id]['id']           = $obj->id;
                $parent_menu[$obj->id]['parent']       = $obj->parent_id;
                $parent_menu[$obj->id]['page_id']      = $obj->page_id;
                $parent_menu[$obj->id]['ext_url']      = $obj->ext_url;
                $parent_menu[$obj->id]['ext_url_link'] = $obj->ext_url_link;
                $parent_menu[$obj->id]['open_new_tab'] = $obj->open_new_tab;
                $parent_menu[$obj->id]['publish']      = $obj->publish;
                $parent_menu[$obj->id]['label']        = $obj->menu;
                $parent_menu[$obj->id]['link']         = $obj->slug;
                $parent_menu[$obj->id]['page_slug']    = $obj->page_slug;
                $parent_menu[$obj->id]['page_url']     = $obj->page_url;
                $parent_menu[$obj->id]['is_homepage']  = $obj->is_homepage;
            } else {
                $sub_menu[$obj->id]['id']           = $obj->id;
                $sub_menu[$obj->id]['parent']       = $obj->parent_id;
                $sub_menu[$obj->id]['page_id']      = $obj->page_id;
                $sub_menu[$obj->id]['ext_url']      = $obj->ext_url;
                $sub_menu[$obj->id]['ext_url_link'] = $obj->ext_url_link;
                $sub_menu[$obj->id]['open_new_tab'] = $obj->open_new_tab;
                $sub_menu[$obj->id]['publish']      = $obj->publish;
                $sub_menu[$obj->id]['label']        = $obj->menu;
                $sub_menu[$obj->id]['link']         = $obj->slug;
                $sub_menu[$obj->id]['page_slug']    = $obj->page_slug;
                $sub_menu[$obj->id]['page_url']     = $obj->page_url;
                $sub_menu[$obj->id]['is_homepage']  = $obj->is_homepage;
            }
        }

        $result = $this->dyn_menu($parent_menu, $sub_menu, 'menu', 'nav', 'subnav');
        return $result;
    }

    public function dyn_menu($parent_array, $sub_array, $qs_val = 'menu', $main_id = 'nav', $sub_id = 'subnav', $extra_style = 'foldout')
    {
        $array = array();
        foreach ($parent_array as $pkey => $pval) {

            $array[$pval['id']] = array(
                'id'           => $pval['id'],
                'slug'         => $pval['link'],
                'menu'         => $pval['label'],
                'menu'         => $pval['label'],
                'page_id'      => $pval['page_id'],
                'is_homepage'  => $pval['is_homepage'],
                'ext_url'      => $pval['ext_url'],
                'ext_url_link' => $pval['ext_url_link'],
                'open_new_tab' => $pval['open_new_tab'],
                'publish'      => $pval['publish'],
                'page_slug'    => $pval['page_slug'],
                'page_url'     => $pval['page_url'],
                'submenus'     => array(),
            );

            foreach ($sub_array as $sval) {

                if ($pkey == $sval['parent']) {
                    $array[$pval['id']]['submenus'][] = array(
                        'id'           => $sval['id'],
                        'slug'         => $sval['link'],
                        'menu'         => $sval['label'],
                        'page_id'      => $sval['page_id'],
                        'is_homepage'  => $sval['is_homepage'],
                        'ext_url'      => $sval['ext_url'],
                        'ext_url_link' => $sval['ext_url_link'],
                        'open_new_tab' => $sval['open_new_tab'],
                        'publish'      => $sval['publish'],
                        'page_slug'    => $sval['page_slug'],
                        'page_url'     => $sval['page_url'],
                    );
                }
            }
        }
        return $array;
    }

}
