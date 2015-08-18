<?php

/**
Template Name: Single Catapult Video Page
 */

get_header(); ?>
<div style="position: relative; left: 625px; margin: 30px auto 30px auto;">
    <?php
    $customdata = get_post_meta($post->ID, 'vimeo_video_id', true);
    if( !empty($customdata) ){
        ?><iframe src="https://player.vimeo.com/video/<?php echo $customdata;?>"
                  width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe>
    <?php }?>
</div>
<?php get_footer(); ?>