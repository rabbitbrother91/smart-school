<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Video_tutorial_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {       
        $this->db->select('video_tutorial.*,class_sections.class_id,class_sections.section_id,classes.class,sections.section')
            ->join('video_tutorial_class_sections', 'video_tutorial_class_sections.video_tutorial_id=video_tutorial.id')
            ->join('class_sections', 'class_sections.id=video_tutorial_class_sections.class_section_id')
            ->join('classes', 'classes.id=class_sections.class_id')
            ->join('sections', 'sections.id=class_sections.section_id')
            ->from('video_tutorial');
            
        if ($id != null) {
            $this->db->where('video_tutorial.id', $id);
        } else {
            $this->db->order_by('video_tutorial.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }

    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("video_tutorial_id", $id);
        $this->db->delete('video_tutorial_class_sections');
        $this->db->where('id', $id);
        $this->db->delete('video_tutorial');
        $message   = DELETE_RECORD_CONSTANT . " On video tutorial id " . $id;
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
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('video_tutorial', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  video tutorial id " . $data['id'];
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
            $this->db->insert('video_tutorial', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On video tutorial id " . $insert_id;
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

            }
            return $insert_id;
        }
    }

    public function count_all($keyword = null, $class_id = null, $class_section_id = null)
    {
        $this->db->join('video_tutorial_class_sections', 'video_tutorial_class_sections.video_tutorial_id=video_tutorial.id');
        $this->db->join('class_sections', 'class_sections.id=video_tutorial_class_sections.class_section_id');
        $this->db->like('video_tutorial.title', $keyword);
        $this->db->like('video_tutorial_class_sections.class_section_id', $class_section_id);
        $this->db->like('class_sections.class_id', $class_id);
        $this->db->group_by('video_tutorial_class_sections.video_tutorial_id');
        $query = $this->db->get("video_tutorial");
        return $query->num_rows();
    }

    public function fetch_details($limit, $start, $keyword = null, $class_id = null, $class_section_id = null)
    {
        $userdata        = $this->customlib->getUserData();
        $staff_id        = $userdata['id'];
        
        if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes") && (empty($class_id))) {
            $class_section_array = $this->customlib->get_myClassSection();
        }
        
        $output = '';
        $this->db->select("video_tutorial.*,class_sections.class_id,class_sections.section_id,classes.class,sections.section, staff.name as staff_name, staff.surname as staff_surname, staff.employee_id as staff_employee_id");
        $this->db->join('staff', 'staff.id=video_tutorial.created_by', 'left');
        $this->db->join('video_tutorial_class_sections', 'video_tutorial_class_sections.video_tutorial_id=video_tutorial.id');
        $this->db->join('class_sections', 'class_sections.id=video_tutorial_class_sections.class_section_id');
        $this->db->join('classes', 'classes.id=class_sections.class_id');
        $this->db->join('sections', 'sections.id=class_sections.section_id');
        
        if(!empty($class_id)){
            if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
                if (!empty($class_id)) {
                    $this->db->where_in("class_sections.class_id", $class_id);
                    
                    $sections = $this->teacher_model->get_teacherrestricted_modeallsections($staff_id);
                    foreach ($sections as $key => $value) {
                        $sections_id[] = $value['section_id'];
                    }
                    $this->db->where_in("class_sections.section_id", $sections_id);
                    
                } else {
                    $this->db->where_in("class_sections.class_id", $class_id);
                }
            } 
        }else{
        
            if (!empty($class_section_array)) {
                $this->datatables->group_start();
                foreach ($class_section_array as $class_sectionkey => $class_sectionvalue) {
                    foreach ($class_sectionvalue as $class_sectionvaluekey => $class_sectionvaluevalue) {
                        $this->datatables->or_group_start();
                        $this->datatables->where('class_sections.class_id', $class_sectionkey);
                        $this->datatables->where('class_sections.section_id', $class_sectionvaluevalue);
                        $this->datatables->group_end();    
                    }
                }
                $this->datatables->group_end();
            }
        }
        
        $this->db->like('video_tutorial.title', $keyword);
        
        if ($class_section_id != null) {
            $this->db->like('video_tutorial_class_sections.class_section_id', $class_section_id);
        }
        if ($class_id != null) {
            $this->db->like('class_sections.class_id', $class_id);
        }
        $this->db->from("video_tutorial");
        $this->db->order_by("id", "DESC");
        $this->db->group_by('video_tutorial_class_sections.video_tutorial_id');
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }

    public function getvideotutorial($limit, $start, $class_id, $section_id)
    {
        $this->db->select('video_tutorial.*,class_sections.class_id,class_sections.section_id,classes.class,sections.section, staff.name as staff_name, staff.surname as staff_surname, staff.employee_id as staff_employee_id,staff_roles.role_id')
            ->join('staff', 'staff.id=video_tutorial.created_by', 'left')
            ->join('staff_roles', 'staff.id=staff_roles.staff_id')
            ->join('video_tutorial_class_sections', 'video_tutorial_class_sections.video_tutorial_id=video_tutorial.id')
            ->join('class_sections', 'class_sections.id=video_tutorial_class_sections.class_section_id')
            ->join('classes', 'classes.id=class_sections.class_id')
            ->join('sections', 'sections.id=class_sections.section_id')
            ->from('video_tutorial');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        if ($limit != '' && $start != '') {
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('video_tutorial.id', 'DESC');
        $this->db->group_by('video_tutorial_class_sections.video_tutorial_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function addsections($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('video_tutorial_class_sections', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On Video Tutorial class sections id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('video_tutorial_class_sections', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On Video Tutorial class sections id " . $id;
            $action    = "Insert";
            $record_id = $id;
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
            return $id;
        }
    }

    public function selectedsection($video_tutorial_id)
    {
        $this->db->select('video_tutorial_class_sections.class_section_id,sections.section')->from('video_tutorial_class_sections');
        $this->db->join('class_sections', 'class_sections.id = video_tutorial_class_sections.class_section_id');
        $this->db->join('sections', 'sections.id = class_sections.section_id', 'left');
        $this->db->where('video_tutorial_class_sections.video_tutorial_id', $video_tutorial_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getclassid($video_tutorial_id)
    {
        $this->db->select('class_sections.class_id')->from('video_tutorial_class_sections');
        $this->db->join('class_sections', 'class_sections.id = video_tutorial_class_sections.class_section_id');
        $this->db->where('video_tutorial_class_sections.video_tutorial_id', $video_tutorial_id);
        $this->db->group_by('class_sections.class_id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function delete($id, $class_section_id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where("video_tutorial_id", $id);
        $this->db->where("class_section_id", $class_section_id);
        $this->db->delete('video_tutorial_class_sections');
        $message   = DELETE_RECORD_CONSTANT . " On video tutorial class sections id " . $id;
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
            
        }
    }

}
