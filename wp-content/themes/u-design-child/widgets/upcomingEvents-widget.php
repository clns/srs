<?php
/**
 * Widget Name: Upcoming Events Widget
 * Description: A widget that allows to display events of all types with location and date.
 * Version: 0.1
 *
 */
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'upcoming_events_load_widget' );

/**
 * Register our widget.
 * 'Upcoming_Events_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function upcoming_events_load_widget() {
	register_widget( 'Upcoming_Events_Widget' );
}

/**
 * Custom Category Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Upcoming_Events_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function Upcoming_Events_Widget() {
		/* Create the widget. */
		$this->WP_Widget( 'upcoming-events-widget', esc_html__('U-Design-Child: Upcoming Events', 'udesign'), array( 'description' => esc_html__('Display events of all types with location and date.', 'udesign') ));
	}

	/**
	 * How to display the widget on the screen.
	 */
    function widget($args, $instance)
    {
        global $before_title, $after_title, $before_widget, $after_widget;
        
        extract($args);

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title']);

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if ($title)
            echo $before_title . $title . $after_title;

        // Print Bootcamps
        $this->getBootcamps();
        
        // Print Trainings
        $this->getTrainings();
        
        // Print Conferences
        $this->getConferences();
        
        // Print Webinars
        $this->getWebinars();

        echo '<div class="event-group"><h4><a href="/events">' . esc_html__('All Events', 'u-design') . '</a></h4></div>';

        /* After widget (defined by themes). */
        echo $after_widget;
    }

	/**
	 * Update the widget settings.
	 */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
    function form($instance)
    {

        /* Set up some default widget settings. */
        $defaults = array('title' => esc_html__('Upcoming Events', 'udesign'));
        $instance = wp_parse_args((array)$instance, $defaults); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title:', 'udesign'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" type="text"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>"
                   class="widefat"/>
        </p>
<?php
    }
    
    /**
     * Prints Bootcamps HTML content
     * */
    function getBootcamps()
    {
        $posts = new WP_Query(array(
            'post_type' => 'bootcamp',
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => 'bootcamp_status',
                    'value' => array('Open', 'Closed'),
                    'compare' => 'IN',
                ),
            ),
            'tax_query' => array(
                array( // Apply "srs" franchise taxonomy condition (applies to bootcamp, conference and training)
                    'taxonomy' => 'franchise',
                    'field' => 'slug',
                    'terms' => array('srs', '', null),
                ),
            )
        ));

        if ($posts->have_posts()) {
            echo '<div class="event-group"><h4>' . esc_html__('Bootcamps', 'u-design') . '</h4>';
            while ( $posts->have_posts() ) : $posts->the_post();
                $start_date_raw = strtotime(get_post_meta(get_the_ID(), 'start_date',true));
                $end_date_raw = strtotime(get_post_meta(get_the_ID(), 'end_date',true));
                $start_date = date("F j", $start_date_raw);
                if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                    $end_date = date("F j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                } else {
                    $end_date = date("j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                }
                $full_date = $start_date . '-' . $end_date;

                echo '<div class="event">
					<h6><a href="' . get_term_link('srs', 'franchise') . "?bootcampID=bc" . get_the_ID() . '">' .
                    get_post_meta(get_the_ID(), 'location_city', true) . ', ' . get_post_meta(get_the_ID(), 'location_state', true)
                    . '</a></h6>
					<p>' . $full_date . '</p>
				</div>';
            endwhile; wp_reset_postdata();
            echo '</div>';
        }
    }

    /**
     * Prints Trainings HTML content
     * */
    function getTrainings()
    {
        $posts = new WP_Query(array(
            'post_type' => 'training',
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => 'conferences_status',
                    'value' => array('Open', 'Closed'),
                    'compare' => 'IN',
                ),
            ),
            'tax_query' => array(
                array( // Apply "srs" franchise taxonomy condition (applies to bootcamp, conference and training)
                    'taxonomy' => 'franchise',
                    'field' => 'slug',
                    'terms' => array('srs', '', null),
                ),
            )
        ));

        if ($posts->have_posts()) {
            echo '<div class="event-group"><h4>' . esc_html__('Trainings', 'u-design') . '</h4>';
            while ( $posts->have_posts() ) : $posts->the_post();
                $start_date_raw = strtotime(get_post_meta(get_the_ID(), 'start_date',true));
                $end_date_raw = strtotime(get_post_meta(get_the_ID(), 'end_date',true));
                $start_date = date("F j", $start_date_raw);
                if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                    $end_date = date("F j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                } else {
                    $end_date = date("j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                }
                $full_date = $start_date . '-' . $end_date;

                echo '<div class="event">
					<h6><a href="' . get_permalink() . '">' .
                    get_post_meta(get_the_ID(), 'location_city', true) . ', ' . get_post_meta(get_the_ID(), 'location_state', true)
                    . '</a></h6>
					<p>' . $full_date . '</p>
				</div>';
            endwhile; wp_reset_postdata();
            echo '</div>';
        }
    }

    /**
     * Prints Conferences HTML content
     * */
    function getConferences()
    {
        $posts = new WP_Query(array(
            'post_type' => 'conference',
            'meta_key' => 'start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => 'conferences_status',
                    'value' => array('Open', 'Closed'),
                    'compare' => 'IN',
                ),
            ),
            'tax_query' => array(
                array( // Apply "srs" franchise taxonomy condition (applies to bootcamp, conference and training)
                    'taxonomy' => 'franchise',
                    'field' => 'slug',
                    'terms' => array('srs', '', null),
                ),
            )
        ));

        if ($posts->have_posts()) {
            echo '<div class="event-group"><h4>' . esc_html__('Conferences', 'u-design') . '</h4>';
            while ( $posts->have_posts() ) : $posts->the_post();
                $start_date_raw = strtotime(get_post_meta(get_the_ID(), 'start_date',true));
                $end_date_raw = strtotime(get_post_meta(get_the_ID(), 'end_date',true));
                $start_date = date("F j", $start_date_raw);
                if (date("m", $start_date_raw) != date("m", $end_date_raw)) {
                    $end_date = date("F j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                } else {
                    $end_date = date("j", strtotime(get_post_meta(get_the_ID(), 'end_date',true)));
                }
                $full_date = $start_date . '-' . $end_date;

                echo '<div class="event">
					<h6><a href="' . get_permalink() . '">' .
                    get_post_meta(get_the_ID(), 'location_city', true) . ', ' . get_post_meta(get_the_ID(), 'location_state', true)
                    . '</a></h6>
					<p>' . $full_date . '</p>
				</div>';
            endwhile; wp_reset_postdata();
            echo '</div>';
        }
    }

    /**
     * Prints Webinars HTML content
     * */
    function getWebinars()
    {
        $posts = new WP_Query(array(
            'post_type' => 'webinar',
            'meta_key' => 'webinar_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => 5,
            'meta_query' => array(
                array(
                    'key' => 'webinar_date',
                    'value' => current_time('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        ));

        if ($posts->have_posts()) {
            echo '<div class="event-group"><h4>' . esc_html__('Webinars', 'u-design') . '</h4>';
            while ( $posts->have_posts() ) : $posts->the_post();
                $full_date = date("F j, Y", strtotime(get_post_meta(get_the_ID(), 'webinar_date', true)));

                echo '<div class="event">
					<h6><a href="' . get_permalink() . '">' .
                    get_post_meta(get_the_ID(), 'webinar_time', true)
                    . '</a></h6>
					<p>' . $full_date . '</p>
				</div>';
            endwhile; wp_reset_postdata();
            echo '</div>';
        }
    }
}


