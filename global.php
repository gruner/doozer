<?php
/**
 * This file contains several php functions for easily creating dynamic navigation by utilizing the site stucture defined in {@link sitemap.php}.
 *
 * It also contains several utility functions for quickly generating other common html elements.
 *
 * Using our existing approach of defining '$page' and '$section' variables for each page (this file uses the convention '$_page_name', and '$_section' to indicate that they are local to each page), we can call the following functions from any page and get dynamicly generated navigation elements.
 *
 * To enable this functionality simply include this file on every page, similar to how we're already including the header and footer files. Then define the site structure in {@link sitemap.php}, also kept in the includes folder. (Note: The root level 'Site Map' page should be named 'site-map.php' to avoid conflicting names.)
 
 @package scFramework
 */


/**
 * the configuration file for the site which stores site-specific settings
 * such as page title and meta information
 *
 * allows for making site-specific changes without having to edit {@link global.php}
 */
require_once('config.php');

/**
 * the sitemap for the site, which we use for generating our various navigation elements
 */
require_once('sitemap.php');


/**
 * checks $_section and $_page_name to see if the current page is the homepage
 * 
 * @global string $_section
 * @global string $_page_name
 * @return bool
 */
function is_homepage() {
  global $_section, $_page_name;
  if($_section == 'Home' && $_page_name == 'Home'){return true;} else {return false;}
}


/**
 * determines if the given section has sub-pages
 *
 * defaults to checking the current section
 * 
 * @global string $_section
 * @param string check specific section for sub-pages
 * @return bool if the current section has sub-pages
 */
function has_sub_items($section='') {
    global $_section;
    # Use the current section unless a specific section is given as a parameter
    if (!$section){
      $section = $_section;
    }
    $sitemap = define_sitemap();
    $sub_items = $sitemap[$section];
    if(count($sub_items) > 1){return true;} else {return false;}
}

/**
 * creates a 'slug name' by stripping 
 * special characters from the given page name
 * and replacing spaces with dashes.
 * 
 * Used for setting unique id's on <<li>> elements
 * in navigation as well as linking to files that follow Sesame's naming convention.
 * 
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
 * echoes the complete title tag of the page
 
 * Follows the convention of: <br/>
 * Section > Page Name - Keyword1 Braces Orthodontics - City ST - Orthodontist(s) Doctor Name(s) Practice Name - State Zip <br/>
 * Looks for local $_page_title variable but defaults to the value definded in {@link config.php}
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
 * echoes completed meta description and meta keyword tags
 * 
 * looks for local $_meta_keywords and $_meta_description variables but 
 * defaults to the values defined in {@link config.php}.
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
 * echoes a formatted list of the top-level navigation links wrapped in a div tag
 * 
 * * adds the slug name as the id of each <<li>><br/>
 * * adds 'class="active"' to the current section<br/>
 * * optionally includes the subnav items as a nested <<ul>>
 * 
 * Example: <br/>
 * {@example main_navigation.php}
 * 
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
        	$slug = slug_name($section);  # use $sub_items[0] to skip 'index' page
        	$nav_string .= "><a href=\"$slug.php\">$section</a>\n";
          if($include_sub_nav && has_sub_items($section)){
            $nav_string .= sub_nav_ul($section);
          }
          $nav_string .= "</li>\n";
    	}
    }
    $nav_string .= "</ul>\n</div>";
    echo $nav_string;
}


/**
 * echoes a <<ul>> wrapped in a <<div>> of the subnav links for the current section
 *
 * optionally get the subnav links for any section given as a parameter
 *
 * Example: <br/>
 * {@example sub_navigation.php}
 *
 * @param string $section optionally show subnav links for specific section
 * @see sub_nav_ul()
 */
function sub_navigation($section='') {
  $sub_nav = sub_nav_ul($section);
  echo "<div id=\"subnav\">\n";
  echo "$sub_nav\n";
  echo "</div>\n";
}


/**
 * creates a formatted <<ul>> of the current section's sub links
 *
 * adds 'class="active"' to the current page and gives each <<li>> a unique id based on the 'slug name'
 *
 * @param string $section optionally show subnav links for specific section
 * @return string the subnav as a <<ul>>
 * @see slug_name()
 * @see sub_navigation() wraps this function in a <<div>>
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
 * gets id and class names for each <<li>> in the navigation
 *
 * * adds id='slug-name'<br/>
 * * adds 'first' and 'last' classes to the first and last items in the list<br/>
 * * adds 'active' class to the current section or page<br/>
 *
 * @param string $current_item the current page or section for setting the 'active' class
 * @param string $child can be a page_name or section as contained in the array $children
 * @param array $children the array that contains $child
 * @return string the formatted attributes for the <<li>>
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
 * a wrapper for calling main_navigation() with included sub navigation
 * 
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @see main_navigation()
 */
function full_navigation($exclusions=array()) {
  main_navigation($exclusions, $include_sub_nav=true);
}


/**
 * echoes a formatted list of the top-level navigation links for placement as the text navigation
 *
 * Examples:
 * {@example text_navigation.php}
 *
 * @param integer $br optionally force a line break after the nth text link
 * @param array $exclusions optionally omit specific sections from the echoed $nav_string
 */
function text_navigation($br=0, $exclusions=array()) {
    $sitemap = define_sitemap();
    $nav_string = '<p class="text_nav">';
    $i = 1;
    foreach ($sitemap as $section => $sub_items) {
    	# skip any sections that are in the exclusions array
    	if (!in_array($section, $exclusions)) {
        $slug = slug_name($section);
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
 * echoes a formatted list of the current section's links
 *
 * adds a header that reads: <br/>
 * 'In this section:'
 * 
 * @param string $section optionally show the index for any given section
 */
function section_index($section="") {

  global $_section; 

  $sitemap = define_sitemap();
  $index = "<h2>In this section:</h2>\n<ul class=\"index\">";
  $index .= sub_nav_ul();
  echo $index;
}


/**
 * echoes a formatted sitemap in the form of nested lists with links to each page
 * @see sitemap.php
 */
function sitemap() {
    
    global $_page_name;  
    
    $sitemap = define_sitemap();
    $sitemap_string = '<ul class="sitemap"><li><a href="index.php">Home</a>';
    foreach ($sitemap as $section => $sub_items) {
        $slug = slug_name($section);
        $sitemap_string .= "<li><a href=\"$slug.php\">$section</a></li>";
        if (has_sub_items($section)) { #don't create nested ul if the only sub item is the same page
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
        $sitemap_string .= '</li>';
    }
    $sitemap_string .= '</ul>';
    echo $sitemap_string;
}

/**
 * echoes a formatted string with links to the current page's parent(s).
 *
 * * $separator string defaults to the &#8250; character but can be overridden with any string<br/>
 * * current page is bolded and unlinked.
 *
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
 * echoes a formatted image tag with calculated width and height attributes
 *
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