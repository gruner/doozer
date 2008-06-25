<?php

require_once('config.php');
require_once('sitemap.php');

# TODO need a way to scope the page's vars
$_page_name = $_page_name;


function is_homepage($_section, $_page_name)
	# checks to see if the current page is the homepage
{
    if($_section == 'Home' && $_page_name == 'Home'){return true;} else {return false;}
}


function stub_name($page_name)
{
	# Create a stub name by stripping 
	# special characters from the page title
	# and replacing spaces with dashes
	
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','/','!','|',':','"','*','&#39;','&copy;','&reg;','&trade;');
	
	$stub_name = strtolower(str_replace('&amp;', "and", str_replace(' ', '-', str_replace($special_chars,'',$page_name))));
	return $stub_name;
}


function page_title($_section, $_page_name) {
  # use default page title unless
  # locally defined
  
  $config = sc_config();
  
  $page_title = '';
  if(isset($_page_title) && !empty($_page_title)) { #TODO $_page_title is out of scope
    $page_title = $_page_title;
  } else {
    $page_title = $config['page_title'];
  }

  # add section and page to the title
  if ($_page_name == 'Home' && $_section == 'Home') {
  	$page_title .= ' | Expert Orthodontics by Dwight A. Frey | Frey Orthodontics';
  }
  $page_title .= " | $_section";
  if($_section != $_page_name) {$page_title .= " > $_page_name";}
  
  echo $page_title;
}


function meta_keywords() {
	# Render the meta keywords string defined in the config file.
	# Page specific definitions will override the default.
	
	$config = sc_config();
	
	if(!isset($_meta_keywords) && !empty($_meta_keywords)) {
		$keywords = $_meta_keywords;
	} else {
		$keywords = $config['meta_keywords'];	
	}
	echo $keywords;
}


function meta_description()
{
	# Render the meta description string defined in the config file.
	# Page specific definitions will override the default.
	
	$config = sc_config();
	
	if (isset($_meta_description) && !empty($_meta_description)) {
	    $description = $_meta_description;
	} else {
	    $description = $config['meta_description'];
	}
	echo $description;
}


function main_navigation($_section) {
    # Parse the sitemap hash for the top level nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    
    $sitemap = define_sitemap();
    $main_nav_exclusions = main_nav_exclusions();
    $nav_string = '<ul>';
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $main_nav_exclusions)) {
        	$nav_string .= '<li';
        	if ($section == $_section) {
            	$nav_string .= " id=\"active\"";
        	}
        	$stub = stub_name($sub_items[0]);
        	$class = stub_name($section);
        	$nav_string .= "><a href=\"$stub.php\" class=\"$class\">$section</a></li>\n";
    	}
    }
    $nav_string .= '</ul>';
    echo $nav_string;
}


function sub_navigation($_section, $_page_name) {
    # Parse the sitemap hash for the current section's nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    
    $sitemap = define_sitemap();
    $sub_nav_string = "<div id=\"subnav\"\n><ul>\n";
    $sub_items = $sitemap[$_section];
    foreach ($sub_items as $sub_name) {
        $sub_nav_string .= '<li';
        # append '.first' and '.last' class names
        if ($sub_name == $sub_items[0]) {$sub_nav_string .= ' class="first"';} 
        elseif ($sub_name == end($sub_items)) {$sub_nav_string .= ' class="last"';}
        if ($sub_name == "$_page_name") { $sub_nav_string .= " id=\"sub_active\"";}
        $stub = stub_name($sub_name);
        $sub_nav_string .= "><a href=\"$stub.php\">$sub_name</a></li>\n";
    }
    $sub_nav_string .= "</ul>\n</div>";
    if($_section != 'Home' && $_page_name != 'Home'){echo $sub_nav_string;}  
}


function build_breadcrumbs() {
    # TODO: finish replacing regex $_SERVER checks with internal page vars
    $sitemap = define_sitemap();
    $breadcrumbs = array("Home" => "/"); #initalize the breadcrumbs array and add the homepage
    foreach ($sitemap as $section => $sub_items) {
        if ($section == $_section) {
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


function breadcrumbs($bc_array, $separator=' &#8250') {
    # assembles a breadcrumbs string 
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


function render_breadcrumbs() {
    $bc_array = build_breadcrumbs();
    $breadcrumbs = breadcrumbs($bc_array);
    echo $breadcrumbs;
}


function render_section_index($section) {
    $sitemap = define_sitemap();
    $index = "<h2>In this section:</h2>\n<ul class=\"index\">";
    $section = $sitemap[$section];
    foreach ($section as $page) {
        $page_stup = stub_name($page);
        $index .= "<li><a href=\"$page_stub.php\"></a>$page</li>";
    }
    $index .= '</ul>';
    echo $index;
}


function render_sitemap($_page_name) {
    # renders the sitemap as nested links
    $sitemap = define_sitemap();
    $sitemap_string = '<ul class="sitemap"><li><a href="/">Home</a></li>';
    foreach ($sitemap as $section => $sub_items) {
        $stub = stub_name($section);
        $sitemap_string .= "<li><a href=\"$stub.php\">$section</a></li>";
        $sitemap_string .= '<ul>';
        foreach ($sub_items as $sub_name) {
                $sub_stub = stub_name($sub_name);
                # Mark the current page, don't create a self referencing link
                if ($sub_name == $_page_name) {
                    $sitemap_string .= "<li>$subName (This Page)</li>";
                } else {
                    $sitemap_string .= "<li><a href=\"$sub_stub.php\">$sub_name</a></li>";
                }
        }
        $sitemap_string .= '</ul>';
    }
    $sitemap_string .= '</ul>';
    echo $sitemap_string;
}

?>