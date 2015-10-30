<?php

get_header(); ?>
<div class="catapult-video-container network-page">
    <div class="catapult-video" >

        <div id="page-banner">
            <div class="banner-text">Catapult Series</div>
        </div>

        <?php
        $customdata = get_post_meta($post->ID, 'vimeo_video_id', true);
        if( !empty($customdata) ){
            ?><iframe src="https://player.vimeo.com/video/<?php echo $customdata;?>"
                      width="950" height="534" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe>
        <?php }?>

        <div id="video-footer-wrap">
            <div class="video-footer-text">
            <?php
            if ( is_user_logged_in() ) {
               echo 'As a member of the SRS Network you are allowed to share this video with your staff. Please do not share it outside your ministry. ';
               echo '<a href="mailto:?to=&body=%0A%0ACheck%20out%20this%20video%20from%20Support%20Raising%20Solutions%3A%0A '.get_permalink().'%0A%0AEXCLUSIVE%20VIDEO%3A%20Please%20do%20not%20share%20the%20link%20to%20this%20teaching%20video.%20Your%20ministry%20is%20a%20part%20of%20the%20SRS%20Network%2C%20who%20has%20permission%20to%20send%20this%20video%20to%20their%20staff.%20%C2%A9%202015%20Support%20Raising%20Solutions &subject='.the_title('','',false).'">Share this Video</a>';
            } else {
                echo 'EXCLUSIVE VIDEO: Please do not share the link to this teaching video. Your ministry is a part of the SRS Network, who has permission to send this video to their staff. &copy 2015 Support Raising Solutions';
            }
            ?>
            </div>
        </div>
    </div>

    <div id="title-wrapper">
        <div class="section-header">
            <?php the_title(); ?>
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
            <a href="<?php bloginfo('url'); ?>/catapult-series/">
                <button> BACK TO ARCHIVE</button>
            </a>
        <?php endif; ?>
    </div>

    <?php
        $output .= get_dynamic_column( 'cont-box-3', 'one_third home-cont-box', 'catapult-video-page-column-1' );
        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box second_column', 'catapult-video-page-column-2' );
        $output .= get_dynamic_column( 'cont-box-4', 'one_third home-cont-box last_column', 'catapult-video-page-column-3' );
        echo $output;
    ?>
</div>
<?php get_footer(); ?>