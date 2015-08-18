<?php
/**
Template Name: Catapult Series
 */


get_header(); ?>

    <div id="content-container" class="full-width">
        <div id="main-content" class="full-width">
            <div id="network" class="network-page catapult">
                <div class="page-banner">
                    <div class="banner-text"><?php echo wp_title("",true); ?></div>
                </div>

                <div class="intro-text">
                    <?php
                    #This section generates the blurb under the page-banner
                    $custom_terms = get_terms('catapulttopic');
                    wp_reset_query();
                    $args = array(
                        'post_type'      => 'catapultvideo',
                        'posts_per_page' => -1,
                    );

                    $loop = new WP_Query($args);

                    if ($loop->have_posts()) {
                            echo the_content();
                        }
                    wp_reset_postdata();
                    ?>
                </i></div>


                <?php
                $custom_terms = get_terms('catapulttopic');?>
                <?php
                foreach($custom_terms as $custom_term) {
                wp_reset_query();

                $args = array(
                    'post_type'      => 'catapultvideo',
                    'posts_per_page' => -1,
                );

                $loop = new WP_Query($args);

                if ($loop->have_posts()) {;?>
                <div class="previews-container-catapult">
                    <div class="previews-border"></div>
                    <div class="section-header">
                        <?php echo $custom_term->name; ?>
                    </div>
                    <?php
                    while ($loop->have_posts()) : $loop->the_post(); ?>
                        <div class="catapult-video-content">
                            <?php
                            if(has_term($custom_term->name, 'catapulttopic')){
                                echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
                                the_content();
                                }
                                ;?>
                        </div>
                    <?php
                    endwhile;
                    }
                    ?></div><?php
                 }
                wp_reset_postdata();
                ?>

            </div>
            </div>
        </div><!-- end main-content -->
    </div><!-- end content-container -->

    <div class="clear"></div>

<?php get_footer(); ?>