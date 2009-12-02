<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php echo meta_tags(); ?>
	<?php echo title_tag(); ?>

	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="css/main.css" />
	<!--[if lt IE 7]><link rel="stylesheet" href="css/ie6.css"><![endif]-->

	<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script src="http://2.scripts.sesamehost.com/scripts/jquery.flash.js"></script>
	<script src="http://4.scripts.sesamehost.com/scripts/jquery.cookie.js"></script>-->

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script src="http://localhost/_lib/scripts/jquery-1.3.2.js" type="text/javascript"></script>
	<script src="http://localhost/_lib/scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="scripts/global.js"></script>
</head>

<body class="<?php echo slug_name($_name); ?>">
<div id="container">
	<div id="hd" class="container">
		<h1 id="logo"><a href="index.php"><?php echo get_site_name(); ?></a></h1>
	</div><!--/hd-->
	<div id="bd" class="container">
		<div id="sidebar" class="column">
			<?php content($_sidebar_content); ?>
		</div><!--/sidebar-->
		<div id="content" class="column">
			<?php echo ie6_alert(); ?>
			<?php echo headline_tag(); ?>
			<?php echo place_image_if_alt(); ?>