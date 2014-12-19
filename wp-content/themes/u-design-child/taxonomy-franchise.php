<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Bootcamp Taxonomy
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$content_position = 'grid_24';
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$args = array(
	'post_type'  => 'bootcamp',
	'franchise'  => $term->slug,
  'orderby'    => 'meta_value_num',
  'meta_key'   => 'start_date',
  'order'      => 'ASC',
  'meta_query' => array(
    array(
      'key'     => 'bootcamp_status',
      'value'   => array('Open', 'Closed'),
      'compare' => 'IN',
    ),
  ),
);
$the_query = new WP_Query($args);
$is_private = get_queried_object()->term_id != 12 ? true : false;
?>

<div id="page-franchise-participant">
<?php if ( $is_private ): ?>
  <div id="franchise-banner">
    <div class="container_24">
      <div class="grid_24 banner-container">
        <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
        <span class="image-helper"></span><img src="<?php echo $image[0]; ?>">
      </div>
    </div>
  </div>
<?php endif; ?>
  <div id="content-container" class="container_24" style="padding: 0;">
    <div id="main-content" class="<?php echo $content_position; ?>">
        <div class="grid_24 page-franchise-participant clearfix">
          <div class="grid_12">
            <div style="padding-top: 80px;">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?> 
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <h1 style="font-size: 26px; line-height: 34px;">Welcome to <?php echo get_post_meta($post->ID, 'ministry_name', true) ?>'s registration site for support raising training.</h1>
                  <?php echo get_post_meta(get_the_ID(), 'welcome_message', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
          </div>
          <div class="grid_12 clearfix">
            <?php if ($the_query->post_count > 1): ?>
              <div id="select-container">    
                <h2>Select your bootcamp.</h2>
                <select id="select-bootcamp" style="display: block; margin: 0 auto;" onChange="changeBootcampDisplay(this.value)">
                  <?php if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
                    $start_date_raw = strtotime(get_post_meta($post->ID, 'start_date',true));
                    $end_date_raw = strtotime(get_post_meta($post->ID, 'end_date',true));
                    $start_date = date("F j", $start_date_raw);
                    if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                      $end_date = date("F j", strtotime(get_post_meta($post->ID, 'end_date',true)));
                    } else {
                      $end_date = date("j", strtotime(get_post_meta($post->ID, 'end_date',true)));
                    } ?>  
                    <option value="bc<?php echo get_the_ID()?>"><?php echo get_post_meta($post->ID, 'location_city', true) . ', ' . get_post_meta($post->ID, 'location_state', true) . ' ' . $start_date . '-' . $end_date ?></option> 
                  <?php endwhile; wp_reset_postdata(); // end of the loop. ?> 
                </select>
              </div>
            <?php endif; ?>
            <div style="text-align:center; padding-top: 150px">
              <h1 style="font-weight: 700;">SRS Bootcamp</h1>
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                }
                $start_date_raw = strtotime(get_post_meta($post->ID, 'start_date',true));
                $end_date_raw = strtotime(get_post_meta($post->ID, 'end_date',true));
                $start_date = date("F j", $start_date_raw);
                if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                  $end_date = date("F j", strtotime(get_post_meta($post->ID, 'end_date',true)));
                } else {
                  $end_date = date("j", strtotime(get_post_meta($post->ID, 'end_date',true)));
                } 
                ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <h2 class="location-header"><?php echo get_post_meta($post->ID, 'location_city', true) . ', ' . get_post_meta($post->ID, 'location_state', true) . ' ' . $start_date . '-' . $end_date ?></h2>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                }
                $bootcamp_status = get_post_meta($post->ID, 'bootcamp_status', true);
                if ( $bootcamp_status == 'Closed') {
                  $closed_style = 'style="text-decoration: line-through"';
                  $closed_text = ' CLOSED';
                  $include_link = false;
                } else {
                  $closed_style = '';
                  $closed_text = '';
                  $include_link = true;
                } 
                ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock button-container" <?php echo $display ?>>
                  <p class="button-text"><?php if ($include_link) { echo '<a href="' . get_post_meta($post->ID, 'registration_link', true) . '">';} echo '<span ' . $closed_style . '>Register</span>'; if ($include_link) { echo '</a>'; } echo $closed_text; ?></p>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
          </div>
          <div class="clear"></div>
          <div class="grid_12 expanding-container col-left">
            <h3 class="expanding-heading">Dates <span class="expanding-icon expanding-icon-minus"></span></h3>
            <div class="expanding-content first-expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php echo get_post_meta(get_the_ID(), 'date_and_times', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
            <h3 class="expanding-heading">Cost <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php echo get_post_meta(get_the_ID(), 'cost', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
            <h3 class="expanding-heading">Location <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php
                    echo '<p>' .
                    get_post_meta(get_the_ID(), 'location_name', true) . '<br/>' .
                    get_post_meta(get_the_ID(), 'location_address', true) . '<br/>' .
                    ((get_post_meta(get_the_ID(), 'location_address_line_2', true))? get_post_meta(get_the_ID(), 'location_address_line_2', true) . '<br/>':'') .
                    get_post_meta(get_the_ID(), 'location_city', true) . ', ' .
                    get_post_meta(get_the_ID(), 'location_state', true) . ' ' .
                    get_post_meta(get_the_ID(), 'location_zip_code', true) . '</p>';
                  ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
            <h3 class="expanding-heading">Lodging <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php echo get_post_meta(get_the_ID(), 'lodging', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div> 
            <h3 class="expanding-heading">Contact <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php echo get_post_meta(get_the_ID(), 'contact', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
            <h3 class="expanding-heading">Other Details <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <?php $first = true; 
                if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); 
                if ( !$first ) {
                  $display = 'style="display: none;"';
                } else {
                  $display = '';
                  $first = false;
                } ?>
                <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
                  <?php echo get_post_meta(get_the_ID(), 'other_details', true); ?>
                </div>
              <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div>
          </div>
          <div class="grid_12 expanding-container col-right">
            <h3 class="expanding-heading">Before Coming to Bootcamp<span class="expanding-icon expanding-icon-minus"></span></h3>
            <div class="expanding-content first-expanding-content">
              <?php 
                if ($is_private) { 
                  $prep_packet_text = 'After registering, download and complete the Preparation Packet (<a href="' . home_url() . '/docs/SRSBootcamp-Prep-Checklist-Network.pdf" target="_blank">Preview Checklist</a> | <a href="' . home_url() . '/preppacketnetwork/" target="_blank">Download Prep Packet</a>)'; 
                } else { 
                  $prep_packet_text = 'After registering, download and complete the Preparation Packet (<a href="' . home_url() . '/docs/SRSBootcamp-Prep-Checklist.pdf" target="_blank">preview the Prep Checklist</a>)'; 
                } 
              ?>
              <ol>
                <li>Register and pay full registration (if you complete the Prep, you will receive the rebate after the Bootcamp)</li>
                <li><?php echo $prep_packet_text ?></li>
                <li>Receive <span style="font-style: italic;">The God Ask</span> and <span style="font-style: italic;">Viewpoints</span>, which will be mailed to you.</li>
              </ol>
            </div>
            <h3 class="expanding-heading">About SRS Bootcamp <span class="expanding-icon expanding-icon-plus"></span></h3>
            <div class="expanding-content">
              <p>Since 2001, SRS has trained nearly 10,000 mission workers from over 500 ministries to be spiritually healthy, vision-driven, and fully funded Great Commission workers. SRS staff members raise their own support to provide excellent training resources, equip you to overcome obstacles in support raising, and help you thrive in ministry. </p>
              <p>SRS Bootcamp is a completely interactive and engaging workshop. It is essential that you complete all the Bootcamp preparation before you arrive (24-40 hours), because once you get to Bootcamp, you will synthesize and practice with others what you learned during your preparation work. At the Bootcamp, you will: </p>
              <ul>
                <li>Gain confidence in communicating the biblical foundation for living on support, asking others to invest, and understanding the "God Ask" </li>
                <li>Learn best practices and gain confidence in sharing your presentation</li>
                <li>Rehearse with your peers and make real calls for appointments</li>
                <li>Experience the value of meeting with people and asking for support face to face</li>
                <li>Discover how to cultivate lasting relationships with your supporters</li>
              </ul>
            </div>
          </div><!-- end grid_12 expanding-container col-right -->
        </div><!-- end container_24 page-franchise-participant clearfix -->
      </div><!-- end page-franchise-participant -->
      <div class="clear"></div>
      <?php	    udesign_main_content_bottom(); ?>
    </div><!-- end main-content-padding -->
  </div><!-- end main-content -->
</div><!-- end content-container -->
<div class="clear"></div>
<?php if ($the_query->post_count > 1) : ?>
  <script>
    var bootcampID = getParameterByName('bootcampID');
    if (bootcampID) {
      setDropDownLink(bootcampID);
      changeBootcampDisplay(bootcampID);
    }
  </script>
<?php
endif;
get_footer();
