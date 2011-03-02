<?php

# This is a sitemap used to generate navigation and breadcrumbs.

$sitemap = array(
  'Home' => 'index.php', // you can specify a specific file
  'Login' => 'http://example.com/login', // you can specify a specific url
  'About Us' => array(
    'Our Mission',
    'Locations',
    'Contact'),
  'FAQs',
  'Our Services',
  'Products' => array(
    'Nuts',
    'Bolts',
    'Clocks' => array(
      'Digital',
      'Analog'),
    'Thermometers' => array(
      'Fahrenheit',
      'Celsius')));
?>