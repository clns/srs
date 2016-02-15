<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Network
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<div id="content-container" class="full-width">
  <div id="main-content" class="full-width">
    <div id="network">
      <div class="page-banner"></div>

      <?php
      # This section generates the blurb under the title
      the_post();
      the_content();
      ?>

    </div>
  </div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();