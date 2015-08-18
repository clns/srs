<?php
/**
Template Name: Catapult Video
 */


get_header(); ?>
<?php
$custom_terms = get_terms('catapulttopic');

foreach($custom_terms as $custom_term) {
    wp_reset_query();
    $args = array('post_type' => 'catapultvideo');
    $loop = new WP_Query($args);

    if ($loop->have_posts()) { ?>

        <header class="catapult-video-title">
            <?php echo '<h2 style = "font-size: 18px; color: black">' . $custom_term->name . '</h2>'; ?>
        </header>
        <?php
        while ($loop->have_posts()) : $loop->the_post(); ?>
            <div class="catapult-video-content">
                <?php
                echo get_the_title();
                if(has_term($custom_term->name, 'catapulttopic')){
                    echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                    the_content();}
                    ;?>
            </div>
        <?php
        endwhile;
    }
    else{
        '<p style = "font-size: 54px; color: black"> There is nothing here </p>';
    }
}
wp_reset_postdata();
?>

<?php get_footer(); ?>
