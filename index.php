<?php

$env = 'production';
if ($env == 'test')
{
  ini_set('display_errors', '1');
  error_reporting(E_ALL);
}

$logr = array();

function logr($str)
{
  global $logr;
  $logr[] = $str;
}


function print_logr()
{
  if ($env == 'test')
  {
    heading('Log');
    print_r($logr);
  }
}


function example($code, $comments='')
{
  echo content_tag('div', format_example($code, $comments), array('class' => 'example'));
}


function format_example($code, $comments='')
{
  $html = content_tag('h3', $code, array('class' => 'code'));
  if (!empty($comments))
  {
    $html .= content_tag('p', $comments, array('class' => 'quiet'));
  }

  ob_start();
  eval("$code;");
  $code = ob_get_contents();
  ob_end_clean();

  $html .= content_tag('div', $code);
  return $html;
}


function heading($text)
{
  echo content_tag('h2', $text, array('class' => 'example'));
}

function desc($text)
{
  echo content_tag('p', htmlspecialchars($text));
}

$_section = 'Products';
$_name = 'Bolts';
//$_title = '[this text replaces the title]';
$_alt = 'this string will be the default alt text when using the place_image() function';
$_content = '<p>sidebar</p>';

require_once('global.php');

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
  		//$('li.first').append('<span class="quiet">  < first</span>');
  		//$('li.last').append('<span class="quiet">  < last</span>');
  		//$('#nav-with-sub a[href^="http://"]').after('<span class="quiet"> (external)</span>');
  		//$('a.head').after('<span class="quiet"> < head </span>');
  	});//end document.ready
  </script>

</head>

<body class="<?php echo slug_name($_name); ?>">

<h1>Doozer &ndash; A PHP Framework</h1>

<p>This is an example page illustrating the available PHP functions that utilize the site structure defined in <code>sitemap.php</code>.</p>

<p>By defining <code>$_page</code> and <code>$_section</code> variables for each page, we can call the following functions from each page and get dynamically generated navigation elements.</p>

<p>These functions are defined in <code>global.php</code>. To enable this functionality simply include <code>global.php</code> on every page before calling the header include. Then define the site structure as an array in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>

<h2>Naming Conventions</h2>

<p>'Slug Name' is a term used in blogging and CMS software for stripping spaces and special characters from a page title to use as a file name or in a URL. The framework relies on PHP files that follow this naming contention. (e.g. A page titled 'About Us' would be named 'about-us.php').</p>

<h2>Configuration</h2>

<p>Several default values are defined in <code>config.php</code>. These include:</p>

<ul>
<li><code>meta_keywords</code> &ndash; the default meta keywords for every page</li>
<li><code>meta_description</code> &ndash; the default meta description for every page</li>
<li><code>page_title</code> &ndash; the default base page title for every page</li>
<li><code>site_name</code> &ndash; the name of the site, used in the page header</li>
</ul>

<h2>Sitemap</h2>

<p>The navigation for the site is defined as a PHP array in the <code>sitemap.php</code> file. All of the navigation functions are created by parsing the sitemap file.</p>

<h2>Page Variables</h2>

<p>Several predefined variables can be defined on each page that will override the default values. These include:</p>

<ul>
<li><code>$_section</code> &ndash; identifies the current section in the navigation</li>
<li><code>$_name</code> &ndash; identifies the current page in the navigation, also used as the default headline</li>
<li><code>$_title</code> &ndash; replaces the default title tag text</li>
<li><code>$_headline</code> &ndash; replaces the default H1 text, also inserted at beginning of title tag</li>
<li><code>$_alt</code> &ndash; alt text for primary page image</li>
<li><code>$_keywords</code> &ndash; replaces the default meta keywords</li>
<li><code>$_description</code> &ndash; replaces the default meta description text</li>
</ul>

<p class="small">(Note: Variables that start with an underscore denote page-specific variables.)</p>

<h2>Examples</h2>

<p>This page lists the available PHP functions followed code examples and the output of each example.</p>

<p>The page variables for this example page are set to:</p>

<ul>
<li><code>$_section = <?php echo $_section; ?></code></li>
<li><code>$_name = <?php echo $_name; ?></code></li>
<!-- <li><code>$_title = <?php echo $_title; ?></code></li>
<li><code>$_headline = <?php echo $_headline; ?></code></li> -->
<!-- <li><code>$_alt = <?php echo $_alt; ?></code></li>
<li><code>$_keywords = <?php echo $_keywords; ?></code></li>
<li><code>$_description = <?php echo $_description; ?></code></li> -->
</ul>

<?php

heading('navigation()');
desc('Formats sitemap.php into the main navigation of the site');
example("echo navigation(\$exclusions = array('Contact Us', 'Site Map'))");
example("echo navigation(\$exclusions = array('Contact Us', 'Site Map'), \$include_sub_nav=true)");

heading('custom_navigation()');
desc('Creates a navigation div with only the specified nav items, excluding everything else');
example("echo custom_navigation(array('Home','Login'))");
example("echo custom_navigation(array('Our Services','Products'), true)");

heading('sub_navigation()');
desc('Creates a navigation div with the subnav items of the current section');
example("echo sub_navigation()");
example("echo sub_navigation(\$section='About Us')", 'specify a different section');
example("echo sub_navigation_with_heading()", 'show the current section as a heading');
example("echo sub_navigation_with_heading('', \$link=true)", 'adds a linked heading');
example("echo sub_navigation_with_heading('', '', 'h1')", 'specify a different tag for the heading');

heading('sub_nav_p()');
desc('Formats the sub-navigation as a <p> instead of a <ul>');
example("sub_nav_p()");
example("sub_nav_p(2)", 'break the string after the second item');
example("sub_nav_p(array(2))", 'same as above');
example("sub_nav_p(array(1,3))", 'break the string after the first and third items');
example("sub_nav_p('', ' >>>> ')", 'change the text that separates items');
example("sub_nav_p(array(3),' &bull; ')");
example("sub_nav_p('', '', '', 'About Us')");

heading('text_navigation()');
desc('Creates a formatted list of the top-level navigation links');
example("echo text_navigation()");
example("echo text_navigation(4)", 'break the string after the fourth item');
example("echo text_navigation(array(4))", 'same as above');
example("echo text_navigation(array(2,5))", 'break the string after the second and fifth items');
example("echo text_navigation(0, \$exclude=array('Contact Us'))", 'exclude "Contact Us" from the navigation');
example("echo text_navigation(4, \$exclude=array('Test'), ' **** ')");

heading('sitemap()');
desc('Outputs the entire sitemap a series of nested <ul>s');
example("echo sitemap()");
example("echo sitemap(\$exclude=array('Products', 'FAQs'))", 'exclude specific sections from the sitemap');

heading('breadcrumbs()');
example("echo breadcrumbs()");
example("echo breadcrumbs(' ++ ')", 'specify custom separator string');

heading('get_site_name()');
example('echo get_site_name()', 'gets the value of "site_name" defined in config.php');

heading('headline_tag()');
desc('Creates an h1 tag. Uses $_heading variable for the text. Defaults to $_name variable if $_heading is not set.');
example('echo headline_tag()');

heading('slug_name()');
desc('Converts a string into a "slug", suitable for file names, css class names, etc.');
$slug_tests = array('TMJ/TMD', 'Invisalign&reg;', 'Damon&trade;', 'Why Braces?');
foreach ($slug_tests as $test){
  example("echo slug_name('$test')");
}

heading('place_image()');
example('echo place_image("alf.jpg")');
example('echo place_image("alf")', 'works without the file extension if there is a corresponding gif, jpg, or png');
example('echo place_image("test-png")');
example('echo place_image("test-gif")');
example('echo place_image("test-jpg")');
example('echo place_image("non-existing-file")', "doesn't output anything if it can't find the file");

heading('ie6_alert()');
desc('Displays an html pop-up if browser is IE6');

heading('title_tag()');
desc('Creates the <title> tag for the page based on the configuration and page values.');

heading('meta_tags()');
desc('Creates the meta keywords and meta description tags based on the configuration and page values');

print_logr();

?>

</body>
</html>