<?php
/**
* 
*/
class Doozer
{
	public config, sitemap;
	private helpers;
	
	public function __construct()
	{
		$this->helpers = new DZHelpers($this);
	}
	
	public function content($section='')
	{
		# code...
	}
	
	public function nav($value='')
	{
		# code...
	}
	
	public function text_nav($value='')
	{
		# code...
	}
	
	protected function __call($method, $params) {
		# check for page-level variable named $_[method]
		# else check for site-level variable
		if ($this->config[$method]){
			echo $this->config[$method];
		}
		else {
			# pass the $method to the helper object
			echo $this.helpers->$method;
		}
	}
}
$dz = new Doozer();
?>