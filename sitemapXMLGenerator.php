<?php

# This file reads the site structure definied in 'sitemap.php'
# and generates the root level 'sitemap.xml' file for search engine indexing

# TODO: put full headers at the top of xml file for validation

require_once('sitemap.php');


function generate_sitemap_xml() {
    $sitemap = defineSitemap();
    $sitemap_string = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $sitemap_string .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    $sitemap_string .= sitemap_url_block('', 'weekly', '1.0');
    $sitemap_string .= sitemap_url_block('home.php', 'weekly', '0.8');
    foreach ($sitemap as $section => $sub_items) {
        foreach ($sub_items as $subName => $page) {
            $sitemap_string .= sitemap_url_block("$section/$page.php");
        }
    }
    $sitemap_string .= "</urlset>";
    return $sitemap_string;
}


function sitemap_url_block($page, $freq = 'monthly', $priority = '') {
    # generates a url block for the given page
    # skips priority unless it's given as an argument
    # with no priority set, search crawlers auto-assign it as '0.5'
    
    $block = "    <url>\n";
    $block .= "        <loc>http://www.plexera.com/$page</loc>\n";
    $block .= "        <changefreq>$freq</changefreq>\n";
    if ($priority != ''){
        $block .= "        <priority>$priority</priority>\n";
    }
    $block .= "    </url>\n";
    return $block;
}


function create_file() {
    $filename = '../sitemap.xml';
    $content = generate_sitemap_xml();
    $handle = fopen($filename, 'w+');
    fwrite($handle, $content);
    fclose($handle);
    echo "sitemap.xml generated and saved";
}

# run the script and generate the file
create_file();

?>