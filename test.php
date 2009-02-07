<?php
ini_set('display_errors', '1');
error_reporting(E_WARNING);
#error_reporting(E_ALL);
$_section = 'Braces 101';
$_page_name = 'Life with Braces&reg;';
$_keyword = 'Invisalign';
$_page_title = '[this text replaces the base title]';
$_alt = 'this string will be the default alt text when using the place_image() function';

$logr = array();

function logr($str)
{
  global $logr;
  $logr[] = $str;
}

require_once('global.php');

function test($code, $comments='')
{
  print "<h2>$code</h2>\n";
  if($comments){print "<p class=\"quiet\">$comments</p>\n";}
  print "<p>\n";
  eval("$code;");
  print "<p>\n<hr/>\n";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php print_meta_tags(); ?>
	<link href="example.css" media="screen" rel="stylesheet" type="text/css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js" type="text/javascript"></script>
	<?php print_page_title(); ?>
	<script type="text/javascript">
    $(document).ready(function(){
  		$('#nav-with-sub li.first').append('<span class="quiet">  < first</span>');
  		$('#nav-with-sub li.last').append('<span class="quiet">  < last</span>');
  	});//end document.ready
  </script>
</head>
<body class="<?php echo slug_name($_page_name); ?>">

<h1>PHP Framework</h1>

<?php 

test('echo get_site_name()', 'gets the value of "site_name" defined in config.php'); 

test("print_navigation(\$exclusions = array('Contact Us', 'Site Map'))");
test("print_navigation(\$exclusions = array('Contact Us', 'Site Map'), \$include_sub_nav=true, \$div_id='nav-with-sub')");

test("print_sub_navigation()");
test("print_sub_navigation_with_heading()");
test("print_sub_navigation_with_heading('', '', 'h1')");

test("sub_nav_p()");
test("sub_nav_p(array(1,3))", 'break the string after the first and third items');
test("sub_nav_p('', ' >>>> ')", 'change the text that separates items');
test("sub_nav_p(array(3),' &bull; ')");

test("text_navigation()");
test("text_navigation(4)");
test("text_navigation(0, \$exclude=array('Contact Us'))");
test("text_navigation(3, \$exclude=array('The Game Room'))");

test("print_sitemap()");
test("print_sitemap(\$exclude=array('Contact Us', 'About Orthodontics'))");

test("breadcrumbs()");

$slug_tests = array('TMJ/TMD', 'Invisalign&reg;', 'Damon&trade;', 'Why Braces?');
foreach ($slug_tests as $test){
  test("echo slug_name('$test')");
}

test('place_image("alf.jpg")');
test('place_image("alf")', 'works without the file extension if there is a corresponding gif, jpg, or png');
test('place_image("test-png")');
test('place_image("test-gif")');
test('place_image("test-jpg")');
test('place_image("non-existing-file")', "doesn't output anything if it can't find the file");


?>

<h2>print_r($sitemap)</h2>
<?php
  $sitemap = parse_sitemap();
	print_r($sitemap);
?>

<h2>Log</h2>
<?php print_r($logr); ?>

</body>
</html>