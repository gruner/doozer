<?php
$_section = 'Braces 101';
$_page_name = 'Life with Braces';
$_keyword = 'Invisalign';
$_page_title = '[this text replaces the base title]';
$_alt = 'this string will be the default alt text when using the place_image() function';
require_once('global.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<?php meta_tags(); ?>
	<link href="example.css" media="screen" rel="stylesheet" type="text/css" />
	<?php page_title(); ?>
</head>
<body class="<?php echo slug_name($_page_name); ?>">

<h1>PHP Framework</h1>

<p>This is an example page illustrating the available php functions that utilize the site stucture defined in <code>sitemap.php</code>.</p>
<p>Using our existing approach of defining <code>$page</code> and <code>$section</code> variables for each page (The framework uses the names <code>$_page_name</code>, and <code>$_section</code>), we can call the following functions from each page and get dynamicly generated navigation elements.</p>
<p>These functions are defined in <code>global.php</code>. To enable this functionality simply include it on every page in addition to the header and footer includes. Then define the site structure as an array in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>
<p>To illustrate these examples, this example page is set to <code>$_section='Braces 101'</code> and  <code>$_page_name='Life with Braces'</code>.</p>

<h2>Naming Conventions</h2>
<p>'Slug Name' is a term used in blogging and CMS software for stripping spaces and special characters from a page title to use as a file name or in a URL. The framework relies on php files that follow this naming contention. (eg A page titled 'Life with Braces' would be named 'life-with-braces.php').</p>

<h2>Navigation</h2>

<pre><code>navigation($exclusions, $include_sub_nav, $div_id');</code></pre>
<p>This function returns a <code>&lt;ul&gt;</code> with list items of the top level navigation links.</p>
<p>For styling purposes, each top-level &lt;a&gt; is given an id  named after the section. It also adds a <code>class="active"</code> to the <code>&lt;li&gt;</code> of the current section.</p>

<h3>Options</h3>

<ul>
  <li>If you don't want certain sections to be included in the generated navigation, create an array with the section names you wish to excude and pass them to the function as the <code>$exclusions</code> parameter.</li>
  <li>$sub_nav is a boolean parameter that specifies if the sub nav should be included as a nested &lt;ul&gt;s.</li>
  <li>The $div_id parameter lets you specify the id of the generated &lt;ul&gt;. It defaults to 'nav' if nothing is given.</li>
</ul>

<h3>Example:</h3>
<pre><code>navigation($exclusions = array('Contact Us','Site Map'));</code></pre>
<?php navigation($exclusions = array('Contact Us','Site Map')); ?>

<pre><code>navigation($exclusions = array('Contact Us','Site Map'), $include_sub_nav=true, $div_id='nav-with-sub');</code></pre>
<?php navigation($exclusions = array('Contact Us','Site Map'), $include_sub_nav=true, $div_id='nav-with-sub'); ?>

<hr />
<h2>Sub Navigation</h2>

<p>This function returns a <code>&lt;ul&gt;</code> with list items of the current section&#8217;s sub-navigation links. It also adds <code>class="active"</code> to the <code>&lt;li&gt;</code> of the current page.</p>

<h3>Example:</h3>

<pre><code>sub_navigation();</code></pre>
<?php sub_navigation(); ?>

<hr />
<h2>Sub Navigation as a Paragraph</h2>

<pre><code>sub_nav_p();</code></pre>
<p>This function returns a <code>&lt;p&gt;</code> with the current section&#8217;s sub-navigation links. It also adds an <code>class="active"</code> to the <code>&lt;li&gt;</code> of the current page. The text or character inserted between each link can be customized.</p>

<h3>Example:</h3>

<pre><code>sub_nav_p();</code></pre>
<?php sub_nav_p(); ?>

<pre><code>sub_nav_p(array(3,7)); # split the list after the 3rd and 7th links</code></pre>
<?php sub_nav_p(array(3,7)); ?>

<pre><code>sub_nav_p(array(5),' &amp;bull; '); # add custom separator string between links</code></pre>
<?php sub_nav_p(array(5),' &bull; '); ?>

<hr />
<h2 class="clear">Text Navigation</h2>
<p>This function returns a <code>&lt;p&gt;</code> string listing the top level navigation items as links separated by 'pipe' characters. The second example shows how you can optionally force a break after a specific number of links.</p>

<h3>Example:</h3>
<pre><code>text_navigation();</code></pre>
<?php text_navigation(); ?>

<pre><code>text_navigation(4); # break the line after the forth link</code></pre>
<?php text_navigation(4); ?>

<hr />
<h2>Sitemap</h2>
<p>This function parses <code>sitemap.php</code> and creates nested <code>&lt;ul&gt;</code>s of the site structure with links to each page.</p>
<h3>Example:</h3>
<pre><code>sitemap();</code></pre>
<?php sitemap(); ?>

<hr />
<h2>Section Index</h2>
<p>This function parses <code>sitemap.php</code> and creates nested <code>&lt;ul&gt;</code>s of the site structure with links to each page.</p>
<h3>Example:</h3>
<pre><code>section_index();</code></pre>
<?php section_index(); ?>

<hr />
<h2>Breadcrumbs</h2>
<h3>Example:</h3>
<pre><code>breadcrumbs();</code></pre>
<?php breadcrumbs(); ?>

<hr />
<h2>Place Image</h2>
<p>This function generates an image tag with calculated width and height attributes as well as an alt tag read from an '_alt' variable at the top of the page.</p>
<h3>Options</h3>
<ul>
  <li>Specify the alt text</li>
  <li>Specify the class name given to the image</li>
</ul>
<h3>Example:</h3>
<pre><code>place_image("filename.jpg", ["alt text", "class_name"]);</code></pre>
<?php place_image("alf.jpg"); ?>

</body>
</html>