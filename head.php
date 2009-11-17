<?php
//include_once('includes/ie6_alert.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php echo meta_tags(); ?>
	<?php echo title_tag(); ?>
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
	<script src="http://2.scripts.sesamehost.com/scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="http://4.scripts.sesamehost.com/scripts/jquery.cookie.js" type="text/javascript" ></script>
	<script src="scripts/global.js" type="text/javascript"></script>
</head>

<body class="<?php echo slug_name($_page_name); ?>">

<div id="container">
	<div id="hd">
		<h1 id="logo"><a href="index.php"><?php echo get_site_name(); ?></a></h1>
	</div><!--/hd-->
	<div id="bd">
	  <div id="sidebar">
			<?php content($_sidebar_content); ?>
	  </div><!--/sidebar-->
		<div id="content">
			<?php if($ie6_alert){ echo $ie6_alert_box; } ?>
			<?php echo ie6_alert(); ?>
			<?php echo headline_tag(); ?>
			<?php echo place_image_if_alt(); ?>