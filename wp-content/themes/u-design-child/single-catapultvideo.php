<?php

get_header(); ?>
<div class="catapult-video-container">
<div class="catapult-video" >
    <?php
    $customdata = get_post_meta($post->ID, 'vimeo_video_id', true);
    if( !empty($customdata) ){
        ?><iframe src="https://player.vimeo.com/video/<?php echo $customdata;?>"
                  width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allow fullscreen></iframe>
    <?php }?>
</div>
</div>
<?php get_footer(); ?>