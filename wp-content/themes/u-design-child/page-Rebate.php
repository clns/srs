<?php
/**
 * @package WordPress
 * @subpackage U-Design
 */
/**
 * Template Name: Rebate
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();

$date1 = date("Ymd", strtotime('-8 day'));
$date2 = date("Ymd", strtotime('today'));

$args = array(
  'post_type'  => 'bootcamp',
  'franchise'  => 'srs',
  'orderby'    => 'meta_value meta_value_num',
  'meta_key'   => 'start_date',
  'order'      => 'ASC',
  'posts_per_page' => -1,
  'meta_query' => array(
    array(
      'key'     => 'end_date',
      'value'   =>  array($date1,$date2),
      'type'    => 'DATE',
      'compare' => 'BETWEEN'
    )
  )

);
$the_query = new WP_Query($args);
//echo "Last SQL-Query: {$the_query->request}";
?>

<div id="content-container" class="full-width">
  <div id="main-content" class="full-width">
    <div class="full-width-content">

      <?php
      the_post();
      the_content();
      ?>

    </div>

    <div id="page-franchise-participant">

      <!-- ### Select Bootcamp Block ### -->
      <?php if ($the_query->post_count > 1): ?>

        <h2>Select your bootcamp.</h2>
        <select id="select-bootcamp" onChange="changeBootcampDisplay(this.value)">
          <?php if ( $the_query->have_posts() ) while ( $the_query->have_posts() ) : $the_query->the_post();
              $franchise_title = generate_franchise_title($post);
            ?>
            <option value="bc<?php echo get_the_ID()?>"><?php echo $franchise_title ?></option>
          <?php endwhile; wp_reset_postdata(); // end of the loop. ?>
        </select>

      <?php endif; ?>
      <div class="bootcamp-header">

        <?php $first = true;
        if ( $the_query->have_posts() ):
          echo "<h1>SRS Bootcamp</h1>";
          while ( $the_query->have_posts() ) : $the_query->the_post();
            if ( !$first ) {
              $display = 'style="display: none;"';
            } else {
              $display = '';
              $first = false;
            }
            $franchise_title = generate_franchise_title($post);
            ?>
            <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock" <?php echo $display ?>>
              <h2 class="location-header"><?php echo $franchise_title ?></h2>
            </div>
            <div class="bc<?php echo get_the_ID() ?> bootcampInfoBlock button-container" <?php echo $display ?>>
              <p class="button-text"><?php echo '<a href="' . get_post_meta($post->ID, 'rebate_link', true) . '">Request Rebate</a>'; ?></p>
            </div>
        <?php
          endwhile; wp_reset_postdata();
        else:
          echo "<h1>No rebates available. Please check back soon.</h1>";
        endif;
        ?>
      </div>



    </div><!-- end page-franchise-participant -->
  </div><!-- end main-content -->
</div><!-- end content-container -->



<?php if ($the_query->post_count > 1) : ?>
  <script>
    var bootcampID = getParameterByName('bootcampID');
    if (bootcampID) {
      setDropDownLink(bootcampID);
      changeBootcampDisplay(bootcampID);
    }
  </script>
<?php
endif;
get_footer();
