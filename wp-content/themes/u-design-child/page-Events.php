<?php
/**
 * @package WordPress
 * @subpackage U-Design-Child
 */
/**
 * Template Name: Events
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

$args = array(
    'post_type' => array('conference', 'bootcamp', 'webinar'),
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'posts_per_page' => 5,
    'paged' => $paged,
    'meta_query' => array(
        array(
            'relation' => 'AND',
            array(
                'relation' => 'OR',
                array('key' => 'start_date',),
                array('key' => 'webinar_date',),
            ),
            array(
                'relation' => 'OR',
                array(
                    'key' => 'conferences_status',
                    'value' => array('Open', 'Closed'),
                    'compare' => 'IN',
                ),
                array(
                    'key' => 'bootcamp_status',
                    'value' => array('Open', 'Closed'),
                    'compare' => 'IN',
                ),
                array(
                    'key' => 'webinar_date',
                    'value' => current_time('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        ),
    ),
    'tax_query' => array(
        'relation' => 'OR',
        array( // Apply "srs" franchise taxonomy condition (applies to bootcamp and conference)
            'taxonomy' => 'franchise',
            'field'    => 'slug',
            'terms' => array('srs', '', null),
        ),
        array( // This condition is needed in case it's a webinar, which doesn't have a franchise
            'taxonomy' => 'franchise',
            'field'    => 'id',
            'operator' => 'NOT EXISTS',
        ),
    ),
);
$the_query = new WP_Query($args);
?>

    <div class="page-content">
        <div class="left-side">
            <div class="content-group">

                <div class="title">
                    <h2>Events</h2>
                </div>

                <div class="events-grid">
                    <div class="header">
                        <div class="name"><?php _e('Event', 'u-design') ?></div>
                        <div class="date"><?php _e('Date', 'u-design') ?></div>
                        <div class="location"><?php _e('Location', 'u-design') ?></div>
                    </div>
                    <?php if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <?php $is_webinar = 'webinar' == get_post_type(); ?>
                        <div class="row" id="event-<?php the_ID(); ?>">
                            <div class="name"><?php echo ucfirst(get_post_type()); ?></div>
                            <div class="date">
                                <?php if(!$is_webinar) {
                                    $start_date_raw = strtotime(get_post_meta(get_the_ID(), 'start_date',true));
                                    $end_date_raw = strtotime(get_post_meta(get_the_ID(), 'end_date',true));
                                    $start_date = date("F j", $start_date_raw);
                                    if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                                        $end_date = date("F j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                                    } else {
                                        $end_date = date("j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                                    }
                                    echo $start_date . '-' . $end_date;
                                } else {
                                    echo date("F j, Y", strtotime(get_post_meta(get_the_ID(), 'webinar_date', true)));
                                }?>
                            </div>
                            <div class="location">
                                <?php
                                    if(!$is_webinar) {
                                        echo get_post_meta(get_the_ID(), 'location_city', true) . ', ' . get_post_meta(get_the_ID(), 'location_state', true);
                                    } else {
                                        echo get_post_meta(get_the_ID(), 'webinar_time', true);
                                    }
                                ?>
                            </div>
                            <div class="description">
                                <?php if(!$is_webinar) {
                                    echo wp_trim_words(get_post_meta(get_the_ID(), 'welcome_message', true), 10);
                                } else {
                                    echo wp_trim_words(get_post_meta(get_the_ID(), 'video_description', true), 10);
                                } ?>
                            </div>
                            <div class="link">
                                <?php $learn_more_url = get_permalink();
                                if ("bootcamp" == get_post_type()) {

                                    $terms = get_the_terms(get_the_ID(), 'franchise');
                                    if ( $terms && !is_wp_error( $terms ) ) {
                                        foreach ( $terms as $term ) {
                                            $learn_more_url = get_term_link( $term->slug, 'franchise') . "?bootcampID=bc" . get_the_ID();
                                        }
                                    }
                                    
                                    $learn_more_url = get_term_link('srs', 'franchise') . "?bootcampID=bc" . get_the_ID();
                                } ?>
                                <a href="<?php echo $learn_more_url; ?>"><?php _e('Learn More...', 'u-design') ?></a>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
                </div><!-- end events-grid -->

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
            <?php if (sidebar_exist('EventsSidebar')) {
                get_sidebar('EventsSidebar');
            } ?>
        </div>
        
    </div><!-- end page-content -->

<?php get_footer(); ?>