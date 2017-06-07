<?php

function my_add_scripts() {
    wp_enqueue_script('flexslider', get_bloginfo('template_directory').'-child/scripts/jquery.flexslider-min.js', array('jquery'));
    wp_enqueue_script('flexslider-init', get_bloginfo('template_directory').'-child/scripts/flexslider-init.js', array('jquery', 'flexslider'));
    wp_enqueue_script('flexslider-manual-direction-controls', get_bloginfo('template_directory').'-child/scripts/manualDirectionControl.js', array('jquery', 'flexslider', 'flexslider-init'));
    wp_enqueue_script('bootcamp-selection', get_bloginfo('template_directory').'-child/scripts/bootcamp-selection.js', array('jquery'));
    wp_enqueue_script('get-url-parameters', get_bloginfo('template_directory').'-child/scripts/get-url-parameters.js', array('jquery'));
    wp_enqueue_script('accordion-init_js', get_bloginfo('template_directory').'-child/scripts/accordion-init.js', array('jquery', 'jquery-ui-accordion'));
}
add_action('wp_enqueue_scripts', 'my_add_scripts');

function my_add_styles() {
    wp_enqueue_style('flexslider', get_bloginfo('template_directory').'-child/flexslider.css');
}
add_action('wp_enqueue_scripts', 'my_add_styles');


function add_meta_responsive_viewport() {

  if ( is_page('leaders-conference-2016') ) {
    echo'<meta name="viewport" content="width=device-width, initial-scale=1.0">';
  }
}
add_action('wp_head', 'add_meta_responsive_viewport');


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


if ( ! function_exists( 'srs_expanding_block' ) ) :
/**
 * Display expanding accordian block
*/
function srs_expanding_block( $title, $key, $the_query, $auto_expand = false, $pre = "", $post = "", $default_text = "" ) {

  if ($auto_expand) {
    $expand_class = "expanding-icon-minus";
    $expand_content_class = "first-expanding-content";
  } else {
    $expand_class = "expanding-icon-plus";
    $expand_content_class = "";
  }

  if ($the_query->post_count > 1) {
    $display = 'style="display: none;"';
  } else {
    $display = '';
  } ?>
  <h3 class="expanding-heading" <?php echo $display ?>><?php echo $title; ?><span class="expanding-icon <?php echo $expand_class; ?>"></span></h3>
  <div class="expanding-content <?php echo $expand_content_class; ?>" <?php echo $display ?>>
    <?php
    if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
      <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
        <?php $postmeta = get_post_meta(get_the_ID(), $key, true);
        if (!empty($postmeta)) {
          echo $pre . $postmeta . $post;
        } else {
          if (!empty($default_text)) {
            echo $pre . $default_text . $post;
          }
        }
        ?>

      </div>
    <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
  </div>

  <?php
}
endif; // srs_expanding_block

// Restrict so 'super-editor' can only add 'Contributors' and 'Subscribers'
add_filter( 'editable_roles', 'srs_supereditor_filter_roles' );
function srs_supereditor_filter_roles( $roles )
{
  $user = wp_get_current_user();
  if( in_array( 'super_editor', $user->roles ) )
  {
    $tmp = array_keys( $roles );
    foreach( $tmp as $r )
    {
      if( 'contributor' == $r || 'subscriber' == $r ) continue;
      unset( $roles[$r] );
    }
  }
  return $roles;
}

// Hide Administrator From User List
function srs_pre_user_query($user_search) {
  $user = wp_get_current_user();
  if (!current_user_can('administrator')) { // Is Not Administrator - Remove Administrator
    global $wpdb;

    $user_search->query_where =
      str_replace('WHERE 1=1',
        "WHERE 1=1 AND {$wpdb->users}.ID IN (
                 SELECT {$wpdb->usermeta}.user_id FROM $wpdb->usermeta
                    WHERE {$wpdb->usermeta}.meta_key = '{$wpdb->prefix}capabilities'
                    AND {$wpdb->usermeta}.meta_value NOT LIKE '%administrator%')",
        $user_search->query_where
      );
  }
}
add_action('pre_user_query', 'srs_pre_user_query');

// Remove Private/Protected from Post Titles
function the_title_trim($title) {
  $title = esc_attr($title);
  $findthese = array(
      '#Protected:#',
      '#Private:#'
  );

  $replacewith = array(
      '', // What to replace "Protected:" with
      '' // What to replace "Private:" with
  );

  $title = preg_replace($findthese, $replacewith, $title);
  return $title;
}
add_filter('the_title', 'the_title_trim');

if ( function_exists('register_sidebar') ) {

  register_sidebar(array(
      'name' => esc_html__('Catapult Video Page Column 1', 'udesign'),
      'id' => 'catapult-video-page-column-1',
      'description' => esc_html__('A widget area, used as the 1st column in the Catapult Video Content area.', 'udesign'),
      'before_widget' => '<div class="cont_col_1 %2$s substitute_widget_class">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="cont_col_1_title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Catapult Video Page Column 2', 'udesign'),
      'id' => 'catapult-video-page-column-2',
      'description' => esc_html__('A widget area, used as the 2nd column in the Catapult Video Content area.', 'udesign'),
      'before_widget' => '<div class="cont_col_2 %2$s substitute_widget_class">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="cont_col_2_title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Catapult Video Page Column 3', 'udesign'),
      'id' => 'catapult-video-page-column-3',
      'description' => esc_html__('A widget area, used as the 3rd column in the Catapult Video Content area.', 'udesign'),
      'before_widget' => '<div class="cont_col_3 %2$s substitute_widget_class">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="cont_col_3_title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Catapult Join Box', 'udesign'),
      'id' => 'catapult-join-box',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="join-box">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="join-box-title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Webinar Join Box', 'udesign'),
      'id' => 'webinar-join-box',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="join-box">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="join-box-title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Facilitator Membership Join Box', 'udesign'),
      'id' => 'facilitator-membership-join-box',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="join-box">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="join-box-title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Network Membership Join Box', 'udesign'),
      'id' => 'network-membership-join-box',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="join-box">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="join-box-title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Facilitator Membership Benefits1 ', 'udesign'),
      'id' => 'facilitator-membership-benefits1',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="section-header">Membership Benefits</div><div class="benefit">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits1-title">',
      'after_title' => '</h3>',
  ));
  register_sidebar(array(
      'name' => esc_html__('Facilitator Membership Benefits2 ', 'udesign'),
      'id' => 'facilitator-membership-benefits2',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="benefit middle">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits2-title">',
      'after_title' => '</h3>',
  ));
  register_sidebar(array(
      'name' => esc_html__('Facilitator Membership Benefits3 ', 'udesign'),
      'id' => 'facilitator-membership-benefits3',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="benefit">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits3-title">',
      'after_title' => '</h3>',
  ));

  register_sidebar(array(
      'name' => esc_html__('Network Membership Benefits1 ', 'udesign'),
      'id' => 'network-membership-benefits1',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="section-header">Membership Benefits</div><div class="benefit">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits1-title">',
      'after_title' => '</h3>',
  ));
  register_sidebar(array(
      'name' => esc_html__('Network Membership Benefits2 ', 'udesign'),
      'id' => 'network-membership-benefits2',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="benefit middle">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits2-title">',
      'after_title' => '</h3>',
  ));
  register_sidebar(array(
      'name' => esc_html__('Network Membership Benefits3 ', 'udesign'),
      'id' => 'network-membership-benefits3',
      'description' => esc_html__('A widget area, used as the orange box on the network pages.', 'udesign'),
      'before_widget' => '<div class="benefit">',
      'after_widget' => '</div>',
      'before_title' => '<h3 class="benefits3-title">',
      'after_title' => '</h3>',
  ));
    register_sidebar(array(
        'name' => 'EventsSidebar',
        'description' => esc_html__('A widget area, used as a sidebar for the Events template.', 'udesign'),
        'before_widget' => '<div id="%1$s" class="widget %2$s substitute_widget_class">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => 'NetworkSidebar',
        'description' => esc_html__('A widget area, used as a sidebar for the NetworkWithSidebar template.', 'udesign'),
        'before_widget' => '<div id="%1$s" class="widget %2$s substitute_widget_class">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => 'NetworkNewsSidebar',
        'description' => esc_html__('A widget area, used as a sidebar for the NetworkNews template.', 'udesign'),
        'before_widget' => '<div id="%1$s" class="widget %2$s substitute_widget_class">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));

}

/* Custom widgets... */
include ('widgets/upcomingEvents-widget.php');

?>
