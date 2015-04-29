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

/*
 * Parse the title of a franchise bootcamp. Used in the taxonomy-franchise.php file
 * Format with unique identifier: City, State - Unique Identifer, Start Date-End Date
 * Example: Fayetteville, AR - Veteran, December 31-January 1
 *
 * Format without unique identifier: City, State Start Date-End Date
 * Example: Fayetteville, AR December 31-January 1
 *
 */
function generate_franchise_title($post) {
  $start_date_raw = strtotime(get_post_meta($post->ID, 'start_date',true));
  $end_date_raw = strtotime(get_post_meta($post->ID, 'end_date',true));
  $start_date = date("F j", $start_date_raw);
  if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
    $end_date = date("F j", strtotime(get_post_meta($post->ID, 'end_date',true)));
  } else {
    $end_date = date("j", strtotime(get_post_meta($post->ID, 'end_date',true)));
  }
  $unique_identifier = get_post_meta($post->ID, 'unique_identifier', true);
  if (empty($unique_identifier)) {
    $franchise_title = get_post_meta( $post->ID, 'location_city', true ) . ', ' . get_post_meta( $post->ID, 'location_state', true ) . ' ' . $start_date . '-' . $end_date;
  } else {
    $franchise_title = get_post_meta( $post->ID, 'location_city', true ) . ', ' . get_post_meta( $post->ID, 'location_state', true ) . ' - ' . $unique_identifier . ', ' . $start_date . '-' . $end_date;
  }
  return $franchise_title;
}
?>
