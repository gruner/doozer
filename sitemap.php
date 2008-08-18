<?php
function define_sitemap() {

  $about_our_office = array(
  'Meet the Orthodontist',
  'Meet the Staff',
  'Office Tour',
  'Office Policies'
  );

  $about_orthodontics = array(
  'Why Braces?',
  'For Children',
  'For Adults',
  'Two-Phase Treatment',
  'Orthodontic Treatments',
  'Ortho Dictionary',
  'Ask the Orthodontist',
  'Resources'
  );

  $braces_101 = array(
  'Life with Braces',
  'Types of Braces',
  'Types of Appliances',
  'Braces Diagram',
  'Brace Painter',
  'Oral Care Video',
  'Retainers',
  'iBraces'
  );

  $contact_us = array(
  'Location',
  'Comment Form',
  'Refer a Friend',
  'Appointment Request'
  );

  # define an empty array for items with no sub nav
  $no_sub = array();

  # separate sections are merged to form the master sitemap hash
  $sitemap = array(
  'Home' => 'index.php',
  'About Our Office' => $about_our_office,
  'About Orthodontics' => $about_orthodontics,
  'Braces 101' => $braces_101,
  'Emergency Care' => $no_sub,
  'The Game Room' => $no_sub,
  'Contact Us' => $contact_us
  );
  
  return $sitemap;
}
?>
