<?php
/**
 * @package WordPress
 * @subpackage U-Design-Child
 */
/**
 * Template Name: Global Team
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$wp_user_query = new WP_User_Query(array(
    'meta_key' => 'srs_global_staff',
    'meta_value' => '1',
));
// Get the results
$users = $wp_user_query->get_results();
?>

    <div class="page-content">
        <div class="left-side">
            <div class="content-group">

                <div class="title">
                    <h2><?php _e('SRS Global Staff', 'u-design'); ?></h2>
                </div>

                <div class="global-team-grid">
                    <?php if (!empty($users)) foreach ($users as $user): ?>
                        <div class="row">
                            <div class="image">
                                <?php echo get_wp_user_avatar($user->data->ID, "150"); ?>
                            </div>
                            <h2><?php echo $user->data->display_name; ?></h2>
                            <h6><?php
                                $job_title = get_user_meta($user->data->ID, 'job_title');
                                if(!empty($job_title[0])) {
                                    echo current($job_title);
                                }
                                $country = get_user_meta($user->data->ID, 'country');
                                if(!empty($country[0])) {
                                    echo ' | ' . current($country);
                                } 
                                ?></h6>
                            <p><?php
                                $bio = get_user_meta($user->data->ID, 'description');
                                if(!empty($bio[0])) {
                                    echo current($bio);
                                }
                                ?></p>
                        </div>
                    <?php endforeach; ?>
                </div><!-- end global-team-grid -->

            </div><!-- end content-group -->
        </div><!-- end left-side -->

        <div class="right-side">
            <?php if (sidebar_exist('GlobalTeamSidebar')) {
                get_sidebar('GlobalTeamSidebar');
            } ?>
        </div>

        <div class="clear"></div>
        
    </div><!-- end page-content -->

<?php get_footer(); ?>