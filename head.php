<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php meta_tags(); ?>
	<script src="scripts/jquery.js" type="text/javascript"></script>
    <script src="scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="scripts/global.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
	<!--[if lt IE 7]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection"><![endif]-->
	<?php page_title(); ?>
</head>

<body class="<?php echo slug_name($_page_name); ?>">

<div id="container">
	<div id="hd"></div><!--end hd-->
	<div id="bd">
		<div id="content">
			<?php place_image_if_alt(); ?>
		</div><!--end content-->
		<div id="sidebar"></div><!--end sidebar-->
	</div><!--end bd-->
	<div id="ft">
		<p>footer text</p>
		<?php text_navigation(); ?>
		<p><a href="http://www.sesamecommunications.com">Orthodontic Web Site by Sesame Design&trade;</a></p>
	</div><!--end ft-->
	<div id="util"></div><!--end util-->
	<?php full_navigation($exclusions = array('Contact Us','Site Map', $include_sub=true)); ?><!--end nav-->
</div><!--end container-->