<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once('simpletest/autorun.php');
require_once('../global.php');

class TestOfFramework extends UnitTestCase {

  function setUp()
  {
    # set the variables that would normally be defined on each page
    $GLOBALS['_h1'] = 'H1 Override';
    $GLOBALS['_section'] = 'Braces 101';
    $GLOBALS['_page_name'] = 'Life with Braces&reg;';
    $GLOBALS['_keyword'] = 'Invisalign';
    $GLOBALS['_page_title'] = '[this text replaces the base title]';
    $GLOBALS['_alt'] = 'lorem ipsum';
  }


  function test_site_name()
  {
    $this->assertEqual('Practice Name', get_site_name());
  }


  function test_slug_name()
  {
    $this->assertEqual(slug_name('Life with Braces&reg;'), 'life-with-braces');
    $this->assertEqual(slug_name('TMJ/TMD'), 'tmj-tmd');
    $this->assertEqual(slug_name('In-Ovation&reg;'), 'in-ovation');
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
    $test2 = array('uno' => $test1, 'dos' => $test1, 'tres' => $test1);
    $this->assertidentical(' class="uno active first"', get_nav_attributes('uno', 'uno', $test2));
    $this->assertidentical(' class="dos active"', get_nav_attributes('dos', 'dos', $test2));
    $this->assertidentical(' class="tres active last"', get_nav_attributes('tres', 'tres', $test2));

    # this section illustrates how php looks at arrays
    $this->assertIdentical(Array('uno','dos','tres') ,array_keys($test2));
    $this->assertIdentical('uno', reset(array_keys($test2)));
    $this->assertidentical('tres', end(array_keys($test2)));
  }


  // function test_sitemap_is_singleton()
  // {
  //   $a = get_sitemap();
  //   $b = get_sitemap();
  //   $this->assertReference($a,$b);
  // }


  function test_array_examples()
  {
    $test1 = array('one','two','three');
    $test2 = array('uno' => $test1, 'dos' => $test1, 'tres' => $test1);
    $test3 = array('foo' => $test2, 'bar');

    $this->assertEqual('one', reset($test1));
    $this->assertEqual('one', reset($test2['uno']));
    $this->assertEqual('one', reset($test3['foo']['uno']));
    $this->assertEqual('one', reset(reset($test2)));
    $this->assertEqual('one', reset(reset(reset($test3))));

    $this->assertEqual('one', get_first_unnested_item($test3));
  }


  function test_get_first_unnested_item()
  {
    $nested = array(
      'one' => array(
        'secondary1' => array(
          'tertiary1',
          'tertiary2'
        ),
        'secondary2',
        'secondary3'
      ),
      'two',
      'three'
    );
    $this->assertEqual('tertiary1', get_first_unnested_item($nested));
  }

  function test_h1_tag()
  {
    $this->assertEqual('<h1 class="life-with-braces">H1 Override</h1>', h1_tag());
  }

}
?>