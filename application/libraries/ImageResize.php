<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ImageResize
{

    protected $dir_path         = "";
    protected $thumb_path       = "";
    protected $maintain_ratio   = true;
    protected $create_thumb     = true;
    protected $thumb_marker     = 'thumb_';
    protected $thumb_width      = 150;
    protected $thumb_height     = 150;
    protected $random_file_name = false;
    protected $files            = array();
    private $response;

    public function __construct($props = array())
    {
        //set local vars
        $this->CI = &get_instance();
        if (count($props) > 0) {
            $this->initialize($props);
        }
    }

    public function initialize($props = array())
    {
        // Convert array elements into class variables
        if (count($props) > 0) {
            foreach ($props as $key => $val) {

                $this->$key = $val;

            }
        }

        return true;
    }

    //resize function
    public function resize($files)
    {      

        $this->files      = $files;
        $this->file_count = count($this->files['name']);
        $this->response   = array();
        //================
        if ($this->file_count > 0) {
            if (!is_array($this->files['name'])) {
                throw new Exception('HTML file input field must be in array format!');
            }
            for ($x = 0; $x < $this->file_count; $x++) {
                //========
                $file_mime_type = mime_content_type($this->files['tmp_name'][$x]);

                if ($file_mime_type != 'inode/x-empty' || $this->files['size'][$x] > 0) {

                    $this->curr_tmp_name = $this->files['tmp_name'][$x];
                    $name                = $this->files['name'][$x];
                    $unique_name         = time() . "-" . uniqid(rand()) . "!";
                    
                    $this->new_file_name = $unique_name . $name;

                    $fileName = $this->new_file_name;
                    //upload image path
                    $upload_image =  $this->CI->customlib->getFolderPath().$this->dir_path . basename($fileName);
                    //upload image
                    if (move_uploaded_file($this->curr_tmp_name, $upload_image)) {
                        //thumbnail creation
                        $this->image_size_info = filesize($upload_image);
                        $thumb_name            ="";
                        if ($this->create_thumb) {
                            if ($file_mime_type == 'image/jpeg' || $file_mime_type == 'image/png' || $file_mime_type == 'image/gif') {
                                $thumb_name            = $unique_name . $this->thumb_marker . $name;

                                $arr_image_details = getimagesize($upload_image); // pass id to thumb name

                                $original_width  = $arr_image_details[0];
                                $original_height = $arr_image_details[1];
                                if ($original_width > $original_height) {
                                    $new_width  = $this->thumb_width;
                                    $new_height = intval($original_height * $new_width / $original_width);
                                } else {
                                    $new_height = $this->thumb_height;
                                    $new_width  = intval($original_width * $new_height / $original_height);
                                }
                                $dest_x = intval(($this->thumb_width - $new_width) / 2);
                                $dest_y = intval(($this->thumb_height - $new_height) / 2);
                                if ($arr_image_details[2] == IMAGETYPE_GIF) {
                                    $imgt          = "ImageGIF";
                                    $imgcreatefrom = "ImageCreateFromGIF";
                                }
                                if ($arr_image_details[2] == IMAGETYPE_JPEG) {
                                    $imgt          = "ImageJPEG";
                                    $imgcreatefrom = "ImageCreateFromJPEG";
                                }
                                if ($arr_image_details[2] == IMAGETYPE_PNG) {
                                    $imgt          = "ImagePNG";
                                    $imgcreatefrom = "ImageCreateFromPNG";
                                }
                                if ($imgt) {
                                    $old_image = $imgcreatefrom($upload_image);
                                    $new_image = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
                                    imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
                                    $imgt($new_image, $this->CI->customlib->getFolderPath().$this->thumb_path . $thumb_name);
                                }

                            }
                            //to create thumbnail of image

                        }
                        $img_array = array(
                            'name'       => $name,
                            'store_name' => $this->new_file_name,
                            'file_type'  => $file_mime_type,
                            'file_size'  => $this->image_size_info,
                            'thumb_name' => $thumb_name,
                            'thumb_path' => $this->thumb_path,
                            'dir_path'   => $this->dir_path,
                            'height'     => 0,
                            'width'      => 0,
                        );

                        $this->response["images"][] = $img_array;
                    }
                }

                //=========
            }
        }
        return $this->response;
        //=================
    }

    public function resizeVideoImg($image_array)
    {     
        $img_data        = json_decode($image_array);
        $image           = $img_data->thumbnail_url;
        $title           = $img_data->title;
        $path_info       = pathinfo($image);
        $file_extenstion = '.' . $path_info['extension']; // "bill

        $contextOptions = array(
            "ssl" => array(
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ),
        );
        $unique_name = time() . "-" . uniqid(rand()) . "!";
        $filename    = time() . $file_extenstion;
        $this->new_file_name = $unique_name . $filename;
        $thumb_name = $unique_name . $this->thumb_marker . $filename;

        if (copy($image, $this->dir_path . '/' . $this->new_file_name, stream_context_create($contextOptions))) {
            $this->videoThumbnail($this->dir_path . '/' . $this->new_file_name, $this->CI->customlib->getFolderPath().$this->thumb_path . '/' . $thumb_name);
            return json_encode(array('vid_title' => $title, 'store_name' => $this->new_file_name, 'file_type' => 'video', 'file_size' => 0, 'thumb_name' => $thumb_name, 'thumb_path' => $this->thumb_path, 'dir_path' => $this->dir_path));
        }
        return false;
    }

    public function videoThumbnail($src, $dest)
    {
        $arr_image_details = getimagesize($src); // pass id to thumb name
        $original_width    = $arr_image_details[0];
        $original_height   = $arr_image_details[1];
        if ($original_width > $original_height) {
            $new_width  = $this->thumb_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $this->thumb_height;
            $new_width  = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($this->thumb_width - $new_width) / 2);
        $dest_y = intval(($this->thumb_height - $new_height) / 2);
        if ($arr_image_details[2] == IMAGETYPE_GIF) {
            $imgt          = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        if ($arr_image_details[2] == IMAGETYPE_JPEG) {
            $imgt          = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        if ($arr_image_details[2] == IMAGETYPE_PNG) {
            $imgt          = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if ($imgt) {
            $old_image = $imgcreatefrom($src);
            $new_image = imagecreatetruecolor($this->thumb_width, $this->thumb_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            $imgt($new_image, $dest);
        }

    }

}
