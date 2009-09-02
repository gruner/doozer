<?php
require_once('doozer.php');

/*
|===============================================================
| SITE CONFIGURATION
|===============================================================
*/
$dz->config = array(
	'meta_keywords' => 'paste, the, site, keywords, here',
	'meta_description' => 'paste the site description text here',
	'title' => 'City ST - Orthodontist name - State zip',
	'title_keywords' => 'Braces Orthodontics',
	'site_name' => 'Practice Name',
	'index_pages' => = true
));

/*
|===============================================================
| DEFINE THE SITEMAP
|===============================================================
*/
$dz->sitemap = array(
	// yada yada
));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php $dz->meta_tags(); ?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
	<!--[if lt IE 7]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection"><![endif]-->
	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script>
	<script src="http://2.scripts.sesamehost.com/scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="http://3.scripts.sesamehost.com/scripts/jquery.sifr.min.js" type="text/javascript" ></script>-->
	<script src="http://localhost/_lib/scripts/jquery-1.2.6.js" type="text/javascript"></script>
	<script src="http://localhost/_lib/scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="http://localhost/_lib/scripts/jquery.sifr.min.js" type="text/javascript" ></script>
	<script src="scripts/global.js" type="text/javascript"></script>
	<?php $dz->title(); ?>
</head>

<body class="<?php $dz->slug(); ?>">

<div id="container">
	<div id="hd" class="container">
		<h1 id="logo"><a href="index.php"><?php $dz->site_name(); ?></a></h1>
	</div><!--/hd-->
	<div id="bd" class="container">
	  <div id="sidebar" class="column">
	  	<?php $dz->content('sidebar'); ?>
	  </div><!--/sidebar-->
		<div id="content" class="column">
			<?php $dz->conditional_image(); ?>
			<?php $dz->content(); ?>
		</div><!--/content-->
	</div><!--/bd-->
	<div id="ft" class="container">
		<p></p>
		<?php $dz->text_nav(); ?>
		<p class="small"><strong><a href="http://www.sesamewebdesign.com">Orthodontic Web Site by Sesame Design&trade;</a></strong></p>
	</div><!--/ft-->
	<div id="util"></div><!--/util-->
	<?php $dz->nav($exclusions = array('Contact Us','Site Map'), $include_sub=true); ?><!--/nav-->
</div><!--/container-->

</body>
</html>