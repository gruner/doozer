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
 * checks config.php for 'index_pages' option
 *
 * If true, section links go to a section 'index page' (i.e. "In this section...)
 * Otherwise the section link points to the first subpage.
 *
 * The default behavior is to OMIT section index pages and requires no configuration.
 */
function has_index_pages() {
	$config = sc_config();
	if($config['index_pages'] == true ) {
		return true;
	} else {
		return false;
	}
}


/**
 * returns the practice name if set in config.php
 */
function get_site_name(){
  $config = sc_config();
  $site_name = $config['site_name'];
  if(isset($site_name) || !empty($site_name)) {
		return $site_name;
	}
}


/**
 * determines if the current section has sub-pages,
 * optionally check any section given as a parameter
 */
function has_sub_items($section='')
{
		global $_section;
		# Use the current section unless a specific section is given as a parameter
		if (!$section)
		{
			$section = $_section;
		}
		$sitemap = parse_sitemap();
		$sub_items = $sitemap[$section];
		if(is_array($sub_items) && count($sub_items) > 1){return true;} else {return false;}
}


/**
 * determines the link to the current section
 *
 * if the section's sub items are an array
 * the 'slug name' is used for the link
 *
 * if the section's sub item is a string, the string is used for the link
 * 
 * optionally check any section given as a parameter
 */
function section_link($section='')
{
		global $_section;
		# Use the current section unless a specific section is given as a parameter
		if (!$section)
		{
			$section = $_section;
		}
		$sitemap = parse_sitemap();
		$link = '';
		$sub = $sitemap[$section];
		if (is_array($sub))
		{
			if (has_index_pages() || !has_sub_items($section))
			{
				$link = slug_name($section);
			}
			
			else
			{
				$link = slug_name($sub[0]);
			}
			$link .= '.php';
		}
		elseif (is_string($sub))
		{
			$link = $sub;
		}
		
		return $link;
}

/**
 * creates a 'slug name' by stripping 
 * special characters from the given page name
 * and replacing spaces with dashes.
 * 
 * Used for setting unique id's on <<li>> elements
 * in navigation as well as linking to files that follow our naming convention.
 */
function slug_name($string) {
	return replace_chars(strtolower(strip_special_chars($string)));
}


/**
 * Strips special characters from a string.
 */
function strip_special_chars($string) {
	# Define special characters that will be stripped from the name
	$special_chars = array('.',',','?','!','|',':','"',"'",'*','&#39;','&copy;','&reg;','&trade;');	
	$processed_string = str_replace($special_chars, '', $string);
	return $processed_string;
}


/**
 * Loops through a hash of replacements
 * and replaces the key with its value in the given string.
 *
 * $replacements array has default values which can be overridden when called
 */
function replace_chars($string, $replacements=array('&amp;' => 'and','&' => 'and', ' ' => '-','/' => '-')) {
	return str_replace(array_keys($replacements), array_values($replacements), $string);
}

/**
 * Converts a string to make it suitable for use in a title tag.
 * Similar to slug_name, but keeps spaces.
 */
function titleize($string) {
	$titleized_name = strip_special_chars($string);
	$titleized_name = replace_chars($titleized_name, $replacements=array('&amp;' => 'and', '&' => 'and', '/' => '-'));
	return $titleized_name;
}

/**
 * echoes the complete title tag of the page
 *
 * Follows the convention of:
 * Section > Page Name - Keyword1 Braces Orthodontics - City ST - Orthodontist(s) Doctor Name(s) Practice Name - State Zip
 * Looks for local $_page_title variable but defaults to the value definded in config.php
 */
function page_title() {
	global $_section, $_page_name, $_keyword, $_page_title;
	$config = sc_config();
	
	if(!isset($_page_title) || empty($_page_title)) {
		$_page_title = $config['page_title'];
	}
	
	if(!isset($_keyword) || empty($_keyword)) {
		$_keyword = $config['title_keywords'];
	}
	
	# prepend the keyword to the title
	$_page_title = "$_keyword - $_page_title"; 
	
	if (!is_homepage()) {
		if($_section != $_page_name) { 
			$_page_title = "$_section > $_page_name - $_page_title";
		} else {
			#prepend the section
			$_page_title = "$_section - $_page_title";
		} 
	}
	
	# remove special chars from page name
	$_page_title = titleize($_page_title);

	echo "<title>$_page_title</title>";
}


/**
 * echoes completed meta description and meta keyword tags
 * 
 * looks for local $_meta_keywords and $_meta_description variables but 
 * defaults to the values defined in config.php.
 */
function meta_tags() {
		global $_keyword, $_description;
		
		$config = sc_config();
		
		$meta_keywords = $config['meta_keywords'];
		$meta_description = $config['meta_description'];
		
		if (isset($_keyword) && !empty($_keyword)){
		# append page-specific keyword to global keywords string
			$keyword = strtolower($_keyword);
			$meta_keywords .= ", $keyword";
		}
		
		# replace global description with local description if it exists
		if (isset($_description) && !empty($_description)){$meta_description = $_description;}
		
		$meta = array('keywords' => $meta_keywords, 'description' => $meta_description);
		
		foreach ($meta as $key => $value){
			if (!isset($value) || empty($value)) {
				$value = $config["meta_$key"];
			}
			$meta_tag = "<meta name=\"$key\" content=\"$value\" />\n";
			echo $meta_tag;
		}
}


function parse_sitemap() {
	$defined_sitemap = define_sitemap();
  $sitemap = array();
  foreach ($defined_sitemap as $section => $sub_section) {
		$sitemap = parse_section($sitemap, $section, $sub_section);
  }
  return $sitemap;
}


function parse_section($sitemap, $section, $sub_section) {
	if (is_string($section)) {
		$sitemap[$section] = parse_sub_section($section, $sub_section);
	} else {
		$sitemap[$sub_section] = parse_sub_section($section, $sub_section);
	}
	return $sitemap;
}


function parse_sub_section($section, $sub_section) {
	if (is_numeric($section)) {
		# make link from page name
		$sub = slug_name($sub_section);
	} else {
		if (is_string($sub_section)) {
			return $sub_section;
		}	elseif (is_array($sub_section)) {
			$sub = array();
			# recusivly call this function for each section that has a sub-section
			foreach ($sub_section as $sub_key => $sub_value) {
				$sub = parse_section($sub, $sub_key, $sub_value);
			}
	  }	else {
			$sub = 'error parsing sitemap';
		}
	}
	return $sub;
}


function filter_sitemap($input, $callback = null)
{
  foreach ($input as $key => $value)
  {
    if (is_array($value))
    {
      $value = filter_sitemap($value, $callback);
    }
  }
  return array_filter($input, $callback);
}


function format_sitemap($input){
	$formatted = '<ul>';
  foreach ($input as $key => $value)
  {
  	$formatted .= '<li>';
    if (is_array($value))
    {
    	//$link = section_link($key);
    	$link = $value['Meet the Orthodontist'];
    	$formatted .= "<a href=\"$link\">$key</a>";
      $formatted .= format_sitemap($value);
    }
    
    else
    {
    	$formatted .= "<a href=\"$value\">$key</a></li>";
    }
    
  }
  $formatted .= '</ul>';
  return $formatted;
}


function sitemap_link($page, $link)
{
	return "<li><a href=\"$link\">$page</a></li>";
}


function test_callback()
{
	$sitemap = parse_sitemap();
	$sitemap =format_sitemap($sitemap);
	echo $sitemap;
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
 * @param string $div_id optionally define the id of the generated div
 */
function navigation($exclusions=array(), $include_sub_nav=false, $div_id='nav') {
		global $_section, $_page_name;
		$sitemap = define_sitemap();
		$nav_string = "<div id=\"$div_id\">\n<ul>\n";
		foreach ($sitemap as $section => $sub_items) {
			# skip any sections that are in the exclusions array
			if (!in_array($section, $exclusions)) {
					$slug = slug_name($section);
					$nav_string .= "<li";
					$nav_string .= get_li_attributes($_section, $section, $sitemap); # set id and class names for the list item
					$link = section_link($section);
					$nav_string .= "><a href=\"$link\" id=\"$slug\"";
          # add class name 'head' to items with sub navigation for accordian styling
          if(has_sub_items($section)){
            $nav_string .= ' class="head"';
          }
          $nav_string .= ">$section</a>\n";
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
 * a wrapper for calling navigation() with included sub navigation
 * 
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @see navigation()
 */
function full_navigation($exclusions=array()) {
	navigation($exclusions, $include_sub_nav=true);
}


/**
 * depricated, use navigation() instead
 * 
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @see navigation()
 */
function main_navigation($exclusions) {
	navigation($exclusions, $include_sub_nav=false);
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
function sub_navigation($section='', $pre_text='') {
  
  # Use the current section unless a specific section is given as a parameter
	if (!$section){
		$section = $_section;
	}
    
	if (has_sub_items($section)) {
		$sub_nav = sub_nav_ul($section);
		echo "<div id=\"subnav\">\n";
		if ($pre_text) { echo "$pre_text"; }
		echo "$sub_nav\n";
		echo "</div>\n";
	}
}


/**
 * @see sub_navigation()
 */
function sub_navigation_with_heading($section='', $link=false) {

		global $_section;
		
		# Use the current section unless a specific section is given as a parameter
		if (!$section){
			$section = $_section;
		}
	
	$heading = "<h3>"; 
	if ($link) {
		$heading_link = section_link($section);
		$heading .= "<a href=\"$heading_link\">"; 
	}
	$heading .= $section;
	if ($link) { $heading .= "</a>"; }
	$heading .= "</h3>"; 
	
	sub_navigation($section, $heading);
}


/**
 * creates a formatted <<ul>> of the current section's sub links
 *
 * adds 'class="active"' to the current page and gives each <<li>> a unique id based on the 'slug name'
 *
 * @param string $section optionally show subnav links for specific section
 * @param bool $include_attr optionally omit class and id attributes set for each <<li>>
 * @return string the subnav as a <<ul>>
 * @see slug_name()
 * @see sub_navigation() wraps this function in a <<div>>
 */
function sub_nav_ul($section='', $include_attr=true) {
	 
		global $_section, $_page_name;
		
		# Use the current section unless a specific section is given as a parameter
		if (!$section){
			$section = $_section;
		}
		
		$sitemap = define_sitemap();
		$sub_nav_string = "<ul>\n";
		$sub_items = $sitemap[$section];
		foreach ($sub_items as $key => $value) {
			
      $slug = slug_name($value);
      
			if (is_string($key)){
				$sub_name = $key;
				$sub_link = $value;
			}else{
				$sub_name = $value;
				$sub_link = "$slug.php";
			}
			
			$sub_nav_string .= "<li";
			if ($include_attr){
				$sub_nav_string .= get_li_attributes($_page_name, $sub_name, $sub_items);
			}
			$sub_nav_string .= "><a href=\"$sub_link\" id=\"$slug\">$sub_name</a></li>\n";
		}
		$sub_nav_string .= "</ul>\n";
		return $sub_nav_string;
}


/**
 * creates a formatted <<p>> of the current section's sub links with ' | ' between each link
 *
 * adds 'class="active"' to the current link
 *
 * @param array $breaks optionally add breaks at specific points in the list
 * @param string $separator optionally specify text that will be inserted between each link
 * @param string $class_name optionally specify the class name of the generated <<p>> tag
 * @param string $section optionally show subnav links for specific section
 * @param bool $include_attr optionally omit class names set for each link
 */
function sub_nav_p($breaks=array(), $separator=' | ', $class_name='sub_nav', $section='', $include_attr=true) {
	 
		global $_section, $_page_name;
		
		# Use the current section unless a specific section is given as a parameter
		if (!$section){
			$section = $_section;
		}
		
		# don't do output anything further if there are no sub items
		if (!has_sub_items($section)) {return;}
		
		$formatted_list = "<p class=\"$class_name\">";
		
		$link_array = array();
		$sitemap = define_sitemap();
		$sub_items = $sitemap[$section];
		foreach ($sub_items as $sub_name) {
			$slug = slug_name($sub_name);
			$link = "<a href=\"$slug.php\"";
			if ($include_attr){
				$link .= get_li_attributes($_page_name, $sub_name, $sub_items);
			}
			$link .= ">$sub_name</a>";
			$link_array[] = $link;
		}
		
		
		# separate the list of links into separate arrays for adding breaks
		if ($breaks) {
			$link_blocks = array();
			$break_count = sizeof($breaks);
			for($j = 0; $j <= $break_count; $j++){
				switch ($j) {
				case 0: #first
					$offset = 0;
					$length = $breaks[$j];
					break;
				case $break_count: #last
					$offset = $breaks[$j-1];
					$length = sizeof($link_array) - $offset;
					break;
				default:
					$offset = $breaks[$j-1];
					$length = $breaks[$j] - $breaks[$j-1];
				}
				$link_blocks[$j] = array_slice($link_array, $offset, $length);
			}
			# loop through newly created blocks and insert the
			for($j = 0; $j < sizeof($link_blocks); $j++){
				$link_blocks[$j] = format_list_with_separator($link_blocks[$j], $separator); # add separator between each link
			}
			# add breaks between each block of links
			$formatted_list .= format_list_with_separator($link_blocks, '<br />');
		} else {
			# if no breaks, add the separator to the raw list
			$formatted_list .= format_list_with_separator($link_array, $separator);
		}
		
		$formatted_list .= '</p>';
		echo $formatted_list;
}


/**
 * gets id and class names for each <<li>> in the navigation
 *
 * * adds 'first' and 'last' classes to the first and last items in the list<br/>
 * * adds 'active' class to the current section or page<br/>
 *
 * @param string $current_item the current page or section for setting the 'active' class
 * @param string $child can be a page_name or section as contained in the array $children
 * @param array $children the array that contains $child
 * @return string the formatted attributes for the <<li>>
 * @todo rename because it's not just formatting li's anymore
 */
function get_li_attributes($current_item, $child, $children){
	$class = array();
	if($child == $children[0]){$class[] = 'first';}
	elseif($child == end($children)){$class[] = 'last';}
	if($child == $current_item){$class[] = 'active';}
	if($class){
		$classes = implode(' ', $class);
		$attr .= " class=\"$classes\"";
	}
	return $attr;
}


/**
 * echoes a formatted list of the top-level navigation links for placement as the text navigation
 *
 * Examples:
 * {@example text_navigation.php}
 *
 * @param integer $br optionally force a line break after the nth text link
 * @param array $exclusions optionally omit specific sections from the echoed $nav_string
 * @todo allow multiple <br/>s
 */
function text_navigation($br=0, $exclusions=array()) {
		$sitemap = define_sitemap();
		$nav_string = '<p class="text-nav">';
		$i = 1;
		foreach ($sitemap as $section => $sub_items) {
			# skip any sections that are in the exclusions array
			if (!in_array($section, $exclusions)) {
				$link = section_link($section);
				$nav_string .= "<a href=\"$link\">$section</a>";
				
				# add a <br/> tag if given as a param
				if($br == $i){
					$nav_string .= '<br />';
				}
				
				# add a separator unless it's the last item in the list or at a break
				if ((count($sitemap) - count($exclusions)) != $i && $br != $i) {
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
	$index = "<h2>In this section:</h2>";
	$index .= sub_nav_ul($_section, false);
	echo $index;
}


/**
 * echoes a formatted sitemap in the form of nested lists with links to each page
 * @see sitemap.php
 */
function sitemap($exclusions=array()) {
		
		global $_page_name;  
		
		$sitemap = parse_sitemap();
		$sitemap_string = '<ul class="sitemap">';
		foreach ($sitemap as $page => $links) {
		  # skip any sections that are in the exclusions array
			if (!in_array($page, $exclusions)) {
				if ($page == $_page_name) {
					$sitemap_string .= "<li>$page (This Page)"; #leave <li> open
				} else {
					$link = section_link($section);
					$sitemap_string .= "<li><a href=\"$link\">$section</a>"; #leave <li> open
				}
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
				$sitemap_string .= '</li>'; # close section <li>
			}
		}
		$sitemap_string .= '</ul>';
		echo $sitemap_string;
}


/**
 * echoes a formatted string with links to the current page's parent(s).
 *
 * * $separator string defaults to the &#8250; character but can be overridden with any string.
 * * current page is bolded and unlinked.
 *
 * @param string $separator the text or html character that will separate each breadcrumb (optional)
 * @todo refactor to use format_list_with_separator
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
				$bc .= "<strong>$name</strong></p>";
			}
			$i++;
		}
		echo $bc;
}


/**
 * formats an array into a single string by inserting the given separator string between items
 *
 * @param array $list 
 * @param string $separator the string inserted between items
 */
function format_list_with_separator($list, $separator=' | ') {
		$formatted_list = implode("$separator", $list);
		return $formatted_list;
}


function render_image_tag($file='', $alt='', $class='', $title) {
  list($w, $h) = getimagesize("images/$file");
	$img_tag = "<img src=\"images/$file\" width=\"$w\" height=\"$h\"";
	if($class){$img_tag .= " class=\"$class\"";}
	if($alt){$img_tag .= " alt=\"$alt\"";}
	if($title){$img_tag .= " alt=\"$title\"";}
	$img_tag .= " />";
	
	echo $img_tag;
}

/**
 * echoes a formatted image tag with calculated width and height attributes
 *
 * if $file isn't specified, looks for any image named after the $_page_name
 *
 * if $alt isn't specified, looks for page variable $_alt
 *
 * @param string $file text for image's 'src' attribute (assumes file is in '/images' directory)
 * @param string $alt text for image's alt attribute (optional, defaults to page's _alt variable, omits attribute if not set)
 * @param string $class text for image's class attribute (optional, omits attribute if not set)
 */
function place_image($file='', $alt='', $class='', $title) {
	
	global $_alt, $_page_name;
	
	if (!$alt){
		$alt = $_alt;
	}
	
	if (!$file){
		$file = slug_name($_page_name);
	}
	
	if (file_exists("images/$file")){
		render_image_tag($file, $alt, $class, $title);
	} else {
	  # look for missing extensions
	  $extensions = array('.jpg', '.gif', '.png');
	  foreach($extensions as $ext){
	    $try_file = $file.$ext;
	    if(file_exists("images/$try_file")){
	      render_image_tag($try_file, $alt, $class, $title);
	      break;
	    }
	  }
	}
}


/**
 * calls place_image() if the $_alt variable is set for the page
 */
function place_image_if_alt(){
	global $_alt;
	if ($_alt){place_image('','','auto');}
}


/**
 * echoes the script tag for creating a spam-friendly email link
 */
function email_link($name, $domain){
	$js = "<script type=\"text/javascript\">\n";
	$js .= "<!--\n";
	$js .= "var name = \"$name\";\n";
	$js .= "var domain = \"$domain\";\n";
	$js .= "document.write('<a href=\\\"mailto:' + name + '@' + domain + '\\\">');\n";
	$js .= "document.write(name + '@' + domain + '</a>');\n";
	$js .= "// -->\n";
	$js .= "</script>\n";
	echo $js;
}



/**
 * echoes a div tag for embedding flash media with a standard notice if flash is not available.
 */
function flash_div($div_name){
	$div = "<div id=\"$div_name\">\n";
	$div .= "<p class=\"notice\">The intended media clip requires a newer version of Adobe Flash&reg; Player. Please visit <a href=\"http://www.adobe.com/go/getflashplayer\">www.adobe.com</a> to download the latest version.</p>\n";
	$div .= "</div>\n";
	echo $div;
}