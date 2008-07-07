<?php

require_once('config.php');
require_once('sitemap.php');
# require any other modules
# require_once('breadcrumbs.php');


function is_homepage() {
  # checks to see if the current page is the homepage
  global $_section, $_page_name;
  if($_section == 'Home' && $_page_name == 'Home'){return true;} else {return false;}
}


function stub_name($_page_name) {
	# Create a stub name by stripping 
	# special characters from the given page name
	# and replacing spaces with dashes
  
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','/','!','|',':','"','*','&#39;','&copy;','&reg;','&trade;');
	
	$stub_name = strtolower(str_replace('&amp;', "and", str_replace(' ', '-', str_replace($special_chars,'',$page_name))));
	return $stub_name;
}


function page_title() {
  # outputs the page title
  
  global $_section, $_page_name, $_page_title;
  $config = sc_config();
  
  # use default page title unless locally defined
  $page_title = '';
  if(isset($_page_title) && !empty($_page_title)) {
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
	
  global $_meta_keywords; 
	$config = sc_config();
	
	if(!isset($_meta_keywords) && !empty($_meta_keywords)) {
		$keywords = $_meta_keywords;
	} else {
		$keywords = $config['meta_keywords'];	
	}
	echo $keywords;
}


function meta_description() {
	# Render the meta description string defined in the config file.
	# Page specific definitions will override the default.
	
  global $_meta_description;
	$config = sc_config();
	
	if (isset($_meta_description) && !empty($_meta_description)) {
	    $description = $_meta_description;
	} else {
	    $description = $config['meta_description'];
	}
	echo $description;
}


function main_navigation($_section, $exclusions) {
    # Parse the sitemap hash for the top level nav items.
    # Print them as separate list items.
    # Tag the given $_section with "active" id
    # Takes an array of $exclusions that it will omit from the returned $nav_string
    
    $sitemap = define_sitemap();
    $nav_string = '<ul>';
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
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


function sub_navigation() {
    # Parse the sitemap hash for the current section's nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    
    global $_section, $_page_name;
    
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


function text_navigation($exclusions) {

    # Parse the sitemap hash for the top level nav items.
    # Print them as a string of links with a separator between items.
    # Takes an array of $exclusions that it will omit from the returned $nav_string
    
    $sitemap = define_sitemap();
    $nav_string = '<p class="text_nav">';
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
        	$stub = stub_name($sub_items[0]);
        	$nav_string .= "<a href=\"$stub.php\">$section</a>";
        	
        	# add a separator unless it's the last item in the list        	
            if ($sitemap[$section] != end($sitemap)) {
                $nav_string .= ' | ';
            }
    	}
    }
    $nav_string .= '</p>';
    echo $nav_string;
}


function render_section_index() {

  global $section; 

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


function render_sitemap() {
    # renders the sitemap as nested links
    
    global $_page_name;  
    
    $sitemap = define_sitemap();
    $sitemap_string = '<ul class="sitemap"><li><a href="index.php">Home</a></li>';
    foreach ($sitemap as $section => $sub_items) {
        # Because all sections don't have their own content and are just 'index' pages
        # each section links to its first sub item.
        # To change this behavior, swap the following two lines.
        # $stub = stub_name($section);
        $stub = stub_name($sub_items[0]);
        $sitemap_string .= "<li><a href=\"$stub.php\">$section</a></li>";
        if (count($sub_items) != 1 && $section != $sub_items[0]) { #don't create nexted ul if only sub item is same page
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
    }
    $sitemap_string .= '</ul>';
    echo $sitemap_string;
}

?>