<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once('simpletest/autorun.php');
require_once('../global.php');
require_once('../nav.php');

class TestOfFramework extends UnitTestCase {

  function setUp()
  {
    # set the variables that would normally be defined on each page
    $GLOBALS['_section'] = 'Braces 101';
    $GLOBALS['_name'] = 'Life with Braces&reg;';
    $GLOBALS['_keyword'] = 'Invisalign';
    $GLOBALS['_title'] = '[this text replaces the base title]';
    $GLOBALS['_alt'] = 'lorem ipsum';
  }


  # ============================================================================ #
  #    UTILITY FUNCTIONS                                                         #
  # ============================================================================ #


  function test_exists()
  {
    $this->assertTrue(exists($GLOBALS['_name']));

    $foo = '';
    $bar = array();
    $this->assertFalse(exists($foo));
    $this->assertFalse(exists($bar));

    $this->assertEqual(use_default('', 'foo'), 'foo');
    $this->assertEqual(use_default('bar', 'foo'), 'bar');

    $this->assertNotEqual(use_default('bar', 'foo'), 'foo');
    $this->assertNotEqual(use_default('', 'foo'), '');
  }


  function test_format_list_with_separator()
  {
    $list = Array('one','two','three','four');
    $this->assertEqual(format_list_with_separator($list), 'one | two | three | four');
    $this->assertEqual(format_list_with_separator($list, ' * '), 'one * two * three * four');
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


  function test_get_site_name()
  {
    global $config;
    $this->assertEqual($config['site_name'], get_site_name());
  }


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


  # ============================================================================ #
  #    SITEMAP PARSING                                                           #
  # ============================================================================ #


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
    $this->assertIdentical('tertiary1', get_first_unnested_item($nested));


    $sitemap = array(
      'foo' => 'foo.php',
      'bar' => array('baz' => 'baz.php'),
      'chewie' => array('han' => 'han.php'),
      'yoda' => array('obi' => array('luke' => 'luke.php')),
      'anakin' => 'vader.php'
    );
    $this->assertIdentical(get_first_unnested_item($sitemap), 'foo.php');
    $this->assertIdentical(get_first_unnested_item($sitemap['bar']), 'baz.php');
    $this->assertIdentical(get_first_unnested_item($sitemap['yoda']), 'luke.php');
    $this->assertIdentical(get_first_unnested_item($sitemap['chewie']), 'han.php');
  }


  // function test_get_nav_attributes()
  // {
  //   $test1 = array('one','two','three');
  //   $test2 = array('uno' => $test1, 'dos' => $test1, 'tres' => $test1);
  //   $this->assertidentical(' class="uno active first"', get_nav_attributes('uno', 'uno', $test2));
  //   $this->assertidentical(' class="dos active"', get_nav_attributes('dos', 'dos', $test2));
  //   $this->assertidentical(' class="tres active last"', get_nav_attributes('tres', 'tres', $test2));
  //
  //   # this section illustrates how php looks at arrays
  //   $this->assertIdentical(Array('uno','dos','tres') ,array_keys($test2));
  //   $this->assertIdentical('uno', reset(array_keys($test2)));
  //   $this->assertidentical('tres', end(array_keys($test2)));
  // }


  function test_format_nav_link()
  {
    $this->assertIdentical(format_nav_link('Lorem Ipsum'), '<a href="lorem-ipsum.php">Lorem Ipsum</a>'."\n");
    $this->assertIdentical(format_nav_link('Lorem Ipsum&reg;'), '<a href="lorem-ipsum.php">Lorem Ipsum&reg;</a>'."\n");
    $this->assertIdentical(format_nav_link('Lorem Ipsum', 'http://google.com'), '<a href="http://google.com">Lorem Ipsum</a>'."\n");
    $this->assertIdentical(format_nav_link('Lorem Ipsum&reg;', '', true), '<a href="lorem-ipsum.php" id="lorem-ipsum">Lorem Ipsum&reg;</a>'."\n");
  }


  function test_find_in_sitemap()
  {
    $sitemap = array(
      'foo' => 'foo.php',
      'bar' => array('baz' => 'baz.php'),
      'chewie' => array('han' => 'han.php'),

      'anakin' => 'vader.php',
      'html' => array('div' => array('p' => array('span' => 'em'))),
      'yoda' => array('obi' => array('luke' => 'luke.php'))
    );

    $this->assertIdentical(find_in_sitemap('foo', $sitemap), 'foo.php');
    $this->assertIdentical(find_in_sitemap('bar', $sitemap), 'baz.php');
    $this->assertIdentical(find_in_sitemap('baz', $sitemap), 'baz.php'); ##

    $this->assertIdentical(find_in_sitemap('yoda', $sitemap), 'luke.php');
    $this->assertIdentical(find_in_sitemap('obi', $sitemap), 'luke.php');
    $this->assertIdentical(find_in_sitemap('luke', $sitemap), 'luke.php');

    $this->assertIdentical(find_in_sitemap('chewie', $sitemap), 'han.php');
    $this->assertIdentical(find_in_sitemap('han', $sitemap), 'han.php'); ##

    $this->assertIdentical(find_in_sitemap('anakin', $sitemap), 'vader.php');

    $this->assertIdentical(find_in_sitemap('html', $sitemap), 'em');
    $this->assertIdentical(find_in_sitemap('div', $sitemap), 'em');
    $this->assertIdentical(find_in_sitemap('p', $sitemap), 'em');
    $this->assertIdentical(find_in_sitemap('span', $sitemap), 'em');
  }


  # ============================================================================ #
  #    HTML HELPERS                                                              #
  # ============================================================================ #

  function test_headline_tag()
  {
    # default to the page name
    $this->assertIdentical('<h1 class="headline">Life with Braces&reg;</h1>'."\n", headline_tag());

    # default to the page name
    $this->assertIdentical('<h1 class="custom">Life with Braces&reg;</h1>'."\n", headline_tag('h1', array('class' => 'custom')));

    # specify a _headline variable
    $GLOBALS['_headline'] = 'H1 Override';
    $this->assertIdentical('<h1 class="headline">H1 Override</h1>'."\n", headline_tag());
  }


  # ============================================================================ #
  #    NAVIGATION HELPERS                                                        #
  # ============================================================================ #


  function test_callout_navigation()
  {
    $callouts = array('one' => 'one.php', 'two' => 'two.php', 'three' => 'three.php');
    $this->assertEqual(callout_navigation($callouts), "<ul class=\"callouts\"><li><a href=\"one.php\">one</a>\n</li>\n<li><a href=\"two.php\">two</a>\n</li>\n<li><a href=\"three.php\">three</a>\n</li>\n</ul>\n");
  }





}
?>