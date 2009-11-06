<?php
ini_set('display_errors', '1');
#error_reporting(E_WARNING);
error_reporting(E_ALL);
$_section = 'Braces 101';
$_page_name = 'Life with Braces&reg;';
$_keywords = 'Invisalign';
$_title = '[this text replaces the title]';
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
  echo "<h3 class=\"code\">$code</h3>\n";
  if($comments){print "<p class=\"quiet\">$comments</p>\n";}
  echo "<div>\n";
  eval("$code;");
  echo "</div>\n<hr/>\n";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php echo meta_tags(); ?>
	<link rel="stylesheet" href="example.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<?php echo title_tag(); ?>

	<script>
    $(document).ready(function(){
  		$('li.first').append('<span class="quiet">  < first</span>');
  		$('li.last').append('<span class="quiet">  < last</span>');
  		$('#nav-with-sub a[href^="http://"]').after('<span class="quiet"> (external)</span>');
  		//$('a.head').after('<span class="quiet"> < head </span>');
  	});//end document.ready
  </script>

</head>

<body class="<?php echo slug_name($_page_name); ?>">

<h1>PHP Framework</h1>

<p>This is an example page illustrating the available php functions that utilize the site stucture defined in <code>sitemap.php</code>.</p>

<p>Using our existing approach of defining <code>$page</code> and <code>$section</code> variables for each page (The framework uses the names <code>$_page_name</code>, and <code>$_section</code>), we can call the following functions from each page and get dynamically generated navigation elements.</p>

<p>These functions are defined in <code>global.php</code>. To enable this functionality simply include <code>global.php</code> on every page before calling the header include. Then define the site structure as an array in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>

<h2>Naming Conventions</h2>

<p>'Slug Name' is a term used in blogging and CMS software for stripping spaces and special characters from a page title to use as a file name or in a URL. The framework relies on php files that follow this naming contention. (e.g. A page titled 'Life with Braces' would be named 'life-with-braces.php').</p>

<h2>Examples</h2>

<p>This page lists PHP commands as headers followed by the output of that exact code. The "&lt; first" and "&lt; last" tags are being added with jQuery to li.first and li.last so you don't have to view the source to see the class names. A similar visualization is being done with external links. The .active class is highlighted in yellow.</p>

<p>To illustrate these examples, this example page has the variables <code>$_section='Braces 101'</code> and <code>$_page_name='Life with Braces'</code>.</p>

<hr />

<?php

test("print_navigation(\$exclusions = array('Contact Us', 'Site Map'))");
test("print_navigation(\$exclusions = array('Contact Us', 'Site Map'), \$include_sub_nav=true, \$div_id='nav-with-sub')");

test("print_inclusive_navigation(array('Home','Patient Login'))", 'print a navigation div with only the specified nav items, excludes everything else');

test("print_sub_navigation()");
test("print_sub_navigation(\$section='Nested')");
test("print_sub_navigation_with_heading()");
test("print_sub_navigation_with_heading('', \$link=true)", 'adds a linked heading');
test("print_sub_navigation_with_heading('', '', 'h1')");

test("sub_nav_p()");
test("sub_nav_p(2)");
test("sub_nav_p(array(2))");
test("sub_nav_p(array(1,3))", 'break the string after the first and third items');
test("sub_nav_p('', ' >>>> ')", 'change the text that separates items');
test("sub_nav_p(array(3),' &bull; ')");
test("sub_nav_p('', '', '', 'Nested')");

test("print_text_navigation()");
test("print_text_navigation(4)");
test("print_text_navigation(array(4))");
test("print_text_navigation(array(2,5))");
test("print_text_navigation(0, \$exclude=array('Contact Us'))");
test("print_text_navigation(4, \$exclude=array('Test'), ' **** ')");

test("print_sitemap()");
test("print_sitemap(\$exclude=array('Contact Us', 'About Orthodontics'))");

test("print_breadcrumbs()");
test("print_breadcrumbs(' ++ ')", 'specify custom separator string');

test('echo get_site_name()', 'gets the value of "site_name" defined in config.php');

test('echo h1_tag()', 'Creates an h1 tag. Uses $_h1 variable for the text (if defined) but defaults to $_page_name variable.');
test('echo h1_tag()');

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

<!--<h2>print_r($sitemap)</h2>-->
<?php
  # $sitemap = parse_sitemap();
	# print_r($sitemap);
?>

<h2>Log</h2>
<?php print_r($logr); ?>

</body>
</html>