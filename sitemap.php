<?php

# This is a sitemap used to generate navigation and breadcrumbs.

$sitemap = array(
  'Home' => 'index.php', // specify a specific file
  'Login' => 'http://example.com/login', // specify a specific url
  'About Us' => array(
    'Meet Our Staff',
    'Location',
    'Contact Form'),
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