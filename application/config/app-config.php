<?php

// https://filext.com/faq/office_mime_types.html
// https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$config['adm_digit_length'] = 6;
$config['exam_type'] = array(
    'basic_system' => lang('basic_system'),
    'school_grade_system' => lang('school_grade_system'),
    'coll_grade_system' => lang('coll_grade_system'),
    'gpa' => lang('gpa_grading_system'),
    'average_passing' => lang('average_passing'),
);

$config['image_validate'] = array(
    'allowed_mime_type' => array('image/jpeg', 'image/jpg', 'image/png'), //mime_type
    'allowed_extension' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'SVG', 'Jpg', 'Jpeg', 'Png', 'Gif', 'Bmp', 'Svg'), // image extensions
    'upload_size' => 10048576, // bytes
);

$config['csv_validate'] = array(
    'allowed_mime_type' => array('application/vnd.ms-excel',
        'text/plain',
        'text/csv',
        'text/tsv','application/csv'), //mime_type
    'allowed_extension' => array('csv'), // image extensions
    'upload_size' => 100048576, // bytes
);
 
$config['file_validate'] = array(
    'allowed_mime_type' => array(
        'application/pdf',
        'application/msword',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/jpg',
        'image/png',
        'audio/mpeg',
        'audio/mpg',
        'audio/mpeg3',
        'audio/mp3',
        'audio/aac',
        'audio/midi',
        'audio/x-midi',
        'audio/ogg',
        'audio/opus',
        'audio/wav',
        'audio/webm',
        'audio/3gpp',
        'audio/3gpp2',
        'video/mp4',
        'video/mpeg',
        'video/3gpp',
        'video/webm',
        'video/x-msvideo',
        'video/msvideo',
        'video/avi',
        'application/x-troff-msvideo',
        'application/xls',
        'video/x-ms-wmv',
        'video/x-ms-asf',
        'application/octet-stream',
        'video/quicktime',
        'video/x-matroska',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ), //mime_type
    'allowed_extension' => array('pptx', 'ppt', 'pdf', 'doc', 'xls', 'ppt', 'docx', 'xlsx', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'mp3', 'aac', 'mp4', 'mpg', '3gp', 'webm', 'mpeg', 'avi', 'wmv', 'mov', 'mid', 'midi', 'oga', 'opus', 'wav', 'weba', '3g2', 'PPTX', 'PPT', 'WEBA', 'MPEG', 'WAV', 'OPUS', 'MIDI', 'OGA', 'MID', 'PDF', 'DOC', 'XLS', 'DOCX', 'XLSX', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP', 'SVG', 'MP3', 'AAC', 'MP4', 'MPG', '3GP', 'WEBM', 'AVI', 'WMV', 'MOV', 'Pptx', 'Ppt', 'Pdf', 'Doc', 'Xls', 'Docx', 'Xlsx', 'Jpg', 'Jpeg', 'Png', 'Gif', 'Bmp', 'Svg', 'Mp3', 'Aac', 'Mp4', 'Mpg', '3Gp', 'Webm', 'Avi', 'Wmv', 'Mov', 'mkv', 'dta'), // image extensions
    'upload_size' => 100048576, // bytes
);
