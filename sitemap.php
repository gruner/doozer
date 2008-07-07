<?php

# This is a sitemap used to generate navigation and breadcrumbs.
# Each site section is composed of a separate hash of page titles
# corresponding to files that follow our naming convention.

# After changing this file be sure to locally run 'sitemapXMLGenerator.php'
# to create an updated 'sitemap.xml' file at the root level of the site.

function define_sitemap() {
    
    $about = array(
    	'Meet The Orthodontist',
    	'Meet the Team',
    	'Office Tour',
    	'Office Policies',
    	'What Sets Us Apart',
    	'Community Photographs'
    );

    $ortho = array(
        'Why Braces?',
    	'For Children',
    	'For Adults',
    	'Two-Phase Treatment',
    	'Orthodontic Treatments',
    	'Ortho Dictionary',
    	'Ask the Orthodontist',
    	'Resources'
    );

    $braces = array(
        'Life with Braces',
    	'Types of Braces',
    	'Types of Appliances',
    	'Braces Diagram',
    	'Oral Care Video',
    	'Retainers'
    );
    
    $ortho_tech = array(
      'Invisalign&reg;',
      'Damon&trade; System',
      'Surgical Orthodontics'
    );

    $contact = array(
      'Location',
      'Comment Form',
      'Refer a Friend',
      'Appointment Request'
    );
    
    #Items with no sub pages
    $emergency = array('Emergency Care');
    
    $sitemap = array('Site Map');
    
    
    # separate sections are merged to form master sitemap hash
    $sitemap = array(
        'About Our Office' => $about,
        'About Orthodontics' => $ortho,
        'Braces 101' => $braces,
        'Orthodontic Technologies' => $ortho_tech,
        'Emergency Care' => $emergency,
        'Contact Us' => $contact,
        'Site Map' => $sitemap
    );
    return $sitemap;
}

?>
