<?php

function my_add_scripts() {
    wp_enqueue_script('flexslider', get_bloginfo('template_directory').'-child/scripts/jquery.flexslider-min.js', array('jquery'));
    wp_enqueue_script('flexslider-init', get_bloginfo('template_directory').'-child/scripts/flexslider-init.js', array('jquery', 'flexslider'));
    wp_enqueue_script('flexslider-manual-direction-controls', get_bloginfo('template_directory').'-child/scripts/manualDirectionControl.js', array('jquery', 'flexslider', 'flexslider-init'));
}
add_action('wp_enqueue_scripts', 'my_add_scripts');

function my_add_styles() {
    wp_enqueue_style('flexslider', get_bloginfo('template_directory').'-child/flexslider.css');
}
add_action('wp_enqueue_scripts', 'my_add_styles');

register_new_royalslider_files(1);

add_filter('new_royalslider_skins', 'new_royalslider_add_custom_skin', 10, 2);
function new_royalslider_add_custom_skin($skins) {
      $skins['rsFacilitators'] = array(
           'label' => 'Facilitators',
           'path' => get_stylesheet_directory_uri() . '/royalslider-facilitators-skin/rs-facilitators.css'  // get_stylesheet_directory_uri returns path to your theme folder
      );
      return $skins;
}

?>