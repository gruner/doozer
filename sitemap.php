<?php
/**
 * This file defines a sitemap that is used to generate structural content (i.e. navigation, breadcrumbs, etc...)
 *
 * Each site section is composed of a separate hash of page titles 
 * corresponding to files that follow Sesame's naming convention.
 *
 * After changing this file be sure to locally run {@link sitemapXMLGenerator.php} 
 * to create an updated 'sitemap.xml' file at the root level of the site.
 *
 * @package scFramework
 */

/**
 * defines an array for each section of the site and combines them to form a single multi-dimensional array used to generate the navigation for the site
 * @return array $sitemap
 */
function define_sitemap() {
    
    # create an array for every section of the site 
    
    $about = array(
    	'Meet The Orthodontist',
    	'Meet the Team',
    	'Office Tour'
    );

    $ortho = array(
    	'For Children',
    	'For Adults',
    	'Two-Phase Treatment',
    	'TMJ/TMD',
    	'Ortho Dictionary',
    	'FAQ',
    	'Resources'
    );

    $braces = array(
    	'Types of Braces',
    	'Types of Appliances',
      'Palatal Expander',
    	'Braces Diagram',
    	'Patient Care Video',
    	'Retainers'
    );
    
    $treatment = array(
      'Orthodontic Treatments',
      'Invisalign&reg;',
      'Surgical Orthodontics'
    );
    
    $games = array(
      'The Game Room',
      'Brace Painter',
      'Color Your Retainer'
    );

    $contact = array(
      'Location',
      'Comment Form',
      'Refer a Friend',
      'Appointment Request'
    );
    
    # define empty array for items with no sub nav
    $no_sub = array();
    
    # separate sections are merged to form the master sitemap hash
    $sitemap = array(
        'Home' => $no_sub,
        'Our Office' => $about,
        'Your First Visit' => $no_sub,
        'About Orthodontics' => $ortho,
        'Braces 101' => $braces,
        'Treatment Options' => $treatment,
        'Emergency Care' => $no_sub,
        'Fun & Games' => $games,
        'Contact Us' => $contact,
        'Site Map' => ''
    );
    return $sitemap;
}

?>
