<?php

# ============================================================================ #

/**
 * D O O Z E R
 *
 * a PHP navigation framework.
 *
 * For more information: {@link http://github.com/gruner/doozer}
 *
 * @author Andrew Gruner
 * @copyright Copyright (c) 2009 Andrew Gruner
 * @license http://opensource.org/licenses/mit-license.php The MIT License
 * @package doozer
 * @version 2.1.1
 */

/**
 * Include the configuration and sitemap files
 *
 * These define site-specific settings
 * such as page title and meta information. This allows for site-specific changes
 * without having to modify this file.
 */
require_once('config.php');
require_once('sitemap.php');


# ============================================================================ #
#    UTILITY FUNCTIONS                                                         #
# ============================================================================ #


/**
 * Determines if the current page is the homepage
 * by checking the $_section and $_name variables.
 */
function is_homepage()
{
  global $_section, $_name;
  return ($_section == 'Home' && $_name == 'Home');
}


/*
 * Checks config.php for 'index_pages' option.
 *
 * If true, section links go to a section 'index page' (i.e. "In this section..."),
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
 * @return The site name if set in config.php, otherwise nothing
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
 * in navigation as well as linking to files
 * that follow the same naming convention.
 */
function slug_name($string)
{
  return replace_chars(strtolower(strip_special_chars($string)));
}


/**
 * Strips special characters from a string.
 *
 * @return The altered string
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
 *
 * Parameters:
 *   $sting - the string to perform the replacement on
 *   $replacements - associative array of search and replace values
 *
 * @return The altered string
 */
function replace_chars($string, $replacements=array('&amp;' => 'and','&' => 'and', ' ' => '-','/' => '-'))
{
  return str_replace(array_keys($replacements), array_values($replacements), $string);
}


/**
 * Converts a string to make it suitable for use in a title tag.
 *
 * Similar to <slug_name>, but keeps spaces.
 *
 * @return The sanitized string
 */
function sanitize_title_text($string)
{
  $sanitized_text = strip_special_chars($string);
  $sanitized_text = replace_chars($sanitized_text, $replacements=array('&amp;' => 'and', '&' => 'and', '/' => '-', 'Dr ' => 'Dr. '));
  return $sanitized_text;
}


/**
 * Formats an array into a single string by inserting the given separator string between items.
 */
function format_list_with_separator($list, $separator=' | ')
{
  return implode($separator, $list);
}


/**
 * Determines if a variable has a value.
 */
function exists($var)
{
  return (isset($var) && !empty($var));
}


/**
 * Checks the existance of a variable, returns it if true
 * otherwise it returns the given default value.
 */
function use_default($var, $default='')
{
  return (exists($var)) ? $var : $default;
}


# ============================================================================ #
#    HTML HELPERS                                                              #
# ============================================================================ #


/**
 * Creates a self-closing html tag.
 *
 * Parameters:
 *   $name - the name of the tag
 *   $options - associative array of key-value pairs used for the attributes of the html tag
 *   $open - defines if the tag is self-closing or left open
 */
function tag($name, $options=null, $open=false)
{
  $tag_options = exists($options) ? tag_options($options) : '';
  $closing = $open ? '>' : ' />';
  return '<'.$name.$tag_options.$closing."\n";
}


/**
 * Creates opening and closing html tags with content in between.
 *
 * Parameters:
 *   $name - the name of the tag
 *   $content - the content that will be wrapped
 *              by the opening and closing tags
 *   $options - associative array of key-value pairs used for the attributes of the html tag
 */
function content_tag($name, $content, $options=null)
{
  $tag_options = exists($options) ? tag_options($options) : '';
  return '<'.$name.$tag_options.'>'.$content.'</'.$name.'>'."\n";
}


/**
 * Formats an associative array into the attributes of an html tag.
 *
 * Parameters:
 *   $options - associative array of html attributes
 *
 * @return Formatted string of name - value pairs
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
 * Creates the title tag for the page.
 *
 * Uses $_title variable for the text but defaults to the value defined in config.php
 *
 * Parameters:
 *   $separator - the string that separates title elements
 *
 * @return Formatted title tag
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
 * Creates formatted meta description and meta keyword tags.
 *
 * looks for local $_keywords and $_description variables but
 * defaults to the values defined in config.php.
 *
 * @return Formatted meta description and meta keyword tags
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
 * Uses $_name for the text unless $_headline is set.
 *
 * Parameters:
 *   $heading - the heading tag (default is h1)
 *   $attr - array of attributes for the returned tag
 *
 * @return Formatted h1 tag
 */
function headline_tag($heading='h1', $attr=array('class' => 'headline'))
{
  global $_name, $_headline;

  $headline = use_default($_headline, $_name);

  if ($_headline !== false)
  {
    return content_tag($heading, $headline, $attr);
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
 * Creates a formatted image tag with calculated width and height attributes.
 *
 * - if $file isn't specified, looks for any image named after $_name
 * - if $alt isn't specified, looks for $_alt var
 *
 * Parameters:
 *   $file - text for image's 'src' attribute (assumes file is in '/images' directory)
 *   $alt - text for image's alt attribute (optional, defaults to page's _alt variable, omits attribute if not set)
 *   $class - text for image's class attribute (optional, omits attribute if not set)
 *   $title -
 *   $subdir
 *
 * @return Formatted image tag
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
 * Calls place_image() if the $_alt variable is set for the page.
 *
 * Looks in images/photos directory.
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
 * Returns the script tag for creating a spam-friendly email link.
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
 * Allows injecting pieces of html content into the header or footer includes,
 * such as sidebar content that might change from page to page.
 *
 * A default can be defined as the second parameter for
 * when the page-specific $content variable isn't defined.
 *
 * Parameters:
 *   $content - html string
 *   $default - html string to use if $content is not defined
 *
 * @return html string or nothing if either parameter is undefined
 */
function content($content, $default='')
{
  $content = use_default($content, $default);
  if (exists($content))
  {
    return $content;
  }
}


# ============================================================================ #
#    NAVIGATION HELPERS                                                        #
# ============================================================================ #


/**
 * Recursively formats the navigation sitemap, building a string of nested <ul>s.
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
 * Creates a formatted <a> tag.
 *
 * If no link is given it uses the item's slug name.
 *
 * @return Formatted <a> tag
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
 * Formats the sitemap into nested lists of links wrapped in a div tag.
 *
 * - adds the slug name as the id of each <a> tag
 * - adds 'class="active"' to the current section
 * - optionally includes the subnav items as a nested <ul>
 *
 * Parameters:
 *   $exclusions - optionally omits any given sections from the echoed $nav_string
 *   $include_sub_nav - optionally include nested sub navigation
 *   $div_id - optionally define the id of the generated div
 *
 * @return Formatted navigation <ul> wrapped in a <div>
 */
function navigation($exclusions=array(), $include_sub_nav=false, $div_id='nav')
{
  $sitemap = get_sitemap();
  $nav = format_navigation($sitemap, $exclusions, $include_sub_nav);
  return content_tag('div', $nav, array('id' => $div_id));
}


/**
 * Creates a navigation div with only the specified nav items, excluding everything else.
 *
 * Returns:
 *   Formatted navigation <ul> wrapped in a <div>
 *
 * See Also:
 *   <navigation>
 */
function custom_navigation($inclusions=array(), $include_sub_nav=false, $div_id='utility-nav')
{
  $sitemap = get_sitemap();
  $sections = array_keys($sitemap);
  $exclusions = array_diff($sections, $inclusions);
  return navigation($exclusions, $include_sub_nav, $div_id);
}


/**
 * Creates a navigation div with the subnav items of the current section.
 *
 * Optionally get the subnav links for any section given as a parameter.
 *
 * Parameters:
 *   $section - defaults to the current section but can be overridden
 *   $pre_string - a string to go before the navigation
 *   $post_string - a string to go after the naviagion
 *   $id - the id of the navigation <div>
 *
 * @return Formatted navigation <ul> wrapped in a <div>
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
 * Same as <sub_navigation> but adds a heading.
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
 * Creates a formatted <p> of the current section's sub links with ' | ' between each link.
 *
 * Parameters:
 *   $breaks - optionally add breaks at specific points in the list
 *   $separator - optionally specify text that will be inserted between each link
 *   $class_name - optionally specify the class name of the generated <p> tag
 *   $section - optionally show subnav links for specific section
 *   $include_attr - optionally omit class names set for each link
 *
 * @return Formatted navigation <p> tag
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
 * Gets class names for any <li> in the navigation.
 *
 * - adds 'first' and 'last' classes to the first and last items in the list
 * - adds 'active' class to the current section or page
 *
 * Parameters:
 *   $current - the current page or section for setting the 'active' class
 *   $nav_item - can be a page_name or section as contained in the array $children
 *   $nav_list - the array that contains $nav_item
 *
 * @return The formatted attributes for the <li>
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
 * Creates a formatted list of the top-level navigation links
 * for placement as the text navigation.
 *
 * Parameters:
 *   $breaks - optionally force a line break after the nth text link
 *   $exclusions - optionally omit specific sections from the echoed $nav_string
 *   $separator - string placed between items
 *   $class_name - class name to add to the returned <p> tag
 *
 * @return The formatted <p> tag
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
 * Takes an array of names and recursively searches through the sitemap
 * to find the corresponding link for each item.
 *
 * Custom links can also be defined as key value pairs.
 *
 * Parameters:
 *   $callouts - array of item names
 *   $attrs - array of attributes for the returned <ul>
 *
 * @return <ul> element with links as list items
 */
function callout_navigation($callouts=array(), $attrs=array('class' => 'callouts'))
{
  $sitemap = get_sitemap();
  $ret = '';

  foreach ($callouts as $key => $value)
  {
    # check for an aliased link in the sitemap or use the hard-coded link
    $href = use_default(find_in_sitemap($value), $value);

    $link_name = (is_numeric($key)) ? $value : $key;
    $a_tag = content_tag('a', $link_name, array('href' => $href));
    $ret .= content_tag('li', $a_tag, array('class' => slug_name($link_name)));
  }
  return content_tag('ul', $ret, $attrs);
}


/**
 * Formats the sitemap in the form of nested lists with links to each page,
 * recursing for nested subnavigation.
 *
 * Parameters:
 *  $input - navigation array to convert to a <ul>
 *  $exclusions - array of top-level items to omit from output
 *  $tag_options - associative array of attributes for the returned tag
 *
 * @return Formatted <ul> tag
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
 * Prints the formatted sitemap in the form of nested lists with links to each page.
 *
 * Parameters:
 *  $exclusions - array of top-level items to omit from output
 *  $tag_options - associative array of attributes for the returned tag
 *
 * @return Formatted <ul> tag
 */
function sitemap($exclusions=array(), $tag_options=array())
{
  $sitemap = get_sitemap();
  return format_sitemap($sitemap, $exclusions, $tag_options);
}


/**
 * @return <a> tag for a single sitemap item.
 */
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
 * Creates a formatted breadcrumb string with links to the current page's parent(s).
 *
 * - $separator string defaults to the &#8250; character but can be overridden with any string.
 * - current page is bolded and unlinked.
 *
 * Parameters:
 *  $separator - the text or html character that will separate each breadcrumb (optional)
 *
 * @return Formatted <p> tag.
 */
function breadcrumbs($separator=' &#8250; ')
{
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
 *
 * @return Parsed sitemap array
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
 * Processes sitemap.php, creating links from item names.
 *
 * @return Parsed sitemap array
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
 *
 * Allows for infinitely nested levels of navigation.
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
 * Recursively searches the sitemap for an item's corresponding link.
 *
 * Parameters:
 *  $item - the item name to search for
 *  $sitemap - the array to search in (defaults to the global sitemap)
 *
 * @return The item's corresponding url
 */
function find_in_sitemap($item, $sitemap=array())
{
  $sitemap = use_default($sitemap, get_sitemap());
  $link = '';

  foreach ($sitemap as $key => $value)
  {
    if ($item === $key)
    {
      if (is_array($value))
      {
        $link = get_first_unnested_item($value);
      }
      else
      {
        $link = $value;
      }
      break;
    }
    elseif (is_array($value))
    {
      $link = find_in_sitemap($item, $value);
      if (exists($link))
      {
        break;
      }
    }
  }
  return $link;
}


/**
 * Determines if the current section has sub-pages,
 * optionally check any section given as a parameter.
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
 * Determines the link for the current section.
 *
 * If the section's sub items are an array
 * the 'slug name' is used for the link,
 * if the section's sub item is a string,
 * the string is used for the link.
 *
 * Optionally check any section given as a parameter.
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
 * sub item that isn't itself an array.
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

#   ================================= END ==================================   #