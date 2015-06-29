<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Webinar
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<div id="content-container" class="full-width">
	<div id="main-content" class="full-width">
		<div id="network-page" class="webinar">
			<div class="page-banner">
        <div class="banner-text">Monthly Webinars</div>
      </div>
      <div class="description-container">
        <div class="intro-text"><i><b>Develop yourself and grow your staff</b> through one-hour, live webinars led by veteran trainers 
        and coaches every month. Receive training beyond Bootcamp to help coach your staff through 
        obstacles in their personal support raising</i></div>
        <div class="bullet-columns"><div class="topic-header">Monthly Webinar Benefits</div>
          <ul>
            <div class="bullet-left">
              <li>Receive training beyond Bootcamp to help coach your staff</li>
              <li>Get questions answered in real time</li>
            </div>
            <div class="bullet-right">
              <li>Receive updates and news about new SRS Network benefits</li>
              <li>Access to archived webinars</li>
            </div>
          </ul>
        </div>
        <div class="clear"></div>
      </div>
      <div class="join-box">
        Plug Into the Community
        <button>Join</button>
      </div>
      <div class="clear"></div>
      <div class="event-container">
        <div class="event">
          <div class="event-border"></div>
          <div class="leader-bio">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/scott_morton.jpg" alt="">
            <span class="leader-name">Scott Morton</span><br>
            Funding Coach,<br>
            <i>The Navigators</i>
          </div>
          <div class="event-header">
            False Theologies in Raising Personal Support
          </div>
          <div class="event-time">
            <div class="date">June 30</div>
            1 p.m. CST
          </div>
        </div>
        <div class="clear"></div>
        <div class="event">
          <div class="event-border"></div>
          <div class="leader-bio">
          <img src="<?php bloginfo('template_directory');?>-child/images/network/betty_barnett.jpg" alt="">
            <span class="leader-name">Betty Barnett</span><br>
            <i>Youth With A Mission</i>
          </div>
          <div class="event-header">
            Finishing Well: Navigating Life's Transitions with Grief, Grace and Gratitude
          </div>
          <div class="event-time">
            <div class="date">July 28</div>
            1 p.m. CST
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