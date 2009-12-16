<?php

# ============================================================================ #

/**
 *  D O O Z E R
 *
 *  a PHP navigation framework.
 *
 *  For more information: {@link http://github/gruner/doozer}
 *
 *  @author Andrew Gruner
 *  @copyright Copyright (c) 2009 Andrew Gruner
 *  @license http://opensource.org/licenses/mit-license.php The MIT License
 *  @package doozer
 *  @version 2.0.3
 */

#------------------------------------------------------------------------------#
#    Copyright (c) 2009 Andrew Gruner                                          #
#                                                                              #
#    Permission is hereby granted, free of charge, to any person               #
#    obtaining a copy of this software and associated documentation            #
#    files (the "Software"), to deal in the Software without                   #
#    restriction, including without limitation the rights to use,              #
#    copy, modify, merge, publish, distribute, sublicense, and/or sell         #
#    copies of the Software, and to permit persons to whom the                 #
#    Software is furnished to do so, subject to the following                  #
#    conditions:                                                               #
#                                                                              #
#    The above copyright notice and this permission notice shall be            #
#    included in all copies or substantial portions of the Software.           #
#                                                                              #
#    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,           #
#    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES           #
#    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND                  #
#    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT               #
#    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,              #
#    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING              #
#    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR             #
#    OTHER DEALINGS IN THE SOFTWARE.                                           #
# ============================================================================ #




/**
 * Include the configuration and sitemap files
 *
 * These define site-specific settings
 * such as page title and meta information. This allows for site-specific changes
 * without having to edit global.php.
 */
require_once('config.php');
require_once('sitemap.php');


# ============================================================================ #
#    UTILITY FUNCTIONS                                                         #
# ============================================================================ #


/**
 * Checks $_section and $_name to see if the current page is the homepage
 */
function is_homepage()
{
  global $_section, $_name;
  return ($_section == 'Home' && $_name == 'Home');
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
  if (exists($config['site_name']))
  {
    return $config['site_name'];
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
  $special_chars = array('.',',','?','!','$','(',')',':','"',"'",'*','&#39;','&copy;','&reg;','&trade;');
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
  $sanitized_text = replace_chars($sanitized_text, $replacements=array('&amp;' => 'and', '&' => 'and', '/' => '-', 'Dr ' => 'Dr. '));
  return $sanitized_text;
}


/**
 * Formats an array into a single string by inserting the given separator string between items
 */
function format_list_with_separator($list, $separator=' | ')
{
  return implode($separator, $list);
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


/**
 * Creates a self-closing html tag
 */
function tag($name, $options=null, $open=false)
{
  $tag_options = exists($options) ? tag_options($options) : '';
  $closing = $open ? '>' : ' />';
  return '<'.$name.$tag_options.$closing."\n";
}


/**
 * Creates opening and closing html tags with content in between
 */
function content_tag($name, $content, $options=null)
{
  $tag_options = exists($options) ? tag_options($options) : '';
  return '<'.$name.$tag_options.'>'.$content.'</'.$name.'>'."\n";
}


/**
 * Formats an associative array into the attributes of an html tag
 */
function tag_options($options)
{
  $boolean_attributes = array('disabled', 'readonly', 'multiple', 'checked', 'autobuffer',
                             'autoplay', 'controls', 'loop', 'selected', 'hidden', 'scoped', 'async',
                             'defer', 'reversed', 'ismap', 'seemless', 'muted', 'required',
                             'autofocus', 'novalidate', 'formnovalidate', 'open');
  if (is_array($options))
  {
    $attrs = array();
    foreach ($options as $key => $value)
    {
      if (in_array($key, $boolean_attributes))
      {
        if (isset($value))
        {
          $attrs[] = "$key=\"$key\"";
        }
      }
      elseif (exists($value))
      {
        $final_value = (is_array($value)) ? implode(' ', $value) : $value;
        $attrs[] = "$key=\"$final_value\"";
      }
    }
  }
  if (!empty($attrs))
  {
    $attrs = implode(' ', $attrs);
    return " $attrs";
  }
}


/**
 * Returns the complete title tag of the page.
 * Looks for local $_title variable but defaults to the value defined in config.php
 */
function title_tag($separator='-')
{
  global $_name, $_title, $_headline, $config;

  $page_name = use_default($_headline, $_name);
  $base_title = use_default($_title, $config['page_title']);
  $title = is_homepage() ? $base_title : "$page_name $separator $base_title";

  # remove special chars from page name
  return content_tag('title', sanitize_title_text($title));
}


/**
 * Returns completed meta description and meta keyword tags.
 *
 * looks for local $_keywords and $_description variables but
 * defaults to the values defined in config.php.
 */
function meta_tags()
{
  global $config, $_keywords, $_description;

  $meta_tags = '';

  $meta = array('keywords' => $config['meta_keywords'], 'description' => $config['meta_description']);

  foreach ($meta as $key => $value)
  {
    $default = '_'.$key;
    $value = use_default($$default, $value); # use the local variable if it exists
    $meta_tags .= tag('meta', array('name' => $key, 'content' => $value))."\n";
  }
  return $meta_tags;
}


/**
 * Returns a formatted h1 tag
 *
 * Uses $_name for the text unless $_headline is set
 */
function headline_tag($heading='h1', $class='headline')
{
  global $_name, $_headline;

  $headline = use_default($_headline, $_name);

  if ($_headline !== false)
  {
    return content_tag($heading, $headline, array('class' => $class));
  }
}


function image_tag($file, $alt='', $class='', $title='')
{
  $file = "images/$file";
  if(file_exists($file))
  {
    list($w, $h) = getimagesize($file);
    return tag('img', array('src' => $file, 'width' => $w, 'height' => $h, 'alt' => $alt, 'class' => $class, 'title' => $title));
  }
}


/**
 * Prints a formatted image tag with calculated width and height attributes
 *
 * if $file isn't specified, looks for any image named after the $_name
 *
 * if $alt isn't specified, looks for page variable $_alt
 *
 * @param string $file text for image's 'src' attribute (assumes file is in '/images' directory)
 * @param string $alt text for image's alt attribute (optional, defaults to page's _alt variable, omits attribute if not set)
 * @param string $class text for image's class attribute (optional, omits attribute if not set)
 */
function place_image($file='', $alt='', $class='', $title='', $subdir='')
{
  global $_alt, $_name;

  $alt = use_default($alt, $_alt);
  $file = use_default($file, slug_name($_name));

  if (exists($subdir))
  {
    $file = $subdir.$file;
  }

  if (file_exists("images/$file"))
  {
    return image_tag($file, $alt, $class, $title);
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
        return image_tag($try_file, $alt, $class, $title);
        break;
      }
    }
  }
}


/**
 * Calls place_image() if the $_alt variable is set for the page
 * Looks in images/photos directory
 */
function place_image_if_alt($file='', $class='auto')
{
  global $_alt;
  if ($_alt)
  {
    return place_image($file, '', $class, '', 'photos/');
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
  $content = use_default($content, $default);
  if (exists($content))
  {
    return $content;
  }
}


/**
 * Returns alert box html if the foul stench of IE6 is recognized
 */
function ie6_alert()
{
  if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') !== FALSE)
  {
    include('ie6_alert.php');
    return $ie6_alert_box;
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
  global $_section, $_name;

  # only include the item's id for top level nav items
  $include_id = $top_level;

  $nav_string = '';
  foreach ($input as $key => $value)
  {
    if (!in_array($key, $exclusions)) # skip any sections that are in the exclusions array
    {
      $current = ($top_level) ? $_section : $_name;

      $li_string = '';

      if (is_string($value)) # link is pre-defined
      {
         $li_string .= format_nav_link($key, $value, $include_id);
      }
      else
      {
        $section_link = get_section_link($key, $value);

        # add .head class for jquery accordion
        $class = (is_array($value) && $include_sub_nav && $top_level) ? 'head' : '';
        $li_string .= format_nav_link($key, $section_link, $include_id, $class);

        if (is_array($value) && $include_sub_nav) # item has subnav
        {
          # recurse through nested navigation
          $li_string .= format_navigation($value, $exclusions, true, false);
        }
      }
      $li_tag_options = get_nav_attributes($current, $key, $input); # append class names to <li>
      $nav_string .= content_tag('li', $li_string, $li_tag_options);
    }
  }

  return content_tag('ul', $nav_string);
}


/**
 * Creates a nav link string
 * If no link is given it uses the item's slug name
 */
function format_nav_link($nav_item, $link='', $include_id=false, $class='')
{
  $slug = slug_name($nav_item);
  $tag_options = array('href' => use_default($link, "$slug.php"));
  if (exists($class)) { $tag_options['class'] = $class; }
  if ($include_id) { $tag_options['id'] = $slug; }

  return content_tag('a', $nav_item, $tag_options);
}


/**
 * Formats the sitemap into nested lists of links wrapped in a div tag
 *
 * * adds the slug name as the id of each <a> tag
 * * adds 'class="active"' to the current section
 * * optionally includes the subnav items as a nested <ul>
 *
 * @param array $exclusions optionally omits any given sections from the echoed $nav_string
 * @param bool $include_sub_nav optionally include nested sub navigation
 * @param string $div_id optionally define the id of the generated div
 */
function navigation($exclusions=array(), $include_sub_nav=false, $div_id='nav')
{
  $sitemap = get_sitemap();
  $nav = format_navigation($sitemap, $exclusions, $include_sub_nav);
  return content_tag('div', $nav, array('id' => $div_id));
}


/**
 * Creates a navigation div with only the specified nav items, excluding everything else
 */
function custom_navigation($inclusions=array(), $include_sub_nav=false, $div_id='utility-nav')
{
  $sitemap = get_sitemap();
  $sections = array_keys($sitemap);
  $exclusions = array_diff($sections, $inclusions);
  return navigation($exclusions, $include_sub_nav, $div_id);
}


/**
 * Creates a navigation div with the subnav items of the current section
 *
 * optionally get the subnav links for any section given as a parameter
 *
 */
function sub_navigation($section='', $pre_string='', $post_string='', $id='subnav')
{
  global $_section;
  # Use the current section unless a specific section is given as a parameter
  $section = use_default($section, $_section);

  $sitemap = get_sitemap();
  if (has_sub_items($section))
  {
    $sub_nav = '';
    if (exists($pre_string)) { $sub_nav .= "$pre_string"; }
    $sub_nav_items = format_navigation($sitemap[$section], $exclusions=array(), $include_sub_nav=true, $include_ids=false);
    $sub_nav .= content_tag('div', $sub_nav_items, array('id' => $id));
    if (exists($post_string)) { $sub_nav .= "$post_string"; }
    return $sub_nav;
  }
}


/**
 *
 */
function sub_navigation_with_heading($section='', $link=false, $tag='h3')
{
  global $_section;

  # Use the current section unless a specific section is given as a parameter
  $section = use_default($section, $_section);
  $heading = ($link) ? content_tag('a', $section, array('href' => get_section_link($section))) : $section;
  return sub_navigation($section, content_tag($tag, $heading));
}


/**
 * Creates a formatted <<p>> of the current section's sub links with ' | ' between each link
 *
 * adds 'class="active"' to the current link
 *
 * @param array $breaks optionally add breaks at specific points in the list
 * @param string $separator optionally specify text that will be inserted between each link
 * @param string $class_name optionally specify the class name of the generated <<p>> tag
 * @param string $section optionally show subnav links for specific section
 * @param bool $include_attr optionally omit class names set for each link
 */
function text_sub_navigation($breaks='', $separator=' | ', $class_name='sub_nav', $section='', $include_attr=true)
{
    global $_section, $_name, $sitemap;

    $section = use_default($section, $_section);

    # don't output anything further if there are no sub items
    if (! has_sub_items($section)) {return;}

    $formatted_list = '';

    $link_array = array();
    $sub_items = $sitemap[$section];


    foreach ($sub_items as $key => $value)
    {
      $link = (is_numeric($key)) ? $value : get_section_link($key, $value);
      $sub_name = (is_numeric($key)) ? $value : $key;
      $tag_options = ($include_attr) ? get_nav_attributes($_name, $sub_name, $sub_items) : array();

      # check the link fot 'http' or '.php' to see if it's hard coded
      # otherwise default to the slug name
      $tag_options['href'] = (stristr($link, 'http') || stristr($link, '.php')) ? $link : slug_name($link).'.php';

      $link_array[] = content_tag('a', $sub_name, $tag_options);
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

    $tag_options = exists($class_name) ? array('class' => $class_name) : null;
    return content_tag('p', $formatted_list, $tag_options);
}


/**
 * Gets class names for each <li> in the navigation
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
  $attr = array();
  $class = array();

  # append relevant class names
  $class[] = slug_name($nav_item);
  if($nav_item === $current){$class[] = 'active';}
  if($nav_item === reset(array_keys($nav_list))){$class[] = 'first';}
  if($nav_item === end(array_keys($nav_list))){$class[] = 'last';}

  if(! empty($class))
  {
    $attr['class'] = implode(' ', $class);
  }

  return $attr;
}

/**
 * Creates a formatted list of the top-level navigation links for placement as the text navigation
 *
 * Examples:
 * {@example text_navigation.php}
 *
 * @param integer or array $breaks optionally force a line break after the nth text link
 * @param array $exclusions optionally omit specific sections from the echoed $nav_string
 * @todo repeat a lot of code from sub_nav_p()
 */
function text_navigation($breaks='', $exclusions=array(), $separator=' | ', $class_name='text_nav')
{
  $formatted_list = '';

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

  $tag_options = exists($class_name) ? array('class' => $class_name) : null;
  return content_tag('p', $formatted_list, $tag_options);
}


/**
 * Formats the sitemap in the form of nested lists with links to each page
 *
 * recurses for endless nested subnavigation
 */
function format_sitemap($input, $exclusions=array(), $tag_options=array())
{
  $formatted = '';
  foreach ($input as $key => $value)
  {
    if (!in_array($key, $exclusions))
    {
      if (is_array($value))
      {
        $link = get_section_link($key, $value);

        # recurse through nested links
        $nested_links = get_sitemap_link($key, $link);
        $nested_links .= format_sitemap($value);

        $formatted .= content_tag('li', $nested_links);
      }
      else
      {
        $formatted .= content_tag('li', get_sitemap_link($key, $value));
      }
    }
  }

  return content_tag('ul', $formatted, $tag_options);
}


/**
 * Prints the formatted sitemap in the form of nested lists with links to each page
 */
function sitemap($exclusions=array(), $tag_options=array())
{
  $sitemap = get_sitemap();
  return format_sitemap($sitemap, $exclusions, $tag_options);
}


function get_sitemap_link($page, $link)
{
  global $_name;
  if ($page == $_name)
  {
    return "$page (You are here)";
  }
  else
  {
    return content_tag('a', $page, array('href' => $link));
  }
}


/**
 * Prints a formatted string with links to the current page's parent(s).
 *
 * * $separator string defaults to the &#8250; character but can be overridden with any string.
 * * current page is bolded and unlinked.
 *
 * @param string $separator the text or html character that will separate each breadcrumb (optional)
 */
function breadcrumbs($separator=' &#8250; ')
{
  # <a class="home"> for home, maybe <span class="section"> for section (literal) and <strong class="active" for current page?

  global $_section, $_name;
  $bc_links = collect_breadcrumbs(get_sitemap());
  $bc_array = array();

  foreach($bc_links as $name => $url)
  {
    $tag = '';
    $attr = array();

    # format the first item as the home link
    if ($name === reset(array_keys($bc_links)))
    {
      $tag = 'a';
      $attr['class'] = 'home';
      $attr['href'] = $url;
    }

    # format the last item
    elseif ($name === end(array_keys($bc_links)))
    {
      $tag = 'strong';
      $attr['class'] = 'active';
    }

    # format the middle items as links if there are index pages, else spans
    else
    {
      $tag = has_index_pages() ? 'a' : 'span';
      if (has_index_pages())
      {
        $attr['href'] = $url;
      }
      $attr['class'] = 'section';
    }

    # add the tag to the list of breadcrumbs
    $bc_array[] = content_tag($tag, $name, $attr);
  }

  $bc_string = format_list_with_separator($bc_array, $separator);
  return content_tag('p', $bc_string, array('class' => 'breadcrumbs'));
}


function collect_breadcrumbs($input, $first_run=true)
{
  global $_section, $_name;
  $bc_hash = array('Home' => 'index.php');

  $current = $first_run ? $_section : $_name;

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
 * Parses the sitemap and saves it as a static variable.
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
 * Processes sitemap.php, creating links from item names
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
 * Processes each section of the sitemap,
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
 * Processes each sub-section of the sitemap,
 * recursively calling itself for each linked sub-sub-section, etc.
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
 * Determines if the current section has sub-pages,
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
  $section = use_default($section, $_section);

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


/**
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


# ============================================================================ #
#    COMPATIBILITY SHORTCUTS                                                   #
# ============================================================================ #

function print_page_title() { echo title_tag(); }
function print_meta_tags() { echo meta_tags(); }


function print_image_tag()
{
  $args = func_get_args();
  echo call_user_func_array('image_tag', $args);
}


function print_navigation()
{
  $args = func_get_args();
  echo call_user_func_array('navigation', $args);
}


function print_inclusive_navigation()
{
  $args = func_get_args();
  echo call_user_func_array('custom_navigation', $args);
}


function print_text_navigation()
{
  $args = func_get_args();
  echo call_user_func_array('text_navigation', $args);
}


function sub_nav_p()
{
  $args = func_get_args();
  echo call_user_func_array('text_sub_navigation', $args);
}


function print_sub_navigation()
{
  $args = func_get_args();
  echo call_user_func_array('sub_navigation', $args);
}


function subnavigation()
{
  $args = func_get_args();
  echo call_user_func_array('sub_navigation', $args);
}


function print_sub_navigation_with_heading()
{
  $args = func_get_args();
  echo call_user_func_array('sub_navigation_with_heading', $args);
}


function print_breadcrumbs()
{
  $args = func_get_args();
  echo call_user_func_array('breadcrumbs', $args);
}

function print_sitemap()
{
  $args = func_get_args();
  echo call_user_func_array('sitemap', $args);
}

#   ================================= END ==================================   #