<?php /* Template Name: Landingpage */ ?>
<?php
global $post;
$year = get_field('year', $post);
$hero_image = get_field('hero_image', $post);
$text = get_field('text', $post);
$keywords = get_field('keywords', $post);
$highlight_text = get_field('highlight_text', $post);

foreach ($keywords as $k => $v) {
	$v = $v['keyword'];
	$v_new = '<span class="align-underline">'.$v.'<img class="line" src="https://alkf.alignlab.com/asset/image/line-home1.png" alt=""/></span>';
	if (stripos($text, $v) !== false) {
		$text = str_replace($v, $v_new, $text);
	}		
}


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Cache-control" content="no-cache" />

		<link rel="icon" type="image/png" href="https://alkf.alignlab.com/asset/favicon/favicon.png" />

		<link rel="stylesheet" href="https://alkf.alignlab.com/asset/font/graphiklcg/style.css" />
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/www/css/style.css" />

		<title>ALKF</title>
	</head>
	<body>
		<header class="header headerJS">
			<div class="container">
				<div class="header-wrap">
					<a href="#" class="header-logo">
						<img src="https://alkf.alignlab.com/asset/image/logo-header.svg" alt="" />
					</a>
					<div class="header-quote">Design built on legacy</div>
					<div class="header-cta">
						<a href="#">Start a project</a>
						<a href="#">Works</a>
					</div>
					<div class="header-btn">
						<div class="header-search">
							<form action="#" method="get">
								<label class="header-search-wrap">
									<div class="header-search-icon">
										<img src="https://alkf.alignlab.com/asset/image/icon-search.svg" alt="" />
									</div>
									<input
										type="search"
										name="s"
										class="rs-form header-search-inp"
										placeholder=""
									/>
								</label>
							</form>
						</div>

						<div class="header-lang">
							<span class="lang-active">EN</span>
							<div class="lang-list">
								<a href="#" class="lang-item">FR</a>
								<a href="#" class="lang-item">VN</a>
							</div>
						</div>

						<div class="header-menu-btn headerOpen">
							<div class="line"></div>
							<div class="line"></div>
							<div class="line"></div>
							<div class="line"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="header-menu">
				<div class="container">
					<div class="header-menu-wrap">
						<div class="header-menu-left">
							<div>
								<a href="#" class="header-logo">
									<img src="https://alkf.alignlab.com/asset/image/logo-header.svg" alt="" />
								</a>
							</div>
							<div class="header-menu-close headerClose">
								<img src="https://alkf.alignlab.com/asset/image/icon-close.svg" alt="" />
							</div>
							<div class="header-menu-info">
								<div class="title-h2">Contact</div>
								<div class="content">
									<a href="#">architect@alkf.com</a>
									<a href="#">(852) 2525 0008</a>
								</div>
							</div>
							<div class="header-menu-info">
								<div class="title-h2">Office</div>
								<div class="content">
									19/F, Universal Trade Centre <br />
									3 Arbuthnot Road <br />
									Central, Hong Kong
								</div>
							</div>
						</div>
						<div class="header-menu-right">
							<div class="menu">
								<ul>
									<li>
										<a href="#">Insight</a>
									</li>
									<li>
										<a href="#">Careers</a>
									</li>
									<li>
										<a href="#">About</a>
									</li>
									<li>
										<a href="#">Project</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		<div class="align-loader loaderJS"></div>

		<main class="main">
			<div class="hero heroJS scrollTrigger">
				<div class="container">
					<div class="heroWrapper">
						<div class="hero-wrap section-pri">
							<div class="hero-top">
								<h1 class="title-h1 hero-title">
									<?php echo $text; ?>
								</h1>
								<div class="hero-year"><?php echo $year; ?></div>
							</div>
							<div class="hero-img">
								<img class="heroImg" src="<?php echo $hero_image['url']; ?>" alt="image" />
							</div>
						</div>
					</div>
					<?php foreach($highlight_text as $hi): ?>
					<div class="hero-txt">
						<div class="big"><?php echo $hi['title']; ?></div>
						<div class="small">
							<?php echo $hi['text']; ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php echo apply_filters("the_content", $post->post_content); ?>
		</main>

		<footer class="footer">
			<div class="container">
				<div class="footer-row section-pri">
					<div class="col-4">
						<div class="title-h2">Choose your own signatures. Contact us.</div>
					</div>
					<div class="col-4">
						<div class="footer-info">
							<div class="title-h2">
								<span class="align-underline active">
									Contact
									<img
										src="https://alkf.alignlab.com/asset/image/line-home13.png"
										alt=""
										class="line"
									/>
								</span>
							</div>
							<div class="content">
								<a href="#">architect@alkf.com</a>
								<a href="#">(852) 2525 0008</a>
							</div>
						</div>
					</div>
					<div class="col-4">
						<div class="footer-info">
							<div class="title-h2">
								<span class="align-underline active">
									Office
									<img
										src="https://alkf.alignlab.com/asset/image/line-home13.png"
										alt=""
										class="line"
									/>
								</span>
							</div>
							<div class="content">
								19/F, Universal Trade Centre <br />
								3 Arbuthnot Road <br />
								Central, Hong Kong
							</div>
						</div>
					</div>
				</div>
				<div class="footer-bottom">
					<div class="footer-copyright">
						© 2022 Andrew Lee King Fun & Associates Architects Ltd.
					</div>
					<div class="footer-url">
						<a href="#"> Back to top</a>
						<a href="#"> Policy</a>
						<a href="#"> Site by Align</a>
					</div>
				</div>
			</div>
		</footer>

		<script type="module" src="<?php echo get_stylesheet_directory_uri() ?>/www/js/script.js" defer></script>
	</body>
</html>
