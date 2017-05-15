<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Events
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

// Begin main posts' loop stuff here
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$global_posts_query = new WP_Query(
    array(
        'post_type' => 'webinar',
        'post_status' => array(
            'publish',
            'private',
        ),
        'meta_query' => array(
            array(
                'key' => 'webinar_date',
                'compare' => '>=',
                'type' => 'DATE',
            )
        ),
        'meta_key' => 'webinar_date',
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'paged' => $paged,
        'posts_per_page' => 5
    )
);
?>

<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">

      <?php
      // Display content of page
      the_post();
      the_content();
      ?>


		<div id="network" class="network-page webinar">
        <div class="title">
            <h2>Events</h2>
        </div>

        <div class="events-grid">

            <div class="header">
                <div class="name">Event</div>
                <div class="date">Date</div>
                <div class="location">Location</div>
            </div>



            <?php


                if($global_posts_query->have_posts()) :
                    while($global_posts_query->have_posts()) : $global_posts_query->the_post(); ?>

                        <div class="row">
                            <div class="name">Bootcamp</div>
                            <div class="date">Jan 12</div>
                            <div class="location">Lubbock, TX</div>
                            <div class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor</div>
                            <div class="link">
                                <a href="">Learn More...</a>
                            </div>
                        </div>

                        <div class="clear"></div>
                    <?php endwhile;

                    // Pagination
                    if(function_exists('wp_pagenavi')) :

                        wp_pagenavi( array( 'query' => $global_posts_query ) );
                    else : ?>
                        <div class="navigation">
                            <div class="alignleft"><?php previous_posts_link() ?></div>
                            <div class="alignright"><?php next_posts_link() ?></div>
                        </div>
                    <?php
                    endif;

                    // Restore original Post Data
                    wp_reset_postdata(); ?>

                <?php else : ?>
                    <h2 class="center"><?php esc_html_e('Not Found', 'udesign'); ?></h2>
                    <p class="center"><?php esc_html_e("Sorry, but you are looking for something that isn't here.", 'udesign'); ?></p>
                    <?php		get_search_form();
                endif; ?>


        </div>
    </div>
  </div><!-- end main-content -->
</div><!-- end content-container -->
    <div class="clear"></div>


<?php
get_footer();?>