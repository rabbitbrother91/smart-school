<?php
if (!empty($page_content_type)) {
    ?>

    <?php
if ($page_content_type == "events") {

        $numOfCols         = 3;
        $rowCount          = 0;
        $bootstrapColWidth = 12 / $numOfCols;
        ?>
        <div class="row">
            <?php
foreach ($category_content as $key => $value) {
            ?>
                <div class="col-md-<?php echo $bootstrapColWidth; ?> col-sm-<?php echo $bootstrapColWidth; ?>">
                    <div class="eventbox">
                        <a href="<?php echo site_url($value['url']); ?>">
                            <?php
if ($value['feature_image'] == "") {
                $feature_image = base_url('uploads/gallery/gallery_default.png');
            } else {
                $feature_image = $value['feature_image'];
            }
            ?>
                            <img src="<?php echo $feature_image; ?>" alt="" title="">
                            <div class="around20">
                                <h3><?php echo $value['title']; ?></h3>
                                <p><?php echo substr($value['description'], 0, 85) . ".."; ?></p>
                            </div><!--./around20-->
                        </a>
                    </div><!--./eventbox-->
                </div>
                <?php
$rowCount++;
            if ($rowCount % $numOfCols == 0) {
                echo '</div><div class="row">';
            }
        }
        ?>
        </div>

        <?php
} elseif ($page_content_type == "gallery") {
        $numOfCols         = 3;
        $rowCount          = 0;
        $bootstrapColWidth = 12 / $numOfCols;
        ?>
        <div class="row">
            <?php
foreach ($category_content as $key => $value) {
            ?>
                <div class="col-md-<?php echo $bootstrapColWidth; ?> col-sm-<?php echo $bootstrapColWidth; ?>">
                    <div class="eventbox">
                        <a href="<?php echo site_url($value['url']); ?>">
                            <?php
if ($value['feature_image'] == "") {
                $feature_image = base_url('uploads/gallery/gallery_default.png');
            } else {
                $feature_image = $value['feature_image'];
            }
            ?>
                            <img src="<?php echo $feature_image; ?>" alt="" title="">
                            <div class="around20">
                                <h3><?php echo $value['title']; ?></h3>
                                <p><?php echo substr(htmlspecialchars($value['description']), 0, 85) . ".."; ?></p>
                            </div><!--./around20-->
                        </a>
                    </div><!--./eventbox-->
                </div>
                <?php
$rowCount++;
            if ($rowCount % $numOfCols == 0) {
                echo '</div><div class="row">';
            }
        }
        ?>
        </div>

        <?php
}

    echo $this->ajax_pagination->create_links(); //pagination link
}
?>