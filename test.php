<?php
$_section = 'Braces 101';
$_page_name = 'Life with Braces&reg;';
$_keyword = 'Invisalign';
$_page_title = '[this text replaces the base title]';
$_alt = 'this string will be the default alt text when using the place_image() function';
require_once('global.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php meta_tags(); ?>
	<link href="example.css" media="screen" rel="stylesheet" type="text/css" />
	<?php page_title(); ?>
</head>
<body class="<?php echo slug_name($_page_name); ?>">

<h1>PHP Framework</h1>

<h2>slug_name()</h2>
<p>
<?php $slug_tests = array('TMJ/TMD', 'Invisalign&reg;', 'Damon&trade;', 'Why Braces?');
foreach ($slug_tests as $test){
  $slug = slug_name($test);
  echo "$test => $slug<br/>";
}
?>
</p>

<h2>get_practice_name()</h2>
<p><?php echo get_practice_name(); ?></p>

<h2>navigation()</h2>
<?php navigation($exclusions = array('Contact Us','Site Map')); ?>
<?php navigation($exclusions = array('Contact Us','Site Map'), $include_sub_nav=true, $div_id='nav-with-sub'); ?>

<hr />
<h2>sub_navigation()</h2>
<?php sub_navigation(); ?>

<hr />
<h2>sub_nav_p()</h2>
<?php sub_nav_p(); ?>
<?php sub_nav_p(array(3,7)); ?>
<?php sub_nav_p(array(5),' &bull; '); ?>

<hr />
<h2 class="clear">text_navigation()</h2>
<?php text_navigation(); ?>
<?php text_navigation(4); ?>
<?php text_navigation(0, array('Contact Us')); ?>
<?php text_navigation(3, array('The Game Room')); ?>

<hr />
<h2>render_sitemap()</h2>
<?php render_sitemap(); ?>

<hr />
<h2>sitemap(array('About Orthodontics'))</h2>
<?php sitemap(array('Contact Us', 'About Orthodontics')); ?>

<hr />
<h2>section_index()</h2>
<?php section_index(); ?>

<hr />
<h2>breadcrumbs()</h2>
<?php breadcrumbs(); ?>

<hr />
<h2>place_image('alf.jpg')</h2>
<?php place_image("alf.jpg"); ?>

<h2>place_image('alf')</h2>
<?php place_image("alf"); ?>

<h2>place_image('test-png')</h2>
<?php place_image("test-png"); ?>

<h2>place_image('test-gif')</h2>
<?php place_image("test-gif"); ?>

<h2>place_image('test-jpg')</h2>
<?php place_image("test-jpg"); ?>

<h2>place_image('no-exist')</h2>
<?php place_image("no-exist"); ?>
</body>
</html>