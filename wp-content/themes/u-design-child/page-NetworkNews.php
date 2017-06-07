<?php
/**
 * @package WordPress
 * @subpackage U-Design-Child
 */
/**
 * Template Name: Network News
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$the_query = new WP_Query(array(
    'post_type' => 'network_news',
    'posts_per_page' => 5,
    'paged' => $paged,
));
?>

    <div class="page-content">
        <div class="left-side">
            <div class="content-group">

                <div class="title" id="network-news">
                    <h2><?php _e('Network Member News', 'u-design'); ?></h2>
                </div>

                <div class="network-news-grid">
                    <?php if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <div class="row">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                            <div class="details">
                                <?php
                                printf(__('By %1$s on %2$s ', 'udesign'), udesign_get_the_author_page_link(), get_the_date());
                                ?>
                                <?php
                                $terms = wp_get_post_terms(get_the_ID(), 'network_news_category');
                                if(!empty($terms)) {
                                    $categories = array();
                                    foreach ($terms as $term) {
                                        $categories[] = '<a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a>';
                                    }
                                    echo ' / ' . implode(', ', $categories);
                                }
                                ?>
                                / <a href="<?php echo comments_link(); ?>"><?php echo comments_number(); ?></a>
                            </div>
                            
                            <p><?php echo wp_trim_words(get_the_excerpt(), 50); ?></p>

                            <a href="<?php the_permalink(); ?>"><?php echo $udesign_options['blog_button_text']; ?></a>

                            <a href="#network-news"><?php _e('Back to Top', 'u-design'); ?></a>

                            <div class="clear"></div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
                </div><!-- end network-news-grid -->

                <div class="clear"></div>

                <div class="paging-toolbar">
                    <?php // Pagination
                    if (function_exists('wp_pagenavi')) :
                        wp_pagenavi(array('query' => $the_query));
                    else : ?>
                        <div class="navigation">
                            <div class="alignleft"><?php previous_posts_link() ?></div>
                            <div class="alignright"><?php next_posts_link() ?></div>
                        </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>

            </div><!-- end content-group -->
        </div><!-- end left-side -->

        <div class="right-side">
            <?php if (sidebar_exist('NetworkNewsSidebar')) {
                get_sidebar('NetworkNewsSidebar');
            } ?>
        </div>
        
    </div><!-- end page-content -->

<?php get_footer(); ?>