<?php
    global $header_title;
    global $post;
    $category_info = fivemedia_get_category_link($post);
    $header_title = '5 Toolkit';
    include "single-curated.php";