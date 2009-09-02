<?php
/**
* 
*/
class DZHelpers
{
	private $_dz;
	
	function __construct($dz)
	{
		# code...
		$_dz = $dz;
	}

	public function test_helper()
	{
		return 'from helper';
	}
	
	public function test_with_param($param)
	{
		return $param;
	}
	
	protected function __call($method, $params) {
		return '';
	}
}

?>