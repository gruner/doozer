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
  global $env, $logr;
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
  $html = content_tag('h3', $code.';', array('class' => 'code'));
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

/**
 * Page Variables
 */
$_section = 'Products';
$_name = 'Bolts';
//$_title = '[this text replaces the title]';
$_alt = 'this string will be the default alt text when using the place_image() function';

require_once('global.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<?php echo meta_tags(); ?>
	<link rel="stylesheet" href="example.css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<title>Doozer PHP Navigation Framework</title>

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

<div class="container">

<h1>Doozer &ndash; A PHP Navigation Framework</h1>

<p>This is an example page illustrating the available PHP functions that utilize the site structure defined in <code>sitemap.php</code>.</p>

<p>By defining <code>$_page</code> and <code>$_section</code> variables for each page, we can call the following functions from each page and get dynamically generated navigation elements.</p>

<p>These functions are defined in <code>global.php</code>. To enable this functionality include <code>global.php</code> on every page before calling the header include. Then define the site structure as an array in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>

<h2>Naming Conventions</h2>

<p>'Slug Name' is a term used in blogging and CMS software for stripping spaces and special characters from a page title to use as a file name or in a URL. The framework relies on PHP files that follow this naming convention. (e.g. A page titled 'About Us' would be named 'about-us.php').</p>

<h2>Configuration</h2>

<p>Several default values are defined in the <code>config.php</code> file. These include:</p>

<ul>
<li><code>meta_keywords</code> &ndash; the default meta keywords for every page</li>
<li><code>meta_description</code> &ndash; the default meta description for every page</li>
<li><code>page_title</code> &ndash; the default base page title for every page</li>
<li><code>site_name</code> &ndash; the name of the site, used in the page header</li>
</ul>

<h2>Sitemap</h2>

<p>The navigation for the site is defined as a PHP array in the <code>sitemap.php</code> file. All of the following navigation functions are created by parsing the sitemap file.</p>

<p>This is the sitemap used for the examples on this page:</p>

<pre>
$sitemap = array(
  'Home' => 'index.php', // specify a specific file
  'Login' => 'http://example.com/login', // specify a specific url
  'About Us' => array(
    'Meet Our Staff',
    'Location',
    'Contact Form'),
  'FAQs',
  'Our Services',
  'Products' => array(
    'Nuts',
    'Bolts',
    'Clocks' => array(
      'Digital',
      'Analog'),
    'Thermometers' => array(
      'Fahrenheit',
      'Celsius')));
</pre>

<p>Each name in the sitemap will be linked to a corresponding php page. (e.g. 'About Us' will link to '<code>about-us.php</code>'.) To override the defaults, specify a file or url. Note how 'Home' links to 'index.php' and 'Login' links to an external url.</p>

<h2>Page Variables</h2>

<p>Several predefined variables can be defined on each page that will override the default values from <code>config.php</code>.</p>

<p>These include:</p>

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

<p>This page lists the available PHP functions followed by code examples with the output of each example.</p>

<p>The page variables for this example page are set to:</p>

<ul>
<li><code>$_section = <?php echo $_section; ?>;</code></li>
<li><code>$_name = <?php echo $_name; ?>;</code></li>
<!-- <li><code>$_title = <?php echo $_title; ?>;</code></li>
<li><code>$_headline = <?php echo $_headline; ?>;</code></li> -->
<!-- <li><code>$_alt = <?php echo $_alt; ?></code>;</li>
<li><code>$_keywords = <?php echo $_keywords; ?>;</code></li>
<li><code>$_description = <?php echo $_description; ?>;</code></li> -->
</ul>

<?php

heading('navigation()');
desc('Formats sitemap.php into the main navigation of the site');
example("echo navigation()");
example("echo navigation(array(), \$include_sub_nav=true)", 'include the nested sub-navigation');
example("echo navigation(\$exclusions = array('Products'))", 'exclude the section "Products"');
example("echo navigation(\$exclusions = array('Products'), \$include_sub_nav=true)", 'exclude the section "Products" and include the sub-navigation');

heading('custom_navigation()');
desc('Creates a navigation div with only the specified nav items, excluding everything else');
example("echo custom_navigation(array('Home','Login'))");
example("echo custom_navigation(array('Our Services','Products'), true)");

heading('text_navigation()');
desc('Creates a formatted list of the top-level navigation links');
example("echo text_navigation()");
example("echo text_navigation(4)", 'break the string after the fourth item');
example("echo text_navigation(array(4))", 'same as above');
example("echo text_navigation(array(2,5))", 'break the string after the second and fifth items');
example("echo text_navigation(0, \$exclude=array('Contact Us'))", 'exclude "Contact Us" from the navigation');
example("echo text_navigation(4, \$exclude=array('Test'), ' **** ')");

heading('sub_navigation()');
desc('Creates a navigation div with the subnav items of the current section');
example("echo sub_navigation()");
example("echo sub_navigation(\$section='About Us')", 'specify a different section');
example("echo sub_navigation_with_heading()", 'show the current section as a heading');
example("echo sub_navigation_with_heading('', \$link=true)", 'adds a linked heading');
example("echo sub_navigation_with_heading('', '', 'h1')", 'specify a different tag for the heading');

heading('text_sub_navigation()');
desc('Formats the sub-navigation as a <p> instead of a <ul>');
example("echo text_sub_navigation()");
example("echo text_sub_navigation(2)", 'break the string after the second item');
example("echo text_sub_navigation(array(2))", 'same as above');
example("echo text_sub_navigation(array(1,3))", 'break the string after the first and third items');
example("echo text_sub_navigation('', ' >>>> ')", 'change the text that separates items');
example("echo text_sub_navigation(array(3),' &bull; ')");
example("echo text_sub_navigation('', ' | ', '', 'About Us')", 'specify a different section');

heading('callout_navigation()');
desc('Creates a <ul> from a list of link name that correspond to pages defined in the sitemap.');

example(
  "echo callout_navigation(array('Login', 'Analog', 'Thermometers'))",
  "For each item we search for a corresponding link in the sitemap, even if it's not a top-level page."
);

example(
  "echo callout_navigation(array('Analog Alias' => 'Analog', 'Thermometers'))",
  "specify a different name for a page in the sitemap"
);

example(
  "echo callout_navigation(array('google' => 'http://google.com', 'Analog', 'Thermometers'))",
  "specify a custom link (google) that's not in the sitemap"
);
example(
  "echo callout_navigation(array('Analog', 'Thermometers'), array('class' => 'custom_class'))",
  'the second parameter lets you specify custom attributes for the generated &lt;ul&gt; tag'
);


heading('sitemap()');
desc('Outputs the entire sitemap as a series of nested <ul>s');
example("echo sitemap()");
example("echo sitemap(\$exclude=array('Products', 'FAQs'))", 'exclude specific sections from the sitemap');
example("echo sitemap(array(), \$tag_options=array('class'=>'sitemap_list'))", '<code>$tag_options</code> lets you specify html attributes for the parent &lt;ul&gt;');

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
$slug_tests = array('This/That', 'Adobe&reg;', 'Kevlar&trade;', 'Who Needs This?');
foreach ($slug_tests as $test){ example("echo slug_name('$test')"); }

heading('place_image()');
example('echo place_image("alf.jpg")');
example('echo place_image("alf")', 'works without the file extension if there is a corresponding gif, jpg, or png');
example('echo place_image("test-png")');
example('echo place_image("test-gif")');
example('echo place_image("test-jpg")');
example('echo place_image("non-existing-file")', "doesn't output anything if it can't find the file");

heading('content_tag()');
desc('Wraps content in an html tag. Specify the attributes for the tag as an array in the third parameter.');

example("echo content_tag('h3', 'My Custom H3 Tag')");
example("echo content_tag('p', 'My Custom P Tag')");
example(
  'echo content_tag(\'p\', \'My Custom P Tag with attributes\', $tag_attributes=array(\'id\' => \'custom_id\', \'class\' => \'custom_class\'))',
  'add an array of attributes to the tag'
);

heading('title_tag()');
desc('Creates the <title> tag for the page based on the configuration and page values.');

heading('meta_tags()');
desc('Creates the meta keywords and meta description tags based on the configuration and page values.');

heading('ie6_alert()');
desc('Displays an html pop-up if browser is IE6.');
//example('echo ie6_alert()');

print_logr();

?>

</div>
</body>
</html>