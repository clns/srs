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
			<div class="page-banner">
        <div class="banner-text">Join the community.
          <div class="logo-text">
            <img src="<?php bloginfo('template_directory');?>-child/images/network/srs_logo.png" alt="">
            <span>Network<sup>TM</sup></span>
          </div>
        </div>
      </div>
      <div class="page-subtitle">Membership options made with you in mind</div>
      <div class="page-container">
        <div class="member-container">
          <div class="member-splash">
            <div class="label">Become a Network Member</div>
            <a href="network-membership"><button>Learn more</button></a>
          </div>
          <div class="description">
            <span>When you become a member</span> of the SRS Network, you become
            part of a vibrant community all over the world. Get access to 
            exclusive resources and training opportunities, helping you 
            create a culture of a fully funded ministry.
          </div>
          <div class="details">
            <div class="detail-heading">Membership Benefits</div>
            <ul>
              <li>50% off registration to the annual National Conference (a $175 value)</li>
              <li>Access to monthly webinars</li>
              <li>SRS Audit Survey</li>
              <li>Access to over 250 Catapult Series videos</li>
            </ul>
            <hr>
            <div class="detail-heading">Who Can Join</div>
            <ul>
              <li>Organization Leaders</li>
              <li>Coaches</li>
              <li>Support Raising Trainers</li>
            </ul>
          </div>
        </div>
        
        <div class="fac-container">
          <div class="fac-splash">
            <div class="label">Become a Facilitator</div>
            <a href="facilitator-membership"><button>Learn more</button></a>
          </div>
          <div class="description">
            <b>Ready to take the next step</b> and facilitate your own
            SRS Bootcamp? Become a certified Facilitator and get access 
            to exclusive resources and training opportunities.
          </div>
          <div class="details">
            <div class="detail-heading">Membership Benefits</div>
            All Network Member benefits plus
            <ul>
              <li>SRS Bootcamp Facilitator Training</li>
              <li><i>Bootcamp in a Box</i> setup materials</li>
              <li><i>Free</i> registration to Annual National Conference</li>
              <li>Discounted additional facilitator memberships</li>
            </ul>
            <hr>
            <div class="detail-heading">Required Qualifications</div>
            <ul>
              <li>Participate in a SRS Bootcamp (2015 or later)</li>
              <li>Participate in a SRS Facilitator Training</li>
              <li>Attend the annual SRS National Conference each year</li>
              <li>Live and minister on personal support</li>
            </ul>
          </div>
        </div>
        <div class="clear"></div>
      </div>
		</div>
	</div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();