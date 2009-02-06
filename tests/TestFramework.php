<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once('simpletest/autorun.php');
require_once('../global.php');

class TestOfFramework extends UnitTestCase {
	
	function setUp()
	{
		$_section = 'Braces 101';
		$_page_name = 'Life with Braces&reg;';
		$_keyword = 'Invisalign';
		$_page_title = '[this text replaces the base title]';
		$_alt = 'lorem ipsum';
	}
	
	
	function test_site_name()
	{
		$this->assertEqual('Practice Name', get_site_name());
	}
	
	
	function test_slug_name()
	{
		$this->assertEqual(slug_name('Life with Braces&reg;'), 'life-with-braces');
		$this->assertEqual(slug_name('TMJ/TMD'), 'tmj-tmd');
		$this->assertEqual(slug_name('Damon&trade;'), 'damon');
		$this->assertEqual(slug_name('Why Braces?'), 'why-braces');
		$this->assertEqual(slug_name('Braces 101'), 'braces-101');
		$this->assertEqual(slug_name('!!??$$Braces!!$$'), 'braces');
		$this->assertEqual(slug_name('!!?()?$$Braces!!$$'), 'braces');
	}
	
	
	function test_place_image()
	{
		#$this->assertEqual(place_image('alf'), '<img src="images/alf.jpg" width="124" height="93" alt="lorem ipsum" />');
		#$this->assertEqual(place_image('alf.jpg'), '<img src="images/alf.jpg" width="124" height="93" alt="lorem ipsum" />');
	}
	
	
	function test_format_list_with_separator()
	{
		$list = Array('one','two','thee','four');
		$this->assertEqual(format_list_with_separator($list), 'one | two | thee | four');
		$this->assertEqual(format_list_with_separator($list, ' * '), 'one * two * thee * four');
	}
	
	
	function test_format_nav_link()
	{
		$this->assertEqual(format_nav_link('Lorem Ipsum'), '<a href="lorem-ipsum.php">Lorem Ipsum</a>');
		$this->assertEqual(format_nav_link('Lorem Ipsum&reg;'), '<a href="lorem-ipsum.php">Lorem Ipsum&reg;</a>');
		$this->assertEqual(format_nav_link('Lorem Ipsum', 'http://google.com'), '<a href="http://google.com">Lorem Ipsum</a>');
		$this->assertEqual(format_nav_link('Lorem Ipsum&reg;', '', true), '<a href="lorem-ipsum.php" id="lorem-ipsum">Lorem Ipsum&reg;</a>');
	}
	
	
	function test_get_nav_attributes()
	{
	  $test1 = array('one','two','three');
  	$this->assertIdentical(' class="one active first"', get_nav_attributes('one', 'one', $test1));
  	$this->assertIdentical(' class="two"', get_nav_attributes('one', 'two', $test1));
  	$this->assertIdentical(' class="three last"', get_nav_attributes('one', 'three', $test1));
  	$this->assertIdentical(' class="three active last"', get_nav_attributes('three', 'three', $test1));
  	
  	$test2 = array('uno' => $test1, 'dos' => $test1, 'tres' => $test1);
  	$this->assertidentical(' class="uno active first"', get_nav_attributes('uno', 'uno', $test2));
  	$this->assertidentical(' class="dos active"', get_nav_attributes('dos', 'dos', $test2));
  	$this->assertidentical(' class="tres active last"', get_nav_attributes('tres', 'tres', $test2));
  	
  	# this section illustrates how php looks at arrays
  	$this->assertIdentical(Array('uno','dos','tres') ,array_keys($test2));
  	$this->assertIdentical('uno', reset(array_keys($test2)));
  	$this->assertidentical('tres', end(array_keys($test2)));
	}
	
	
	function test_sitemap_is_singleton()
	{
		// $a = get_sitemap();
		// $b = get_sitemap();
		// $this->assertReference($a,$b);
	}

}
?>