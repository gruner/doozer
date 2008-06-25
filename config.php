<?php
function sc_config(){
	# sets default values that will be used on every page unless that page overrides it

    $sc_config = array();
    
    # default meta keywords for every page
    $sc_config['meta_keywords'] = '';
    
    # default meta description for every page
    $sc_config['meta_description'] = '';
    
    # default base page title for every page
    $sc_config['page_title'] = '';
    
    return $sc_config;
}

?>