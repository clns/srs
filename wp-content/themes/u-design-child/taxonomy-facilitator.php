<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Facilitator Taxomony
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$content_position = 'grid_24';
?>
<div id="page-franchise-facilitator">
    <div id="franchise-banner">
        <div class="container_24">
            <div class="grid_24 banner-container">
                <?php if (has_post_thumbnail( $post->ID ) ): ?>
                    <?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?>
                    <span class="image-helper"></span><img src="<?php echo $image[0]; ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="content-container" class="container_24">
        <div id="main-content" class="<?php echo $content_position; ?>">
            <div class="grid_24 clearfix">
                <div class="grid_12 left-container">
                    <h1>Bootcamp Facilitator Portal</h1>
                    <h2>Registration Reports</h2>
                    <div class="button">                   
						<?php
						// Get the registration_report_link custom field which was added to the faciliator taxonomy using PODs 
						$term =	$wp_query->queried_object; // get taxonomy of current page
						$args = array('hide_empty' => 0);                            
						$taxterms = get_terms('facilitator', $args);
						$podterms = pods('facilitator');
						foreach($taxterms as $taxterm) :
							if($taxterm->name == $term->name) {
								$podterms->fetch($taxterm->term_id);
								$reportlink = $podterms->get_field('registration_report_link');
								break;
							}
						endforeach;
						?>                            
                    
                        <p><a href="<?php echo $reportlink; ?>">View Reports</a></p>
                    </div>
                </div>
            </div><!-- end container_24 page-franchise-participant clearfix -->
      </div><!-- end main-content-padding -->
      <div class="clear"></div>
  </div><!-- end main-content -->
</div><!-- end page-franchise-participant -->
    </div><!-- end content-container -->

    <div class="clear"></div>

<?php

get_footer();




