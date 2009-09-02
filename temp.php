<?php
ini_set('display_errors',1); 
error_reporting(E_ALL);

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
	'index_pages' => true,
	'test_config' => 'from config'
);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>untitled</title>
	
</head>

<body>

<p><?php $dz->title_keywords(); ?></p>
<p><?php $dz->test_helper(); ?></p>
<p><?php $dz->page(); ?></p>
<p><?php $dz->test_with_param('Hello from awesomeness!'); ?></p>
<?php $dz->content(); ?>
<?php # $dz->content('sidebar'); # echoes '$_sidebar' var defined on the page ?>
<?php $dz->image_tag('test-png.png'); ?>
</body>
</html>