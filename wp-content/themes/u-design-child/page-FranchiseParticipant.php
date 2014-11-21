<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Franchise Participant
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$content_position = 'grid_24';
?>

    <div id="custom-bg" style="background-image: url('<?php echo $image[0]; ?>')">

    </div>

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
    <div id="content-container" class="container_24" style="padding: 0;">
        <div id="main-content" class="<?php echo $content_position; ?>">
            <div>
                <?php       udesign_main_content_top( is_front_page() ); ?>

                <?php	    if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
                    <?php               udesign_entry_before(); ?>
                    <div class="entry" style="padding-top: 0";>
                    <?php                   udesign_entry_top(); ?>
                    <?php			the_content(__('<p class="serif">Read the rest of this page &raquo;</p>', 'udesign')); ?>
                    <?php                   udesign_entry_bottom(); ?>
                </div>
                <?php               udesign_entry_after(); ?>
            </div>
            <?php		( $udesign_options['show_comments_on_pages'] == 'yes' ) ? comments_template() : '';
            endwhile; endif; ?>
            <div class="clear"></div>
            <?php	    udesign_main_content_bottom(); ?>
        </div><!-- end main-content-padding -->
    </div><!-- end main-content -->

    </div><!-- end content-container -->

    <div class="clear"></div>

<?php

get_footer();




