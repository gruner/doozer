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


function slug_name($page_name) {
	# Create a slug name by stripping 
	# special characters from the given page name
	# and replacing spaces with dashes
  
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','/','!','|',':','"','*','&#39;','&copy;','&reg;','&trade;');
	
	$slug_name = strtolower(str_replace('&amp;', "and", str_replace(' ', '-', str_replace($special_chars,'',$page_name))));
	return $slug_name;
}


function page_title() {
  # outputs the page title in the form of:
  # Section > Page Name - Keyword1 Braces Orthodontics - City ST - Orthodontist(s) Doctor Name(s) Practice Name - State Zip 
  
  global $_section, $_page_name, $_keyword, $_page_title;
  $config = sc_config();
  
  if(!isset($_page_title) || empty($_page_title)) {
    $_page_title = $config['page_title'];
  } 
  
  if (!is_homepage()) {
    if(isset($_keyword) && !empty($_keyword)) { $_page_title = "$_keyword $_page_title"; } # prepend the keyword
    if($_section != $_page_name) { $_page_title = "> $_page_name - $_page_title"; } #prepend the page_name
    $_page_title = "$_section $_page_title"; #prepend the section
  }

  echo $_page_title;
}


function meta_tags() {
    global $_meta_keywords, $_meta_description, $_keyword;
    $config = sc_config();
    
      # append page-specific keyword to 
    if (isset($_keyword) && !empty($_keyword)){$_meta_keywords .= ", $_keyword";}
    
    $meta = array('keywords' => $_meta_keywords, 'description' => $_meta_description);
    
    foreach ($meta as $key => $value){
      if (!isset($value) || empty($value)) {
        $value = $config["meta_$key"];
      }
      $meta_tag = "<meta name=\"$key\" content=\"$value\" />\n";
      echo $meta_tag;
    }
}


function main_navigation($exclusions=array()) {
    # Parse the sitemap hash for the top level nav items.
    # Print them as separate list items.
    # Tag the given $_section with "active" id
    # Takes an array of $exclusions that it will omit from the returned $nav_string
    
    global $_section, $_page_name;
    $sitemap = define_sitemap();
    $nav_string = "<div id=\"nav\"\n><ul>\n";
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
        	$nav_string .= '<li';
        	if ($section == $_section) {
            	$nav_string .= " id=\"active\"";
        	}
        	$slug = slug_name($sub_items[0]);
        	$class = slug_name($section);
        	$nav_string .= "><a href=\"$slug.php\" class=\"$class\">$section</a></li>\n";
    	}
    }
    $nav_string .= "</ul>\n</div>";
    echo $nav_string;
}


function sub_navigation() {
    # Parse the sitemap hash for the current section's nav items.
    # Print them as separate list items.
    # Tag the current section with "active" id
    
    global $_section, $_page_name;
    
    if($_section != 'Home' && $_page_name != 'Home'){
        $sitemap = define_sitemap();
        $sub_nav_string = "<div id=\"subnav\"\n><ul>\n";
        $sub_items = $sitemap[$_section];
        foreach ($sub_items as $sub_name) {
            $sub_nav_string .= '<li';
            # append '.first' and '.last' class names
            if ($sub_name == $sub_items[0]) {$sub_nav_string .= ' class="first"';} 
            elseif ($sub_name == end($sub_items)) {$sub_nav_string .= ' class="last"';}
            if ($sub_name == "$_page_name") { $sub_nav_string .= " id=\"sub_active\"";}
            $slug = slug_name($sub_name);
            $sub_nav_string .= "><a href=\"$slug.php\">$sub_name</a></li>\n";
        }
        $sub_nav_string .= "</ul>\n</div>";
        echo $sub_nav_string;
    }  
}


function text_navigation($exclusions=array()) {

    # Parse the sitemap hash for the top level nav items.
    # Print them as a string of links with a separator between items.
    # Takes an array of $exclusions that it will omit from the returned $nav_string
    
    $sitemap = define_sitemap();
    $nav_string = '<p class="text_nav">';
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
        	$slug = slug_name($sub_items[0]);
        	$nav_string .= "<a href=\"$slug.php\">$section</a>";
        	
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
     $page_stup = slug_name($page);
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
        # $slug = slug_name($section);
        $slug = slug_name($sub_items[0]);
        $sitemap_string .= "<li><a href=\"$slug.php\">$section</a></li>";
        if (count($sub_items) != 1 && $section != $sub_items[0]) { #don't create nexted ul if only sub item is same page
            $sitemap_string .= '<ul>';
            foreach ($sub_items as $sub_name) {
                $sub_stub = slug_name($sub_name);
                # Mark the current page, don't create a self referencing link
                if ($sub_name == $_page_name) {
                    $sitemap_string .= "<li>$sub_name (This Page)</li>";
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


function breadcrumbs($separator=' &#8250') {
    # assembles a breadcrumbs string 
    # The last array item is bolded, 
    # the rest are linked
    
    global $_section, $_page_name;
    
    $bc_hash = array('Home' => 'index', $_section => slug_name($_section), $_page_name => slug_name($_page_name));
    $bc = '<p class="breadcrumbs">';
    $i = 1;
    $count = count($bc_hash);
    foreach($bc_hash as $name => $url){
      if ($i < $count){
        $bc .= "<a href=\"$url.php\">$name</a>";
        $bc .= " $separator ";
      }else{
        $bc .= "<strong>$name</strong><p>";
      }
      $i++;
    }
    echo $bc;
}

?>