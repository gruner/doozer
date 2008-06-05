<?php

# This is a sitemap used to generate navigation and breadcrumbs.
# Each site section is composed of a separate hash in the form of:
#   "Page Title" => "url" (assumes '.php')

# After changing this file be sure to locally run 'sitemapXMLGenerator.php'
# to create an updated 'sitemap.xml' file at the root level
    
function define_sitemap() {
    
    $company = array(
        'About Our Office',
    	'Meet The Orthodontist',
    	'Office Tour',
    	'Office Policies',
    	'Contests & Events'
    );

    $ortho = array(
        'About Orthodontics',
    	'For Children',
    	'For Adults',
    	'Two-Phase Treatment',
    	'Orthodontic Treatments',
    	'Ortho Dictionary',
    	'FAQ',
    	'Links'
    );

    $braces = array(
        'Life with Braces',
    	'Types of Braces',
    	'Types of Appliances',
    	'Palatal Expander',
    	'Braces Diagram',
    	'Brace Painter',
    	'Patient Care Video',
    	'Retainers',
    	'Invisalign®',
    	'Damon™ System',
    	'Surgical Orthodontics'
    );

    $contact = array(
        "Contact Us",
        "Location",
        "Refer a Friend"
    );
    
    #Items with no sub pages
    # Emergency Care
    # The Game Room
    
    $text_only = array(
    	'Site Map',
    	'Privacy Policy'	
	);
    
    
    # separate sections are merged to form master sitemap hash
    $sitemap = array(
        "About Our Office" => $about,
        "About Orthodontics" => $ortho,
        "Braces 101" => $braces,
        "Emergency Care" => $emergency,
        "The Game Room" => $games,
        "Contact Us" => $contact
    );
    return $sitemap;
}
?>
