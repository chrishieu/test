<?php

$title = get_field('title');
$text = get_field('text');
$keywords = get_field('keywords');
foreach ($keywords as $k => $v) {
	$v_new = '<span class="align-underline align-hover">'.$v['kw'].
            '<img class="line" src="https://alkf.alignlab.com/asset/image/line-home5.png" alt="" />
            <div class="hover">
                <img src="'.$v['image']['url'].'" alt="" />
                <div class="info">
                    <div class="year">'.$v['year'].'</div>
                    <div class="title">'.$v['title'].'</div>
                </div>
            </div></span>';
	if (stripos($text, $v['kw']) !== false) {
		$text = str_replace($v['kw'], $v_new, $text);
	}		
}
?>
<section class="award section-pri scrollTrigger">
    <div class="container">
        <div class="award-wrap">
            <?php if($title): ?>
            <h2 class="title-h2 award-title"><?php echo $title; ?></h2>
            <?php endif; ?>

            <div class="award-content">
                <?php echo $text; ?>
            </div>
        </div>
    </div>
</section>
