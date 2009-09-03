<?php
// ini_set('display_errors',1); 
// error_reporting(E_ALL);

require_once('Doozer/Doozer.php');

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
	'sidebar' => '<h1>Sidebar</h1>',
	'index_pages' => true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php $dz->meta_tags(); ?>

	<title>untitled</title>
	
</head>

<body>
<?php $dz->meta_tags(); ?>
<p><?php $dz->title_keywords(); ?></p>
<p><?php $dz->test_helper(); ?></p>
<p><?php $dz->page(); ?></p>
<p><?php $dz->test_with_param('Hello from awesomeness!'); ?></p>
<?php $dz->content(); ?>

<?php $dz->content('sidebar'); ?>
<?php $dz->sidebar(); ?>

<?php $dz->image_tag('test-png.png'); ?>

</body>
</html>