<h1><?php echo $page['title']; ?></h1>
<p><?php echo $page['description']; ?></p>
<div class="mediarow spaceb50">
    <div class="row">
        <?php
if (isset($page['page_contents'])) {
    echo "<div class='gallery'>";
    foreach ($page['page_contents'] as $page_content_key => $page_content_value) {

        $url = base_url($page_content_value->dir_path . $page_content_value->img_name);

        if ($page_content_value->file_type == "video") {
            $url = $page_content_value->vid_url;
        }
        ?>
                <div class="col-sm-6 col-md-4 col-lg-4 img_div_modal">
                    <div class="galleryfancy">
                        <div class="gallheight">
                            <a href="<?php echo $url ?>" data-toggle="lightbox" data-gallery="mixedgallery"  data-title="a" >
                                <img  alt="" src="<?php echo $url ?>">
                                <div class="content-overlay"></div>
                                <div class="overlay-details fadeIn-bottom">
                                    <i class="fa fa-search-plus"></i>
                                </div></a>
                        </div>
                    </div>
                </div><!--./col-md-3-->
                <?php
}
    echo "</div>";
}
?>
    </div>
</div>