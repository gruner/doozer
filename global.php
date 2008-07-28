<?php
/**
 * This file contains several php function for easily creating dynamic navigation by utilizing the site stucture defined in {@link sitemap.php}.
 *
 * It also contains several utility functions for quickly generating other common html elements.
 *
 * Using our existing approach of defining '$page' and '$section' variables for each page (this file uses the convention '$_page_name', and '$_section' to indicate that they are local to each page), we can call the following functions from any page and get dynamicly generated navigation elements.
 *
 * To enable this functionality simply include this file on every page, similar to how we're already including the header and footer files. Then define the site structure in {@link sitemap.php}, also kept in the includes folder. (Note: The root level 'Site Map' page should be named 'site-map.php' to avoid conflicting names.)
 
 @package scFramework
 */


/**
 * Include the configuration file for the site which stores site-specific settings
 * such as page title and meta information. This allows for making site-specific changes
 * without having to edit global.php.
 */
require_once('config.php');

/**
 * Include the sitemap for the site, which we parse in order to generate the navigation.
 */
require_once('sitemap.php');


/**
 * Checks $_section and $_page_name to see if the current page is the homepage
 * @global string $_section
 * @global string $_page_name
 * @return bool
 */
function is_homepage() {
  global $_section, $_page_name;
  if($_section == 'Home' && $_page_name == 'Home'){return true;} else {return false;}
}


/**
 * Creates a 'slug name' by stripping 
 * special characters from the given page name
 * and replacing spaces with dashes.
 * This function is used to set unique id's for <<li>> elements
 * in navigation as well as linking to files that follow the proper naming scheme.
 * @param string $page_name the name of the page to convert
 * @return string processed string
 */
function slug_name($page_name) {
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','/','!','|',':','"','*','&#39;','&copy;','&reg;','&trade;');	
	$slug_name = strtolower(str_replace('&amp;', "and", str_replace(' ', '-', str_replace($special_chars,'',$page_name))));
	return $slug_name;
}


/**
 * Echo's a formatted title following the convention of:
 * Section > Page Name - Keyword1 Braces Orthodontics - City ST - Orthodontist(s) Doctor Name(s) Practice Name - State Zip
 * Looks for local $_page_title variable but defaults to the value definged in config.php
 */
function page_title() {
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

  echo "<title>$_page_title</title>";
}


/**
 * Echo's formatted meta description and meta keyword tags. 
 * Looks for local $_meta_keywords and $_meta_description variables but
 * defaults to the values defined in config.php.
 */
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


/**
 * Echos a formatted list of the top-level navigation links.
 * Adds the slug name as the id of each <<li>>. 
 * Adds 'class="active"' to the current section.
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @param bool $include_sub_nav optionally include nested sub navigation
 */
function main_navigation($exclusions=array(), $include_sub_nav=false) {
    global $_section, $_page_name;
    $sitemap = define_sitemap();
    $nav_string = "<div id=\"nav\">\n<ul>\n";
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
          $slug = slug_name($section);
        	$nav_string .= "<li";
          $nav_string .= get_li_attributes($_section, $section, $sitemap); # set id and class names for the list item
        	$slug = slug_name($sub_items[0]);
        	$nav_string .= "><a href=\"$slug.php\">$section</a>\n";
          if($include_sub_nav && $sub_items[0] != $section){
            $nav_string .= sub_nav_ul($section);
          }
          $nav_string .= "</li>\n";
    	}
    }
    $nav_string .= "</ul>\n</div>";
    echo $nav_string;
}


/**
 * Echo's a formatted <<div>> of the current section's sub links by taking the output of 
 * sub_nav_ul() and wrapping it in a <<div>>.
 * @param string $section optionally show sub_navigation links for any given section
 * @see sub_nav_ul()
 */
function sub_navigation($section='') {
  $sub_nav = sub_nav_ul($section);
  echo "<div id=\"subnav\">\n";
  echo "$sub_nav\n";
  echo "</div>\n";
}


/**
 * Creates a formatted <<ul>> of the current section's sub links.
 * Adds 'class="active"' to the current page and gives each <<li>> a unique id based on the 'slug name'.
 * @param string $section optionally show sub_navigation links for any given section
 * @return the subnav as a <<ul>>
 * @see slug_name()
 */
function sub_nav_ul($section='') {
   
    global $_section, $_page_name;
    
    # Use the current section unless a specific section is given as a parameter
    if (!$section){
      $section = $_section;
    }
    
    if(!is_homepage()){
        $sitemap = define_sitemap();
        $sub_nav_string = "<ul>\n";
        $sub_items = $sitemap[$section];
        foreach ($sub_items as $sub_name) {
            $slug = slug_name($sub_name);
            $sub_nav_string .= "<li";
            $sub_nav_string .= get_li_attributes($_page_name, $sub_name, $sub_items);
            $sub_nav_string .= "><a href=\"$slug.php\">$sub_name</a></li>\n";
        }
        $sub_nav_string .= "</ul>\n";
        return $sub_nav_string;
    }  
}


/**
 * Sets id and class names for a list item. 
 * Adds id='slug-name' and 
 * adds 'first' and 'last' classes to appropriate list items. 
 * Also adds 'active' class to the current section or page.
 * @param string $current_item the current page or section for setting the 'active' class
 * @param string $child can be a page_name or section as contained in the array $children
 * @param array $children the array that contains $child
 * @return the formatted attributes as a string
 */
function get_li_attributes($current_item, $child, $children){
  $class = array();
  if($child == $children[0]){$class[] = 'first';}
  elseif($child == end($children)){$class[] = 'last';}
  if($child == $current_item){$class[] = 'active';}
  $slug = slug_name($child);
  $attr = " id=\"$slug\"";
  if($class){
    $classes = implode(' ', $class);
    $attr .= " class=\"$classes\"";
  }
  return $attr;
}


/**
 * A wrapper for calling main_navigation() with included sub navigation.
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @see main_navigation()
 */
function full_navigation($exclusions=array()) {
  main_navigation($exclusions, $include_sub_nav=true);
}


/**
 * Echo's a formatted list of the top-level navigation links.
 * @param integer $br optionally force a break tag after the nth text link
 * @param array $exclustions optionally omits any given sections from the echoed $nav_string
 */
function text_navigation($br=0, $exclusions=array()) {
    $sitemap = define_sitemap();
    $nav_string = '<p class="text_nav">';
    $i = 1;
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
        $slug = slug_name($sub_items[0]);
        $nav_string .= "<a href=\"$slug.php\">$section</a>";
        
        # add a <br/> tag if given as a param
        if($br == $i){
          $nav_string .= '<br />';
        }
        
        # add a separator unless it's the last item in the list        	
        if ($sitemap[$section] != end($sitemap) && ($br != $i)) {
          $nav_string .= ' | ';
        }
        $i++;
    	}
    }
    $nav_string .= '</p>';
    echo $nav_string;
}


/**
 * Echo's a formatted list of the current section's links with a header that reads 
 * 'In this section:'
 * @param string $section optionally show the index for any given section
 */
function section_index($section="") {

  global $_section; 

  $sitemap = define_sitemap();
  $index = "<h2>In this section:</h2>\n<ul class=\"index\">";
  
  # Use the current section unless a specific section is given as a parameter
  if (!$section){
    $section = $_section;
  }
  $section = $sitemap[$section];
  foreach ($section as $page) {
     $slug = slug_name($page);
     $index .= "<li><a href=\"$slug.php\">$page</a></li>";
  }
  $index .= '</ul>';
  echo $index;
}


/**
 * Echo's a formatted sitemap in the form of nested lists with links to each page.
 * @see sitemap.php
 */
function sitemap() {
    
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
        if (count($sub_items) != 1 && $section != $sub_items[0]) { #don't create nested ul if the only sub item is the same page
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

/**
 * Echo's a formatted string with links to the current page's parent(s).
 * The $separator string defaults to the &#8250; character but can be overridden with any string.
 * The current page is bolded and unlinked.
 * @param string $separator the text or html character that will separate each breadcrumb (optional)
 */
function breadcrumbs($separator='&#8250;') {
    
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


/**
 * Echo's a formatted image tag with calculated width and height attributes.
 * @param string $file text for image's 'src' attribute (relative path to the file, not just the name)
 * @param string $alt text for image's alt attribute (optional, defaults to page's _alt variable, omits attribute if not set)
 * @param string $title text for image's title attribute (optional, omits attribute if not set)
 */
function place_image($file, $alt='', $title=''){
  
  global $_alt;
  
  if (!$alt){
    $alt = $_alt;
  }

  list($w, $h) = getimagesize($file);
  $img_tag = "<img src=\"$file\" width=\"$w\" height=\"$h\"";
  if($alt){$img_tag .= " alt=\"$alt\"";}
  if($title){$img_tag .= " title=\"$title\"";}
  $img_tag .= " />";
  
  echo $img_tag;
}
?>