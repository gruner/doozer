<?php

/**
 * include this file to use the older function names
 */
 

function navigation($exclusions=array(), $include_sub_nav=false, $div_id='nav')
{
  print_navigation($exclusions, $include_sub_nav, $div_id);  
}


function full_navigation($exclusions=array())
{
  print_navigation($exclusions, $include_sub_nav=true);
} 
 

function sub_navigation($section='', $pre_string='', $post_string='')
{
  print_sub_navigation($section, $pre_string, $post_string);
}


function sub_navigation_with_heading($section='', $link=false, $tag='h3')
{
  print_sub_navigation_with_heading($section, $link, $tag);
}


function text_navigation($breaks='', $exclusions=array(), $separator=' | ', $class_name='text_nav')
{
  print_text_navigation($breaks, $exclusions, $separator, $class_name);
}

function breadcrumbs($separator='&#8250;')
{
  print_breadcrumbs($separator);
}


/*
 * deprecated
 */
function main_navigation($exclusions)
{
  print_navigation($exclusions, $include_sub_nav=false);
}


?>