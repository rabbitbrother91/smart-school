<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


// Config variables

$config["generate_image_file"] = true;
$config["generate_thumbnails"] = true;
$config["image_max_size"] = 500; //Maximum image size (height and width)
$config["thumbnail_size"] = 200; //Thumbnails will be cropped to 200x200 pixels
$config["thumbnail_prefix"] = "thumb_"; //Normal thumb Prefix
$config["quality"] = 100; //jpeg quality
$config["random_file_name"] = false; //randomize each file name

