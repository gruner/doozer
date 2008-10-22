<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php meta_tags(); ?>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script>
	<script src="http://2.scripts.sesamehost.com/scripts/jquery.flash.js" type="text/javascript"></script>
	<script src="http://3.scripts.sesamehost.com/scripts/jquery.sifr.min.js" type="text/javascript" ></script>
	<script src="http://4.scripts.sesamehost.com/scripts/jquery.pngFix.js" type="text/javascript"></script>
	<script src="http://5.scripts.sesamehost.com/scripts/jquery.nospam.js" type="text/javascript"></script>
	<script src="http://6.scripts.sesamehost.com/scripts/jquery.fancybox.js" type="text/javascript"></script>
	<script src="scripts/global.js" type="text/javascript"></script>
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="css/main.css" media="screen" rel="stylesheet" type="text/css" />
	<!--[if lt IE 7]><link rel="stylesheet" href="css/ie6.css" type="text/css" media="screen, projection"><![endif]-->
	<?php page_title(); ?>
</head>

<body class="<?php echo slug_name($_page_name); ?>">

<div id="container">
	<div id="hd">
		<h1 id="logo"><a href="index.php">Practice Name</a></h1>
	</div><!--end hd-->
	<div id="bd">
		<div id="content">
			<?php place_image_if_alt(); ?>
			<?php if($_top_link){echo '<p class="bottom"><a href="#hd">Back to top</a></p>'; } ?>
		</div><!--end content-->
		<div id="sidebar"></div><!--end sidebar-->
	</div><!--end bd-->
	<div id="ft">
		<p>footer text</p>
		<?php text_navigation(); ?>
		<p><a href="http://www.sesamecommunications.com">Orthodontic Web Site by Sesame Design&trade;</a></p>
	</div><!--end ft-->
	<div id="util"></div><!--end util-->
	<?php navigation($exclusions = array('Contact Us','Site Map'), $include_sub=true); ?><!--end nav-->
</div><!--end container-->