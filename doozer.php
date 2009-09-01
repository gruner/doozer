<?php
/**
* 
*/
class Doozer
{
	function _site_config()
	{
		# code...
	}
	
	
	
	function __construct()
	{
		$t = $this;
		$site = $t->_site_config();
		$page = $t->_page_config();
		$sitemap = $t->_parse_sitemap();
	}
}
$dz = new Doozer();
?>

<?php $dz->nav(); ?>
<?php Doozer::nav(); ?>
<?php $dz->subnav(); ?>
<?php $dz->breadcrumbs(); ?>