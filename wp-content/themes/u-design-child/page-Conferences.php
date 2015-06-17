<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Conference Taxonomy
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$content_position = 'grid_24';
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$args = array(
    'post_type'  => 'conference',
    'franchise'  => $term->slug,
    'orderby'    => 'meta_value_num',
    'meta_key'   => 'start_date',
    'order'      => 'ASC',
    'meta_query' => array(
        array(
            'key'     => 'conferences_status',
            'value'   => array('Open', 'Closed'),
            'compare' => 'IN',
        ),
    ),
);
$the_query = new WP_Query($args);
?>

<div id="page-franchise-participant">
  <div class="container_24">

    <?php $first = true;
    if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
        if ( !$first ) {
            $display = 'style="display: none;"';
        } else {
            $display = '';
            $first = false;
        } ?>
        <div class="grid_24 bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
          <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
          <span class="image-helper"></span><img src="<?php echo $image[0]; ?>">
        </div>
    <?php endwhile; wp_reset_postdata(); // end of the loop. ?>

  </div>
  <div id="content-container" class="container_24" style="padding: 0;">
    <div id="main-content" class="<?php echo $content_position; ?>">
      <div class="grid_24 page-franchise-participant clearfix">

        <!-- ### Welcome Block ### -->
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
        <h1 style="font-size: 26px; line-height: 34px;"><?php echo get_post_meta($post->ID, 'welcome_title', true); ?></h1>
        <?php echo get_post_meta($post->ID, 'welcome_message', true); ?>
    </div>
<?php endwhile; wp_reset_postdata(); // end of the loop. ?>
          </div>
        </div>
        <!-- ### End Welcome Block ### -->

        <!-- ### Select Bootcamp Block ### -->
        <div class="grid_12 clearfix">
          <?php if ($the_query->post_count > 1): ?>
    <div id="select-container">
        <h2>Select your Conference.</h2>
        <select id="select-bootcamp" style="display: block; margin: 0 auto;" onChange="changeBootcampDisplay(this.value)">
            <?php if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
                $franchise_title = generate_franchise_title($post);
                ?>
                <option value="bc<?php echo get_the_ID()?>"><?php echo $franchise_title ?></option>
            <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
        </select>
    </div>
<?php endif; ?>
          <div class="bootcamp-header">
            <h1>Next Event</h1>
            <?php $first = true;
if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
    if ( !$first ) {
        $display = 'style="display: none;"';
    } else {
        $display = '';
        $first = false;
    }
    $franchise_title = generate_franchise_title($post);
    ?>
    <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
        <h2 class="location-header"><?php echo $franchise_title ?></h2>
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
        <!-- ### End Select Bootcamp Block ### -->

        <!-- ### Left Column### -->
        <div class="grid_12 expanding-container col-left">

          <!-- ### Dates Expanding Block ### -->
          <?php echo srs_expanding_block( "Dates", "date_and_times", $the_query, true); ?>
          <!-- ### End Dates Expanding Block ### -->

          <!-- ### Cost Expanding Block ### -->
          <?php echo srs_expanding_block( "Cost", "cost", $the_query ); ?>
          <!-- ### End Cost Expanding Block ### -->

          <!-- ### Location Expanding Block ### -->
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
                $loc_name = get_post_meta(get_the_ID(), 'location_name', true);
                $loc_addr = get_post_meta(get_the_ID(), 'location_address', true);
                $loc_addr2 = get_post_meta(get_the_ID(), 'location_address_line_2', true);
                $loc_city = get_post_meta(get_the_ID(), 'location_city', true);
                $loc_state = get_post_meta(get_the_ID(), 'location_state', true);
                $loc_zip = get_post_meta(get_the_ID(), 'location_zip_code', true);
                if (!empty($loc_name) || !empty($loc_addr) || !empty($loc_addr2) || !empty($loc_city) || !empty($loc_state) || !empty($loc_zip)) {
                  $loc_full = "<p>";
                  $loc_full .= $loc_name ? $loc_name . "<br/>" : "";
                  $loc_full .= $loc_addr ? $loc_addr . "<br/>" : "";
                  $loc_full .= $loc_addr2 ? $loc_addr2 . "<br/>" : "";
                  $loc_full .= $loc_city ? $loc_city . ", " : "";
                  $loc_full .= $loc_state ? $loc_state . " " : "";
                  $loc_full .= $loc_zip ? $loc_zip : "";
                  $loc_full .= "</p>";
                  echo $loc_full;
                }
                ?>
              </div>
            <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
          </div>
          <!-- ### End Location Expanding Block ### -->

          <!-- ### Lodging Expanding Block ### -->
          <?php echo srs_expanding_block( "Lodging", "lodging", $the_query ); ?>
          <!-- ### End Lodging Expanding Block ### -->

          <!-- ### Contact Expanding Block ### -->
          <?php echo srs_expanding_block( "Contact", "contact", $the_query ); ?>
          <!-- ### End Contact Expanding Block ### -->

          <!-- ### Other Details Expanding Block ### -->
          <?php echo srs_expanding_block( "Other Details", "other_details", $the_query ); ?>
          <!-- ### End Other Details Expanding Block ### -->

        </div>
        <!-- ### End Left Column ### -->

        <!-- ### Right Column ### -->
        <div class="grid_12 expanding-container col-right">

          <!-- ### Before Coming Expanding Block ### -->
          <?php echo srs_expanding_block( "Before Coming to Conference", "before_coming", $the_query, true, "<ol>",  "</ol>"); ?>
          <!-- ### End Before Coming Expanding Block ### -->

          <!-- ### About SRS Expanding Block ### -->
          <?php echo srs_expanding_block( "About SRS Conference", "about_srs_conference", $the_query ); ?>
          <!-- ### End About SRS Expanding Block ### -->

        </div>
        <!-- ### End Right Column ### -->

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
        }
        changeBootcampDisplay($("#select-bootcamp").val());
    </script>
<?php else :
  if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
    $bcid = "bc" . $post->ID;
  endwhile; wp_reset_postdata(); // end of the loop
?>
    <script>
      changeBootcampDisplay( <?php echo '"'.$bcid.'"'; ?> );
    </script>
<?php
endif;
get_footer();
