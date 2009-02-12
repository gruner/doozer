<?php
function define_sitemap() {

  # Each section of the site is defined as a separate array.
  # Each string in the array will be converted into a link
  # i.e. 'Life with Braces' becomes 'life-with-braces.php'.

  $about_our_office = array(
  'Meet the Orthodontist',
  'Meet the Staff',
  'External Link' => 'http://google.com'
  );

  $about_orthodontics = array(
  'Why Braces?',
  'For Children',
  'For Adults',
  'Resources'
  );

  $braces_101 = array(
  'Life with Braces&reg;',
  'Types of Braces',
  'Types of Appliances',
  'Braces Diagram',
  );

  $contact_us = array(
  'Location',
  'Comment Form',
  'Refer a Friend',
  'Appointment Request'
  );
  
  $tertiary = array(
  	'tertiary 1',
  	'tertiary 2' => 'http://google.com',
  	'tertiary 3',
  	'tertiary 4'
  );
  
  $secondary = array(
  	'secondary 1',
  	'secondary 2',
  	'secondary 3',
  	'tertiary' => $tertiary
  );

  # Separate sections are merged to form the master sitemap hash.
  # These are the top level links.
  #
  # Each section can link to one of the arrays defined above,
  # or can contain a single string which will be used as the link
  
  $sitemap = array(
  'Home' => 'index.php',
  'Patient Login' => 'http://orthosesame.com',
  'About Our Office' => $about_our_office,
  'About Orthodontics' => $about_orthodontics,
  'Braces 101' => $braces_101,
  'Emergency Care', # if the array is empty the link will become 'emergency-care.php'
  'Nested' => $secondary,
  'Contact Us' => $contact_us,
  'Test'
  );
  
  return $sitemap;
}
?>
