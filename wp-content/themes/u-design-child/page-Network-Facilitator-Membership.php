<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Network Facilitator Membership
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">
		<div id="network" class="network-page fac-membership">
			<div class="page-banner">
        <div class="banner-text">Facilitator Membership</div>
      </div>
      <div class="description-container">
        <?php
          // Display content of page
          the_post();
          the_content();
        ?>

        <div class="clear"></div>
      </div>

          <?php if(is_active_sidebar('facilitator-membership-join-box')): { dynamic_sidebar( 'facilitator-membership-join-box' ); }else : endif;?>

      <div class="clear"></div>
          <div class="benefits-container"><div class="benefits-border"></div>

          <?php if(is_active_sidebar('facilitator-membership-benefits1')): {dynamic_sidebar( 'facilitator-membership-benefits1' ); }else : endif;?>
          <?php if(is_active_sidebar('facilitator-membership-benefits2')): {dynamic_sidebar( 'facilitator-membership-benefits2' ); }else : endif;?>
          <?php if(is_active_sidebar('facilitator-membership-benefits3')): {dynamic_sidebar( 'facilitator-membership-benefits3' ); }else : endif;?>

          </div>
      </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();