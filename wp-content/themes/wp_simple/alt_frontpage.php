<?php
/*
  Template Name: Alternate Frontpage
 */
 
get_header();
if(trim(get_post_meta( get_the_ID(), 'fp_content_pos', true )) == 'above') {
    get_template_part( 'parts/frontpage', 'content'); 
}
if (trim(get_post_meta( get_the_ID(), 'toggle_featured', true )) == 'on') {
    get_template_part( 'parts/frontpage', 'featured'); 
}
if(trim(get_post_meta( get_the_ID(), 'fp_content_pos', true )) == 'below') {
    get_template_part( 'parts/frontpage', 'content'); 
}
if ( 'posts' == get_option( 'show_on_front' ) ) { 
    get_template_part( 'parts/content', 'blog'); 
} else {
    get_template_part( 'parts/frontpage', 'blog');
}    

get_footer(); 


// Additional conditions may be found in parts/banner, nimbus/options, sidebar. 

?>