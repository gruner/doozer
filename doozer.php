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
		$this->page = new DZPage();  # basename(dirname(__FILE__))
		# array_merge($this->config, $this->page->meta); # TODO find a place for
	}


	public function config($config)
	{
		$this->config = array_merge($this->page->meta, $config);
	}


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
class DZPage
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


$dz = new Doozer();
?>