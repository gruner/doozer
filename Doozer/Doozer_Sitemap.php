<?php

// class Doozer_Tree
// {
// 	public $value, $children;
// 
// 	function __construct($value)
// 	{
// 		$this->value = $value;
// 		$this->children = array();
// 	}
// 	
// 	public function add_child($value)
// 	{
// 		$subtree = new Doozer_Tree($value);
// 		$this->children[] = $subtree;
// 		return $subtree;
// 	}
// }


// class Doozer_Sitemap extends Doozer_Tree
// {
// 	
// 	function __construct($sitemap)
// 	{
// 		# code...
// 	}
// 	
// 	/**
// 	 * converts the sitemap into nested uls
// 	 */
// 	public function to_html()
// 	{
// 		# code...
// 	}
// 	
// 	/**
// 	 * converts the sitemap into navigation div
// 	 */
// 	public function navigation()
// 	{
// 		# code...
// 	}
// }


class Doozer_Sitemap
{
	public $page, $section;
	private $sitemap = array();

	function __construct($page, $section)
	{
		$this->page = $page;
		$this->section = $section;
	}
	
	
	function set_sitemap($raw_sitemap)
	{
		if (!empty($this->$sitemap))
		{
			$this->$sitemap = $this->parse_sitemap($raw_sitemap);
		}
	}


	/**
	 * processes the defined, expanding the outline into specific pages and urls
	 */
	function parse_sitemap($raw_sitemap)
	{
		$parsed_sitemap = array();
		foreach ($raw_sitemap as $section => $sub_section)
		{
			$parsed_sitemap = $this->parse_section($parsed_sitemap, $section, $sub_section);
		}
		return $parsed_sitemap;
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
			$sitemap[$section] = $this->parse_sub_section($section, $sub_section);
		}
		else
			# array is in the form of
			# key => $section
			# with key being numeric because there are no sub-items
		{
			$sitemap[$sub_section] = $this->parse_sub_section($section, $sub_section);
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
			$sub = $this->slug($sub_section).'.php';
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
					$sub = $this->parse_section($sub, $sub_key, $sub_value);
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
	 * recursively formats the navigation sitemap, building a string of nested <ul>s
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
	
	

}

?>