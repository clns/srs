<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Catapult
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">
		<div id="network" class="network-page catapult">
			<div class="page-banner">
        <div class="banner-text">Catapult Series</div>
      </div>
      <div class="description-container">
        <?php
          // Display content of page
          the_post();
          the_content();
        ?>
        <div class="clear"></div>
      </div>

          <?php if(is_active_sidebar('catapult-join-box')): { dynamic_sidebar( 'catapult-join-box' ); }else : endif;?>

      <div class="clear"></div>
      <div class="previews-container">
        <div class="previews-border"></div>
        <div class="section-header">Catapult Series Previews</div>
        <div class="benefit">
          <a href="https://vimeo.com/131104564" rel="prettyPhoto">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/biblical_basis.jpg" alt="">
            <div>Biblical Basis for Personal Support Raising: Support raising in the Bible</div>
          </a>
        </div>
        <div class="benefit middle">
          <a href="https://vimeo.com/131451143" rel="prettyPhoto">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/right_perspective.jpg" alt="">
            <div>Gaining the Right Perspective on Support Raising: How to view money</div>
          </a>
        </div>
        <div class="benefit">
          <a href="https://vimeo.com/131678534" rel="prettyPhoto">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/preparing_planning.jpg" alt="">
            <div>Preparing and Planning to Raise Support: Raising more than 100%</div>
          </a>
        </div>
        <div class="benefit">
          <a href="https://vimeo.com/131884254" rel="prettyPhoto">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/phone_call.jpg" alt="">
            <div>The Phone Call, Appointment, and Follow Up: How I raised my full support</div>
          </a>
        </div>
        <div class="benefit middle">
          <a href="https://vimeo.com/131566017" rel="prettyPhoto">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/maintenance_cultivation.jpg" alt="">
            <div>Maintenance and Cultivation of Your Support Team: Maintaining and cultivating your team</div>
          </a>
        </div>
      </div>
    </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->
<div class="clear"></div>

<?php

get_footer();