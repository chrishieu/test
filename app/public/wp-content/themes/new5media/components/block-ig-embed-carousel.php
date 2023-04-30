<?php

$title = get_field('title');
$description = get_field('description');
$background_color = get_field('background_color');
$text_color = get_field('text_color');
$insta_scripts = get_field('insta_scripts');

?>
<div class="module-carousel-has-bg module-ig-embed-carousel" style="background-color: <?php echo $background_color; ?>; color: <?php echo $text_color; ?>">
    <div class="container-fluid">
        <div class="row title-wrap">
            <div class="col-xs-12">
                <h3><?php echo $title; ?></h3>
                <p><?php echo $description; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="list-wrap">
                    <div class="arrows-custom prev"></div>
                    <div class="arrows-custom next"></div>
                    <ul class="dots-custom"></ul>
                    <div class="list">
                        <?php foreach($insta_scripts as $script): ?>
                            <div class="item">
                                <div class="ig-wrap">
                                    <?php echo $script['script']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>