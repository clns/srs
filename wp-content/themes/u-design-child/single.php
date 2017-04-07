<?php


/**

 * @package WordPress

 * @subpackage U-Design

 */



global $udesign_options;



// construct an array of portfolio categories

$portfolio_categories_array = explode( ',', $udesign_options['portfolio_categories'] );



if ( $portfolio_categories_array != "" && post_is_in_category_or_descendants( $portfolio_categories_array ) ) :

    // Test if this Post is assigned to the Portfolio category or any descendant and switch the single's template accordingly

    include 'single-Portfolio.php';

else : // Continue with normal Loop (Blog category)



    get_header();



    $content_position = ( $udesign_options['blog_sidebar'] == 'left' ) ? 'grid_18 push_6' : 'grid_18';

    if ( $udesign_options['remove_single_sidebar'] == 'yes' ) $content_position = 'grid_24';

?>

    <div id="content-container" class="container_24">

	<div id="main-content" class="<?php echo $content_position; ?>">

	    <div class="main-content-padding">



<div style="display:inline-block; margin-left:55px;"><div style="float:left; margin-right:60px; margin-top:-15px"><h3 style="font-weight:700; font-size:16px !important;"><?php the_category(', '); ?></h3></div>

<div class="widgetcolor" style="float:left; margin-right:15px;">

<?php 

$src = 'https://supportraisingsolutions.org/wp-content/uploads/2014/01/ArrowBack.png'; //image url

previous_post_link('%link', '<img style="border:none;" src="'. $src .'" /> Previous', TRUE, ''); ?></div>



<div class="widgetcolor" style="float:left;"><?php 

$src = 'https://supportraisingsolutions.org/wp-content/uploads/2014/01/ArrowForward.png'; //image url

next_post_link('%link', 'Next <img style="border:none;" src="'. $src .'" />', TRUE, ''); ?></div>

</div>





<?php           do_action('udesign_above_page_content'); ?>

<?php		if (have_posts()) :

		    while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

<div style="margin-left:55px;"><h1 class="posttitle"><?php single_post_title(''); ?></h1>

<div class="postmetadata"></div><!-- AddThis Button BEGIN --><div style="margin-left:-70px; margin-top:-80px; position:absolute;"><a class="addthis_button_compact" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=ra-51b8901a44103141"><img src="https://supportraisingsolutions.org/wp-content/uploads/2013/06/Share.png" width="61" height="18px" alt="Share" style="border:0"/></a><script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script><script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-51b8901a44103141"></script><script type="text/javascript">var addthis_config = addthis_config||{};addthis_config.services_exclude = 'mailto';</script></div><!-- AddThis Button END -->

                                    <span style="font-size:15px;"><span>

<?php                                   if( $udesign_options['show_postmetadata_author'] == 'yes' ) :

                                           printf( __('By %1$s on %2$s ', 'udesign'),'</span>'.udesign_get_the_author_page_link().'<span>', get_the_date() );

                                        else :

                                            printf( __('On %1$s ', 'udesign'), get_the_date() );

                                        endif; ?>

                                    </span> &nbsp; / &nbsp; <?php comments_popup_link( __( 'Leave a comment', 'udesign' ), __( '1 Comment', 'udesign' ), __( '% Comments', 'udesign' ) ); ?> <?php edit_post_link(__('Edit', 'udesign'), '<div style="float:right;margin:0 10px;">', '</div>'); ?>  

<?php                               echo ( $udesign_options['show_postmetadata_tags'] == 'yes' ) ? the_tags('<div class="post-tags-wrapper">'.__('Tags: ', 'udesign'), ', ', '</div>') : ''; ?>

                               </span> </div>

			    <div class="entry">

<div style="margin-left:55px;"><?php                           // Post Image

                                if( $udesign_options['display_post_image_in_single_post'] == 'yes' ) display_post_image_fn( $post->ID, false );

				the_content(__('<p class="serif">Read the rest of this entry &raquo;</p>', 'udesign'));

				wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			    </div>			  </div>

			</div>

<a name="comment"></a>

<?php			comments_template();

		    endwhile; else: ?>

			<p><?php esc_html_e("Sorry, no posts matched your criteria.", 'udesign'); ?></p>

<?php		endif; ?>

	    </div><!-- end main-content-padding -->

	</div><!-- end main-content -->

<?php

	if( ( !$udesign_options['remove_single_sidebar'] == 'yes' ) && sidebar_exist('BlogSidebar') ) { get_sidebar('BlogSidebar'); }

?>

    </div><!-- end content-container -->

<?php

endif; // end normal Loop ?>



<div class="clear"></div>



<?php



get_footer(); 