<?php

require_once('config.php');
require_once('sitemap.php');


function stub_name($page_title)
{	
	# Create a stub name by stripping special characters from the page title
	# and replacing spaces with dashes
	
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','/','!','|',':','"','*','&#39;','&copy;','&reg;','&trade;');
	
	$stub_name = strtolower(str_replace('&amp;', "and", str_replace(' ', '-', str_replace($special_chars,'',$page_title))));
	return $stub_name;
}


function meta_keywords()
{
	# Render the meta keywords string defined in the config file.
	# Page specific definitions will override the default.
	# unless $_keywords && !empty($_keywords);
	$keywords = sc_config['meta_keywords'];
	echo $keywords;
}


function meta_description()
{
	# Render the meta description string defined in the config file.
	# Page specific definitions will override the default.
	
	# unless $_description && !empty($_description);
	$description = sc_config['meta_description'];
	echo $description;
}


function page_title($value='')
{
	# generate the page title
}


function main_navigation()
{
    # Parse the sitemap hash for the top level nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    $sitemap = define_sitemap();
    $nav_string = '';
    foreach ($sitemap as $section => $sub_items) {
        $nav_string .= '<li ';
        if (eregi("/$section/", $_SERVER['PHP_SELF'])) { 
             $nav_string .= "id=\"active\"";
        }
        $nav_string .= "><a href=\"/$section/index.php\">$section</a></li>";
    }
    echo $nav_string;
}


function sub_navigation() {
    # Parse the sitemap hash for the current level nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    $sitemap = define_sitemap();
    $sub_nav_string = '';
    foreach ($sitemap as $section => $sub_items) {
        if (eregi("/$section/", $_SERVER['PHP_SELF'])) {
            $sub_nav_string .= "<h3><a href=\"/$section/index.php\">$section</a></h3>";
            $sub_nav_string .= '<ul>';
            foreach ($sub_items as $sub_name => $sub_url) {
                $sub_name = replace_text($sub_name); # add special formatting to certain product names
                $sub_nav_string .= '<li';
                if (eregi("/$section/$sub_url.php", $_SERVER['PHP_SELF'])) { $sub_nav_string .= " id=\"sideactive\"";}
                $sub_nav_string .= "><a href=\"/$section/$sub_url.php\">$sub_name</a></li>";
            }
            $sub_nav_string .= '</ul>';
            break;
        }
    }
    echo $sub_nav_string;
}


function build_breadcrumbs() {
    $sitemap = define_sitemap();
    $breadcrumbs = array("Home" => "/"); #initalize the breadcrumbs array and add the homepage
    foreach ($sitemap as $section => $sub_items) {
        if (eregi("/$section/", $_SERVER['PHP_SELF'])) {
            $breadcrumbs[$section] = "/$section/index.php";
            foreach ($sub_items as $sub_name => $sub_url) {
                $sub_name = replace_text($sub_name); # add any special formatting for product names
                $sub_url = "/$section/$sub_url.php";
                if (eregi($sub_url, $_SERVER['PHP_SELF'])) { 
                    $breadcrumbs[$sub_name] = $sub_url;
                }
            }
            break;
        }
    }
    return $breadcrumbs;
}


function render_breadcrumbs($bc_array, $separator) {
    # The last array item is bolded, 
    # the rest are linked
    foreach ($bc_array as $bc => $link) {
        if ($bc_array[$bc] == end($bc_array)) {
            # Format the last item as bold with no link
            $bc_array[$bc] = "<strong>$bc</strong>";
        } else {
            # Format bc item as a link
            $bc_array[$bc] = "<a href=\"$link\">$bc</a>";
        }
    }
    # Convert the array to a string and insert separator between each element
    $bcString = implode($separator, $bc_array);
    return $bcString;
}


function breadcrumbs() {
    $separator = ' &#8250; '; # the HTML character that is inserted between items
    $bc_array = build_breadcrumbs();
    $breadcrumbs = render_breadcrumbs($bc_array, $separator);
    echo $breadcrumbs;
}


function render_sitemap() {
    # renders the sitemap as nested links
    $sitemap = define_sitemap();
    $sitemap_string = '<ul class="sitemap"><li><a href="/">Home</a></li>';
    $breadcrumbs = array("Home" => "/"); #initalize the breadcrumbs array and add the homepage
    foreach ($sitemap as $section => $sub_items) {
        $sitemap_string .= "<li><a href=\"/$section/index.php\">$section</a></li>";
        $sitemap_string .= '<ul>';
        foreach ($sub_items as $subName => $subURL) {
                $subName = replace_text($subName); # add any special formatting for product names
                $subURL = "/$section/$subURL.php";
                
                # Mark the current page, don't create a self referencing link
                if (eregi($subURL, $_SERVER['PHP_SELF'])) {
                    $sitemap_string .= "<li>$subName (This Page)</li>";
                } else {
                    $sitemap_string .= "<li><a href=\"$subURL\">$subName</a></li>";
                }
        }
        $sitemap_string .= '</ul>';
    }
    $sitemap_string .= '</ul>';
    echo $sitemap_string;
}

?>