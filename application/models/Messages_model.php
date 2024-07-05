<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Messages_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session    = $this->setting_model->getCurrentSession();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function get($id = null)
    {
        $this->db->select()->from('messages');
        if ($id != null) {
            $this->db->where('messages.id', $id);
        } else {
            $this->db->order_by('messages.created_at', 'desc');
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
        $this->db->where('id', $id);
        $this->db->delete('messages');
        $message   = DELETE_RECORD_CONSTANT . " On messages id " . $id;
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
            $this->db->update('messages', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  messages id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('messages', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On messages id " . $insert_id;
            $action    = "Insert";
            $record_id = $id = $insert_id;
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

    public function get_classname($id)
    {
        $filter_class = $this->db->select('class')->from('classes')->where('id', $id)->get()->row_array();
        return $this->lang->line('class') . " " . $this->lang->line('name') . " : " . $filter_class['class'];
    }

    public function get_sectionname($id)
    {
        $filter_section = $this->db->select('section')->from('sections')->where('id', $id)->get()->row_array();
        return $this->lang->line('section') . " " . $this->lang->line('name') . " : " . $filter_section['section'];
    }

    public function get_categoryname($id)
    {
        $filter_category = $this->db->select('category')->from('categories')->where('id', $id)->get()->row_array();
        return $this->lang->line('category') . " " . $this->lang->line('name') . " : " . $filter_category['category'];
    }

    public function get_subject_groupname($id)
    {
        $filter_subject_groupname = $this->db->select('name')->from('subject_groups')->where('id', $id)->get()->row_array();
        return $this->lang->line('subject') . " " . $this->lang->line('group') . " " . $this->lang->line('name') . " : " . $filter_subject_groupname['name'];
    }

    public function get_subject_name($id)
    {
        $filter_get_subject_name = $this->db->select('subjects.name')->from('subject_group_subjects')->join('subjects', 'subject_group_subjects.subject_id=subjects.id', 'inner')->where('subject_group_subjects.id', $id)->get()->row_array();
        return $this->lang->line('subject') . " " . $this->lang->line('name') . " : " . $filter_get_subject_name['name'];
    }

    public function get_student_name($id)
    {
        $filter_get_student_name = $this->db->select('firstname,middlename,lastname')->from('students')->where('students.id', $id)->get()->row_array();
        return $this->lang->line('student') . " " . $this->lang->line('name') . " : " . $this->customlib->getFullName($filter_get_student_name['firstname'], $filter_get_student_name['middlename'], $filter_get_student_name['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
    }

    public function get_staff_name($id)
    {
        $filter_get_student_name = $this->db->select('CONCAT_WS(" ",name,surname,"(",employee_id,")") as name')->from('staff')->where('staff.id', $id)->get()->row_array();
        return $this->lang->line('collect') . " " . $this->lang->line('by') . " : " . $filter_get_student_name['name'];
    }

    public function get_exphead_name($id)
    {
        $filter_get_student_name = $this->db->select('exp_category')->from('expense_head')->where('expense_head.id', $id)->get()->row_array();
        return $this->lang->line('search') . " " . $this->lang->line('income_head') . " : " . $filter_get_student_name['exp_category'];
    }

    public function get_inchead_name($id)
    {
        $filter_get_student_name = $this->db->select('income_category')->from('income_head')->where('income_head.id', $id)->get()->row_array();
        return $this->lang->line('search') . " " . $this->lang->line('expense_head') . " : " . $filter_get_student_name['income_category'];
    }

    public function get_attendance_type($id)
    {
        $filter_get_student_name = $this->db->select('type')->from('attendence_type')->where('attendence_type.id', $id)->get()->row_array();
        return $this->lang->line('attendence') . " " . $this->lang->line('type') . " : " . $filter_get_student_name['type'];
    }

    public function get_exam_group($id)
    {
        $filter_get_exam_group = $this->db->select('name')->from('exam_groups')->where('exam_groups.id', $id)->get()->row_array();
        return $this->lang->line('exam') . " " . $this->lang->line('group') . " : " . $filter_get_exam_group['name'];
    }

    public function get_examname($id)
    {
        $filter_get_exam = $this->db->select('exam')->from('exam_group_class_batch_exams')->where('exam_group_class_batch_exams.id', $id)->get()->row_array();

        return $this->lang->line('exam') . " : " . $filter_get_exam['exam'];
    }

    public function get_onlineexamname($id)
    {
        $filter_get_exam = $this->db->select('exam')->from('onlineexam')->where('onlineexam.id', $id)->get()->row_array();

        return $this->lang->line('exam') . " : " . $filter_get_exam['exam'];
    }

    public function get_sessionname($id)
    {
        $filter_get_sessionname = $this->db->select('session')->from('sessions')->where('sessions.id', $id)->get()->row_array();

        return $this->lang->line('session') . " : " . $filter_get_sessionname['session'];
    }

    public function get_rolename($id)
    {
        $filter_get_rolename = $this->db->select('name')->from('roles')->where('roles.id', $id)->get()->row_array();

        return $this->lang->line('role') . " : " . $filter_get_rolename['name'];
    }

    public function get_designation($id)
    {
        $filter_get_rolename = $this->db->select('designation')->from('staff_designation')->where('staff_designation.id', $id)->get()->row_array();

        return $this->lang->line('designation') . " : " . $filter_get_rolename['designation'];
    }

    public function get_route_title($id)
    {
        $filter_get_route_title = $this->db->select('route_title')->from('transport_route')->where('transport_route.id', $id)->get()->row_array();

        return $this->lang->line('route_title') . " : " . $filter_get_route_title['route_title'];
    }

    public function get_student_full_name($id)
    {
        return $this->db->select('firstname,middlename,lastname')->from('students')->where('students.id', $id)->get()->row_array();

    }

    public function get_email_template($id = null)
    {
        $this->db->select('*')->from('email_template');
        if ($id != null) {
            $this->db->where('email_template.id', $id);
        } else {
            $this->db->order_by('email_template.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }

    public function add_email_template($data, $FILES = array())
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('email_template', $data);

            if (!empty($FILES)) {
                foreach ($FILES as $key => $file_value) {
                    $attachment['attachment_name']   = $file_value['attachment_name'];
                    $attachment['email_template_id'] = $data['id'];
                    $attachment['attachment']        = $file_value['attachment'];
                    $this->db->insert('email_template_attachment', $attachment);
                }
            }

            $message   = UPDATE_RECORD_CONSTANT . " On  email template id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('email_template', $data);
            $insert_id = $this->db->insert_id();

            if (!empty($FILES)) {

                if (isset($_FILES['files']) && !empty($_FILES['files'])) {
                    $no_files = count($_FILES["files"]['name']);
                    for ($i = 0; $i < $no_files; $i++) {
                        if ($_FILES["files"]["error"][$i] > 0) {
                            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
                        } else {

                            $uploaddir = './uploads/communicate/email_template_images/';
                            if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                                die("Error creating folder $uploaddir");
                            }
                            $fileInfo = pathinfo($_FILES["files"]["name"][$i]);
                            $document = basename($_FILES['files']['name'][$i]);

                            $img_name = $this->customlib->uniqueFileName() . '.' . $fileInfo['extension'];
                            move_uploaded_file($_FILES["files"]["tmp_name"][$i], $uploaddir . $img_name);

                            $attachmentdata['attachment_name']   = $document;
                            $attachmentdata['email_template_id'] = $insert_id;
                            $attachmentdata['attachment']        = $img_name;
                            $this->db->insert('email_template_attachment', $attachmentdata);
                        }
                    }
                }
            }

            $message   = INSERT_RECORD_CONSTANT . " On email template id " . $insert_id;
            $action    = "Insert";
            $record_id = $id = $insert_id;
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

    public function delete_email_template($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================

        $attachment_list = $this->get_email_template_attachment($id);

        if (!empty($attachment_list)) {
            foreach ($attachment_list as $key => $attachment_list_value) {
                $this->db->where('id', $attachment_list_value['id']);
                $this->db->delete('email_template_attachment');
                unlink(realpath('./uploads/communicate/email_template_images/' . $attachment_list_value['attachment']));
            }
        }

        $this->db->where('id', $id);
        $this->db->delete('email_template');

        $message   = DELETE_RECORD_CONSTANT . " On email template id " . $id;
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

    public function get_sms_template($id = null)
    {
        $this->db->select('*')->from('sms_template');
        if ($id != null) {
            $this->db->where('sms_template.id', $id);
        } else {
            $this->db->order_by('sms_template.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result_array();
        }
    }

    public function add_sms_template($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('sms_template', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  sms template id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('sms_template', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On sms template id " . $insert_id;
            $action    = "Insert";
            $record_id = $id = $insert_id;
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

    public function delete_sms_template($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('sms_template');
        $message   = DELETE_RECORD_CONSTANT . " On email template id " . $id;
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

    public function get_email_template_attachment($email_template_id)
    {
        $this->db->select('*');
        $this->db->from('email_template_attachment');
        $this->db->where('email_template_attachment.email_template_id', $email_template_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_scheduledata($date)
    {
        return $this->db->select('*')->from('messages')->where('date_format(`schedule_date_time`,"%Y-%m-%d %H:%i") ="' . $date . '"')->where('is_schedule', 1)->or_where('sent', 0)->get()->result_array();

    }

    public function get_message_attachment($message_id)
    {
        return $this->db->select('*')->from('email_attachments')->where('message_id', $message_id)->get()->result_array();
    }

    public function schedule($id = null)
    {
        $this->db->select()->from('messages');
        $this->db->where('messages.is_schedule', '1');
        if ($id != null) {
            $this->db->where('messages.id', $id);
        } else {
            $this->db->order_by('messages.schedule_date_time', 'asc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function get_single_email_template_attachment($id)
    {
        $this->db->select('*');
        $this->db->from('email_template_attachment');
        $this->db->where('email_template_attachment.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_email_attachment($message_id)
    {
        $this->db->select('email_attachments.*');
        $this->db->from('email_attachments');
        $this->db->where('email_attachments.message_id', $message_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function check_email_attachment($message_id, $name)
    {
        $this->db->select('email_attachments.*');
        $this->db->from('email_attachments');
        $this->db->where('email_attachments.attachment', $name);
        $this->db->where('email_attachments.message_id', $message_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function add_email_attachment($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('email_attachments', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  email attachments id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('email_attachments', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On email attachments id " . $insert_id;
            $action    = "Insert";
            $record_id = $id = $insert_id;
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

    public function delete_email_attachment($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('message_id', $id);
        $this->db->delete('email_attachments');

        $message   = DELETE_RECORD_CONSTANT . " On email attachment id " . $id;
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

    public function check_template_attachment($template_id, $name)
    {
        $this->db->select('email_template_attachment.*');
        $this->db->from('email_template_attachment');
        $this->db->where('email_template_attachment.attachment', $name);
        $this->db->where('email_template_attachment.email_template_id', $template_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function delete_template_attachment($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('email_template_id', $id);
        $this->db->delete('email_template_attachment');

        $message   = DELETE_RECORD_CONSTANT . " On email template attachment id " . $id;
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
}
