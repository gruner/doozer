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
	<?php print_meta_tags(); ?>
	<link href="example.css" media="screen" rel="stylesheet" type="text/css" />
	<?php print_page_title(); ?>
</head>
<body class="<?php echo slug_name($_page_name); ?>">

<h2>print_sitemap()</h2>
<?php print_sitemap(); ?>
</body>
</html>