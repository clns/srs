<?php

function my_add_scripts() {
    wp_enqueue_script('flexslider', get_bloginfo('template_directory').'-child/scripts/jquery.flexslider-min.js', array('jquery'));
    wp_enqueue_script('flexslider-init', get_bloginfo('template_directory').'-child/scripts/flexslider-init.js', array('jquery', 'flexslider'));
    wp_enqueue_script('flexslider-manual-direction-controls', get_bloginfo('template_directory').'-child/scripts/manualDirectionControl.js', array('jquery', 'flexslider', 'flexslider-init'));
    wp_enqueue_script('bootcamp-selection', get_bloginfo('template_directory').'-child/scripts/bootcamp-selection.js', array('jquery'));
    wp_enqueue_script('get-url-parameters', get_bloginfo('template_directory').'-child/scripts/get-url-parameters.js', array('jquery'));
}
add_action('wp_enqueue_scripts', 'my_add_scripts');

function my_add_styles() {
    wp_enqueue_style('flexslider', get_bloginfo('template_directory').'-child/flexslider.css');
}
add_action('wp_enqueue_scripts', 'my_add_styles');


add_filter('new_royalslider_skins', 'new_royalslider_add_custom_skin', 10, 2);
function new_royalslider_add_custom_skin($skins) {
      $skins['rsFacilitators'] = array(
           'label' => 'Facilitators',
           'path' => get_stylesheet_directory_uri() . '/royalslider-facilitators-skin/rs-facilitators.css'  // get_stylesheet_directory_uri returns path to your theme folder
      );
      return $skins;
}

/* Allow PHP to be executed in widgets. Used on the bootcamp page. */
add_filter('widget_text','execute_php',100);
function execute_php($html) {
  if(strpos($html,"<"."?php")!==false) {
    ob_start();
    eval("?".">".$html);
    $html=ob_get_contents();
    ob_end_clean();
  }
  return $html;
}
?>
