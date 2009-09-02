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
		$this->helpers = new DZHelpers($this);
		//$this->content_dir = dirname($_SERVER['REQUEST_URI']); $cur_dir = basename(dirname($_SERVER[PHP_SELF]))
		$this->get_page();
		$this->merge_page_vars();
		# $this->load_content($this->page);
	}


	public function content($section='')
	{
		$page = "$this->page".'.php'; # TODO: make path be relative to the index page
		$dz = $this;
		if (empty($section))
		{
			if (file_exists($page))
			{
				include $page;
				// get_defined_vars(); filter to get all '$_' vars
			}
		}
		else
		{
			# TODO: return the $_[section] var
			return;
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
		echo $this->page;
	}


	private function get_page()
	{
		# TODO: should probably do some sanitizing of the query string

		if (isset($this->page)) {
			return $this->page;
		}
		else
		{
			# query string variable sets the page, defaults to 'home'
			$this->page = isset($_GET['page']) ? $_GET['page'] : 'home';
			return $this->page;
		}
	}
	
	
	private function merge_page_vars()
	{
		# code...
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
class DZPage
{
	public $meta, $fn;
	
	function __construct($name)
	{
		# code...
	}
}


$dz = new Doozer();
?>