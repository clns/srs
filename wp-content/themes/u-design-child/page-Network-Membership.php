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
		<div id="network-page">
			<div class="page-banner">
        <div class="banner-text">Network Membership</div>
      </div>
      <div class="description-container">
        <div class="intro-text"><i><b>When you become a member of the SRS Network</b>, you will become part of a vibrant community
        all over the world. Members of the SRS Trainers Network will have access to exclusive resources and training opportunities,
        helping you create a culture of a fully funded ministry. Join today and discover all that membership has to offer!</i></div>
        <div class="bullet-benefits"><div class="topic-header">Your Membership Benefits</div>
          <ul>
            <li>50% off registration to the National Conference</li>
            <li>Access to monthly webinars</li>
            <li>Access to over 250 Catapult Series videos</li>
            <li>SRS Audit Survey</li>
          </ul>
        </div>
        <div class="bullet-join-req"><div class="topic-header">Who Can Join</div>
          <ul>
            <li>Organization Leaders</li>
            <li>Coaches</li>
            <li>Support Raising Trainers</li>
          </ul>
        </div>
        <div class="clear"></div>
      </div>
      <div class="join-box">
        Become a Member
        <hr>
        $300/yr<br>
        <button>Join</button>
      </div>
      <div class="clear"></div>
      <div class="benefits-container">
        <div class="benefits-border"></div>
        <div class="section-header">Membership Benefits</div>
        <div class="benefit">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/national_conference_benefit.jpg" alt="">
          <div class="topic-header">National Conference</div>
          Come and be equipped by workshops and plenary
          sessions on best practices to help recruits overcome
          the fear of supoprt raising, train new staff, coach veterans, and 
          instill a fully funded culture in your ministry.
        </div>
        <div class="benefit middle">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/catapult_benefit.jpg" alt="">
          <div class="topic-header">Catapult Series</div>
          Equip and refuel yourself through hundreds of 
          topical teaching videos on personal support raising 
          by Steve Shadrach, author of The God Ask and the
          SRS Bootcamp.
        </div>
        <div class="benefit">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/webinar_benefit.jpg" alt="">
          <div class="topic-header">Monthly Webinars</div>
          Develop yourself and your support raising coaches 
          through monthly one hour webinars led by veteran 
          trainers who teach on common support raising 
          challenges and answer your questions directly on the call.
        </div>
      </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();