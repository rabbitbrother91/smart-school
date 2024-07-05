<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('display_custom_fields')) {

    function display_custom_fields($belongs_to, $rel_id = false, $where = array())
    {
        $CI = &get_instance();
        $CI->db->from('custom_fields');
        $CI->db->where('belong_to', $belongs_to);
        $CI->db->order_by('custom_fields.belong_to', 'asc');
        $CI->db->order_by('custom_fields.weight', 'asc');
        $query       = $CI->db->get();
        $result      = $query->result_array();
        $fields_html = '';

        foreach ($result as $result_key => $field) {

            $type = $field['type'];

            $label      = ucfirst($field['name']);
            $field_name = 'custom_fields[' . $field['belong_to'] . '][' . $field['id'] . ']';
            if ($field['bs_column'] == '' || $field['bs_column'] == 0) {
                $field['bs_column'] = 12;
            }
            $input_class = "";
            $value       = "";
            if ($rel_id !== false) {

                $return_value = get_custom_field_value($rel_id, $field['id'], $belongs_to);
                if (!empty($return_value)) {
                    $value = $return_value->field_value;
                }
            }

            $fields_html .= '<div class="col-md-' . $field['bs_column'] . '">';
            if ($field['type'] == 'input' || $field['type'] == 'number') {
                $type = $field['type'] == 'input' ? 'text' : 'number';
                $fields_html .= render_input_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'textarea') {
                $fields_html .= render_textarea_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'select') {

                $options = optionSplit($field['field_values']);

                $fields_html .= render_select_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'multiselect') {
                $options = optionSplit($field['field_values']);
                $fields_html .= render_multiselect_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'checkbox') {

                $options = optionSplit($field['field_values']);
                $fields_html .= render_checkbox_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'date_picker') {

                $type = $field['type'];
                $fields_html .= render_date_picker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'date_picker_time') {

                $type = $field['type'];
                $fields_html .= render_date_picker_time_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'colorpicker') {

                $type = $field['type'];
                $fields_html .= render_colorpicker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'link') {

                $type = $field['type'];
                $fields_html .= render_link_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            }
            $fields_html .= '</div>';
        }

        return $fields_html;
    }
    
    function display_admission_custom_fields($belongs_to, $rel_id = false, $where = array())
    {
        $CI = &get_instance();
        $CI->db->from('custom_fields');
        $CI->db->where('belong_to', $belongs_to);
        $CI->db->order_by('custom_fields.belong_to', 'asc');
        $CI->db->order_by('custom_fields.weight', 'asc');
        $query       = $CI->db->get();
        $result      = $query->result_array();
        $fields_html = '';

        foreach ($result as $result_key => $field) {

            $type = $field['type'];
            $label      = ucfirst($field['name']);          
            
            if($CI->customlib->getfieldstatus($field['name']))
            {
                $field_name = 'custom_fields[' . $field['belong_to'] . '][' . $field['id'] . ']';
                if ($field['bs_column'] == '' || $field['bs_column'] == 0) {
                    $field['bs_column'] = 12;
                }
                $input_class = "";
                $value       = "";
                if ($rel_id !== false) {

                    $return_value = get_custom_field_value($rel_id, $field['id'], $belongs_to);
                    if (!empty($return_value)) {
                        $value = $return_value->field_value;
                    }
                }

                $fields_html .= '<div class="col-md-' . $field['bs_column'] . '">';
                if ($field['type'] == 'input' || $field['type'] == 'number') {
                    $type = $field['type'] == 'input' ? 'text' : 'number';
                    $fields_html .= render_input_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'textarea') {
                    $fields_html .= render_textarea_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'select') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_select_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'multiselect') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_multiselect_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'checkbox') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_checkbox_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'date_picker') {
                    $type = $field['type'];
                    $fields_html .= render_date_picker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'date_picker_time') {
                    $type = $field['type'];
                    $fields_html .= render_date_picker_time_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'colorpicker') {
                    $type = $field['type'];
                    $fields_html .= render_colorpicker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'link') {
                    $type = $field['type'];
                    $fields_html .= render_link_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                }
                $fields_html .= '</div>';
            }           
        }

        return $fields_html;
    }

    function render_input_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . $value . '">';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_textarea_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';
        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<textarea id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' >' . $value . '</textarea>';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_select_field($name, $options, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<select id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . '>';
        $input .= '<option value="">Select</option>';
        foreach ($options as $option_key => $option_value) {
            $input .= '<option value="' . $option_value . '" ' . set_select($name, $option_value, (set_value($name, $value) == $option_value) ? true : false) . '>' . $option_value . '</option>';

        }
        $input .= '</select>';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';
        return $input;
    }

    function render_multiselect_field($name, $options, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<select id="' . $name . '" name="' . $name . '[]" class="form-control' . $input_class . '" ' . $_input_attrs . ' multiple  >' . $value . '>';
        $input .= '<option value="">Select</option>';
        foreach ($options as $option_key => $option_value) {

            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                if (isset($_POST[$name]) && in_array($option_value, $name)) {
                    $chk_status = true;
                } else {
                    $chk_status = false;
                }

            } elseif ($value != "" && in_array($option_value, explode(",", $value))) {
                $chk_status = true;
            } else {
                $chk_status = false;
            }

            $input .= '<option value="' . $option_value . '" ' . set_select($name, $option_value, $chk_status) . '>' . $option_value . '</option>';
        }

        $input .= '</select>';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_checkbox_field($name, $options, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }
        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        $input .= '<div class="checkbox">';
        foreach ($options as $option_key => $option_value) {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                if (isset($_POST[$name]) && in_array($option_value, $name)) {
                    $chk_status = true;
                } else {
                    $chk_status = false;
                }

            } elseif ($value != "" && in_array($option_value, explode(",", $value))) {
                $chk_status = true;
            } else {
                $chk_status = false;
            }
            $input .= '<label class="checkbox-inline">';

            $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '[]"  value="' . $option_value . '" ' . set_checkbox($name, $option_value, $chk_status) . '>' . $option_value . '</label>';
            $input .= '</label>';
        }

        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';
        $input .= '</div>';
        return $input;
    }

    function render_date_picker_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';
        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<input  id="' . $name . '" name="' . $name . '" class="form-control date' . $input_class . '" ' . $_input_attrs . ' value="' . $value . '">';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_date_picker_time_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';
        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<input  id="' . $name . '" name="' . $name . '" class="form-control datetime' . $input_class . '" ' . $_input_attrs . ' value="' . $value . '">';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_colorpicker_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';
        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<input  id="' . $name . '" name="' . $name . '" class="form-control color' . $input_class . '" ' . $_input_attrs . ' value="' . $value . '">';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function render_link_field($name, $belong_to, $field_id, $validation, $label = '', $value = '', $type = 'text', $input_class = '')
    {
        $input            = '';
        $_form_group_attr = '';
        $_input_attrs     = '';

        if (isset($_POST['custom_fields'][$belong_to][$field_id])) {
            $value = $_POST['custom_fields'][$belong_to][$field_id];
        }
        if (!empty($input_class)) {
            $input_class = ' ' . $input_class;
        }

        $input .= '<div class="form-group">';

        if ($label != '') {
            $input .= '<label for="' . $name . '" class="control-label">' . $label . '</label>';
        }

        if ($validation) {
            $input .= "<small class='req'> *</small>";
        }
        
        $input .= '<input type="' . $type . '" id="' . $name . '" name="' . $name . '" class="form-control' . $input_class . '" ' . $_input_attrs . ' value="' . $value . '">';
        $input .= '<span class="text-danger">' . form_error($name) . '</span>';
        $input .= '</div>';

        return $input;
    }

    function get_custom_field_value($rel_id, $field_id, $belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT * FROM `custom_fields` INNER JOIN custom_field_values on custom_field_values.belong_table_id=' . $CI->db->escape($rel_id) . ' and custom_field_values.custom_field_id=custom_fields.id WHERE belong_to =' . $CI->db->escape($belongs_to) . ' and custom_field_values.custom_field_id=' . $CI->db->escape($field_id);

        $query = $CI->db->query($sql);
        return $query->row();
    }

    function optionSplit($values)
    {
        return explode(',', $values);
    }

    function removeBreak($option_value)
    {
        return preg_replace('/<br\\s*?\\/?>\\s*$/', '', $option_value);
    }

    function get_custom_table_values($table_id, $belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT custom_field_values.*,custom_fields.name,custom_fields.type,custom_fields.belong_to  FROM `custom_field_values` RIGHT JOIN custom_fields on custom_fields.id=custom_field_values.custom_field_id  and belong_table_id=' . $CI->db->escape($table_id) . ' WHERE custom_fields.belong_to=' . $CI->db->escape($belongs_to) . ' ORDER by custom_fields.weight asc';
        $query = $CI->db->query($sql);
        return $query->result();
    }

    function get_custom_print_table_values($table_id, $belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT custom_field_values.*,custom_fields.name,custom_fields.type,custom_fields.belong_to  FROM `custom_field_values` RIGHT JOIN custom_fields on custom_fields.id=custom_field_values.custom_field_id  and belong_table_id=' . $CI->db->escape($table_id) . ' WHERE custom_fields.belong_to=' . $CI->db->escape($belongs_to) . ' AND custom_fields.visible_on_table=1 ORDER by custom_fields.weight asc';
        $query = $CI->db->query($sql);
        return $query->result();
    }

    function get_custom_fields($belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT custom_fields.*  FROM `custom_fields` WHERE custom_fields.belong_to=' . $CI->db->escape($belongs_to) . ' ORDER by custom_fields.id asc';
        $query = $CI->db->query($sql);
        return $query->result();
    }

    function get_student_editable_fields()
    {
        $fields = array(
            'firstname'                  => lang('first_name'),
            'admission_date'             => lang('admission_date'),
            'middlename'                 => lang('middle_name'),
            'lastname'                   => lang('last_name'),
            'rte'                        => lang('rte'),
            'student_photo'              => lang('student_photo'),
            'mobile_no'                  => lang('mobile_number'),
            'student_email'              => lang('email'),
            'religion'                   => lang('religion'),
            'cast'                       => lang('caste'),
            'dob'                        => lang('date_of_birth'),
            'is_blood_group'             => lang('blood_group'),
            'if_guardian_is'             => lang('if_guardian_is'),
            'gender'                     => lang('gender'),
            'current_address'            => lang('current_address'),
            'permanent_address'          => lang('permanent_address'),
            'category'                   => lang('category'),
            'bank_account_no'            => lang('bank_account_number'),
            'bank_name'                  => lang('bank_name'),
            'ifsc_code'                  => lang('ifsc_code'),
            'father_name'                => lang('father_name'),
            'father_phone'               => lang('father_phone'),
            'father_occupation'          => lang('father_occupation'),
            'mother_name'                => lang('mother_name'),
            'mother_phone'               => lang('mother_phone'),
            'is_student_house'           => lang('house'),
            'mother_occupation'          => lang('mother_occupation'),
            'guardian_name'              => lang('guardian_name'),
            'guardian_relation'          => lang('guardian_relation'),
            'guardian_phone'             => lang('guardian_phone'),
            'guardian_occupation'        => lang('guardian_occupation'),
            'guardian_address'           => lang('guardian_address'),
            'guardian_email'             => lang('guardian_email'),
            'national_identification_no' => lang('national_identification_number'),
            'local_identification_no'    => lang('local_identification_number'),
            'father_pic'                 => lang('father_photo'),
            'mother_pic'                 => lang('mother_photo'),
            'guardian_pic'               => lang('guardian_photo'),
            'student_height'             => lang('height'),
            'student_weight'             => lang('weight'),
            'previous_school_details'    => lang('previous_school_details')
        );
        return $fields;
    }

    function get_onlineadmission_editable_fields()
    {
         $fields = array(
            
            'middlename'                 => lang('middle_name'),
            'lastname'                   => lang('last_name'),
            'category'                   => lang('category'),
            'religion'                   => lang('religion'),
            'cast'                       => lang('caste'),
            'mobile_no'                  => lang('mobile_number'),
            'student_email'              => lang('email'),
            'student_photo'              => lang('student_photo'),
            'is_student_house'           => lang('house'),
            'is_blood_group'             => lang('blood_group'),
            'student_height'             => lang('height'),
            'student_weight'             => lang('weight'),
            'measurement_date'           => lang('measurement_date'),
            'father_name'                => lang('father_name'),
            'father_phone'               => lang('father_phone'),
            'father_occupation'          => lang('father_occupation'),
            'father_pic'                 => lang('father_photo'),
            'mother_name'                => lang('mother_name'),
            'mother_phone'               => lang('mother_phone'),
            'mother_occupation'          => lang('mother_occupation'),
            'mother_pic'                 => lang('mother_photo'),
            'if_guardian_is'             => lang('if_guardian_is'),
            'guardian_name'              => lang('guardian_name'),
            'guardian_relation'          => lang('guardian_relation'),
            'guardian_phone'             => lang('guardian_phone'),
            'guardian_email'             => lang('guardian_email'),
            'guardian_occupation'        => lang('guardian_occupation'),
            'guardian_photo'             => lang('guardian_photo'),
            'guardian_address'           => lang('guardian_address'),
            'current_address'            => lang('if_guardian_address_is_current_address'),
            'permanent_address'          => lang('if_permanent_address_is_current_address'),
            'bank_account_no'            => lang('bank_account_number'),
            'bank_name'                  => lang('bank_name'),
            'ifsc_code'                  => lang('ifsc_code'),
            'national_identification_no' => lang('national_identification_number'),
            'local_identification_no'    => lang('local_identification_number'),
            'rte'                        => lang('rte'),
            'previous_school_details'    => lang('previous_school_details'),
            'student_note'               => lang('note'),        
            'upload_documents'           => lang('upload_documents')         
        );
        return $fields;
    }

    function display_onlineadmission_custom_fields($belongs_to, $rel_id = false, $where = array())
    {
        $CI = &get_instance();
        $CI->db->from('custom_fields');
        $CI->db->where('belong_to', $belongs_to);
        $CI->db->order_by('custom_fields.belong_to', 'asc');
        $CI->db->order_by('custom_fields.weight', 'asc');
        $query       = $CI->db->get();
        $result      = $query->result_array();
        $fields_html = '';

        foreach ($result as $result_key => $field) {

            $type = $field['type'];
            $label      = ucfirst($field['name']);          
            
            if($CI->customlib->getfieldstatus($field['name']))
            {
                $field_name = 'custom_fields[' . $field['belong_to'] . '][' . $field['id'] . ']';
                if ($field['bs_column'] == '' || $field['bs_column'] == 0) {
                    $field['bs_column'] = 12;
                }
                $input_class = "";
                $value       = "";
                if ($rel_id !== false) {
                    $return_value = get_onlineadmission_custom_field_value($rel_id, $field['id'], $belongs_to);
                    if (!empty($return_value)) {
                        $value = $return_value->field_value;
                    }
                }

                $fields_html .= '<div class="col-md-' . $field['bs_column'] . '">';
                if ($field['type'] == 'input' || $field['type'] == 'number') {
                    $type = $field['type'] == 'input' ? 'text' : 'number';
                    $fields_html .= render_input_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'textarea') {
                    $fields_html .= render_textarea_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'select') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_select_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'multiselect') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_multiselect_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'checkbox') {
                    $options = optionSplit($field['field_values']);
                    $fields_html .= render_checkbox_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'date_picker') {
                    $type = $field['type'];
                    $fields_html .= render_date_picker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'date_picker_time') {
                    $type = $field['type'];
                    $fields_html .= render_date_picker_time_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'colorpicker') {
                    $type = $field['type'];
                    $fields_html .= render_colorpicker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                } elseif ($field['type'] == 'link') {
                    $type = $field['type'];
                    $fields_html .= render_link_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
                }
                $fields_html .= '</div>';
            }           
        }

        return $fields_html;
    }

    function get_onlineadmission_custom_field_value($rel_id, $field_id, $belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT * FROM `custom_fields` INNER JOIN online_admission_custom_field_value on online_admission_custom_field_value.belong_table_id=' . $CI->db->escape($rel_id) . ' and online_admission_custom_field_value.custom_field_id=custom_fields.id WHERE belong_to =' . $CI->db->escape($belongs_to) . ' and online_admission_custom_field_value.custom_field_id=' . $CI->db->escape($field_id);
        $query = $CI->db->query($sql);
        return $query->row();
    }

    function get_onlineadmission_custom_table_values($table_id, $belongs_to)
    {
        $CI  = &get_instance();
        $sql = 'SELECT online_admission_custom_field_value.*,custom_fields.name,custom_fields.type,custom_fields.belong_to  FROM `online_admission_custom_field_value` RIGHT JOIN custom_fields on custom_fields.id=online_admission_custom_field_value.custom_field_id  and belong_table_id=' . $CI->db->escape($table_id) . ' WHERE custom_fields.belong_to=' . $CI->db->escape($belongs_to) . ' ORDER by custom_fields.weight asc';
        $query = $CI->db->query($sql);
        return $query->result();
    }    
    
    function display_custom_fields_student_penal_edit_profile($belongs_to, $rel_id = false, $where = array())
    {
        $CI = &get_instance();
        $CI->db->from('custom_fields');
        $CI->db->where('belong_to', $belongs_to);
        $CI->db->order_by('custom_fields.belong_to', 'asc');
        $CI->db->order_by('custom_fields.weight', 'asc');
        $query       = $CI->db->get();
        $result      = $query->result_array();
        $fields_html = '';

        foreach ($result as $result_key => $field) {            
         if($CI->customlib->checkprofilesettingfieldexist($field['name']))
            {
            $type = $field['type'];

            $label      = ucfirst($field['name']);
            $field_name = 'custom_fields[' . $field['belong_to'] . '][' . $field['id'] . ']';
            if ($field['bs_column'] == '' || $field['bs_column'] == 0) {
                $field['bs_column'] = 12;
            }
            $input_class = "";
            $value       = "";
            if ($rel_id !== false) {

                $return_value = get_custom_field_value($rel_id, $field['id'], $belongs_to);
                if (!empty($return_value)) {
                    $value = $return_value->field_value;
                }
            }

            $fields_html .= '<div class="col-md-' . $field['bs_column'] . '">';
            if ($field['type'] == 'input' || $field['type'] == 'number') {
                $type = $field['type'] == 'input' ? 'text' : 'number';
                $fields_html .= render_input_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'textarea') {
                $fields_html .= render_textarea_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'select') {
                $options = optionSplit($field['field_values']);
                $fields_html .= render_select_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'multiselect') {
                $options = optionSplit($field['field_values']);
                $fields_html .= render_multiselect_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'checkbox') {
                $options = optionSplit($field['field_values']);
                $fields_html .= render_checkbox_field($field_name, $options, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'date_picker') {
                $type = $field['type'];
                $fields_html .= render_date_picker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'date_picker_time') {
                $type = $field['type'];
                $fields_html .= render_date_picker_time_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'colorpicker') {
                $type = $field['type'];
                $fields_html .= render_colorpicker_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            } elseif ($field['type'] == 'link') {
                $type = $field['type'];
                $fields_html .= render_link_field($field_name, $field['belong_to'], $field['id'], $field['validation'], $label, $value, $type, $input_class);
            }
            $fields_html .= '</div>';
        }
        
        }

        return $fields_html;
    }
}