<?php
require_once('Doozer_Sitemap.php');
require_once('Doozer_Helpers.php');

/**
* dev note: all public methods should echo a result
*/
class Doozer
{
	public $meta, $page;
	private $helpers;
	
	function __construct()
	{
		$this->helpers = new Doozer_Helpers($this);
		$this->page = new Doozer_Page();  # basename(dirname(__FILE__))
		$this->meta = array('index_pages' => false);
	}

/**
 * outputs content from the page, either the page itself
 * or one of its page vars given as $section
 *
 * useful for defining pieces of content, such as a sidebar, that can
 * be plugged into the template
 */
	public function content($section='')
	{
		$dz = $this;
		if (empty($section))
		{
			if (file_exists($this->page->fn))
			{
				include $this->page->fn;
			}
		}
		elseif(isset($this->meta[$section]))
		{
			echo $this->meta[$section];
		}
	}


	public function nav($value='')
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

	// private function load_content($page)
	// {
	// 	if (file_exists($page.'.php'))
	// 	{
	// 		ob_start();
	// 		include $page.'.php';
	// 		$this->content = ob_get_contents();
	// 		// get_defined_vars(); filter to get all '$_' vars
	// 		ob_end_clean();
	// 	}
	// 	return false;
	// }
	
	protected function __set($var, $val)
	{
		if ($var == 'config' && is_array($val))
		{
			$this->meta = $val + $this->meta; # merge the new values with the meta array
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
* 
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
		# * parse the page as text?
		# * output buffering?
	}
}

/**
* 
*/
class Doozer_Navigation
{
	
	public $page, $section;
	
	function __construct($page, $section)
	{
		$this->$page = $page;
		$this->$section = $section;
	}
}

# instantiate a new Doozer object
$dz = new Doozer();
?>