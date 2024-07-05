<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cms_program_model extends MY_Model
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
        $this->db->select()->from('front_cms_programs');
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

    public function getByCategory($category = null, $params = array(),$is_front=0)
    {
     if($category == "events" && $is_front){
         $this->db->where('event_start <=',date('Y-m-d'));
         $this->db->where(' event_end >=',date('Y-m-d'));

     }
        $this->db->select('*');
        $this->db->from('front_cms_programs');
        $this->db->order_by('created_at', 'desc');
        $this->db->where('type', $category);
        if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit'], $params['start']);
        } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
            $this->db->limit($params['limit']);
        }

        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    public function updateFeaturedImage($id, $record_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $data = array(
            'featured_img' => 'yes',
        );
        $this->db->where('id', $record_id);
        $this->db->update('front_cms_program_photos', $data);
        $data = array(
            'featured_img' => 'no',
        );
        $this->db->where('id !=', $record_id);
        $this->db->where('program_id =', $id);
        $this->db->update('front_cms_program_photos', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On  update Featured Image id " . $record_id;
        $action    = "Update";
        $record_id = $record_id;
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

    public function getBySlug($slug = null)
    {
        $this->db->select()->from('front_cms_programs');
        if ($slug != null) {
            $this->db->where('slug', $slug);
        }
        $query  = $this->db->get();
        $result = $query->row_array();
        if ($query->num_rows()) {
            $result['page_contents'] = $this->front_cms_program_photos($query->row()->id);
        }
        return $result;
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function front_cms_program_photos($program_id)
    {
        $this->db->select('front_cms_media_gallery.*');
        $this->db->from('front_cms_program_photos');
        $this->db->join('front_cms_media_gallery', 'front_cms_program_photos.media_gallery_id = front_cms_media_gallery.id');
        $this->db->where('front_cms_program_photos.program_id', $program_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function remove($slug)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('slug', $slug);
        $this->db->delete('front_cms_programs');
        $message   = DELETE_RECORD_CONSTANT . " On event id " . $slug;
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
            $this->db->update('front_cms_programs', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  event id " . $data['id'];
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
            $this->db->insert('front_cms_programs', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On event id " . $insert_id;
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

    public function inst_batch($data, $contents)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $this->db->insert('front_cms_programs', $data);
        $insert_id = $this->db->insert_id();
        if (isset($contents) && !empty($contents)) {
            $total_rec = count($contents);
            for ($i = 0; $i < $total_rec; $i++) {
                $contents[$i]['program_id'] = $insert_id;
            }
            $this->db->insert_batch('front_cms_program_photos', $contents);
        }
        $this->db->trans_complete(); # Completing transaction

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function update_batch($data, $contents, $remove_content)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        $this->db->where('id', $data['id']);
        $this->db->update('front_cms_programs', $data);
        if (!empty($remove_content)) {
            $this->db->where('program_id', $data['id']);
            $this->db->where_in('media_gallery_id', $remove_content);
            $this->db->delete('front_cms_program_photos');
        }
        if (isset($contents) && !empty($contents)) {
            $total_rec = count($contents);
            for ($i = 0; $i < $total_rec; $i++) {
                $contents[$i]['program_id'] = $data['id'];
            }
            $this->db->insert_batch('front_cms_program_photos', $contents);
        }

        $this->db->trans_complete(); # Completing transaction
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function addImage($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('front_cms_program_photos', $data);
        $insert_id = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On cms program photos id " . $insert_id;
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

    public function removeImage($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('front_cms_program_photos');
        $message   = DELETE_RECORD_CONSTANT . " On event id " . $id;
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

    public function removeBySlug($slug, $type)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('slug', $slug);
        $this->db->where('type', $type);
        $this->db->delete('front_cms_programs');
        $message   = DELETE_RECORD_CONSTANT . " On event title " . $slug;
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

    public function banner($banner_content, $data)
    {
        $this->db->trans_begin();
        //===============
        $banner_content_record = $this->getByCategory($banner_content);
        if ($banner_content_record) {
            $data['program_id'] = $banner_content_record[0]['id'];
            $this->db->insert('front_cms_program_photos', $data);
        } else {
            $insert_program     = array('type' => $banner_content, 'title' => 'Banner Images');
            $insert_program_id  = $this->add($insert_program);
            $data['program_id'] = $insert_program_id;
            $this->db->insert('front_cms_program_photos', $data);
        }

        //=======================

        $this->db->trans_complete(); # Completing transaction
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function bannerDelete($banner_content, $media_gallery_id)
    {
        $this->db->trans_begin();
        //===============
        $banner_content_record = $this->getByCategory($banner_content);
        if ($banner_content_record) {
            $data = array('program_id' => $banner_content_record[0]['id'], 'media_gallery_id' => $media_gallery_id);
            $this->db->where($data);
            $this->db->delete('front_cms_program_photos');
            $message   = DELETE_RECORD_CONSTANT . " On banner delete id " . $banner_content_record[0]['id'];
            $action    = "Delete";
            $record_id = $banner_content_record[0]['id'];
            $this->log($message, $record_id, $action);
        } else {

        }

        //=======================

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
