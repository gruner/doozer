<?php
/**
 * This file defines site-specific variables that are used by  global.php.
 *
 * The data defined in this file are used as default values throughout a site. 
 * Each separate page can override the default values by defining them locally.
 *
 * The purpose is to define all site-specifig information in this single file rather than having to alter global.php for each site.
 *
 */

/**
 * This function returns a single array of key-value pairs that define data used throughout a site.
 * @return array $sc_config the array of default configuration values
 */
function sc_config(){
	# sets default values that will be used on every page unless that page overrides it

    $sc_config = array();
    
    # default meta keywords for every page
    $sc_config['meta_keywords'] = 'paste, the, site, keywords, here';

    # default meta description for every page
    $sc_config['meta_description'] = 'paste the site description text here';

    # default base page title for every page
    $sc_config['page_title'] = 'City ST - Orthodontist name - State zip';
    
    $sc_config['title_keywords'] = 'Braces Orthodontics';
    
    $sc_config['site_name'] = 'Practice Name';

    return $sc_config;
}

?>