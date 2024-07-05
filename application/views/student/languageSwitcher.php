<?php

$student_session = $this->session->userdata('student'); 
$lang_id        = $student_session['language']['lang_id'];
$lang    = $this->setting_model->get();
$json_languages = json_decode($lang[0]['languages']);

foreach ($json_languages as $value) {
    $result = $this->db->select('languages.language,languages.country_code,languages.id')->from('languages')->where('id', $value)->get()->row_array();
    ?>
    <option data-content='<span class="flag-icon flag-icon-<?php echo $result['country_code']; ?>"></span> <?php echo $result['language']; ?>' value="<?php echo $result['id']; ?>" <?php
if ($lang_id == $value) {
        echo "Selected";
    }
    ?>><?php echo $result['language']; ?></option>
    <?php
}
?>