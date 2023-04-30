<?php
    global $header_title;
    global $post;
    $category_info = fivemedia_get_category_link($post);
    // $header_title  =  'High 5 / '.$category_info['name'];
    $header_title = 'High 5';
    include "single-curated.php";