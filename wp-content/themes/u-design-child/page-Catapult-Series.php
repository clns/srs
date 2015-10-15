<?php
/**
Template Name: Catapult Series
 */


get_header(); ?>

    <div id="content-container" class="full-width">
        <div id="main-content" class="full-width">
            <div id="network" class="network-page catapult">
                <div class="page-banner">
                    <div class="banner-text"><?php the_title(); ?></div>
                </div>

                <div class="intro-text">
                    <?php
                    # This section generates the blurb under the page-banner
                    the_post();
                    the_content();
                    ?>
                </div>

                <?php
                foreach( get_terms( 'catapulttopic', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) { ?>
                    <div class="benefits-container">
                        <div class="benefits-border"></div>
                        <div class="section-header"><?php echo $parent_term->name; ?></div>

                        <?php
                        foreach( get_terms( 'catapulttopic', array( 'hide_empty' => false, 'parent' => $parent_term->term_id ) ) as $child_term ) {
                            wp_reset_query();

                            $args = array(
                                'post_type'      => 'catapultvideo',
                                'posts_per_page' => -1,
                            );

                            $loop = new WP_Query($args);

                            if ($loop->have_posts()) {;?>
                                <div class="expanding-container">
                                    <div class="expanding-heading">
                                        <div class="expand-button expanding-icon expanding-icon-plus"></div>
                                        <h3><?php echo $child_term->name; ?></h3>
                                    </div>
                                    <div class="expanding-content">
                                        <?php
                                        while ($loop->have_posts()) : $loop->the_post();
                                            if(has_term($child_term->name, 'catapulttopic')) {
                                                echo '<span><a href="' . get_permalink() . '" target="_blank">' . get_the_title() . '</a></span>';
                                            }
                                        endwhile; ?>
                                    </div>
                                </div>
                            <?php
                            } // end if
                        } // end foreach ?>
                    </div>
                <?php
                }

                wp_reset_postdata();
                ?>

            </div><!-- end network -->
        </div><!-- end main-content -->
    </div><!-- end content-container -->

    <div class="clear"></div>

<?php get_footer(); ?>