<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Media extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model("filetype_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('media_manager', 'can_view')) {
            access_denied();
        }
       
        $data['title']      = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->session->set_userdata('top_menu', 'Front CMS');
        $this->session->set_userdata('sub_menu', 'admin/front/media');
        $data['mediaTypes'] = $this->customlib->mediaType();
        $this->load->view('layout/header');
        $this->load->view('admin/front/media/index', $data);
        $this->load->view('layout/footer');
    }

    public function getMedia()
    {
        $data               = array();
        $data['mediaTypes'] = $this->customlib->mediaType();
        $this->load->view('admin/front/media/getMedia', $data);
    } 

    public function getPage()
    {
        $keyword    = $this->input->get('keyword');
        $file_type  = $this->input->get('file_type');
        $is_gallery = $this->input->get('is_gallery');
        if (!isset($is_gallery)) {
            $is_gallery = 1;
        }
        $this->load->model("cms_media_model");
        $this->load->library("pagination");
        $config             = array();
        $config["base_url"] = "#";

        $config["total_rows"]       = $this->cms_media_model->count_all($keyword, $file_type);
        $config["per_page"]         = 30;
        $config["uri_segment"]      = 5;
        $config["use_page_numbers"] = true;
        $config["full_tag_open"]    = '<ul class="pagination">';
        $config["full_tag_close"]   = '</ul>';
        $config["first_tag_open"]   = '<li>';
        $config["first_tag_close"]  = '</li>';
        $config["last_tag_open"]    = '<li>';
        $config["last_tag_close"]   = '</li>';
        $config['next_link']        = '&gt;';
        $config["next_tag_open"]    = '<li>';
        $config["next_tag_close"]   = '</li>';
        $config["prev_link"]        = "&lt;";
        $config["prev_tag_open"]    = "<li>";
        $config["prev_tag_close"]   = "</li>";
        $config["cur_tag_open"]     = "<li class='active'><a href='#'>";
        $config["cur_tag_close"]    = "</a></li>";
        $config["num_tag_open"]     = "<li>";
        $config["num_tag_close"]    = "</li>";
        $config["num_links"]        = 1;
        $this->pagination->initialize($config);
        $page        = $this->uri->segment(5);
        $start       = ($page - 1) * $config["per_page"];
        $result      = $this->cms_media_model->fetch_details($config["per_page"], $start, $keyword, $file_type);
        $img_data    = array();
        $check_empty = 0;
        if (!empty($result)) {
            $check_empty = 1;
            foreach ($result as $res_key => $res_value) {

                $div = $this->genrateDiv($res_value, $is_gallery);
                $img_data[] = $div;
            }
        }

        $output = array(
            'pagination_link' => $this->pagination->create_links(),
            'result_status'   => $check_empty,
            'result'          => $img_data,
        );
        echo json_encode($output);
    }

    public function deleteItem()
    {
        if (!$this->rbac->hasPrivilege('media_manager', 'can_delete')) {
            access_denied();
        }
        $record_id = $this->input->post('record_id');
        $record    = $this->cms_media_model->get($record_id);
        if ($record) {

            $destination_path = "uploads/gallery/media/" . $record['img_name'];
            $thumb_path       = "uploads/gallery/media/thumb/" . $record['img_name'];
            $del_record       = $this->cms_media_model->remove($record_id);
            if ($del_record) {
                if (is_readable($destination_path) && unlink($destination_path) && is_readable($thumb_path) && unlink($thumb_path)) {

                }
                echo json_encode(array('status' => 1, 'msg' => $this->lang->line('delete_message')));
            } else {
                echo json_encode(array('status' => 0, 'msg' => $this->lang->line('please_try_again')));
            }
        } else {
            echo json_encode(array('status' => 0, 'msg' => $this->lang->line('please_try_again')));
        }
    }

    public function addImage()
    {
        if (!$this->rbac->hasPrivilege('media_manager', 'can_add')) {
            access_denied();
        }
        $msg               = "";
        $result            = $this->filetype_model->get();
        $allowedExts       = array_map('trim', array_map('strtolower', explode(',', ($result->image_extension . "," . $result->file_extension))));
        $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', ($result->image_mime . "," . $result->file_mime))));

        if (isset($_FILES['files']) && !empty($_FILES['files'])) {
            $dir_path               = "uploads/gallery/media/";
            $thumb_path             = "uploads/gallery/media/thumb/";
            $config['thumb_path']   = $thumb_path;
            $config['dir_path']     = $dir_path;
            $config['thumb_width']  = 200;
            $config['thumb_height'] = 200;
            $this->load->library('imageResize', $config);
            $responses      = $this->imageresize->resize($_FILES["files"]);
            $response_array = array();
            if ($responses) {
                $img_array  = array();
                $validation = 0;
                foreach ($responses['images'] as $key => $value) {

                    $validation = 1;
                    $temp       = explode(".", $value['store_name']);
                    $file_type  = strtolower($value['file_type']);
                    $file_size  = $value['file_size'];
                    $extension = end($temp);
                    $extension = strtolower($extension);

                    if (!in_array($extension, $allowedExts)) {
                        $validation = 0;
                        $msg        = $this->lang->line('extension_not_allowed');
                    }
                    if (!in_array($file_type, $allowed_mime_type)) {
                        $validation = 0;
                        $msg        = $this->lang->line('extension_not_allowed');
                    }
                    if ($file_size > $result->file_size) {
                        $validation = 0;
                        $msg        = $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . ' MB';
                    }
                }

                if ($validation == 1) {
                    foreach ($responses['images'] as $key => $value) {
                        $data = array(
                            'image'      => $value['name'],
                            'img_name'   => $value['store_name'],
                            'file_type'  => $value['file_type'],
                            'file_size'  => $value['file_size'],
                            'thumb_name' => $value['thumb_name'],
                            'thumb_path' => $value['thumb_path'],
                            'dir_path'   => $value['dir_path'],
                        );
                        $insert_id         = $this->cms_media_model->add($data);
                        $data['record_id'] = $insert_id;
                        $img_array[]       = $data;
                    }
                    $response_array['status'] = 0;
                    $response_array['msg']    = $this->lang->line('success_message');

                } else {
                    $response_array['status'] = 0;
                    $response_array['msg']    = $msg;

                }

            } else {
                $response_array['status'] = 0;
                $response_array['msg']    = $this->lang->line('something_went_wrong');
            }
            echo json_encode($response_array);
        }
    }

    public function genrateDiv($result, $is_gallery)
    {

        $is_image = "0";
        $is_video = "0";
        if ($result->file_type == 'image/png' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/jpeg' || $result->file_type == 'image/gif') {

            $thumb_file = $this->media_storage->getImageURL($result->thumb_path . $result->thumb_name);
            $file       = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
            $is_image   = 1;
        } elseif ($result->file_type == 'video') {
            $thumb_file = $this->media_storage->getImageURL($result->thumb_path . $result->thumb_name);
            $file       = $this->media_storage->getImageURL($result->thumb_path . $result->thumb_name);
            $file_src   = $result->vid_url;
            $is_video   = 1;
        } elseif ($result->file_type == 'text/plain') {
            $thumb_file = $this->media_storage->getImageURL('backend/images/txticon.png');
            $file       = $this->media_storage->getImageURL('backend/images/txticon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'application/zip' || $result->file_type == 'application/x-rar' || $result->file_type == "application/x-7z-compressed" || $result->file_type == "application/x-rar-compressed" || $result->file_type == "application/x-bzip" || $result->file_type == "application/x-bzip2") {
            $thumb_file = $this->media_storage->getImageURL('backend/images/zipicon.png');
            $file       = $this->media_storage->getImageURL('backend/images/zipicon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'application/pdf') {
            $thumb_file = $this->media_storage->getImageURL('backend/images/pdficon.png');
            $file       = $this->media_storage->getImageURL('backend/images/pdficon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'application/msword' || $result->file_type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            $thumb_file = $this->media_storage->getImageURL('backend/images/wordicon.png');
            $file       = $this->media_storage->getImageURL('backend/images/wordicon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'application/vnd.ms-powerpoint' || $result->file_type == "application/vnd.openxmlformats-officedocument.presentationml.presentation") {
            $thumb_file = $this->media_storage->getImageURL('backend/images/pptxicon.png');
            $file       = $this->media_storage->getImageURL('backend/images/pptxicon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'video/mp4' || $result->file_type == "video/mpeg" || $result->file_type == "video/ogg" || $result->file_type == "video/dvd" || $result->file_type == "video/webm" || $result->file_type == "video/x-ms-wmv" || $result->file_type == "video/3gpp" || $result->file_type == "video/x-msvideo" || $result->file_type == 'video/x-ms-asf' || $result->file_type == 'video/x-f4v') {
            $thumb_file = $this->media_storage->getImageURL('backend/images/video-icon.png');
            $file       = $this->media_storage->getImageURL('backend/images/video-icon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } elseif ($result->file_type == 'application/vnd.ms-excel' || $result->file_type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            $thumb_file = $this->media_storage->getImageURL('backend/images/excelicon.png');
            $file       = $this->media_storage->getImageURL('backend/images/excelicon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        } else {
            $thumb_file = $this->media_storage->getImageURL('backend/images/docsicon.png');
            $file       = $this->media_storage->getImageURL('backend/images/docsicon.png');
            $file_src   = $this->media_storage->getImageURL($result->dir_path . $result->img_name);
        }
//==============
        $output = '';
        $output .= "<div class='col-lg-2 col-sm-4 col-md-3 col-xs-6 img_div_modal image_div div_record_" . $result->id . "'>";
        $output .= "<div class='fadeoverlay'>";
        $output .= "<div class='fadeheight'>";
        $output .= "<img class='' data-fid='" . $result->id . "' data-content_type='" . $result->file_type . "' data-content_name='" . $this->media_storage->fileview($result->img_name) . "' data-is_image='" . $is_image . "' data-vid_url='" . $result->vid_url . "' data-img='" . $this->media_storage->getImageURL($result->dir_path . $result->img_name) . "'  data-thumb_img='" . $this->media_storage->getImageURL($result->thumb_path . $result->thumb_name) . "' src='" . $thumb_file .  "'>";
        $output .= "</div>";
        if ($is_video == 1) {
            $output .= "<i class='fa fa-youtube-play videoicon'></i>";
        }
        if ($is_image == 1) {
            $output .= "<i class='fa fa-picture-o videoicon'></i>";
        }
        if (!$is_gallery) {
            $output .= "<div class='overlay3'>";
            $output .= "<a href='#' class='uploadcheckbtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#detail' data-thumb_image='" . $thumb_file .  "' data-image='" . $file .  "' data-source='" . $file_src . "' data-media_name='" . $this->media_storage->fileview($result->img_name) . "' data-media_size='" . $result->file_size . "' data-media_type='" . $result->file_type . "'><i class='fa fa-navicon'></i></a>";            
            if ($this->rbac->hasPrivilege('media_manager', 'can_delete')) {
            $output .= "<a href='#' class='uploadclosebtn' data-record_id='" . $result->id . "' data-toggle='modal' data-target='#confirm-delete'><i class=' fa fa-trash-o'></i></a>";
            }            
            $output .= "<p class='processing'>" . $this->lang->line('processing') . "</p>";
            $output .= "</div>";
        }

        $output .= "</div>";
        if ($is_video == 1) {
            $output .= "<p class='fadeoverlay-para'>" . $result->vid_title . "</p>";
        } else {
            $output .= "<p class='fadeoverlay-para'>" . $this->media_storage->fileview($result->img_name) . "</p>";
        }
        $output .= "</div>";
        return $output;
//================
    }

    public function addVideo()
    {
        if (!$this->rbac->hasPrivilege('media_manager', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_error_delimiters('', '');

        $video_url = $this->input->post('video_url');

        if (isset($_FILES['file']) && $video_url == "") {
            $this->form_validation->set_rules('file', 'file', "callback_handle_upload_file[file]|trim|xss_clean");
        } else {
            $this->form_validation->set_rules('video_url', $this->lang->line('url'), 'required|trim|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $data = array(
                'video_url' => form_error('video_url'),
                'file'      => form_error('file'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            if (isset($_FILES['file']) && !empty($_FILES['file']) && $_FILES['file']['error'][0] == UPLOAD_ERR_OK) {

                $dir_path   = "uploads/gallery/media/";
                $thumb_path = "uploads/gallery/media/thumb/";

                $config['thumb_path']   = $thumb_path;
                $config['dir_path']     = $dir_path;
                $config['thumb_width']  = 200;
                $config['thumb_height'] = 130;
                $this->load->library('imageResize', $config);

                $responses = $this->imageresize->resize($_FILES["file"]);

                $response_array = array();
                if ($responses) {
                    $img_array  = array();
                    $validation = 1;

                    if ($validation == 1) {
                        foreach ($responses['images'] as $key => $value) {

                            $data = array(
                                'image'      => $value['name'],
                                'img_name'   => $value['store_name'],
                                'file_type'  => $value['file_type'],
                                'file_size'  => $value['file_size'],
                                'thumb_name' => $value['thumb_name'],
                                'thumb_path' => $value['thumb_path'],
                                'dir_path'   => $value['dir_path'],
                                'created_at' => date('Y-m-d H:i:s'),
                            );

                        
                            $insert_id         = $this->cms_media_model->add($data);
                            $data['record_id'] = $insert_id;
                            $img_array[]       = $data;
                        }
                        $response_array['status'] = 1;
                        $response_array['msg']    = $this->lang->line('success_message');

                    } else {
                        $response_array['status'] = 0;
                        $response_array['msg']    = $this->lang->line('extension_not_allowed');
                    }

                } else {
                    $response_array['status'] = 0;
                    $response_array['msg']    = $this->lang->line('something_went_wrong');
                }
                echo json_encode($response_array);

            } else {

                $url     = $this->input->post('video_url');
                $youtube = "https://www.youtube.com/oembed?url=" . $url . "&format=json";
                $curl    = curl_init($youtube);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                $return   = curl_exec($curl);
                $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                $response = array('status' => 0, 'msg' => $this->lang->line('something_went_wrong'));
                if ($httpcode == 200) {
                    $img_array = array();

                    $dir_path   = "uploads/gallery/youtube_video/";
                    $thumb_path = "uploads/gallery/youtube_video/thumb/";

                    $config['thumb_path']   = $thumb_path;
                    $config['dir_path']     = $dir_path;
                    $config['thumb_width']  = 200;
                    $config['thumb_height'] = 130;
                    $this->load->library('imageResize', $config);

                    $upload_response = $this->imageresize->resizeVideoImg($return);

                    if ($upload_response) {
                        $upload_response = json_decode($upload_response);
                        $data            = array(
                            'vid_url'    => $url,
                            'vid_title'  => $upload_response->vid_title,
                            'img_name'   => $upload_response->store_name,
                            'file_type'  => $upload_response->file_type,
                            'file_size'  => $upload_response->file_size,
                            'thumb_name' => $upload_response->thumb_name,
                            'thumb_path' => $upload_response->thumb_path,
                            'dir_path'   => $upload_response->dir_path,
                        );
                        $insert_id = $this->cms_media_model->add($data);
                        
                        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('file_upload_successfully'), 'error' => ''));                    
                        
                    } else {                         
                        
                        $data = array(                       
                            'msg'      => $this->lang->line('please_try_again'),
                        );
                         $array = array('status' => 0, 'error' => $data);
                        echo json_encode($array);
                        
                    }
                } else {                  
                    
                    $data = array(                       
                        'msg'      => $this->lang->line('please_fill_correct_youtube_video_link'),
                    );
                   
                    $array = array('status' => 0, 'error' => $data);
                    echo json_encode($array);
            
                }
                
            }
        }
    }

    public function handle_upload_file($field, $var)
    {
        $result = $this->filetype_model->get();

        if (isset($_FILES[$var])
            && !empty($_FILES[$var]['name'][0])
            && !empty($_FILES[$var]["type"][0])
            && $_FILES[$var]["size"][0] != 0 && file_exists($_FILES[$var]["tmp_name"][0])) {

            $file_type = $_FILES[$var]['type'][0];
            $file_size = $_FILES[$var]["size"][0];
            $file_name = $_FILES[$var]["name"][0];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', ($result->image_extension . "," . $result->file_extension))));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', ($result->image_mime . "," . $result->file_mime))));

            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload_file', $this->lang->line('file_type_not_allowed'));
                return false;
            } elseif (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_upload_file', $this->lang->line('file_type_not_allowed'));
                return false;
            } elseif ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_upload_file', $this->lang->line('file_size_shoud_be_less_than') .
                    number_format($result->file_size / 1048576, 2) . ' MB');
                return false;
            }

            return true;
        }
        $this->form_validation->set_message('handle_upload_file', $this->lang->line('please_choose_a_file_or_enter_youtube_video_link'));
        return false;
    }

}
