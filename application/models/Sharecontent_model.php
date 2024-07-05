<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sharecontent_model extends MY_Model
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
        $this->db->select()->from('share_contents');
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
    
    public function checkvalid($upload_content_id, $share_content_id)
    {
        $this->db->select('share_upload_contents.*,upload_contents.thumb_path,upload_contents.dir_path,upload_contents.img_name,upload_contents.thumb_name,upload_contents.file_type,upload_contents.vid_url,upload_contents.vid_title,share_date,valid_upto')->from('share_upload_contents');
        $this->db->join('upload_contents', 'upload_contents.id = share_upload_contents.upload_content_id');
        $this->db->join('share_contents', 'share_contents.id = share_upload_contents.share_content_id');
        $this->db->where('share_upload_contents.upload_content_id', $upload_content_id);
        $this->db->where('share_upload_contents.share_content_id', $share_content_id);
        $query = $this->db->get();
        if ($query->num_rows() >= 0) {
            return $query->row();
        }
        return false;
    }

    public function getShareContentWithDocuments($id = null)
    {
        $result = array();
        $this->db->select('share_contents.*, staff.name,staff.surname,staff.employee_id,staff_roles.role_id')->from('share_contents');
        $this->db->join('staff', 'staff.id = share_contents.created_by');
        $this->db->join('staff_roles', 'staff.id = staff_roles.staff_id');
        
        if ($id != null) {
            $this->db->where('share_contents.id', $id);
        } else {
            $this->db->order_by('share_contents.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            $result = $query->row();
        } else {
            $result = $query->result();
        }
        if (!empty($result)) {
            if ($id != null) {

                $result->{"upload_contents"} = $this->getShareContentDocumentsByID($id);
            } else {

                foreach ($result as $result_key => $result_value) {
                    $result[$result_key]->{"upload_contents"} = $this->getShareContentDocumentsByID($result_value->id);
                }
            }

            return $result;
        }
        return false;
    }

    public function getShareContentDocumentsByID($share_content_id = null)
    {
        $result = array();
        $this->db->select('share_upload_contents.*,upload_contents.real_name,upload_contents.thumb_path,upload_contents.dir_path,upload_contents.img_name,upload_contents.thumb_name,upload_contents.file_type,upload_contents.mime_type,upload_contents.vid_url,upload_contents.vid_title')->from('share_upload_contents');
        $this->db->where('share_upload_contents.share_content_id', $share_content_id);
        $this->db->join('upload_contents', 'upload_contents.id = share_upload_contents.upload_content_id');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;

        }
        return false;
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
        $this->db->delete('share_contents');
        $message   = DELETE_RECORD_CONSTANT . " On share_contents id " . $id;
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
    public function add($data, $share_content_data, $selected_contents)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $this->db->insert('share_contents', $data);
        $share_content_id = $this->db->insert_id();

        if (!empty($share_content_data)) {
            foreach ($share_content_data as $share_key => $share_value) {
                $share_content_data[$share_key]['share_content_id'] = $share_content_id;
            }
            $this->db->insert_batch('share_content_for', $share_content_data);
        }

        if (!empty($selected_contents)) {
            foreach ($selected_contents as $content_key => $content_value) {
                $selected_contents[$content_key]['share_content_id'] = $share_content_id;
            }
            $this->db->insert_batch('share_upload_contents', $selected_contents);
        }

        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $share_content_id;
        }
    }

    public function getsharelist()
    {
        $this->datatables
            ->select('share_contents.*,staff.name,staff.surname,staff.employee_id')
            ->searchable('title,send_to,share_date,valid_upto,description,staff.name,staff.surname,share_contents.description')
            ->orderable('title,send_to,share_date,valid_upto,staff.name,description')
            ->join("staff", "share_contents.created_by = staff.id")
            ->sort('share_contents.id', 'desc')
            ->from('share_contents');
        return $this->datatables->generate('json');
    }

    public function getOtherStaffsharelist($role_id,$staff_id)
    {
        $sql="select * from (SELECT `share_contents`.*, `staff`.`name`, `staff`.`surname`,`staff`.`employee_id` FROM `share_contents` JOIN `staff` ON `share_contents`.`created_by` = `staff`.`id` WHERE share_contents.created_by=".$staff_id." UNION SELECT `share_contents`.*, `staff`.`name`, `staff`.`surname`,`staff`.`employee_id` FROM `share_content_for`  INNER join share_contents on share_contents.id = share_content_for.share_content_id JOIN `staff` ON `share_contents`.`created_by` = `staff`.`id` WHERE (group_id=".$role_id." or staff_id=".$staff_id. ") and (share_contents.valid_upto >= ".$this->db->escape(date('Y-m-d'))."  or share_contents.valid_upto IS NULL )) as a ";

        $this->datatables->query($sql)
        ->sort('a.id','desc')
        ->searchable('title,send_to,share_date,valid_upto,description,name,surname,description')
        ->orderable('title,send_to,share_date,valid_upto,name,description')
        ->query_where_enable(FALSE);       
        return $this->datatables->generate('json');  
    }
    
    public function getSharedUserBySharedID($share_content_id)
    {
        $sql= "SELECT share_content_for.*,classes.class,sections.section,students.firstname as student_first_name,students.lastname as student_last_name,students.middlename as `student_middle_name`, students.admission_no as `student_admission_on`,users.username,staff.name,roles.name as role_name,staff.name as staff_first_name,staff.surname as staff_surname ,staff_roles.id as staff_role_id ,staff_role_alias.name as staff_role_name, staff.employee_id as staff_employee_id,users.childs,parent_student.guardian_name  FROM `share_content_for` LEFT JOIN roles on roles.id= share_content_for.group_id LEFT join students on students.id =share_content_for.student_id LEFT join users on users.id =share_content_for.user_parent_id LEFT JOIN students as parent_student on parent_student.id = users.childs LEFT join staff on staff.id = share_content_for.staff_id LEFT JOIN staff_roles on staff_roles.staff_id =staff.id LEFT JOIN roles as `staff_role_alias` on staff_role_alias.id = staff_roles.role_id LEFT JOIN class_sections on class_sections.id =share_content_for.class_section_id LEFT JOIN classes on classes.id=class_sections.class_id LEFT JOIN sections on sections.id=class_sections.section_id WHERE share_content_id=".$share_content_id;
          $query = $this->db->query($sql);
          return $query->result();
    }

    public function getStudentsharelist($student_id,$class_id,$section_id)
    {
        $sql="SELECT `share_contents`.*, `staff`.`name`, `staff`.`surname`, `staff`.`employee_id`, staff_roles.role_id FROM `share_contents` JOIN `staff` ON `share_contents`.`created_by` = `staff`.`id` JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` WHERE share_contents.id in (SELECT share_content_id FROM `share_content_for` WHERE group_id ='student' or student_id='".$student_id."' or class_section_id=(SELECT class_sections.id from class_sections WHERE class_sections.class_id='".$class_id."' and class_sections.section_id='".$section_id."'))";      
        $this->datatables->query($sql)
        ->sort('share_contents.id', 'desc')
        ->searchable('title,send_to,share_date,valid_upto,description,staff.name,staff.surname')
        ->orderable('title,share_date,valid_upto,staff.name,staff.surname')            
        ->query_where_enable(TRUE);       
        return $this->datatables->generate('json');  
    }
    
    public function getParentsharelist($user_parent_id,$class_id,$section_id)
    {
        $sql="SELECT `share_contents`.*, `staff`.`name`, `staff`.`surname`, `staff`.`employee_id`, staff_roles.role_id FROM `share_contents` JOIN `staff` ON `share_contents`.`created_by` = `staff`.`id` JOIN `staff_roles` ON `staff_roles`.`staff_id` = `staff`.`id` WHERE share_contents.id in (SELECT share_content_id FROM `share_content_for` WHERE group_id ='parent' or user_parent_id='".$user_parent_id."' or class_section_id=(SELECT class_sections.id from class_sections WHERE class_sections.class_id='".$class_id."' and class_sections.section_id='".$section_id."')) ";      
        $this->datatables->query($sql)
       ->sort('share_contents.id', 'desc')
        ->searchable('title,send_to,share_date,valid_upto,description,staff.name,staff.surname')
        ->orderable('title,send_to,share_date,valid_upto,staff.name')
        ->query_where_enable(TRUE);       
        return $this->datatables->generate('json');   
    }

}