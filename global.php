<?php
/**
 * This file contains several php functions for easily creating dynamic navigation by utilizing the site structure defined in sitemap.php.
 *
 * It also contains several utility functions for quickly generating other common html elements.
 *
 * Using our existing approach of defining '$page' and '$section' variables for each page (this framework uses the convention '$_page_name', and '$_section' to indicate that they are local to each page), we can call the following functions from any page and get dynamically generated navigation elements.
 *
 * To enable this functionality simply include this file on every page, similar to how we're already including the header and footer files. Then define the site structure in sitemap.php, also kept in the includes folder. (Note: The root level 'Site Map' page should be named 'site-map.php' to avoid conflicting names.)

 @package scFramework
 */


/**
 * Include the configuration file for the site which stores site-specific settings
 * such as page title and meta information. This allows for site-specific changes
 * without having to edit global.php.
 */
require_once('config.php');
require_once('sitemap.php'); // Include the sitemap for the site, which we parse in order to generate the navigation.
//include_once('ie6_alert.php');



# ============================================================================ #
#    UTILITY FUNCTIONS                                                         #
# ============================================================================ #


/**
 * Checks $_section and $_page_name to see if the current page is the homepage
 */
function is_homepage()
{
  global $_section, $_page_name;
  return ($_section == 'Home' && $_page_name == 'Home');
}


/**
 * Checks config.php for 'index_pages' option
 *
 * If true, section links go to a section 'index page' (i.e. "In this section...)
 * Otherwise the section link points to the first sub-page.
 *
 * The default behavior is to OMIT section index pages and requires no configuration.
 */
function has_index_pages()
{
  global $config;
  return (isset($config['index_pages']) && $config['index_pages'] == true);
}


/**
 * Returns the site name if set in config.php
 */
function get_site_name()
{
  global $config;
  $site_name = $config['site_name'];
  if (exists($site_name))
  {
    return $site_name;
  }
}


/**
 * Creates a 'slug name' by stripping
 * special characters from the given page name
 * and replacing spaces with dashes.
 *
 * Used for setting unique id's on <li> elements
 * in navigation as well as linking to files that follow our naming convention.
 */
function slug_name($string)
{
  return replace_chars(strtolower(strip_special_chars($string)));
}


/**
 * Strips special characters from a string.
 */
function strip_special_chars($string)
{
  # Define special characters that will be stripped from the name
  $special_chars = array('.',',','?','!','$','|','(',')',':','"',"'",'*','&#39;','&copy;','&reg;','&trade;');
  $processed_string = str_replace($special_chars, '', $string);
  return $processed_string;
}


/**
 * Loops through a hash of replacements
 * and replaces the key with its value in the given string.
 *
 * $replacements array has default values which can be overridden when called
 */
function replace_chars($string, $replacements=array('&amp;' => 'and','&' => 'and', ' ' => '-','/' => '-'))
{
  return str_replace(array_keys($replacements), array_values($replacements), $string);
}


/**
 * Converts a string to make it suitable for use in a title tag.
 * Similar to slug_name, but keeps spaces.
 */
function sanitize_title_text($string)
{
  $sanitized_text = strip_special_chars($string);
  $sanitized_text = replace_chars($sanitized_text, $replacements=array('&amp;' => 'and', '&' => 'and', '/' => '-'));
  return $sanitized_text;
}


/**
 * formats an array into a single string by inserting the given separator string between items
 */
function format_list_with_separator($list, $separator=' | ')
{
  $formatted_list = implode("$separator", $list);
  return $formatted_list;
}


/**
 * Checks to see if a variable has a value
 */
function exists($var)
{
  return (isset($var) && !empty($var));
}


/**
 * Returns a default value if the variable doesn't exist
 */
function use_default($var, $default='')
{
  return (exists($var)) ? $var : $default;
}


# ============================================================================ #
#    HTML HELPERS                                                              #
# ============================================================================ #


/*
 * Returns the complete title tag of the page.
 * Looks for local $_title variable but defaults to the value definded in config.php
 */
function title_tag()
{
  global $_page_name, $_title, $config;

  $title = use_default($_title, "$_page_name - ".$config['page_title']);

  # remove special chars from page name
  $title = sanitize_title_text($title);
  return "<title>$title</title>";
}

function print_page_title()
{
  echo title_tag();
}


/**
 * Returns completed meta description and meta keyword tags.
 *
 * looks for local $_keywords and $_description variables but
 * defaults to the values defined in config.php.
 */
function meta_tags()
{
  global $_keywords, $_description, $config;

  $meta_tags = '';

  $meta = array('keywords' => $config['meta_keywords'], 'description' => $config['meta_description']);

  foreach ($meta as $key => $value)
  {
    $default = '_'.$key;
    $value = use_default($$default, $value); # use the local variable if it exists
    $meta_tags .= "<meta name=\"$key\" content=\"$value\" />\n";
  }
  return $meta_tags;
}

function print_meta_tags()
{
  echo meta_tags();
}


/**
 * Returns a formatted h1 tag
 *
 * Uses $_page_name for the text unless $_h1 is set
 */
function h1_tag()
{
  global $_page_name, $_h1;

  $h1 = use_default($_h1, $_page_name);

  if ($h1 != false)
  {
    $slug = slug_name($_page_name);
    return "<h1 class=\"$slug\">$h1</h1>";
  }
}


function print_image_tag($file='', $alt='', $class='', $title='')
{
  list($w, $h) = getimagesize("images/$file");
  $img_tag = "<img src=\"images/$file\" width=\"$w\" height=\"$h\"";
  if($class){$img_tag .= " class=\"$class\"";}
  if($alt){$img_tag .= " alt=\"$alt\"";}
  if($title){$img_tag .= " alt=\"$title\"";}
  $img_tag .= " />";

  echo $img_tag;
}

/**
 * prints a formatted image tag with calculated width and height attributes
 *
 * if $file isn't specified, looks for any image named after the $_page_name
 *
 * if $alt isn't specified, looks for page variable $_alt
 *
 * @param string $file text for image's 'src' attribute (assumes file is in '/images' directory)
 * @param string $alt text for image's alt attribute (optional, defaults to page's _alt variable, omits attribute if not set)
 * @param string $class text for image's class attribute (optional, omits attribute if not set)
 */
function place_image($file='', $alt='', $class='', $title='')
{
  global $_alt, $_page_name;

  $alt = use_default($alt, $_alt);
  $file = use_default($file, slug_name($_page_name));

  if (file_exists("images/$file"))
  {
    print_image_tag($file, $alt, $class, $title);
  }
  else
  {
    # look for missing extensions
    $extensions = array('.jpg', '.gif', '.png');
    foreach($extensions as $ext)
    {
      $try_file = $file.$ext;
      if(file_exists("images/$try_file"))
      {
        print_image_tag($try_file, $alt, $class, $title);
        break;
      }
    }
  }
}


/**
 * Calls place_image() if the $_alt variable is set for the page
 */
function place_image_if_alt()
{
  global $_alt;
  if ($_alt)
  {
    place_image('','','auto');
  }
}


/**
 * Prints the script tag for creating a spam-friendly email link
 */
function email_link_tag($address)
{
  $pieces = explode("@", $address);
  $name = $pieces[0];
  $domain = $pieces[1];

  $js = "<script type=\"text/javascript\">\n";
  $js .= "<!--\n";
  $js .= "var name = \"$name\";\n";
  $js .= "var domain = \"$domain\";\n";
  $js .= "document.write('<a href=\\\"mailto:' + name + '@' + domain + '\\\">');\n";
  $js .= "document.write(name + '@' + domain + '</a>');\n";
  $js .= "// -->\n";
  $js .= "</script>\n";
  return $js;
}


/**
 * Allows injecting pieces of content into the header or footer includes
 */
function content($content, $default='')
{
  $content = use_default($content, $default)
  if (exists($content))
  {
    echo $content;
  }
}


# ============================================================================ #
#    NAVIGATION HELPERS                                                        #
# ============================================================================ #


/**
 * Recursively formats the navigation sitemap, building a string of nested <ul>s
 */
function format_navigation($input, $exclusions=array(), $include_sub_nav=false, $top_level=true)
{
  global $_section, $_page_name;

  # only include the item's id for top level nav items
  $include_id = $top_level;

  $nav_string = "\n<ul>";
  foreach ($input as $key => $value)
  {
    if (!in_array($key, $exclusions)) # skip any sections that are in the exclusions array
    {
      if ($top_level){$current = $_section;}
      else {$current = $_page_name;}

      $nav_string .= "\n<li";
      $nav_string .= get_nav_attributes($current, $key, $input).'>'; # append class names to <li>

      if (is_string($value)) # link is pre-defined
      {
         $nav_string .= format_nav_link($key, $value, $include_id);
      }
      else
      {
        $section_link = get_section_link($key, $value);

        # add .head class for jquery accordion
        $class = (is_array($value) && $include_sub_nav && $top_level) ? 'head' : '';
        $nav_string .= format_nav_link($key, $section_link, $include_id, $class);

        if (is_array($value) && $include_sub_nav) # item has subnav
        {
          # recurse through nested navigation
          $nav_string .= format_navigation($value, $exclusions, true, false);
        }
      }
      $nav_string .= "</li>";
    }
  }
  $nav_string .= "\n</ul>";
  return $nav_string;
}


/**
 * Creates a nav link string.
 * If no link is given it uses the item's slug name
 */
function format_nav_link($nav_item, $link='', $include_id=false, $class='')
{
  $slug = slug_name($nav_item);
  $id = '';

  if (! $link) {$link = "$slug.php";}
  if ($include_id) {$id = " id=\"$slug\"";}
  if ($class) {$class = " class=\"$class\"";}

  return "<a href=\"$link\"$id$class>$nav_item</a>";
}


/**
 * Prints the formatted sitemap as nested lists of links wrapped in a div tag
 *
 * * adds the slug name as the id of each <a> tag
 * * adds 'class="active"' to the current section
 * * optionally includes the subnav items as a nested <ul>
 *
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @param bool $include_sub_nav optionally include nested sub navigation
 * @param string $div_id optionally define the id of the generated div
 */
function print_navigation($exclusions=array(), $include_sub_nav=false, $div_id='nav')
{
  $sitemap = get_sitemap();
  $nav = "\n<div id=\"$div_id\">";
  $nav .= format_navigation($sitemap, $exclusions, $include_sub_nav);
  $nav .= "\n</div>";
  echo $nav;
}


/**
 * print a navigation div with only the specified nav items, excluding everything else
 */
function print_inclusive_navigation($inclusions=array(), $include_sub_nav=false, $div_id="util")
{
  $sitemap = get_sitemap();
  $sections = array_keys($sitemap);
  $exclusions = array_diff($sections, $inclusions);
  print_navigation($exclusions, $include_sub_nav, $div_id);
}


/*
 * prints a <ul> wrapped in a <div> of the subnav links for the current section
 *
 * optionally get the subnav links for any section given as a parameter
 *
 */
function print_sub_navigation($section='', $pre_string='', $post_string='')
{
  global $_section;
  # Use the current section unless a specific section is given as a parameter
  if (!$section)
  {
    $section = $_section;
  }

  $sitemap = get_sitemap();
  if (has_sub_items($section))
  {
    $sub_nav = '';
    if ($pre_string) { $sub_nav .= "$pre_string"; }
    $sub_nav .= "<div id=\"subnav\">\n";
    $sub_nav .= format_navigation($sitemap[$section], $exclusions=array(), $include_sub_nav=true, $include_ids=false);
    $sub_nav .= "</div>\n";
    if ($post_string) { $sub_nav .= "$post_string"; }
    echo $sub_nav;
  }
}


function print_sub_navigation_with_heading($section='', $link=false, $tag='h3')
{
  global $_section;

  # Use the current section unless a specific section is given as a parameter
  if (! $section){ $section = $_section; }

  $heading = "<$tag>";
  if ($link) {
    $heading_link = get_section_link($section);
    $heading .= "<a href=\"$heading_link\">";
  }
  $heading .= $section;
  if ($link) { $heading .= "</a>"; }
  $heading .= "</$tag>";

  print_sub_navigation($section, $heading);
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
function sub_nav_p($breaks='', $separator=' | ', $class_name='sub_nav', $section='', $include_attr=true)
{

    global $_section, $_page_name, $sitemap;

    # Use the current section unless a specific section is given as a parameter
    if (! $section)
    {
      $section = $_section;
    }

    # don't output anything further if there are no sub items
    if (! has_sub_items($section)) {return;}

    $formatted_list = "<p class=\"$class_name\">";

    $link_array = array();
    $sub_items = $sitemap[$section];
    foreach ($sub_items as $sub_name)
    {
      $slug = slug_name($sub_name);
      $link = "<a href=\"$slug.php\"";
      if ($include_attr)
      {
        $link .= get_nav_attributes($_page_name, $sub_name, $sub_items);
      }
      $link .= ">$sub_name</a>";
      $link_array[] = $link;
    }


    # separate the list of links into separate arrays for adding breaks
    if ($breaks)
    {
      # breaks can be a single number or an array of numbers
      if (! is_array($breaks) && is_numeric($breaks))
      {
        $breaks = array($breaks);
      }

      $link_blocks = array();
      $break_count = sizeof($breaks);
      for($j = 0; $j <= $break_count; $j++)
      {
        switch ($j)
        {
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
      for($j = 0; $j < sizeof($link_blocks); $j++)
      {
        $link_blocks[$j] = format_list_with_separator($link_blocks[$j], $separator); # add separator between each link
      }
      # add breaks between each block of links
      $formatted_list .= format_list_with_separator($link_blocks, '<br />');
    }
    else
    {
      # if no breaks, add the separator to the raw list
      $formatted_list .= format_list_with_separator($link_array, $separator);
    }

    $formatted_list .= '</p>';
    echo $formatted_list;
}


/**
 * gets id and class names for each <li> in the navigation
 *
 * * adds 'first' and 'last' classes to the first and last items in the list<br/>
 * * adds 'active' class to the current section or page
 *
 * @param string $current the current page or section for setting the 'active' class
 * @param string $nav_item can be a page_name or section as contained in the array $children
 * @param array $nav_list the array that contains $nav_item
 * @return string the formatted attributes for the <li>
 */
function get_nav_attributes($current, $nav_item, $nav_list)
{
  $attr = '';
  $class = array();

  # append relevant class names
  $class[] = slug_name($nav_item);
  if($nav_item === $current){$class[] = 'active';}
  if($nav_item === reset(array_keys($nav_list))){$class[] = 'first';}
  if($nav_item === end(array_keys($nav_list))){$class[] = 'last';}

  if($class)
  {
    $classes = implode(' ', $class);
    $attr .= " class=\"$classes\"";
  }

  return $attr;
}


/**
 * prints a formatted list of the top-level navigation links for placement as the text navigation
 *
 * Examples:
 * {@example text_navigation.php}
 *
 * @param integer or array $breaks optionally force a line break after the nth text link
 * @param array $exclusions optionally omit specific sections from the echoed $nav_string
 * @todo repeat a lot of code from sub_nav_p()
 */
function print_text_navigation($breaks='', $exclusions=array(), $separator=' | ', $class_name='text_nav')
{

  $formatted_list = "<p class=\"$class_name\">";

  $link_array = array();
  $sitemap = get_sitemap();
  foreach ($sitemap as $section => $sub_items)
  {
    if (! in_array($section, $exclusions))
    {
      $href = get_section_link($section, $sub_items);
      $link = "<a href=\"$href\">$section</a>";
      $link_array[] = $link;
    }
  }

  # separate the list of links into separate arrays for adding breaks
  if ($breaks)
  {
    # breaks can be a single number or an array of numbers
    if (! is_array($breaks) && is_numeric($breaks))
    {
      $breaks = array($breaks);
    }

    $link_blocks = array();
    $break_count = sizeof($breaks);
    for($j = 0; $j <= $break_count; $j++)
    {
      switch ($j)
      {
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
    for($j = 0; $j < sizeof($link_blocks); $j++)
    {
      $link_blocks[$j] = format_list_with_separator($link_blocks[$j], $separator); # add separator between each link
    }
    # add breaks between each block of links
    $formatted_list .= format_list_with_separator($link_blocks, '<br />');
  }
  else
  {
    # if no breaks, add the separator to the raw list
    $formatted_list .= format_list_with_separator($link_array, $separator);
  }

  $formatted_list .= '</p>';
  echo $formatted_list;
}


/*
 * formats the sitemap in the form of nested lists with links to each page
 *
 * recurses for endless nested subnavigation
 */
function format_sitemap($input, $exclusions=array())
{
  $formatted = '<ul>';
  foreach ($input as $key => $value)
  {
    if (!in_array($key, $exclusions))
    {
      if (is_array($value))
      {
        $link = get_section_link($key, $value);
        $formatted .= '<li>'.get_sitemap_link($key, $link);
        $formatted .= format_sitemap($value);
        $formatted .= '</li>';
      }
      else
      {
        $formatted .= '<li>'.get_sitemap_link($key, $value).'</li>';
      }
    }
  }
  $formatted .= '</ul>';
  return $formatted;
}


/*
 * prints the formatted sitemap in the form of nested lists with links to each page
 */
function print_sitemap($exclusions=array())
{
  $sitemap = get_sitemap();
  echo format_sitemap($sitemap, $exclusions);
}


function get_sitemap_link($page, $link)
{
  global $_page_name;
  if ($page == $_page_name)
  {
    return "$page (You are here)";
  }
  else
  {
    return "<a href=\"$link\">$page</a>";
  }
}


/*
 * prints a formatted string with links to the current page's parent(s).
 *
 * * $separator string defaults to the &#8250; character but can be overridden with any string.
 * * current page is bolded and unlinked.
 *
 * @param string $separator the text or html character that will separate each breadcrumb (optional)
 */
function print_breadcrumbs($separator=' &#8250; ')
{
  global $_section, $_page_name;
  $bc_hash = collect_breadcrumbs(get_sitemap());
  $bc_array = array();

  foreach($bc_hash as $name => $url)
  {
    # format all items except the last as links
    if ($name != end(array_keys($bc_hash)))
    {
      $bc_array[] = "<a href=\"$url\">$name</a>";
    }
    else
    {
      $bc_array[] = "<strong>$name</strong>";
    }
  }

  $bc_string = '<p class="breadcrumbs">';
  $bc_string .= format_list_with_separator($bc_array, $separator);
  $bc_string .= '</p>';
  echo $bc_string;
}


function collect_breadcrumbs($input, $first_run=true)
{
  global $_section, $_page_name;
  $bc_hash = array('Home' => 'index.php');

  $current = $first_run ? $_section : $_page_name;

  foreach ($input as $key => $value)
  {
    if ($key == $current)
    {
      if (is_string($value)) # link is pre-defined
      {
        $bc_hash[$key] = $value;
      }
      else
      {
        $bc_hash[$key] = get_section_link($key, $value);
        if (is_array($value)) # item has subnav
        {
          # recurse through nested navigation
          # TODO not sure why it doesn't work for tertiary navigation
          $temp_recurse = collect_breadcrumbs($value, false);
          $bc_hash = array_merge($bc_hash, $temp_recurse);
        }
      }
    }

  }
  return $bc_hash;
}


# ============================================================================ #
#    SITEMAP PARSING                                                           #
# ============================================================================ #


/**
 * Only parse the sitemap once and save it as a static variable.
 */
function get_sitemap()
{
  static $sitemap;
  if (!is_array($sitemap))
  {
    # does not currently exist, so create it
    $sitemap = parse_sitemap();
  }
  return $sitemap;
}


/**
 * processes sitemap.php, creating links from item names
 */
function parse_sitemap()
{
  $defined_sitemap = $GLOBALS['sitemap'];
  $sitemap = array();
  foreach ($defined_sitemap as $section => $sub_section)
  {
    $sitemap = parse_section($sitemap, $section, $sub_section);
  }
  return $sitemap;
}


/**
 * processes each section of the sitemap,
 * determining if there is a linked sub-section
 */
function parse_section($sitemap, $section, $sub_section)
{
  if (is_string($section))
    # array is in the form of
    # $section => $sub_section
  {
    $sitemap[$section] = parse_sub_section($section, $sub_section);
  }
  else
    # array is in the form of
    # key => $section
    # with key being numeric because there are no sub-items
  {
    $sitemap[$sub_section] = parse_sub_section($section, $sub_section);
  }
  return $sitemap;
}


/**
 * processes each suv-section of the sitemap,
 * recusively calling itself for each linked sub-sub-section, etc.
 * Allows for infinitely nested levels of navigation
 */
function parse_sub_section($section, $sub_section)
{
  if (is_numeric($section)) # numeric key, $sub_section is really the $section
  {
    # make link from page name
    $sub = slug_name($sub_section).'.php';
  }
  else
  {
    if (is_string($sub_section)) # $sub_section is a defined link
    {
      return $sub_section;
    }

    elseif (is_array($sub_section))
    {
      $sub = array();
      # recursively process each section that has a sub-section
      foreach ($sub_section as $sub_key => $sub_value)
      {
        $sub = parse_section($sub, $sub_key, $sub_value);
      }
    }

    else
    {
      $sub = 'error parsing sitemap';
    }
  }
  return $sub;
}


/**
 * determines if the current section has sub-pages,
 * optionally check any section given as a parameter
 */
function has_sub_items($section='')
{
  global $_section;

  # Use the current section unless a specific section is given as a parameter
  $section = use_default($section, $_section);

  $sitemap = get_sitemap();
  $sub_items = $sitemap[$section];
  return (is_array($sub_items) && count($sub_items) > 1);
}


/**
 * Determines the link to the current section
 *
 * if the section's sub items are an array
 * the 'slug name' is used for the link
 *
 * if the section's sub item is a string, the string is used for the link
 *
 * optionally check any section given as a parameter
 */
function get_section_link($section='', $section_sub='')
{
  global $_section;
  # Use the current section unless a specific section is given as a parameter
  if (! $section) { $section = $_section; }

  # if $section_sub is undefined, get it from the sitemap
  if (! $section_sub)
  {
    $sitemap = get_sitemap();
    $section_sub = $sitemap[$section];
  }

  $link = '';

  if (is_array($section_sub))
  {
    if (has_index_pages())
    {
      $link .= slug_name($section);
      $link .= '.php';
    }
    else
    {
      $link .= get_first_unnested_item($section_sub); # link to first sub item that isn't a nested array
    }
  }
  elseif (is_string($section_sub)) # link is pre-defined
  {
    $link = $section_sub;
  }

  return $link;
}


/*
 * Recurse through an array to find the first
 * sub item that isn't itself an array
 */
function get_first_unnested_item($items)
{
  $sub_item = reset($items);
  while (is_array($sub_item))
  {
    $sub_item = reset($sub_item);
  }
  return $sub_item;
}


/**
 * Not using this yet
 */
// function filter_sitemap($input, $callback=null)
// {
//   foreach ($input as $key => $value)
//   {
//     if (is_array($value))
//     {
//       $value = filter_sitemap($value, $callback);
//     }
//   }
//   return array_filter($input, $callback);
// }