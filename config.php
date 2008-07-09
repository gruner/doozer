<?php
function sc_config(){
	# sets default values that will be used on every page unless that page overrides it

    $sc_config = array();
    
    # default meta keywords for every page
    $sc_config['meta_keywords'] = 'key, words, separated, by, commas';
    
    # default meta description for every page
    $sc_config['meta_description'] = 'paste the page description here';
    
    # default base page title for every page
    $sc_config['page_title'] = 'Braces Orthodontics - City ST - Orthodontist(s) Doctor Name(s) Practice Name - State Zip';
    
    return $sc_config;
}

?>