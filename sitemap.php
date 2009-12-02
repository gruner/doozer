<?php

# This is a sitemap used to generate navigation and breadcrumbs.

$sitemap = array(
  'Home' => 'index.php',
  'Login' => 'http://google.com',
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
      'Celsius')),
  'Site Map');
?>