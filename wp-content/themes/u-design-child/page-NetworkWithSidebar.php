<?php
/**
 * @package WordPress
 * @subpackage U-Design-Child
 */
/**
 * Template Name: Network With Sidebar
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly

get_header();

global $udesign_options;

$network_news = new WP_Query(array(
    'post_type' => 'network_news',
    'posts_per_page' => 1
));

$webinars = new WP_Query(array(
    'post_type' => 'webinar',
    'posts_per_page' => 3
));

$catapult_topics = get_terms( array(
    'taxonomy' => 'catapulttopic',
    'hide_empty' => true,
    'parent' => 0
) );

$blog_posts = new WP_Query(array(
    'posts_per_page' => 2
));
?>
    <div class="page-content">
        
        <div class="left-side">
            <div class="hero">
                <?php
                # Display page content
                the_post();
                the_content();
                ?>
            </div>
            
            <!-- NETWORK NEWS -->
            <div class="content-group border-bottom">
                <div class="title">
                    <h2><?php _e('Network News', 'u-design') ?></h2><span></span><a href="/network-news"><?php _e('Archive', 'u-design') ?></a>
                </div>
                <?php if ( $network_news->have_posts() ) while ( $network_news->have_posts() ) : $network_news->the_post(); ?>
                <div class="network-news-summary">
                    <?php
                    $thumbnail = false;
                    if (has_post_thumbnail(get_the_ID())) {
                        $thumbnail = '<img src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" />';
                    } else {
                        // Post doesn't have a thumbnail, try attachments
                        $images = get_posts(array(
                            'post_type' => 'attachment',
                            'post_mime_type' => 'image',
                            'post_parent' => get_the_ID(),
                            'posts_per_page' => 1,
                        ));
                        if ($images) {
                            $img_data = wp_get_attachment_image_src($images[0]->ID, 'post-thumbnail');
                            $thumbnail = '<img src="' . $img_data[0] . '" alt="' . $images[0]->post_title . '" />';
                        }
                    }
                    if($thumbnail) { ?>
                        <a href="<?php the_permalink(); ?>" class="image"><?php echo $thumbnail; ?></a>
                    <?php } ?>
                    
                    <div class="body">
                        <h5><?php echo the_title(); ?></h5>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 32); ?></p>
                        <a href="<?php the_permalink(); ?>"><?php echo $udesign_options['blog_button_text']; ?></a>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div><!-- end content-group for network news-->

            <!-- MONTHLY WEBINARS -->
            <div class="content-group border-bottom">
                <div class="title">
                    <h2><?php _e('Monthly Webinars', 'u-design') ?></h2><span></span><a href="/webinars"><?php _e('Archive', 'u-design') ?></a>
                </div>
                <div class="monthly-webinar-summary-wrapper">
                <?php if ( $webinars->have_posts() ) while ( $webinars->have_posts() ) : $webinars->the_post(); ?>
                    <div class="monthly-webinar-summary">
                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>" class="image">
                            <?php echo get_wp_user_avatar("", "150"); ?>
                        </a>
                        <h5>
                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a>
                        </h5>
                        <h6>
                            <?php
                            $webinar_date = get_post_meta(get_the_ID(), "webinar_date", true);
                            if (!empty($webinar_date)) {
                                $date = new DateTime($webinar_date);
                                $webinar_date = $date->format('M j, Y');
                                echo $webinar_date;
                            }
                            ?>
                        </h6>
                        <p><?php the_title(); ?></p>
                    </div>
                <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
                </div>
            </div><!-- end content-group for monthly webinars -->

            <!-- CATAPULT VIDEOS -->
            <div class="content-group border-bottom">
                <div class="title">
                    <h2><?php _e('Catapult Videos', 'u-design') ?></h2><span></span><a href="/catapult-series"><?php _e('Archive', 'u-design') ?></a>
                </div>
                <div class="catapult-video-summary-wrapper">
                    <?php $displayed_posts = array(); ?>
                    <?php foreach ($catapult_topics as $topic) {
                        $catapult_videos = new WP_Query(array(
                            'post_type' => 'catapultvideo',
                            'posts_per_page' => 1,
                            'meta_key' => 'network_dashboard',
                            'meta_value' => '1',
                            'tax_query' => array(
                                array (
                                    'taxonomy' => 'catapulttopic',
                                    'field' => 'slug',
                                    'terms' => $topic->slug
                                )
                            ),
                            'post__not_in' => $displayed_posts,
                        )) ?>
                        <?php if ( $catapult_videos->have_posts() ) while ( $catapult_videos->have_posts() ) : $catapult_videos->the_post(); ?>
                            <?php $displayed_posts[] = get_the_ID(); ?>
                            <div class="catapult-video-summary">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"></a>
                                <p><?php echo $topic->name; ?></p>
                                <h5>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h5>
                            </div>
                            
                        <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
                    <?php } ?>
                </div>
            </div><!-- end content-group for catapult videos -->
            
            <!-- SRS BLOG -->
            <div class="content-group">
                <div class="title">
                    <h2><?php _e('SRS Blog', 'u-design') ?></h2></a>
                </div>
                <?php if ( $blog_posts->have_posts() ) while ( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>
                    <div class="srs-blog-summary">
                        <?php
                        $thumbnail = false;
                        if (has_post_thumbnail(get_the_ID())) {
                            $thumbnail = '<img src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" />';
                        } else {
                            // Post doesn't have a thumbnail, try attachments
                            $images = get_posts(array(
                                'post_type' => 'attachment',
                                'post_mime_type' => 'image',
                                'post_parent' => get_the_ID(),
                                'posts_per_page' => 1,
                            ));
                            if ($images) {
                                $img_data = wp_get_attachment_image_src($images[0]->ID, 'post-thumbnail');
                                $thumbnail = '<img src="' . $img_data[0] . '" alt="' . $images[0]->post_title . '" />';
                            }
                        }
                        if($thumbnail) { ?>
                            <a href="<?php the_permalink(); ?>" class="image"><?php echo $thumbnail; ?></a>
                        <?php } ?>

                        <div class="body">
                            <h5><?php the_title(); ?></h5>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 32); ?></p>
                            <a href="<?php the_permalink(); ?>"><?php echo $udesign_options['blog_button_text']; ?></a>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
            </div><!-- end content-group for srs blog -->
            
        </div><!-- end left-side -->

        <div class="right-side">
            <?php if (sidebar_exist('NetworkSidebar')) {
                get_sidebar('NetworkSidebar');
            } ?>
        </div>
        
    </div><!-- end page-content -->
<?php

get_footer();