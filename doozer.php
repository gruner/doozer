<?php
require_once('dz_helpers.php');
/**
* dev note: all public methods should echo a result
*/
class Doozer
{
	public $config, $page;
	private $helpers, $content, $page_vars;
	
	function __construct()
	{
		$this->helpers = new DZHelpers($this);
		$this->get_page();
		$this->load_content($this->page); # load the page corresponding to page var and eval its code
	}
	
	public function content($section='')
	{
		echo $this->content;
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
	
	private function load_content($page)
	{
		if (file_exists($page.'.php'))
		{
			ob_start();
			include $page.'.php';
			$this->content = ob_get_contents();
			// get_defined_vars(); filter to get all '$_' vars
			ob_end_clean();
		}
		return false;
	}

	protected function __call($method, $params) {
		# check for page-level variable named $_[method]
		# else check for site-level variable
		if (isset($this->config[$method])){
			echo $this->config[$method];
		}
		else {
			# pass the $method to the helper object
			echo $this->helpers->$method($params);
		}
	}
}
$dz = new Doozer();
?>