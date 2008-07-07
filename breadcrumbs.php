<?php

function build_breadcrumbs() {
    # TODO: finish replacing regex $_SERVER checks with internal page vars
    $sitemap = define_sitemap();
    $breadcrumbs = array("Home" => "/"); #initalize the breadcrumbs array and add the homepage
    foreach ($sitemap as $section => $sub_items) {
        if ($section == $_section) {
            $breadcrumbs[$section] = "/$section/index.php";
            foreach ($sub_items as $sub_name => $sub_url) {
                $sub_name = replace_text($sub_name); # add any special formatting for product names
                $sub_url = "/$section/$sub_url.php";
                if (eregi($sub_url, $_SERVER['PHP_SELF'])) { 
                    $breadcrumbs[$sub_name] = $sub_url;
                }
            }
            break;
        }
    }
    return $breadcrumbs;
}


function breadcrumbs($bc_array, $separator=' &#8250') {
    # assembles a breadcrumbs string 
    # The last array item is bolded, 
    # the rest are linked
    
    foreach ($bc_array as $bc => $link) {
        if ($bc_array[$bc] == end($bc_array)) {
            # Format the last item as bold with no link
            $bc_array[$bc] = "<strong>$bc</strong>";
        } else {
            # Format bc item as a link
            $bc_array[$bc] = "<a href=\"$link\">$bc</a>";
        }
    }
    # Convert the array to a string and insert separator between each element
    $bcString = implode($separator, $bc_array);
    return $bcString;
}


function render_breadcrumbs() {
    $bc_array = build_breadcrumbs();
    $breadcrumbs = breadcrumbs($bc_array);
    echo $breadcrumbs;
}

?>