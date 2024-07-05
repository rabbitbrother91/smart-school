<?php defined('BASEPATH') or exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Site Under Maintenance</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;900&display=swap" rel="stylesheet">
    <?php $url = base_url() . '/backend/images/maintenance.jpg'?>

    <style>

      body { text-align: center; font-family: 'Roboto', sans-serif; background:url('<?php echo $url; ?>') top center no-repeat; background-size:cover;color: #333;background-attachment: fixed; }
      h1 { font-size: 50px; font-weight: bold;margin: 0; }
      article { display: flex; justify-content: center; align-items: center; flex-direction: column; height: 50vh;}
      @media(max-width: 767px){
        body{padding:0px 20px;}
        article{max-width: 100%;height: 100%;}
      }
    </style>

</head>
<body class="bg">
    <article>
        <h1>Site Under Maintenance</h1>
        <p>Sorry for the inconvenience. To improve our services, we have momentarily shutdown our site.</p>
    </article>
</body>
</html>