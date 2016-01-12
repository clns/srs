<?php

get_header(); ?>
<div class="webinar-video-container network-page">
    <div class="webinar-video" >

        <div class="page-banner">
            <div class="banner-text">Monthly Webinars</div>
        </div>


        <?php
        $customdata = get_post_meta($post->ID, 'vimeo_video_id', true);
        if( !empty($customdata) ){
        ?><iframe src="https://player.vimeo.com/video/<?php echo $customdata;?>"
                  width="950" height="534" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe>
        <?php }?>

<!--        <div id="video-footer-wrap">-->
<!--            <div class="video-footer-text">-->
<!--            --><?php
//            if ( is_user_logged_in() ) {
//               echo 'As a member of the SRS Network you are allowed to share this video with your staff. Please do not share it outside your ministry. ';
//               echo '<a href="mailto:?to=&body=%0A%0ACheck%20out%20this%20video%20from%20Support%20Raising%20Solutions%3A%0A '.get_permalink().' &subject='.the_title('','',false).'">Share this Video</a>';
//            } else {
//                echo 'EXCLUSIVE VIDEO: Please do not share the link to this teaching video. Your ministry is a part of the SRS Network, who has permission to send this video to their staff. &copy 2015 Support Raising Solutions';
//            }
//            ?>
<!--            </div>-->
<!--        </div>-->
    </div>

    <div id="title-wrapper">
        <div class="section-header">
            <?php the_title(); ?>
        </div>
        <div class="date-time-wrapper">
            <div class="srs-webinar-note"> SRS Webinar</div>
            <div class="date">
              <?php $webinar_date = get_post_meta($post->ID, "webinar_date", true);
                if (!empty($webinar_date)) {
                 $date = new DateTime($webinar_date);
                 $webinar_date = $date->format('  M j');
                  echo $webinar_date;
                }
              ?>
            </div>
            <div class="event-time">
              <?php $webinar_time = get_post_meta($post->ID, "webinar_time", true);
                if (!empty($webinar_time)) {
                 $time = new DateTime($webinar_time);
                 $webinar_time = $time->format('g a ');
                  echo $webinar_time;
                }
              ?>CT
            </div>
        </div>
        <div class="intro-text">
            <?php
            # This section generates the blurb under the title
            the_post();
            the_content();
            ?>
        </div>

    </div>
    <div id="button-wrapper">
        <?php
        if(is_user_logged_in()): ?>
            <a href="<?php bloginfo('url'); ?>/webinar/">
                <button> BACK TO ARCHIVE</button>
            </a>
        <?php endif; ?>
    </div>
    <div class = "leader-bio-wrapper">
        <div class="leader-bio">
                <?php echo get_wp_user_avatar($userID); ?>
            <br>
<!--            get_author_posts_url-->
            <div class="author-name-wrapper">
                <?php the_author();

                    echo ", ";
                 ?>
            </div>

            <div class="job-title-wrapper">
            <?php
                $jobtitle = get_the_author_meta("job_title");
                if (!empty($jobtitle)) {
                echo $jobtitle.", ";
                } ?>
            </div>
            <div class = "organization-title-wrapper">
                    <?php $organization = get_the_author_meta("organization");
                    if (!empty($organization)) {
                        echo $organization;
                    } ?>
            </div>
            <div class = "bio-info-wrapper">
                <?php $bioinfo = get_the_author_meta("description");
                if (!empty($bioinfo)) {
                    echo $bioinfo."<br>";
                } ?>
            </div>
            <div class="bio-info-button-wrapper">
                <?php
                if(is_user_logged_in()): ?>
                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID'));?>">
<!--                        "--><?php //bloginfo('url'); ?><!--/blog/">-->
                        <button> SRS Blog Articles</button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<!--    --><?php
//        $output .= get_dynamic_column( 'cont-box-3', 'one_third home-cont-box', 'catapult-video-page-column-1' );
//        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box second_column', 'catapult-video-page-column-2' );
//        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box last_column', 'catapult-video-page-column-3' );
//        echo $output;
//    ?>
</div>
<?php get_footer(); ?>