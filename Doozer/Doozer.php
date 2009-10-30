<?php
require_once('Doozer_Sitemap.php');
require_once('Doozer_Helpers.php');

/**
* dev note: all public methods should echo a result
*/
class Doozer
{
	public $meta, $page;
	private $sitemap, $helpers;
	
	function __construct()
	{
		$this->helpers = new Doozer_Helpers($this);
		$this->sitemap = new Doozer_Sitemap();
		$this->page = new Doozer_Page();
		$this->meta = array('index_pages' => false); # set any default values here
	}

/**
 * outputs content from the page, either the page itself
 * or a snippet of html defined on the page, given as the $html var
 *
 * useful for defining pieces of html content per page, such as a sidebar, that can
 * be plugged into the template
 */
	public function content($html='')
	{
		$dz = $this; # set $dz for page scope
		if (empty($html))
		{
			if (file_exists($this->page->fn))
			{
				include $this->page->fn;
			}
		}
		elseif(isset($this->meta[$html]))
		{
			echo $this->meta[$html];
		}
	}


	public function nav()
	{
		# code...
	}


	public function text_nav($value='')
	{
		# code...
	}


	public function page()
	{
		echo $this->page->name;
	}


	protected function __set($var, $val)
	{
		if (is_array($val))
		{
			if ($var == 'config')
			{
				$this->meta = $val + $this->meta; # merge the new values with the meta array
			}
			elseif ($var == 'sitemap')
			{
				$this->sitemap = new Doozer_Sitemap($val);
			}
		}
		elseif ($var == 'sitemap' && is_array($val))
		{
			$this->sitemap->set_sitemap($val);
		}
	}


	protected function __call($method, $args) {
		# check for page-level variable named $_[method]
		# else check for site-level variable
		if (isset($this->meta[$method])){
			echo $this->meta[$method];
		}
		else {
			# pass the $method to the helper object
			echo call_user_func_array(array($this->helpers, $method), $args);
		}
	}
}


/**
* Holds the state of the current page and defines its corresponding file
*/
class Doozer_Page
{
	public $name, $fn, $meta;
	
	function __construct($root_dir='')
	{
		# query string variable sets the page, defaults to 'home'
		# TODO: should probably do some sanitizing of the query string
		$this->name = isset($_GET['page']) ? $_GET['page'] : 'home';
		$this->fn = $root_dir.$this->name.'.php';
		$this->get_meta();
	}
	
	private function get_meta()
	{
		$this->meta = array();
		# get the meta vars from the page
		# * need to get these right away
		# * parse the page as text?
		# * output buffering?
		//get_defined_vars(); //array_keys(get_defined_vars()));
	}
}


# instantiate a new Doozer object
$dz = new Doozer();
?>