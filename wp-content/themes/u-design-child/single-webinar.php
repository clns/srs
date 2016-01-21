<?php

get_header(); ?>
<div class="webinar-video-container network-page">
    <div class="webinar-video" >

        <div class="page-banner">
            <div class="banner-text">Monthly Webinars</div>
        </div>
    </div>

            <?php
            # This section generates the blurb under the title
            the_post();
            the_content();
            ?>


    <?php
    $webinar_time = get_post_meta($post->ID, "webinar_time", true);
    $webinar_date = get_post_meta($post->ID, "webinar_date", true);
    $webinar_link = get_post_meta($post->ID, "webinar_link", true);
    $todaysDate = time() - (time() % 86400);
    $vimeo_video_id = get_post_meta($post->ID, 'vimeo_video_id', true);
		if (is_user_logged_in() ) {
			if ( strtotime($webinar_date) <= $todaysDate and !empty($vimeo_video_id)) {
                echo '<iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id;?>" width="950" height="534" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe>';
            }
            else{ ?>
					<div class="join-box" id="single-webinar">
						<div class="login-credentials">Login Credentials:</div>
						<div class="login-credentials">Dial in Number:<span> (123)-456-7890</span></div>
						<div class="login-credentials">Pin:<span> 1234</span></div>
						<button><a href="<?php echo  $webinar_link?>" target="_blank" style="color: white; font-size: 15px !important">Join the Webinar</a></button>
						<button class="blue"><a href="javascript:cal.download('Webinar')" style="color: white; font-size: 15px !important">Add to Cal</a></button>
					</div>
                <?php
            }
        }
        else{
			$permalink = get_permalink($post->ID);
            echo '<div class="join-box" id="single-webinar">';
                if ( strtotime($webinar_date) <= $todaysDate){
                    echo 'Sign in to Watch the Webinar Video';
                }
                else{
                    echo 'Sign in to Join the Webinar';
                };
            echo '<br><br>
                <a href="/supportraisingsolutions.org/login/?redirect_to=%2Fsupportraisingsolutions.org%2Fwebinar%2F<?php echo $post->post_name;?>">Sign In</a> | <a href="mailto:info@supportraisingsolutions.org?subject=SRS Network Membership&body=I am interested in in the SRS Monthly Webinars. Please contact me with more information.">Join</a>
            </div>';
        }

    $id = "logged-out";
    if (is_user_logged_in() and strtotime($webinar_date) <= $todaysDate) {
        $id = "logged-in";
    }?>


    <div class="title-wrapper" id="<?php echo $id?>">
        <?php if((is_user_logged_in()) and (strtotime($webinar_date) <= $todaysDate)) {?>
            <div class="section-header">
                <span id="author"><?php the_author(); echo ":</span> <span id=\"title\">"; the_title(); ?></span>
            </div>
            <?php
        };?>

        <div class="date-time-wrapper" id="<?php echo $id?>">
            <div class="srs-webinar-note" id="<?php echo $id?>"> SRS Webinar</div>
            <div class="date">
                <?php
                 if (!empty($webinar_date)) {
                     $date = new DateTime($webinar_date);
                      $webinar_date = $date->format('  M j');
                      echo $webinar_date;
                  };
                 ?>
            </div>
            <div class="event-time" id="<?php echo $id?>">
                  <?php
                   if (!empty($webinar_time)) {
                        $time = new DateTime($webinar_time);
                        $webinar_time = $time->format('g:i a ');
                        echo $webinar_time;
                    }; echo 'CT';
                  ?>
            </div>
        </div>

        <?php
        $webinar_date = get_post_meta($post->ID, "webinar_date", true);
        if(strtotime($webinar_date) <= $todaysDate and is_user_logged_in()) {?>
            <div id="button-wrapper">
                <a href="<?php bloginfo('url'); ?>/webinar/">
                    <button> BACK TO ARCHIVE</button>
                </a>
            </div>
        <?php }
        else{
            ?>
            <div class="section-header" id="logged-out">
                <span id="author"><?php the_author(); echo ": </span>"; echo the_title(); ?></span>
            </div>
        <?php } ?>

        <div class="video-description">
            <?php $video_description = get_post_meta($post->ID, "video_description", true);
            echo $video_description;
            ?>
        </div>
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