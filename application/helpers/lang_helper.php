<?php

function set_language($lang) {
    $CI = get_instance();
    $language_result = $CI->language_model->get($lang);
    $language_array = array('lang_id' => $language_result['id'], 'language' => $language_result['language']);
    $CI->session->userdata['admin']['language'] = $language_array;
    $CI->config->set_item('language', $language_result['language']);
}

?>