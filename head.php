<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="<?php meta_keywords() ?>" />
	<meta name="description" content="<?php meta_description() ?>" />
	<title><?php page_title() ?></title>
	<script src="scripts/swfobject.js" type="text/javascript"></script>
	<script src="scripts/global.js" type="text/javascript"></script>
	<link href="images/favicon.ico" rel="shortcut icon" />
	<link href="stylesheets/blueprint/screen.css" media="screen" rel="stylesheet" type="text/css"/>
    <link href="stylesheets/blueprint/print.css" media="print" rel="stylesheet" type="text/css"/>
	<link href="styles.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class='container' id='hd'>
      <div id='util'>
        <p><a href="contact-us.php">Contact Us</a> | <a href="index.php">Home</a></p>
      </div>
      <a href="index.php" class="logo"><img src="images/frey-logo.gif" alt="Frey Orthodontics - A Future To Smile About" width="525" height="75" /></a></div>
    <!--end hd-->
    <div class='container' id='nav_main'>
        <?php main_navigation() ?>
    </div>
    <!--end nav_main-->
    <div class='container' id='bd'>
      <div class='column' id='sidebar'>
        <?php include_once('sidebar.php') ?>
        <img src="images/credentials.gif" width="210" height="138" />
      </div>
      <!--end sidebar-->
      <div class='column last' id='content'>