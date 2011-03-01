<?php require_once('lib/doozer/doozer.php') ?>
<?php
  $page_slug = ''; // get slug form url
  
  // if file matches slug
  if (is_file)
  {
  	# code...
  }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php echo meta_tags() ?>
		<?php echo title_tag() ?>
	</head>
	<body class="<?php echo slug_name($_name); ?>">
		<div id="container">
			<div id="hd" class="container">
				<h1 id="logo"><a href="index.php"><?php echo get_site_name() ?></a></h1>
			</div><!--/hd-->
			<div id="bd" class="container">
				<div id="sidebar" class="column">
					<?php echo content($_sidebar_content); ?>
				</div><!--/sidebar-->
				<div id="content" class="column">
					<?php echo ie6_alert() ?>
					<?php echo headline_tag() ?>
					<?php echo place_image_if_alt() ?>
					<?php echo content() ?>
				</div><!--/content-->
			</div><!--/bd-->
			<div id="ft" class="container">
				<?php echo text_navigation() ?>
			</div><!--/ft-->
			<?php echo navigation($exclusions = array('Contact Us','Site Map'), $include_sub=true) ?><!--/nav-->
		</div><!--/container-->
	</body>
</html>