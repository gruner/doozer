<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="<?php meta_keywords() ?>" />
	<meta name="description" content="<?php meta_description() ?>" />
	<title><?php page_title($_section, $_page_name) ?></title>
	<script src="scripts/swfobject.js" type="text/javascript"></script>
	<script src="scripts/global.js" type="text/javascript"></script>
	<link href="images/favicon.ico" rel="shortcut icon" />
	<link href="stylesheets/main.css" media="screen" rel="stylesheet" type="text/css" />
	<!--[if IE]><link rel="stylesheet" href="stylesheets/ie.css" type="text/css" media="screen, projection"><![endif]-->
</head>

<body>
<?php main_navigation($_section) ?>
<?php sub_navigation($_section, $_page_name) ?>
<?php text_navigation() ?>