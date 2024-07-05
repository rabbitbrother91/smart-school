<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Teacher_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date    = $this->setting_model->getDateYmd();
    }

    public function get($id = null)
    {
        $this->db->select()->from('teachers');
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

    public function getTeacher($id = null)
    {
        $this->db->select('teachers.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`');
        $this->db->from('teachers');
        $this->db->join('users', 'users.user_id = teachers.id', 'left');
        $this->db->where('users.role', 'teacher');
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getTeacherByEmail($email = null)
    {
        $this->db->select('teachers.*,users.id as `user_tbl_id`,users.username,users.password as `user_tbl_password`,users.is_active as `user_tbl_active`');
        $this->db->from('teachers');
        $this->db->join('users', 'users.user_id = teachers.id', 'left');
        $this->db->where('users.role', 'teacher');
        $this->db->where('teachers.email', $email);
        $query = $this->db->get();
        if ($email != null) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function getLibraryTeacher()
    {
        if ($this->session->has_userdata('admin')) {
            $getStaffRole = $this->customlib->getStaffRole();
            $staffrole    = json_decode($getStaffRole);
            $superadmin_visible = $this->customlib->superadmin_visible();
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $this->db->where("roles.id !=", 7);
            }
        }

        $this->db->select('staff.*, IFNULL(libarary_members.id,0) as `libarary_member_id`, IFNULL(libarary_members.library_card_no,0) as `library_card_no`')->from('staff');
        $this->db->join('libarary_members', 'libarary_members.member_id = staff.id and libarary_members.member_type = "teacher"', 'left');
        $this->db->join("staff_roles", "staff_roles.staff_id = staff.id", "left");
        $this->db->join("roles", "staff_roles.role_id = roles.id", "left");
        $this->db->where('staff.is_active', 1);
        $this->db->order_by('staff.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('teachers');
    }

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('teachers', $data);
        } else {
            $this->db->insert('teachers', $data);
            return $this->db->insert_id();
        }
    }

    public function getTotalTeacher()
    {
        $sql   = "SELECT count(*) as `total_teacher` FROM `teachers`";
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function searchNameLike($searchterm)
    {
        $this->db->select('teachers.*')->from('teachers');
        $this->db->group_start();
        $this->db->like('teachers.name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('teachers.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function rating($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_rating', $data);
        } else {
            $this->db->insert('staff_rating', $data);
            return $this->db->insert_id();
        }
    }

    public function my_classes($staff_id)
    {
        $class_id = array();
        $query    = $this->db->query("select ct.class_id  from class_teacher ct  where  ct.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "' ");
        $data     = $query->result_array();
        foreach ($data as $key => $value) {
            $class_id[] = $value['class_id'];
        }
        return $class_id;
    }

    public function get_classbysubject_group_id($subject_group_id)
    {
        $query        = $this->db->query("select st.class_id from subject_timetable st where st.subject_group_id='" . $subject_group_id . "' and session_id='" . $this->current_session . "' group by st.class_id");
        return $query = $query->result_array();
    }

    public function get_subjectby_staffid($staff_id)
    {
        $query        = $this->db->query("select GROUP_CONCAT(st.subject_group_subject_id) as subject from subject_timetable st where st.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "' group by staff_id ");
        return $query = $query->row_array();
    }

    public function get_examsubjects($staff_id)
    {
	
        $subject_id = array();
	$class_teacher_sections      = $this->db->query("SELECT class_id, section_id FROM `class_teacher` where session_id='" . $this->current_session . "'");
        $class_teacher_subjectquerydata = $class_teacher_sections->result_array();
	
	 foreach ($class_teacher_subjectquerydata as $cskey => $csvalue) {
	$sclass_id=$csvalue['class_id'];
	$ssection_id=$csvalue['section_id'];
	$class_teacher_subject     = $this->db->query("select sgs.subject_id  from subject_timetable st inner join subject_group_subjects sgs on st.subject_group_subject_id=sgs.id where st.class_id='".$sclass_id."' and st.section_id='".$ssection_id."' and st.staff_id='" . $staff_id . "' and st.session_id='" . $this->current_session . "' ");
        $class_teacher_subjectquerydata = $class_teacher_subject->result_array();
         foreach ($class_teacher_subjectquerydata as $ctskey => $ctsvalue) {
            $subject_id[$ctsvalue['subject_id']] = $ctsvalue['subject_id'];
        }  

        }

	

        $query      = $this->db->query("select sgs.subject_id  from subject_timetable st inner join subject_group_subjects sgs on st.subject_group_subject_id=sgs.id where st.staff_id='" . $staff_id . "' and st.session_id='" . $this->current_session . "' ");
        $querydata = $query->result_array();
        foreach ($querydata as $key => $value) {
            $subject_id[$value['subject_id']] = $value['subject_id'];
        }

        return $subject_id;
    }

    public function get_subjectby_classid($class_id, $section_id, $staff_id)
    {
        $query        = $this->db->query("select GROUP_CONCAT(st.subject_group_subject_id) as subject from subject_timetable st where st.staff_id='" . $staff_id . "' and class_id='" . $class_id . "' and section_id='" . $section_id . "' and st.session_id='" . $this->current_session . "'  group by staff_id ");
        return $query = $query->row_array();
    }

    public function get_teacherrestricted_mode($staff_id)
    {
        $ides1      = "";
        $ides       = "";
        $class_ides = "";
        $ides11     = array();
        $query      = $this->db->query("select CONCAT_WS(',',GROUP_CONCAT(st.class_id)) as c from subject_timetable st where st.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "' group by st.staff_id");
        $query      = $query->result_array();

        $query1 = $this->db->query("select CONCAT_WS(',',GROUP_CONCAT(ct.class_id)) as c from class_teacher ct  where  ct.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "' group by ct.staff_id");
        $query1 = $query1->result_array();

        if (!empty($query1) && !empty($query)) {
            $class_ides = $query1[0]['c'] . "," . $query[0]['c'];
        } elseif (!empty($query)) {
            $class_ides = $query[0]['c'];
        } elseif (!empty($query1)) {
            $class_ides = $query1[0]['c'];
        }
        if (!empty($class_ides)) {
            $ides = explode(',', $class_ides);
            foreach ($ides as $key => $value) {
                if ($value != '') {
                    $ides11[] = $value;
                }
            }
            $ides1 = implode(',', $ides11);
        }

        if (!empty($ides1)) {
            $class_ides = $ides1;
            $classlist  = $this->db->query("select * from classes  where id in(" . $class_ides . ")");
            $data       = $classlist->result_array();
        } else {
            $data = array();
        }

        return $data;
    }

    public function get_daywiseattendanceclass($staff_id)
    {
        $query1 = $this->db->query("select CONCAT_WS(',',GROUP_CONCAT(ct.class_id)) as c from class_teacher ct  where  ct.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "' group by ct.staff_id");
        $query1 = $query1->result_array();

        $class_ides = '';
        if (!empty($query1)) {
            $class_ides = $query1[0]['c'];
        }

        $ides11 = array();
        $ides1  = '';
        if (!empty($class_ides)) {

            $ides = explode(',', $class_ides);
            foreach ($ides as $key => $value) {
                if ($value != '') {
                    $ides11[] = $value;
                }
            }

            $ides1 = implode(',', $ides11);
        }

        if (!empty($ides1)) {

            $class_ides = $ides1;
            $classlist  = $this->db->query("select * from classes  where id in(" . $class_ides . ")");
            $data       = $classlist->result_array();
        } else {

            $data = array();
        }

        return $data;
    }

    public function get_teacherrestricted_modesections($staff_id, $classid)
    {
        $ides1        = array();
        $ides         = array();
        $section_ides = '';
        $ides11       = array();
        $query1       = $this->db->query("select GROUP_CONCAT(st.section_id) as s from subject_timetable st where (st.staff_id='" . $staff_id . "' and st.class_id='" . $classid . "') and session_id='" . $this->current_session . "' ");
        $section_id1  = $query1->result_array();
        $query2       = $this->db->query("select GROUP_CONCAT(st.section_id) as s from class_teacher st where (st.staff_id='" . $staff_id . "' and st.class_id='" . $classid . "' and session_id='" . $this->current_session . "')");
        $section_id2  = $query2->result_array();
        if (!empty($section_id1) && !empty($section_id2)) {
            $section_ides = $section_id1[0]['s'] . "," . $section_id2[0]['s'];
        } elseif (!empty($section_id1)) {
            $section_ides = $section_id1[0]['s'];
        } elseif (!empty($section_id2)) {
            $section_ides = $section_id2[0]['s'];
        }
        if (!empty($section_ides)) {
            $ides = explode(',', $section_ides);
            foreach ($ides as $key => $value) {
                if ($value != '') {
                    $ides11[] = $value;
                }
            }
            if (!empty($ides11)) {
                $ides1 = implode(',', $ides11);
            }
        }

        if (isset($_GET['day_wise']) && !empty($_GET['day_wise'])) {

            if (!empty($section_id2)) {
                $section_ides = $section_id2[0]['s'];
            }
            $ides11 = array();
            $ides   = explode(',', $section_ides);
            foreach ($ides as $key => $value) {
                if ($value != '') {
                    $ides11[] = $value;
                }
            }

            if (!empty($ides11)) {

                $ides1 = implode(',', $ides11);
            }
        }

        if (!empty($ides1)) {

            $section = $this->db->query("select class_sections.id,class_sections.section_id as section_id,sections.section from class_sections join sections  on class_sections.section_id=sections.id where sections.id in(" . $ides1 . ") and class_id=" . $classid . "");

            $data = $section->result_array();
        } else {
            $data = array();
        }

        return $data;
    }

    public function get_teacherrestricted_modeallsections($staff_id)
    {
        $query1      = $this->db->query("select GROUP_CONCAT(st.section_id) as s from subject_timetable st where (st.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "')");
        $section_id1 = $query1->result_array();
        $query2      = $this->db->query("select GROUP_CONCAT(st.section_id) as s from class_teacher st where (st.staff_id='" . $staff_id . "' and session_id='" . $this->current_session . "')");
        $section_id2 = $query2->result_array();
        if (!empty($section_id1) && !empty($section_id2)) {
            $section_ides = $section_id1[0]['s'] . "," . $section_id2[0]['s'];
        } elseif (!empty($section_id1)) {
            $section_ides = $section_id1[0]['s'];
        } elseif (!empty($section_id2)) {
            $section_ides = $section_id2[0]['s'];
        }
        $ides = explode(',', $section_ides);
        foreach ($ides as $key => $value) {
            if ($value != '') {
                $ides11[] = $value;
            }
        }

        $ides1 = implode(',', $ides11);

        if (!empty($ides1)) {
            $section_ides = $ides1;
            $sectionlist  = $this->db->query("select id as section_id,section from sections  where id in(" . $section_ides . ")");
            $data         = $sectionlist->result_array();
        } else {
            $data = array();
        }
        return $data;
    }

}
