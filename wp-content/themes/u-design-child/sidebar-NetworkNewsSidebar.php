<?php
/**
 * @package WordPress
 * @subpackage U-Design-Child
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div id="sidebar">
    <div id="sidebarSubnav">
        <?php udesign_sidebar_top(); ?>

        <?php // Widgetized sidebar
        if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('NetworkNewsSidebar')) : ?>

            <div class="custom-formatting">
                <h3><?php esc_html_e('About This Sidebar', 'udesign'); ?></h3>
                <ul>
                    <?php _e("To edit this sidebar, go to admin backend's <strong><em>Appearance -> Widgets</em></strong> and place widgets into the <strong><em>NetworkNewsSidebar</em></strong> Widget Area", 'udesign'); ?>
                </ul>
            </div>

        <?php endif; ?>

        <?php udesign_sidebar_bottom(); ?>
    </div><!-- end sidebarSubnav -->
</div><!-- end sidebar -->


