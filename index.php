<?php
$_section = 'Braces 101';
$_page_name = 'Life with Braces';
$_keyword = 'Invisalign';
$_page_title = 'title override';
$_alt = 'example image used to see if Andrew\'s code is working.';
require_once('global.php');
include_once('head.php');
?>
<p>This is an example page illustrating the available php functions that utilize the site stucture defined in <code>sitemap.php</code>.</p>
<p>Using our existing approach of defining <code>$page</code> and <code>$section</code> variables for each page (I&#8217;ve named them <code>$_page_name</code>, and <code>$_section</code>), we can call the following functions from each page and get dynamicly generated navigation elements.</p>
<p>These functions are defined in <code>global.php</code>. To enable this functionality simply include it on every page in addition to the header and footer includes. Then define the site structure as a hash in <code>sitemap.php</code>, also kept in the includes folder. (<strong>Note:</strong> The root level &#8216;Site Map&#8217; page should be named <code>site-map.php</code> to avoid conflicting names.)</p>
<p>To illustrate these examples, this page is set to <code>$_section='Braces 101'</code> and  <code>$_page_name='Life with Braces'</code>.</p>

<h2 id="main_navigation">Main Navigation</h2>
<pre><code>main_navigation($exclusions = array('Contact Us','Site Map'));</code></pre>
<p>This function returns a <code>&lt;ul&gt;</code> with list items of the top level navigation links. It also adds an <code>id="active"</code> to the <code>&lt;li&gt;</code> of the current section. It will omit any sections listed in the given <code>$exclusions</code> parameter.</p>
<h3>Example Output:</h3>
<?php main_navigation($exclusions = array('Contact Us','Site Map')) ?>

<h2 id="sub_navigation">Sub Navigation</h2>
<pre><code>sub_navigation();</code></pre>
<p>This function returns a <code>&lt;ul&gt;</code> with list items of the current section&#8217;s sub-navigation links. It also adds an <code>id="subactive"</code> to the <code>&lt;li&gt;</code> of the current page.</p>
<h3>Example Output:</h3>
<?php sub_navigation() ?>

<h2 id="text_navigation">Text Navigation</h2>
<pre><code>text_navigation();</code></pre>
<p>This function returns a <code>&lt;p&gt;</code> string listing the top level navigation items as links separated by 'pipe' characters.</p>
<h3>Example Output:</h3>
<?php text_navigation() ?>

<h2 id="sitemap">Sitemap</h2>
<pre><code>sitemap();</code></pre>
<p>This function parses <code>sitemap.php</code> and creates nested <code>&lt;ul&gt;</code>s of the site structure with links to each page.</p>
<h3>Example Output:</h3>
<?php sitemap() ?>

<h2>Section Index</h2>
<pre><code>section_index();</code></pre>
<p>This function parses <code>sitemap.php</code> and creates nested <code>&lt;ul&gt;</code>s of the site structure with links to each page.</p>
<h3>Example Output:</h3>
<?php section_index() ?>

<h2>Breadcrumbs</h2>
<pre><code>breadcrumbs();</code></pre>
<h3>Example Output:</h3>
<?php breadcrumbs() ?>

<h2>Place Image</h2>
<pre><code>place_image("images/filename.jpg", ["alt text", "title"]);</code></pre>
<h3>Example Output:</h3>
<?php place_image("alf.jpg", "", "alf likes to eat cats") ?>

<?php include_once('foot.php') ?>