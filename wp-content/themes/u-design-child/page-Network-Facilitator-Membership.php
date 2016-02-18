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
        <p><strong>Are you ready to take the next step</strong> and facilitate your own Bootcamp? With a Facilitator
        Membership, you can equip your staff and help create a culture of a fully funded ministry. As a 
        certified Facilitator, you will have access to exclusive resources and training opportunities, like 
        Bootcamp in a Box and free registration to the <a href="leaders-conference/">Support Raising Leader's Conference.</a></p>
        <div class="bullet-benefits"><div class="detail-heading">Your Membership Benefits</div>
          <ul>
            <li><i>Free</i> SRS Bootcamp <a href="facilitatorstraining">Facilitator Training</a></li>
            <li><i>Bootcamp in a Box</i> materials and curriculum</li>
            <li><i>Free</i> registration to <a href="leaders-conference/">Support Raising Leader's Conference</a></li>
            <li>Discounted additional facilitator memberships</li>
            <li>Access to all Network Member benefits</li>
            <li>SRS Bootcamp registration is only $125 for your staff, a savings of $264 from our public Bootcamps</li>
          </ul>
        </div>
        <div class="bullet-join-req"><div class="detail-heading">Required Qualifications</div>
          <ul>
            <li>Participate in a public SRS Bootcamp (2014 or later)</li>
            <li>Participate in an SRS <a href="facilitatorstraining">Facilitator Training</a></li>
            <li>Attend annual <a href="leaders-conference/">Support Raising Leader's Conference</a></li>
            <li>Live and minister on personal support</li>
          </ul>
        </div>
        <div class="clear"></div>
      </div>
      <div class="join-box">
        Become a Certified Facilitator
        <hr>
        $900/yr<br>
        <a href="facilitators"><button>Preview</button></a>
      </div>
      <div class="clear"></div>
      <div class="benefits-container">
        <div class="benefits-border"></div>
        <div class="section-header">Membership Benefits</div>
        <div class="benefit">
          <a href="facilitatorstraining">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/facilitator_training_benefit.jpg" alt="">
          <div class="detail-heading">Facilitator Training</div></a>
          Through our practical hands-on training, you will 
          develop your skills as a facilitator and learn all the 
          elements of hosting and leading an SRS Bootcamp. 
          You will be certified in training your staff to reach 
          full support and stay fully funded. <a href="facilitatorstraining">Read More.</a>
        </div>
        <div class="benefit middle">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/bootcamp_benefit.jpg" alt="">
          <div class="detail-heading">Bootcamp in a Box</div>
          The new SRS Bootcamp is designed to help you 
          succeed in training and equipping your staff. Not 
          only do we provide everything you and your staff 
          need for Bootcamp, we also walk you through the process.
        </div>
        <div class="benefit">
          <a href="leaders-conference/">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/national_conference_benefit.jpg" alt="">
          <div class="detail-heading">Support Raising Leader's Conference</div></a>
          Come be encouraged and equipped by workshops 
          and plenary sessions on best practices how to help 
          recruits overcome the fear of support raising, train 
          new staff, coach veterans, and establish a fully funded 
          culture in your ministry.<a href="leaders-conference/">Read More.</a>
        </div>
      </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->

<div class="clear"></div>

<?php

get_footer();