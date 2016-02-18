<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Network Membership
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">
		<div id="network" class="network-page">
			<div class="page-banner">
        <div class="banner-text">Network Membership</div>
      </div>
      <div class="description-container">
        <?php
          the_post();
          the_content();
        ?>

        <div class="clear"></div>
      </div>
          <?php if(is_active_sidebar('network-membership-join-box')): { dynamic_sidebar( 'network-membership-join-box' ); }else : endif;?>

      <div class="clear"></div>
      <div class="benefits-container">
        <div class="benefits-border"></div>
        <div class="section-header">Membership Benefits</div>
        <div class="benefit">
          <a href="leaders-conference/">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/national_conference_benefit.jpg" alt="">
          <div class="detail-heading">Support Raising Leader's Conference</div></a>
          Come and be equipped by workshops and plenary
          sessions on best practices to help recruits overcome
          the fear of support raising, train new staff, coach veterans, and 
          instill a fully funded culture in your ministry. <a href="leaders-conference/">Read More.</a>
        </div>
        <div class="benefit middle">
          <a href="catapult">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/catapult_benefit.jpg" alt="">
          <div class="detail-heading">Catapult Series</div></a>
          Equip and refuel yourself through hundreds of 
          topical teaching videos on personal support raising 
          by Steve Shadrach, author of The God Ask and founder of Support Raising Solutions. <a href="catapult">Read More.</a>
        </div>
        <div class="benefit">
          <a href="webinar">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/webinar_benefit.jpg" alt="">
          <div class="detail-heading">Monthly Webinars</div></a>
          Develop yourself and your support raising coaches 
          through monthly one-hour webinars led by veteran 
          trainers who teach on common support raising 
          challenges and answer your questions directly on the call. <a href="webinar">Read More.</a>
        </div>
      </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();