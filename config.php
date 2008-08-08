<?php
/**
 * This file defines site-specific variables that are used by {@link global.php}.
 *
 * The data defined in this file are used as default values throughout a site. 
 * Each separate page can override the default values by defining them locally.
 *
 * The purpose is to define all site-specifig information in this single file rather than having to alter {@link global.php} for each site.
 *
 * @package scFramework
 */

/**
 * This function returns a single array of key-value pairs that define data used throughout a site.
 * @return array $sc_config the array of default configuration values
 */
function sc_config(){
	# sets default values that will be used on every page unless that page overrides it

    $sc_config = array();
    
    # default meta keywords for every page
    $sc_config['meta_keywords'] = 'scott brustein, scott b brustein dds, staten island, new york, orthodontics, orthodontist, braces, adult orthodontics, child orthodontics, 10308';

    # default meta description for every page
    $sc_config['meta_description'] = 'Orthodontics for Children and Adults – Your Staten Island Orthodontist - Premium Orthodontics in Staten Island, New York 10308. Dr. Scott Brustein is the trusted Staten Island orthodontist providing orthodontic care for children and adults.';

    # default base page title for every page
    $sc_config['page_title'] = 'Braces Orthodontics - Staten Island NY - Orthodontist Scott Brustein - New York 10308';

    return $sc_config;
}

?>