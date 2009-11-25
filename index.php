<?php
ini_set('display_errors', '1');
#error_reporting(E_WARNING);
error_reporting(E_ALL);
$_section = 'Braces 101';
$_name = 'Life with Braces&reg;';
$_keywords = 'Invisalign';
//$_title = '[this text replaces the title]';
$_alt = 'this string will be the default alt text when using the place_image() function';
$_content = '<p>sidebar</p>';

$logr = array();

function logr($str)
{
  global $logr;
  $logr[] = $str;
}

require_once('global.php');

function example($code, $comments='')
{
  echo content_tag('div', example_inner($code, $comments), array('class' => 'example'));
}


function example_inner($code, $comments='')
{
  $html = content_tag('h3', $code, array('class' => 'code'));
  $html .= "\n";
  if (!empty($comments))
  {
    $html .= content_tag('p', $comments, array('class' => 'quiet'));
    $html .= "\n";
  }
  //$html .= content_tag('p', eval("$code;"));
  $html .= '<div>';
  $html .= eval("$code;");
  $html .= '</div>';
  $html .= "\n";
  return $html;
}

function heading($text)
{
  echo "<hr/>\n";
  echo content_tag('h2', $text);
  echo "\n";
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

<body class="<?php echo slug_name($_name); ?>">

<h1>Doozer &ndash; A PHP Framework</h1>

<p>This is an example page illustrating the available php functions that utilize the site structure defined in <code>sitemap.php</code>.</p>

<p>By defining <code>$_page</code> and <code>$_section</code> variables for each page, we can call the following functions from each page and get dynamically generated navigation elements.</p>

<p>These functions are defined in <code>global.php</code>. To enable this functionality simply include <code>global.php</code> on every page before calling the header include. Then define the site structure as an array in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>

<h2>Naming Conventions</h2>

<p>'Slug Name' is a term used in blogging and CMS software for stripping spaces and special characters from a page title to use as a file name or in a URL. The framework relies on php files that follow this naming contention. (e.g. A page titled 'About Us' would be named 'about-us.php').</p>

<h2>Configuration</h2>

<p>Several default values are defined in <code>config.php</code>. These include:</p>

<ul>
<li><code>meta_keywords</code> &ndash; the default meta keywords for every page</li>
<li><code>meta_description</code> &ndash; the default meta description for every page</li>
<li><code>page_title</code> &ndash; the default base page title for every page</li>
<li><code>site_name</code> &ndash; the name of the site, used in the page header</li>
</ul>

<h2>Page Variables</h2>

<p>Several special variables can be defined on each page that will override the default values. These include:</p>

<ul>
<li><code>$_section</code> &ndash; identifies the current section in the navigation</li>
<li><code>$_name</code> &ndash; identifies the current page in the navigation, also used as the default headline</li>
<li><code>$_title</code> &ndash; replaces the default title tag text</li>
<li><code>$_headline</code> &ndash; replaces the default H1 text, also inserted at beginning of title tag</li>
<li><code>$_alt</code> &ndash; alt text for primary page image</li>
<li><code>$_keywords</code> &ndash; replaces the default meta keywords</li>
<li><code>$_description</code> &ndash; replaces the default meta description text</li>
</ul>

<h2>Examples</h2>

<p>This page lists the available PHP functions followed by the output of that code. The "&lt; first" and "&lt; last" tags are being added with jQuery to li.first and li.last so you don't have to view the source to see the class names. A similar visualization is being done with external links. The .active class is highlighted in yellow.</p>

<p>The page variables for this example page are set to:</p>

<ul>
<li><code>$_section = <?php echo $_section; ?></code></li>
<li><code>$_name = <?php echo $_name; ?></code></li>
<li><code>$_title = <?php echo $_title; ?></code></li>
<li><code>$_headline = <?php echo $_headline; ?></code></li>
<li><code>$_alt = <?php echo $_alt; ?></code></li>
<li><code>$_keywords = <?php echo $_keywords; ?></code></li>
<li><code>$_description = <?php echo $_description; ?></code></li>
</ul>

<?php

heading('navigation()');
example("echo navigation(\$exclusions = array('Contact Us', 'Site Map'))");
example("echo navigation(\$exclusions = array('Contact Us', 'Site Map'), \$include_sub_nav=true, \$div_id='nav-with-sub')");

heading('custom_navigation()');
example("echo custom_navigation(array('Home','Patient Login'))", 'print a navigation div with only the specified nav items, excludes everything else');
example("echo custom_navigation(array('About Our Office','About Orthodontics'), true, 'inclusive')");

heading('sub_navigation()');
example("echo sub_navigation()");
example("echo sub_navigation(\$section='Nested')");
example("echo sub_navigation_with_heading()");
example("echo sub_navigation_with_heading('', \$link=true)", 'adds a linked heading');
example("echo sub_navigation_with_heading('', '', 'h1')");

heading('sub_nav_p()');
example("sub_nav_p()");
example("sub_nav_p(2)");
example("sub_nav_p(array(2))");
example("sub_nav_p(array(1,3))", 'break the string after the first and third items');
example("sub_nav_p('', ' >>>> ')", 'change the text that separates items');
example("sub_nav_p(array(3),' &bull; ')");
example("sub_nav_p('', '', '', 'Nested')");

heading('text_navigation()');
example("echo text_navigation()");
example("echo text_navigation(4)");
example("echo text_navigation(array(4))");
example("echo text_navigation(array(2,5))");
example("echo text_navigation(0, \$exclude=array('Contact Us'))");
example("echo text_navigation(4, \$exclude=array('Test'), ' **** ')");

heading('sitemap()');
example("echo sitemap()");
example("echo sitemap(\$exclude=array('Contact Us', 'About Orthodontics'))");

heading('breadcrumbs()');
example("echo breadcrumbs()");
example("echo breadcrumbs(' ++ ')", 'specify custom separator string');

heading('get_site_name()');
example('echo get_site_name()', 'gets the value of "site_name" defined in config.php');

heading('headline_tag()');
example('echo headline_tag()', 'Creates an h1 tag. Uses $_heading variable for the text (if defined) but defaults to $_name variable.');

heading('slug_name()');
$slug_tests = array('TMJ/TMD', 'Invisalign&reg;', 'Damon&trade;', 'Why Braces?');
foreach ($slug_tests as $test){
  example("echo slug_name('$test')");
}

example('place_image("alf.jpg")');
example('place_image("alf")', 'works without the file extension if there is a corresponding gif, jpg, or png');
example('place_image("test-png")');
example('place_image("test-gif")');
example('place_image("test-jpg")');
example('place_image("non-existing-file")', "doesn't output anything if it can't find the file");

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