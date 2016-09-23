<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: FullWidth
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
  <div id="content-container" class="full-width">
    <div id="main-content" class="full-width">
      <div class="full-width-content">

        <?php
            the_post();
            the_content();
        ?>

      </div>
    </div><!-- end main-content -->
  </div><!-- end content-container -->

  <div class="clear"></div>
<?php

//get_footer();