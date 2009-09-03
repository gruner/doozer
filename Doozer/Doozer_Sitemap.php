<?php

/**
* 
*/
class Doozer_Tree
{
	public $value, $children;

	function __construct($value)
	{
		$this->name = $name;
		$this->children = array();
	}
	
	public function add_child($value)
	{
		$subtree = new Doozer_Tree($value);
		$this->children[] = $subtree;
		return $subtree;
	}
}




/**
* 
*/
class Doozer_Sitemap extends Doozer_Tree
{
	
	function __construct($sitemap)
	{
		# code...
	}
	
	/**
	 * converts the sitemap into nested uls
	 */
	public function to_html()
	{
		# code...
	}
}
?>