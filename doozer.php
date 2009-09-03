<?php
require_once('dz_helpers.php');

/**
* dev note: all public methods should echo a result
*/
class Doozer
{
	public $config, $page;
	private $helpers;
	
	function __construct()
	{
		$this->helpers = new Doozer_Helpers($this);
		$this->page = new Doozer_Page();  # basename(dirname(__FILE__))
	}


	public function config($config)
	{
		# this violates our goal of having public methods echo a result
		# but don't know what else to do
		$this->config = array_merge($this->page->meta, $config);
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
		elseif(isset($this->config[$section]))
		{
			echo $this->config[$section];
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


	protected function __call($method, $params) {
		# check for page-level variable named $_[method]
		# else check for site-level variable
		if (isset($this->config[$method])){
			echo $this->config[$method];
		}
		else {
			# pass the $method to the helper object
			echo $this->helpers->$method($params[0]);
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
class Doozer_Sitemap
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

/**
* 
*/
class Doozer_Navigation
{
	
	public $page, $section
	
	function __construct($page, $section)
	{
		$this->$page = $page;
		$this->$section = $section;
	}
}


$dz = new Doozer();
?>