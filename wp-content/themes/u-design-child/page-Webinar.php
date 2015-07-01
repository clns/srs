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
      
      <?php
        $global_posts_query = new WP_Query(
          array(
            'post_type' => 'webinar',
            'meta_query' => array(
              array(
                'key' => 'webinar_date',
                'value' => date("Y-m-d"),
                'compare' => '>=',
                'type' => 'DATE'
              )
            ),
            'meta_key' => 'webinar_date',
            'orderby' => 'meta_value',
            'order' => 'ASC'
          )
        );
        
        if($global_posts_query->have_posts()) :
          while($global_posts_query->have_posts()) : $global_posts_query->the_post(); ?>		
            <div class="event">
              <div class="event-border"></div>
              <div class="leader-bio">
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>"><?php echo get_wp_user_avatar($userID); ?>
                <span class="leader-name"><?php the_author(); ?></span></a><br>
                <?php if (!empty(get_the_author_meta("job_title"))) {
                   echo the_author_meta("job_title");?>,<br>
                <?php } ?>
                <i>
                  <?php $organization = get_the_author_meta("organization"); 
                  if (!empty($organization)) {
                    echo $organization; 
                  } ?>
                </i>
              </div>
              <div class="event-header">
                <?php the_title(); ?>
              </div>
              <div class="event-time">
                <div class="date">
                  <?php $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                    if (!empty($webinar_date)) {
                     $date = new DateTime($webinar_date);
                     $webinar_date = $date->format('M j');
                      echo $webinar_date;
                    }
                  ?>
                </div>
                  <?php $webinar_time = get_post_meta($post->ID, "webinar_time", true);
                    if (!empty($webinar_time)) {
                     $time = new DateTime($webinar_time);
                     $webinar_time = $time->format('g a ');
                      echo $webinar_time;
                    } 
                  ?>CT
              </div>
            </div>
            <div class="clear"></div>
        <?php		
          endwhile;
        endif;
        ?>
    </div>
    </div>
	</div><!-- end main-content -->
</div><!-- end content-container -->
<div class="clear"></div>

<?php

get_footer();