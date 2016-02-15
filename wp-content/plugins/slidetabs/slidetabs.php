<?php
/*
 * SlideTabs
 *
 * Plugin Name:        SlideTabs for WordPress
 * Plugin URI:         http://www.slidetabs.com
 * Description:        Create tab interfaces to organice and present any type of content on your website.
 * Author:             WebStack
 * Author URI:         http://www.slidetabs.com
	
 * Version:            1.1.5
 * Requires at least:  3.0
 * Tested up to:       3.5.1
	
 * @package            SlideTabs
 * @category           Core
 * @copyright          Copyright 2013, WebStack.
 * @license            http://www.slidetabs.com/license
 */


class SlideTabs {

	function SlideTabs() {
		global $wpdb;
		
		/*
		 * Backwards compatibility code for determining plugin and content directories
		 */
		if (!function_exists('is_ssl')) {
			function is_ssl() {
				if (isset($_SERVER['HTTPS'])) {
					if ('on' == strtolower($_SERVER['HTTPS']))
						return true;
					if ('1' == $_SERVER['HTTPS'])
						return true;
				} elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
					return true;
				}
				return false;
			}
		}
		if (version_compare(get_bloginfo('version'), '3.0' , '<') && is_ssl()) { $wp_content_url = str_replace('http://', 'https://', get_option('siteurl')); }
		else { $wp_content_url = get_option('siteurl'); }
		if (defined('WP_CONTENT_URL')) { $wp_content_url = WP_CONTENT_URL; }
		else { $wp_content_url .= '/wp-content'; }
		if (defined('WP_CONTENT_DIR')) { $wp_content_dir = WP_CONTENT_DIR; }
		else { $wp_content_dir = ABSPATH . 'wp-content'; }
		$wp_plugin_url = $wp_content_url . '/plugins';
		$wp_plugin_dir = $wp_content_dir . '/plugins';
		$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
		$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';
		
		$this->db                                =& $wpdb;		
		$this->version                           = '1.1.5';
		$this->post_type                         = 'slidetabs';
		$this->content_post_type                 = 'slidetabs_content';
		$this->ajax_post_type                    = 'slidetabs_ajax';
		$this->scripts_to_load                   = array();
		$this->default_template                  = 'clean';
		$this->dynamic_title_length              = 100;
		$this->dynamic_title_length_with_image   = 50;
		$this->dynamic_excerpt_length            = 100;
		$this->dynamic_excerpt_length_with_image = 50;
		$this->use_old_tinymce_editor            = version_compare(get_bloginfo('version'), '3.2.1', '<=');
		$this->use_old_media_manager             = version_compare(get_bloginfo('version'), '3.4.2', '<=');
		$this->plugin_settings                   = get_option('slidetabs_plugin_settings', array(
			'enqueue_jquery'            => true,
			'enqueue_jquery_easing'     => true,
			'enqueue_jquery_mousewheel' => true,
			'ssl_check'                 => true
		));
				
		if (function_exists('add_action')) {
			add_action('init'                            , array($this, 'slidetabs_init'));           // add custom post types
			add_action('admin_menu'                      , array($this, 'admin_menu'));               // add sidebar navigation
			add_action('admin_menu'                      , array($this, 'add_custom_box'));           // add custom sidebar box with dynamic options
			add_action('save_post'						 , array($this, 'save_dynamic_meta'));        // process meta information for dynamic tabs when saving a post
			add_action('admin_init'                      , array($this, 'tinymce_add_buttons'));      // add the SlideTabs button to the TinyMCE navigation
			add_action('admin_init'                      , array($this, 'admin_init'));               // add and register JavaScript and stylesheets for the admin pages
			add_action('admin_footer'                    , array($this, 'tinymce_plugin_dialog'));    // add the TinyMCE dialog window markup
			add_action('admin_footer'                    , array($this, 'embed_dialog'));	          // add the embed dialog window markup
			add_action('wp_ajax_slidetabs_preview'       , array($this, 'slidetabs_preview'));        // preview AJAX action
			add_action('wp_ajax_slidetabs_add_tab'       , array($this, 'slidetabs_add_tab'));        // add-tab AJAX action
			add_action('wp_ajax_slidetabs_add_background', array($this, 'slidetabs_add_background')); // set background image AJAX action
			add_action('wp_ajax_slidetabs_date_format'   , array($this, 'slidetabs_date_format'));    // get the date format
			add_action('admin_print_scripts-slidetabs_page_slidetabs/slidetabs_add_new', array($this, 'admin_head')); // add JavaScript and stylesheets for the admin interface on appropriate pages
			add_action('admin_print_scripts-slidetabs_page_slidetabs/slidetabs_dynamic', array($this, 'admin_head')); // add JavaScript and stylesheets for the admin interface on appropriate pages
			add_action('admin_print_scripts-toplevel_page_slidetabs'                   , array($this, 'admin_head')); // add JavaScript and stylesheets for the admin interface on appropriate pages
			
			// print the JavaScript and CSS files required for displaying the tabs in public view
			if (!is_admin()) {
				add_action('wp'             , array($this, 'wp_hook'));       // pre-load templates used by SlideTabs in post(s)
				add_action('wp_print_styles', array($this, 'print_styles'));  // print the template CSS files
				add_action('wp_footer'      , array($this, 'print_scripts')); // print the JavaScript files and plugin activation code
			}

			// change the default TinyMCE options for the plugin pages			
			add_filter('tiny_mce_before_init', array($this, 'tinymce_set_options'));
			
			// add the shortcode for outputting the tabs markup
			add_shortcode('slidetabs', array($this, 'slidetabs_shortcode'));
						
    		add_action('admin_print_footer_scripts', 'slidetabs_wp_tiny_mce_preload_dialogs', 30);
		}
	}
	
	
	/*
	 * SlideTabs Init
	 */
	function slidetabs_init() {
		$this->register_post_types(); // add custom post types
		
		// load the local jQuery version on the public pages
		if (!is_admin() && $this->plugin_settings['enqueue_jquery']) {
			wp_deregister_script('jquery');
			wp_register_script('jquery', $this->url('/js/jquery-1.7.2.min.js'), false, '1.7.2');
			//wp_register_script('jquery', $this->url('/js/jquery-1.9.1.min.js'), false, '1.9.1');
						
			wp_enqueue_script('jquery');
		}
	}
	
	
	/*
	 * Add the main sidebar admin menu
	 */	
	function admin_menu() {
		add_menu_page('SlideTabs', 'SlideTabs', 'publish_posts', basename(__FILE__), array($this, 'admin_page_manage'), $this->url('/images/st_icon.png'));
		add_submenu_page(basename(__FILE__), 'Manage SlideTabs'     , 'Manage'         , 'publish_posts', basename(__FILE__)                               , array($this, 'admin_page_manage'));
		add_submenu_page(basename(__FILE__), 'Add SlideTabs'        , 'Add New'        , 'publish_posts', basename(__FILE__) . '/slidetabs_add_new'        , array($this, 'admin_page_add_new'));
		add_submenu_page(basename(__FILE__), 'Add Dynamic SlideTabs', 'Add Dynamic'    , 'publish_posts', basename(__FILE__) . '/slidetabs_dynamic'        , array($this, 'admin_page_add_dynamic'));
		add_submenu_page(basename(__FILE__), 'Plugin Settings'      , 'Plugin Settings', 'publish_posts', basename(__FILE__) . '/slidetabs_plugin_settings', array($this, 'admin_page_plugin_settings'));
		add_submenu_page(basename(__FILE__), 'Help'                 , 'Help'           , 'publish_posts', basename(__FILE__) . '/slidetabs_help'           , array($this, 'admin_page_help'));
	}				
	
	
	/*
	 * Resources and pre-processing for admin pages
	 */	
	function admin_init() {
		if ($this->slidetabs_is_current()) {
			wp_register_style('slidetabs-admin-css'     , $this->url('/css/slidetabs_admin.css'), array(), $this->version, 'screen');
			wp_register_style('slidetabs-preview-ui-css', $this->url('/css/jquery_ui-slidetabs_preview_dialog.css'));
			wp_register_style('slidetabs-dialog-ui-css' , $this->url('/css/jquery_ui-slidetabs_dialog.css'));
		
			wp_register_script('slidetabs-admin-js', $this->url('/js/slidetabs_admin.js'), array('jquery', 'media-upload'), $this->version, !$this->use_old_tinymce_editor);
			
			// declare variables with namespaces to use with the admin javascript file
			wp_localize_script('slidetabs-admin-js', 'stVars', array(
				'pluginURL'       => $this->url(),
				'ajaxURL'         => admin_url('admin-ajax.php'),
				'timthumbURL'     => plugins_url('/slidetabs/includes/timthumb/timthumb.php'),
				'oldEditor'       => ($this->use_old_tinymce_editor == true) ? 'true' : 'false',
				'oldMediaManager' => ($this->use_old_media_manager == true) ? 'true' : 'false'
			));
			
			wp_enqueue_style('slidetabs-admin-css');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('slidetabs-preview-ui-css');
			wp_enqueue_style('slidetabs-dialog-ui-css');
			
			// load the local jQuery version
			global $concatenate_scripts;
			$concatenate_scripts = false;
			wp_deregister_script('jquery');
			if ($this->use_old_media_manager == true) {
				wp_register_script('jquery', $this->url('/js/jquery-1.7.2.min.js'), false, '1.7.2');
			} else {
				wp_register_script('jquery', $this->url('/js/jquery-1.8.3.min.js'), false, '1.8.3');
			}
			
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-sortable');
			
			if ($this->use_old_media_manager == true) {
				wp_enqueue_script('thickbox');
				wp_enqueue_script('editor');
				wp_enqueue_script('media-upload');
				wp_enqueue_script('quicktags');
			} else {
				wp_enqueue_media();
			}
			
			if (isset($_POST) && !empty($_POST)) {
				switch($_POST['action']) {                              
					case 'create':
						$message_id = 1;
						if ($_POST['dynamic'] == 1) { $message_id = 2; }
					break;
					case 'edit':
						$message_id = 3;
					break;
				}
				
				if (in_array($_POST['action'], array('create', 'edit'))) {
					$slidetabs = $this->save($_POST);
					wp_redirect($this->action($slidetabs['dynamic'] == '1' ? '/slidetabs_dynamic' : '') . '&action=edit&id=' . $slidetabs['id'] . '&message=' . $message_id);
				}
			}				
		}
	}		
	
	
	/*
	 * Load the JavaScript for the admin interface 
	 */
	function admin_head() {
		wp_print_scripts('jquery');
		
		wp_enqueue_script('slidetabs-admin-js');
				
		if ($this->slidetabs_is_current()) {
			if ($this->use_old_tinymce_editor === true) {
				wp_tiny_mce(false, array('editor_selector' => 'tab_content'));
			}
		}
	}			
	
	
	/*
	 * Check if the current page is a SlideTabs plugin admin page
	 */	
	function slidetabs_is_current() {
		return (boolean) ((basename($_SERVER['PHP_SELF']) == 'admin.php') && (strpos($_GET['page'], basename(__FILE__)) !== false));
	}
	
	
	/*
	 * Create a path from the given values
	 */
	function create_path($path, $str) {
		if (isset($str) && !empty($str)) {
			$sep = "/" == substr($str, 0, 1) ? "" : "/";
			return $path . $sep . $str;
		} else {
			return $path;
		}
	}
	
	
	/*
	 * Get the plugin directory
	 */
	function dir($str = '') {
		$path = $this->create_path(WP_PLUGIN_DIR . '/' . basename(dirname( __FILE__ )), $str);	
		return $path;
	}
	
	
	/*
	 * Get the plugin URL
	 */
	function url($str = '') {
		$path = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));
				
		if ($this->plugin_settings['ssl_check'] == true) {
			if (isset($_SERVER['HTTPS']) && (boolean) $_SERVER['HTTPS'] === true) {
				$path = str_replace('http://', 'https://', $path);
			}
		}
		
		$path = $this->create_path($path, $str);	
		return $path;
	}
	
	
	/* 
	 * Get the URL for the specified action	
	 */
	function action($str = null) {
		$path = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=' . basename(__FILE__);
		
		if (isset($str) && !empty($str)) { return $path . $str; } 
		else { return $path; }
	}
	
	
	/*
	 * Display an admin-action message
	 */
	function display_message($static_message = 0) {
		if (isset($_GET['message']) || $static_message > 0) {			
			$message_id = $static_message > 0 ? $static_message : intval($_GET['message']);	
				
			$messages = array(        
				'<strong>SlideTabs created.</strong> You can now insert it into a <a href="' . get_bloginfo('wpurl') . '/wp-admin/edit.php">post</a> or <a href="' . get_bloginfo('wpurl') . '/wp-admin/edit.php?post_type=page">page</a>.',
				'<strong>Dynamic SlideTabs created.</strong> You can now insert them into a <a href="' . get_bloginfo('wpurl') . '/wp-admin/edit.php">post</a> or <a href="' . get_bloginfo('wpurl') . '/wp-admin/edit.php?post_type=page">page</a>.',
				'SlideTabs updated.',
				'SlideTabs deleted.',
				'Plugin settings updated.',
				'SlideTabs duplicated.'
			);						
			
			echo '<div id="message" class="updated fade below-h2"><p>' . $messages[($message_id-1)] . '</p></div>';
		}
	}
	
	
	/*
	 * Sanitize data using the wp_kses() method
	 */
	function sanitize($str = '') {
		if (!function_exists('wp_kses')) { require_once(ABSPATH . 'wp-includes/kses.php'); }
		
		global $allowedposttags;
		global $allowedprotocols;
		
		if (is_string($str)) { $str = htmlentities(stripslashes($str), ENT_QUOTES, 'UTF-8'); }
		
		$str = wp_kses($str, $allowedposttags, $allowedprotocols);
		
		return $str;
	}
	
	
	/*
	 * Generate a URL for the specified orderby value
	 */
	function orderby($orderby = 'title') {
		$order = 'ASC';
		
		$current_order = $order;
		if (isset($_GET['order']) && !empty($_GET['order'])) { $current_order = $_GET['order']; }
		
		$current_orderby = $orderby;
		if (isset($_GET['orderby']) && !empty($_GET['orderby'])) { $current_orderby = $_GET['orderby']; }
		
		$url = '&orderby=' . $orderby . '&order=';
		if ($current_orderby == $orderby) { $url .= $current_order == 'ASC' ? 'DESC' : 'ASC'; } 
		else { $url .= $order; }
		
		return $this->action($url);
	}
	
	
	/*
	 * Get the current orderby status
	 */
	function get_current_orderby($orderby = 'title') {
		$order = 'ASC';
		
		$current_order = $order;
		if (isset($_GET['order']) && !empty($_GET['order'])) { $current_order = $_GET['order']; }
		
		$current_orderby = 'title';
		if (isset($_GET['orderby']) && !empty( $_GET['orderby'])) { $current_orderby = $_GET['orderby']; }
		
		if ($current_orderby == $orderby) { return 'sorted ' . strtolower($current_order); } 
		else { return 'sortable asc'; }
	}
	
	
	/*
	 * Load all the SlideTabs entries
	 */
	function load($slidetabs_id = null, $orderby = 'title', $order = 'ASC') {
		$slidetabs = array();
		
		$query_params = array(
			'post_type'      => $this->post_type,          // look for the specific post_type
			'posts_per_page' => -1,                        // find all the posts
			'orderby'        => $orderby,                  
			'order'          => $order,                    
			'post__not_in'   => get_option('sticky_posts') // don't include sticky posts
		);

		if (isset($slidetabs_id)) {
			if (is_array($slidetabs_id)) {            			
				$query_params['post__in'] = $slidetabs_id;
			}
		}
		
		$slidetabs_posts = new WP_Query($query_params);
		
		foreach ((array) $slidetabs_posts->posts as $post) {
			$post_id = $post->ID;
			
			$dynamic_options = unserialize(get_post_meta($post_id, '_slidetabs_dynamic_options', true));
			$slidetabs_options = unserialize(get_post_meta($post_id, '_slidetabs_options', true));                
			
			$slidetabs[] = array(
				'id'                => $post_id,
				'dynamic' 			=> get_post_meta($post_id, '_slidetabs_is_dynamic', true),
				'title'             => get_the_title($post_id),
				'tabs_post_id'      => $post_id,
				'dynamic_options' 	=> $dynamic_options,
				'slidetabs_options' => $slidetabs_options,
				'template'          => get_post_meta($post_id, '_slidetabs_template', true),
				'new_format'        => get_post_meta($post_id, '_slidetabs_new_format', true),
				'created_at'        => $post->post_date,
				'updated_at'        => $post->post_modified
			);
		}						
		
		return $slidetabs;
	}
	
	/*
	 * Load a single SlideTabs entry
	 */
	function load_single($slidetabs_id = null) {
		$query = new WP_Query(array('p' => $slidetabs_id, 'post_type' => $this->post_type));
		$slidetabs_post = $query->posts[0];
		
		if (isset($slidetabs_post) && !empty($slidetabs_post)) {		
		
			$post_id = $slidetabs_post->ID;
			
			$dynamic_options = unserialize(get_post_meta($post_id, '_slidetabs_dynamic_options', true));
			$slidetabs_options = unserialize(get_post_meta($post_id, '_slidetabs_options', true));
											
			$slidetabs = array(
				'id'                => $post_id,
				'dynamic' 			=> get_post_meta($post_id, '_slidetabs_is_dynamic', true),
				'title'             => get_the_title($post_id),
				'tabs_post_id'      => $post_id,
				'dynamic_options' 	=> $dynamic_options,
				'slidetabs_options' => $slidetabs_options,
				'template'          => get_post_meta($post_id, '_slidetabs_template', true),
				'new_format'        => get_post_meta($post_id, '_slidetabs_new_format', true),
				'created_at'        => $slidetabs_post->post_date,
				'updated_at'        => $slidetabs_post->post_modified
			);
			
			return $slidetabs;
		} else {
			return '';
		}
	}
	
	
	/* 
	 * Get the tabs content for a specific entry
	 */
	function load_tabs($slidetabs_id) {
		$tab_posts = new WP_Query(array(
			'post_type'      => array($this->content_post_type, $this->ajax_post_type),
			'post_parent'    => $slidetabs_id,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post__not_in'   => get_option('sticky_posts')
		));				
		
		$tabs = array();
				
		foreach((array) $tab_posts->posts as $post) {
			$post_id = $post->ID;
			
			$tabs[] = array(
				'id'           => $post_id,
				'slidetabs_id' => $slidetabs_id,
				'tab_type'     => $post->post_type,
				'title'        => get_the_title($post_id),
				'ext_link'     => $post->post_content_filtered,
				'bg_url'       => get_post_meta($post_id, '_slidetabs_bg_url', true),
				'content'      => $post->post_content,
				'tab_order'    => $post->menu_order,
				'created_at'   => $post->post_date,
				'updated_at'   => $post->post_modified
			);
		}
		
		return $tabs;
	}
	
	
	/*
	 * Run the the_content filters on the tabs content
	 */
	function process_tab_content($content, $convert, $br, $editing = false, $new_format = '') {
		$content = stripslashes($content);
		if (empty($new_format) || $this->use_old_tinymce_editor) {
			$content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
		}
		
		if ($editing === false) { $content = do_shortcode($content); }
				
		//if (get_user_option('rich_editing') == 'true' || ($editing === false)) {
		if ($editing == false) {
			if ($convert != 'false') {
				$content = wpautop($content, $br); // wrap double line-breaks with paragraph tags	
			}
		}
		
		$content = str_replace(']]>', ']]&gt;', $content);
				
		return $content;
	}
	
	
	/*
	 * Get the SlideTabs options
	 */
	function get_option($slidetabs, $option) {
		$defaults = array(
			'ajaxCache'         => 'true',
			'ajaxError'         => 'Failed to load content',
			'ajaxSpinner'       => 'false',
			
			'autoplay'          => 'false',
			'autoplayClickStop' => 'false',
			'autoplayControls'  => 'false',
			'autoplayInterval'  => 5000,
			
			'autoHeight'        => 'false',
			'autoHeightSpeed'   => 300,			
			
			'buttonsFunction'   => 'slide',
			
			'contentAnim'       => 'slideH',
			'contentAnimSpeed'  => 600,
			'contentEasing'     => 'easeInOutExpo',
			
			'externalLinking'   => 'false',
			
			'responsive'        => 'false',
					
			'tabsAlignment'     => 'align_top',
			'tabsAnimSpeed'     => 300,								
			'tabsEasing'        => '',
			'tabsLoop'          => 'false',
			'tabsSaveState'     => 'false',
			'tabsScroll'        => 'false',
			'tabsToSlide'       => 1,
			
			'textConversion'    => 'pb',
			
			'totalHeight'       => '',
			'totalWidth'        => '',
			
			'touchSupport'      => 'false',
			
			'urlLinking'        => 'false',
			
			'useWebKit'         => 'true'
		);
		
		return (isset($slidetabs['slidetabs_options'][$option]) ? $slidetabs['slidetabs_options'][$option] : $defaults[$option]);
	}
	
	
	/*
	 * Get the dynamic SlideTabs options
	 */
	function get_dynamic_option($slidetabs, $option) {
		$defaults = array( 
			'type'                      => 'recent',
			'post_type'			        => 'posts',
			'order'         	        => 'DESC',
			'orderby'                   => 'date',
			'filter_by_category'        => 0,
			'filter_categories'	        => array(),
			'tab_content_type'	        => 'titles',
			'date_format'		        => 'F j, Y',
			'total'                     => 5,
			'cache_minutes'             => 30,
			'image_posts_only'          => '0',
			'image_source'              => 'content',
			'title_length'              => $this->dynamic_title_length,
			'title_length_with_image'   => $this->dynamic_title_length_with_image,
			'excerpt_length'            => $this->dynamic_excerpt_length,
			'excerpt_length_with_image' => $this->dynamic_excerpt_length_with_image,
			'validate_images'           => false,
			'max_image_width'           => '',
			'max_image_height'          => ''
		);
		
		return (isset($slidetabs['dynamic_options'][$option]) ? $slidetabs['dynamic_options'][$option] : $defaults[$option]);
	}
	
	
	/*
	 * Add new SlideTabs
	 */ 
	function admin_page_add_new() {
		global $slidetabs_class;

		$form_action = 'create';
		$default_tab_amount = 1;
			
		$templates = $this->get_templates();
		
		// set the default entry values
		$slidetabs = array( 
			'title'             => 'New SlideTabs',
			'slidetabs_options' => array(),
			'tabs_post_id'      => time(),
			'template'          => $this->default_template
		 );
	
		$tabs = array();		
		
		// set the default tab values
		for ($i = 1; $i <= $default_tab_amount; $i++) {
			$tabs[] = array(
				'title'        => '',
				'ext_link'     => '',
				'content'      => '',
				'tab_order'    => $i,
				'tabs_post_id' => $slidetabs['tabs_post_id']
			 );
		}
		
		// set the default options
		$slidetabs_params = array(
			'slidetabs-create_wpnonce' => wp_create_nonce('slidetabs-for-wordpress'),
			'action' => 'create',        
			'slidetabs_options' => array(
				'ajaxCache'         => $this->get_option($slidetabs, 'ajaxCache'),
				'ajaxError'         => $this->get_option($slidetabs, 'ajaxError'),
				'ajaxSpinner'       => $this->get_option($slidetabs, 'ajaxSpinner'),
				
				'autoplay'          => $this->get_option($slidetabs, 'autoplay'),
				'autoplayClickStop' => $this->get_option($slidetabs, 'autoplayClickStop'),
				'autoplayControls'  => $this->get_option($slidetabs, 'autoplayControls'),
				'autoplayInterval'  => $this->get_option($slidetabs, 'autoplayInterval'),
				
				'autoHeight'        => $this->get_option($slidetabs, 'autoHeight'),
				'autoHeightSpeed'   => $this->get_option($slidetabs, 'autoHeightSpeed'),
				
				'buttonsFunction'   => $this->get_option($slidetabs, 'buttonsFunction'),
				
				'contentAnim'       => $this->get_option($slidetabs, 'contentAnim'),
				'contentAnimSpeed'  => $this->get_option($slidetabs, 'contentAnimSpeed'),
				'contentEasing'     => $this->get_option($slidetabs, 'contentEasing'),
				
				'externalLinking'   => $this->get_option($slidetabs, 'externalLinking'),
				
				'responsive'        => $this->get_option($slidetabs, 'responsive'),
						
				'tabActive'         => $this->get_option($slidetabs, 1),
				
				'tabsAlignment'     => $this->get_option($slidetabs, 'tabsAlignment'),
				'tabsAnimSpeed'     => $this->get_option($slidetabs, 'tabsAnimSpeed'),
				'tabsEasing'        => $this->get_option($slidetabs, 'tabsEasing'),
				'tabsLoop'          => $this->get_option($slidetabs, 'tabsLoop'),
				'tabsSaveState'     => $this->get_option($slidetabs, 'tabsSaveState'),
				'tabsScroll'        => $this->get_option($slidetabs, 'tabsScroll'),
				'tabsToSlide'       => $this->get_option($slidetabs, 'tabsToSlide'),
				
				'textConversion'    => $this->get_option($slidetabs, 'textConversion'),
				
				'touchSupport'      => $this->get_option($slidetabs, 'touchSupport'),
				
				'urlLinking'        => $this->get_option($slidetabs, 'urlLinking'),
				
				'useWebKit'         => $this->get_option($slidetabs, 'useWebKit')
			),
			'template' => $slidetabs['template'],
			'title' => $slidetabs['title']
		);
		
		// save the tabs as an auto-draft
		$slidetabs = $this->save($slidetabs_params, 'auto-draft');
		
		for ($i = 0; $i < count($tabs); $i++) {
			$tabs[$i]['tabs_post_id'] = $slidetabs['id'];
		}
						
		// include the editor form
		include($this->dir('/includes/edit_form.php'));
	}
	
	
	/*
	 * Add dynamic SlideTabs
	 */
	function admin_page_add_dynamic() {
		global $slidetabs_class;
		$form_action = 'create';
		
		if (isset($_GET['action']) && !empty($_GET['action'])) {
			$form_action = $_GET['action'];
		}
		
		$default_tab_amount = 5;
		$templates = $this->get_templates('dynamic');
		$first_template = reset($templates); // get the first item from the $templates array
		$first_template = (isset($first_template['slug']) && !empty($first_template['slug'])) ? $first_template['slug'] : 'clean_dynamic';
		
		// get the available post types
		if (function_exists('get_post_types')) {
			$post_types_obj = get_post_types(array('public' => true), 'objects', 'and');
			
			$post_types = array();																																				
			foreach ($post_types_obj as $i => $post_type_obj) {
				$post_types[$i]['value'] = $post_type_obj->name;
				$post_types[$i]['label'] = $post_type_obj->labels->name;
			}
			
			if ($post_types['attachment']) { unset($post_types['attachment']); }
		} else {
			$post_types = array('posts' => array('value' => 'post', 'label' => 'Posts'), 'pages' => array('value' => 'page', 'label' => 'Pages'));
		}
		
		$categories = get_categories(array(
			'type'       => 'post',
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => false
		));
		
		$orderby = array(
			'Date'     => 'date',
			'Modified' => 'modified',
			'Title'    => 'title'			
		);
		
		$order = array(
			'Ascending'  => 'ASC',
			'Descending' => 'DESC'
		);
		
		$slidetabs = array( 
			'title'               => '',
			'slidetabs_options' => array(
				'autoplay'          => $this->get_option($slidetabs, 'autoplay'),
				'autoplayClickStop' => $this->get_option($slidetabs, 'autoplayClickStop'),
				'autoplayControls'  => $this->get_option($slidetabs, 'autoplayControls'),
				'autoplayInterval'  => ($this->get_option($slidetabs, 'autoplayInterval')),
				
				'autoHeight'        => $this->get_option($slidetabs, 'autoHeight'),
				'autoHeightSpeed'   => $this->get_option($slidetabs, 'autoHeightSpeed'),
				
				'buttonsFunction'   => $this->get_option($slidetabs, 'buttonsFunction'),
				
				'contentAnim'       => $this->get_option($slidetabs, 'contentAnim'),
				'contentAnimSpeed'  => $this->get_option($slidetabs, 'contentAnimSpeed'),
				'contentEasing'     => $this->get_option($slidetabs, 'contentEasing'),
				
				'externalLinking'   => $this->get_option($slidetabs, 'externalLinking'),
				
				'responsive'        => $this->get_option($slidetabs, 'responsive'),
				
				'tabsAlignment'     => $this->get_option($slidetabs, 'tabsAlignment'),
				'tabsAnimSpeed'     => $this->get_option($slidetabs, 'tabsAnimSpeed'),
				'tabsEasing'        => $this->get_option($slidetabs, 'tabsEasing'),
				'tabsLoop'          => $this->get_option($slidetabs, 'tabsLoop'),
				'tabsSaveState'     => $this->get_option($slidetabs, 'tabsSaveState'),
				'tabsScroll'        => $this->get_option($slidetabs, 'tabsScroll'),
				'tabsToSlide'       => $this->get_option($slidetabs, 'tabsToSlide'),
				
				'textConversion'    => $this->get_option($slidetabs, 'textConversion'),
				
				'touchSupport'      => $this->get_option($slidetabs, 'touchSupport'),
				
				'urlLinking'        => $this->get_option($slidetabs, 'urlLinking'),
				
				'useWebKit'         => $this->get_option($slidetabs, 'useWebKit')
			),
			'dynamic_options' => array(
				'type'                      => 'recent',
				'post_type'			        => 'posts',
				'order'                     => 'DESC',
				'orderby'                   => 'date',
				'filter_by_category'        => 0,
				'filter_categories'         => array(),
				'tab_content_type'          => 'titles',
				'date_format'		        => 'F j, Y',
				'total'                     => 5,
				'image_posts_only'          => '0',
				'image_source'              => 'content',
				'title_length'              => $this->dynamic_title_length,
				'title_length_with_image'   => $this->dynamic_title_length_with_image,
				'excerpt_length'            => $this->dynamic_excerpt_length,
				'excerpt_length_with_image' => $this->dynamic_excerpt_length_with_image,
				'max_image_width'           => '',
				'max_image_height'          => ''
			),
			'template'     => $first_template,
			'tabs_post_id' => time(),
			'dynamic'      => '1'
		);
		
		if (isset($_GET['id']) && !empty($_GET['id'])) {
			$slidetabs_id = intval($_GET['id']);
			$slidetabs = $this->load_single($slidetabs_id);
		}
		
		include($this->dir('/includes/add_edit_dynamic.php'));
	}
	
	
	/*
	 * Get the date format
	 */
	function slidetabs_date_format() {
		$date_format = $_POST['date'];
		echo date_i18n($date_format, time());
		exit;
	}
	
	
	/*
	 * Prepare the tab title
	 */
	function prepare_tab_title($title) {
		$tab_title_decoded = html_entity_decode(stripslashes($title), ENT_QUOTES, 'UTF-8');
		return $title = strip_tags($tab_title_decoded, '<span>');
	}
							
	
	/*
	 * Save the SlideTabs
	 */
	function save($post_params = null, $post_status = 'publish') {
		if (!isset($post_params)) { return false; }
		
		$action = $post_params['action'];
	
		// validate the nonce value
		if (!wp_verify_nonce($post_params['slidetabs-' . $action . '_wpnonce'], 'slidetabs-for-wordpress')) { return false; }
			
		$params = array();
		
		// validate the $_POST data		
		foreach ((array) $post_params as $key => $val) {        
			if (is_string($val)) {
				$params[$key] = $this->sanitize($val);
			} elseif (is_array($val)) {            
				foreach ((array) $val as $key1 => $val1) {
					if (is_array($val1)) {
						$sub_arr = array();
						foreach ((array) $val1 as $key2 => $val2) {
							$sanitized = $val2;
							switch ($key2) {
								case 'title':
								case 'ext_link':																								
								case 'content':
								case 'bg_url':
									$sanitized = html_entity_decode($this->sanitize($val2), ENT_QUOTES, 'UTF-8');
								break;
							}
							$sub_arr[$key2] = $sanitized;
						}
						$params[$key][$key1] = $sub_arr;
					} else {
						$params[$key][$key1] = $this->sanitize($val1);
					}
				}
			}
		}
					
		$slidetabs_params = array( 
			'title'        => $params['title'],
			'dynamic'      => $params['dynamic'],
			'tabs_post_id' => $params['tabs_post_id']
		);
		
		if ($params['dynamic'] == '1') {
			if (!isset($params['dynamic_options']['filter_by_category']) || !isset($params['dynamic_options']['filter_categories'])) {
				$params['dynamic_options']['filter_by_category'] = '0';
				$params['dynamic_options']['filter_categories'] = array();
			}
			$params['dynamic_options']['date_format'] = ($params['dynamic_options']['date_format'] == '') ? 'F j, Y' : $params['dynamic_options']['date_format'];
			$params['dynamic_options']['total'] = intval($params['dynamic_options']['total']);
			if ($params['dynamic_options']['total'] === 0) { $params['dynamic_options']['total'] = 1; }
			if (!isset($params['dynamic_options']['image_posts_only'])) { $params['dynamic_options']['image_posts_only'] = '0'; }									
			$params['dynamic_options']['title_length'] = intval($params['dynamic_options']['title_length']);
			$params['dynamic_options']['title_length_with_image'] = intval($params['dynamic_options']['title_length_with_image']);
			$params['dynamic_options']['excerpt_length'] = intval($params['dynamic_options']['excerpt_length']);
			$params['dynamic_options']['excerpt_length_with_image'] = intval($params['dynamic_options']['excerpt_length_with_image']);															
			$params['dynamic_options']['max_image_width'] = intval($params['dynamic_options']['max_image_width']);
			$params['dynamic_options']['max_image_height'] = intval($params['dynamic_options']['max_image_height']);
			if ($params['dynamic_options']['max_image_width'] === 0) { $params['dynamic_options']['max_image_width'] = ''; }
			if ($params['dynamic_options']['max_image_height'] === 0) { $params['dynamic_options']['max_image_height'] = ''; }
			
			// serialize the dynamic options before saving
			$slidetabs_params['dynamic_options'] = serialize($params['dynamic_options']);
		}
		
		$slidetabs_params['template'] = $params['template'];
		
		if (!isset($params['slidetabs_options']['ajaxCache'])) { $params['slidetabs_options']['ajaxCache'] = 'false'; }
			
		if (!isset($params['slidetabs_options']['autoplay'])) { $params['slidetabs_options']['autoplay'] = 'false'; }
		$params['slidetabs_options']['autoplayInterval'] = $params['slidetabs_options']['autoplayInterval'] * 1000;
		
		$params['slidetabs_options']['autoHeightSpeed'] = intval($params['slidetabs_options']['autoHeightSpeed']);
		
		$params['slidetabs_options']['contentAnimSpeed'] = intval($params['slidetabs_options']['contentAnimSpeed']);
		
		$params['slidetabs_options']['orientation'] = ($params['slidetabs_options']['tabsAlignment'] == 'align_top' || $params['slidetabs_options']['tabsAlignment'] == 'align_bottom') ? 'horizontal' : 'vertical';
		
		$params['slidetabs_options']['tabsAnimSpeed'] = intval($params['slidetabs_options']['tabsAnimSpeed']);
		if (!isset($params['slidetabs_options']['tabsScroll'])) { $params['slidetabs_options']['tabsScroll'] = 'false'; }
		$params['slidetabs_options']['tabsToSlide'] = (!isset($params['slidetabs_options']['tabsToSlide'])) ? 1 : intval($params['slidetabs_options']['tabsToSlide']);
		
		$params['slidetabs_options']['totalHeight'] = intval($params['slidetabs_options']['totalHeight']);
		$params['slidetabs_options']['totalWidth'] = (strtolower($params['slidetabs_options']['totalWidth']) == 'auto') ? strtolower($params['slidetabs_options']['totalWidth']) : intval($params['slidetabs_options']['totalWidth']);
		if ($params['slidetabs_options']['totalHeight'] === 0) { unset($params['slidetabs_options']['totalHeight']); }
		if ($params['slidetabs_options']['totalWidth'] === 0) { unset($params['slidetabs_options']['totalWidth']); }
		
		if (!isset($params['slidetabs_options']['touchSupport'])) { $params['slidetabs_options']['touchSupport'] = 'false'; }
		
		if (!isset($params['slidetabs_options']['useWebKit'])) { $params['slidetabs_options']['useWebKit'] = 'false'; }
				
		// serialize the options before saving
		$slidetabs_params['slidetabs_options'] = serialize($params['slidetabs_options']);
					
		// check the current action
		switch ($action) {
			case 'create':				
				if (isset($params['id'])) {
					$slidetabs_id = wp_update_post(array(
						'ID'           => $params['id'],
						'post_status'  => 'publish',
						'post_content' => '',
						'post_title'   => $slidetabs_params['title']
					));
				} else {
					// insert a new entry in the database
					$slidetabs_id = wp_insert_post(array(
						'post_content'   => '',
						'post_title'     => $slidetabs_params['title'],
						'post_status'    => $post_status,
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_type'      => $this->post_type
					));								
				}
											
				// insert new tab entries linked to the newly created SlideTabs if this is not a dynamic entry
            	if ($params['dynamic'] != '1') {
					foreach ((array) $params['tab'] as $tab) {
						$tab['title'] = $this->prepare_tab_title($tab['title']);
												
						$tab_id = wp_insert_post(array(
							'post_content'          => $tab['content'],
							'post_title'            => $tab['title'],
							'post_status'           => 'publish',
							'comment_status'        => 'closed',
							'ping_status'           => 'closed',							
							'post_content_filtered' => $tab['ext_link'],
							'post_parent'           => $slidetabs_id,
							'menu_order'            => $tab['tab_order'],
							'post_type'             => $tab['tab_type']
						));
												
						$this->update_content_bg($tab, $tab_id);
					}
				}
			break;
			
			case 'edit':
				$slidetabs_id = wp_update_post(array(
					'ID'           => $params['id'],
					'post_content' => "",
					'post_title'   => $slidetabs_params['title'],
				 ));
				
				if ($params['dynamic'] != '1') {
					// get the tab content associated with this entry
					$tabs = $this->load_tabs($slidetabs_id);
					
					$existing_tabs = array();
					$new_tabs = array();
	
					// create an array of id's of the current tabs associated with this entry
					foreach ((array) $tabs as $tab) {
						if (isset($tab['id']) && !empty($tab['id'])) {
							$existing_tabs[] = $tab['id'];
						}
					}
					
					foreach ((array) $params['tab'] as $tab ) {
						$tab['title'] = $this->prepare_tab_title($tab['title']);
						$tab['content'] = ($tab['tab_type'] == $this->ajax_post_type) ? strip_tags($tab['content']) : $tab['content']; // strip any html from the ajax link
						
						// compare each submitted tab to see if it already exists in the database
						if (isset($tab['id']) && !empty($tab['id'])) {
							 $tab_id = wp_update_post(array(
								'ID'                    => $tab['id'],
								'post_content'          => $tab['content'],
								'post_title'            => $tab['title'],								
								'post_content_filtered' => $tab['ext_link'],								
								'menu_order'            => $tab['tab_order']
							 ));
	
							// add the submitted tab id to an array to later compare against the previously existing tabs
							$new_tabs[] = $tab_id;
						} else {
							// if tab does not exist yet, add it to the database
							$tab_id = wp_insert_post(array(
								'post_content'          => $tab['content'],
								'post_title'            => $tab['title'],
								'post_status'           => 'publish',
								'comment_status'        => 'closed',
								'ping_status'           => 'closed',								
								'post_content_filtered' => $tab['ext_link'],								
								'post_parent'           => $slidetabs_id,
								'menu_order'            => $tab['tab_order'],
								'post_type'             => $tab['tab_type']
							));
							
							// add the id of the new tab to the comparison array
							$new_tabs[] = $tab_id;
						}
												
						$this->update_content_bg($tab, $tab_id);
					}
					
					// compare the array of tabs that existed prior to submission to the tabs that were submitted
					foreach ((array) $existing_tabs as $tab_id) {
						// if the previously existing tabs is not found in the submitted tab array, remove it from the database
						if (!in_array($tab_id, $new_tabs)) {
							wp_delete_post($tab_id, true);
						}
					}
				}
			break;
		}
		
		// update the meta data
		update_post_meta($slidetabs_id, '_slidetabs_options', $slidetabs_params['slidetabs_options']);
		update_post_meta($slidetabs_id, '_slidetabs_is_dynamic', $slidetabs_params['dynamic']);
		update_post_meta($slidetabs_id, '_slidetabs_template', $slidetabs_params['template']);
		if (isset($slidetabs_params['dynamic_options'])) { update_post_meta($slidetabs_id, '_slidetabs_dynamic_options', $slidetabs_params['dynamic_options']); }
		
		// flag the saved SlideTabs to use the new stored data format
		update_post_meta($slidetabs_id, '_slidetabs_new_format', true);
		
		// save the preload templates option
		$preload_templates = get_option('slidetabs_preload_templates', array());		
		if (isset($params['slidetabs_options']['preloadTemplate'])) {
			$preload_templates[$slidetabs_id] = array($params['template'], ($params['dynamic']) ? 'dynamic' : 'static');
		} else {
			unset($preload_templates[$slidetabs_id]);
		}
		update_option('slidetabs_preload_templates', $preload_templates);		
			
		if ($post_status == 'publish') { $this->delete_drafts(); }
		
		$slidetabs = $this->load_single($slidetabs_id);
		
		return $slidetabs;
	}
	
	
	/*
	 * Save/delete the tab's content background
	 */
	function update_content_bg($tab, $tab_id) {
		if (!empty($tab['bg_url'])) {
			if ($tab['bg_url'] == 'false') {
				delete_post_meta($tab_id, '_slidetabs_bg_url'); 
			} else {
				update_post_meta($tab_id, '_slidetabs_bg_url', $tab['bg_url']);
			}
		}	
	}
	
	
	/*
	 * Delete auto-draft entries
	 */
	function delete_drafts() {
		$table = $this->db->prefix . 'posts';
		$drafts	= $this->db->get_results("SELECT " . $table . ".* FROM " . $table . " WHERE 1=1 AND " . $table . ".post_type = 'slidetabs' AND (" . $table . ".post_status = 'auto-draft') ORDER BY " . $table . ".post_date DESC");
		
		foreach ($drafts as $draft) {
			wp_delete_post($draft->ID, true);
		}
	}
	
	
	/*
	 * Manage the SlideTabs entry
	 */
	function admin_page_manage() {
		global $slidetabs_class;
		
		$action = 'manage';
		
		if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
			$action = $_REQUEST['action'];
			$slidetabs_id = $_REQUEST['id'];
		}
		
		// check the current action	
		if ($action == 'edit') {
				global $post_ID;
				
				$form_action = 'edit';				
				$post_ID = $_GET['id']; // sets the post id for editor
							
				$templates = $this->get_templates();
				$slidetabs = $this->load_single($slidetabs_id);
				
				if ($slidetabs['tabs_post_id'] == '0') { $slidetabs['tabs_post_id'] = time(); }
				
				// get the tab content
				$tabs = $this->load_tabs($slidetabs_id);		

				for ($i = 0; $i < count($tabs); $i++) {
					$tabs[$i]['tabs_post_id'] = $slidetabs['tabs_post_id']; // assign the tabs_post_id to each tab
					$tabs[$i]['clean_title'] = strip_tags($tabs[$i]['title']);
				}
								
				include($this->dir('/includes/edit_form.php'));
		} else {
			if ($action == 'duplicate') {
				if (check_admin_referer('slidetabs_duplicate_' . $slidetabs_id)) {
					$slidetabs = $this->load_single($slidetabs_id);
										
					// save the duplicated tabs entry
					$duplicated_id = wp_insert_post(array(
						'post_content'   => '',
						'post_title'     => $slidetabs['title'] . ' - Copy',
						'post_status'    => 'publish',
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
						'post_type'      => $this->post_type
					));
								
					if ($slidetabs['dynamic'] != '1') {
						$tabs = $this->load_tabs($slidetabs_id);
												
						// save the duplicated tab content
						foreach ((array) $tabs as $tab) {
							$tab_id = wp_insert_post(array(
								'post_content'          => $tab['content'],
								'post_title'            => $tab['title'],
								'post_status'           => 'publish',
								'comment_status'        => 'closed',
								'ping_status'           => 'closed',							
								'post_content_filtered' => $tab['ext_link'],
								'post_parent'           => $duplicated_id,
								'menu_order'            => $tab['tab_order'],
								'post_type'             => $tab['tab_type']
							));
														
							if (!empty($tab['bg_url'])) {
								add_post_meta($tab_id, '_slidetabs_bg_url', $tab['bg_url']);
							}
						}
					} else {
						// serialize the dynamic options before saving
						$slidetabs_dynamic_options = serialize($slidetabs['dynamic_options']);
					}
					
					// serialize the options before saving
					$slidetabs_options = serialize($slidetabs['slidetabs_options']);	
				
					// Create the meta data for the duplicated tabs
					add_post_meta($duplicated_id, '_slidetabs_options', $slidetabs_options);
					add_post_meta($duplicated_id, '_slidetabs_is_dynamic', $slidetabs['dynamic']);
					add_post_meta($duplicated_id, '_slidetabs_template', $slidetabs['template']);
					if (isset($slidetabs['dynamic_options'])) { add_post_meta($duplicated_id, '_slidetabs_dynamic_options', $slidetabs_dynamic_options); }
				} else {
					return false;
				}			
			}
									
			if ($action == 'delete') {
				if (check_admin_referer('slidetabs_delete_' . $slidetabs_id)) {
					// delete the entry
					wp_delete_post($slidetabs_id, true);
					
					// delete the tab content associated with the entry
					$tabs = $this->load_tabs($slidetabs_id);
					
					foreach ($tabs as $tab) {
						wp_delete_post($tab['id'], true);
					}								
				} else {
					return false;
				}
			}
			
			$orderby = 'title';
			
			if (isset($_GET['orderby']) && !empty($_GET['orderby'])) {
				$orderby = $_GET['orderby'];
			}
			
			$order = 'ASC';
			
			if (isset($_GET['order']) && !empty($_GET['order'])) {
				$order = $_GET['order'];
			}								
			
			// get all the SlideTabs entries
			$slidetabs = $this->load(null, $orderby, $order);            			
							
			include($this->dir('/includes/manage.php'));
		}
	}
	
	
	/*
	 * Global plugin Settings
	 */
	function admin_page_plugin_settings() {
		global $slidetabs_class;
		
		$static_message = 0;					
		
		if (isset($_POST) && !empty($_POST)) {
			if (!wp_verify_nonce($_POST['slidetabs-plugin-settings_wpnonce'], 'slidetabs-for-wordpress')) {				
				return false;
			}						

			$settings = array( 
				'enqueue_jquery'            => isset($_POST['enqueue_jquery'])            ? true : false,
				'enqueue_jquery_easing'     => isset($_POST['enqueue_jquery_easing'])     ? true : false,
				'enqueue_jquery_mousewheel' => isset($_POST['enqueue_jquery_mousewheel']) ? true : false				
			);

			update_option('slidetabs_plugin_settings', $settings);
			
			$slidetabs_class->plugin_settings = get_option('slidetabs_plugin_settings', array( 
				'enqueue_jquery'            => true,
				'enqueue_jquery_easing'     => true,
				'enqueue_jquery_mousewheel' => true
			));
			
			$static_message = 5;					
		}
		
		include($this->dir('/includes/plugin_settings.php'));
	}
	
	
	/*
	 * Create the modal window for the embed link
	 */
	function embed_dialog() {				
		if ($this->slidetabs_is_current()) {
			include($this->dir('/includes/embed_dialog.php'));
		}
	}
	
	
	/*
	 * Set custom TinyMCE options
	 */
	function tinymce_set_options($initArray) {
		// fail silently for WordPress 3.3+ since the new wp_editor() command does not require this modification
		if ($this->use_old_tinymce_editor === false) {
			return $initArray;
		}
		
		if ($this->slidetabs_is_current()) {
		 	$initArray['editor_selector'] = 'tab_content';
        	$initArray['mode'] = 'specific_textareas';
			$initArray['theme_advanced_buttons1'] = 'bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,justifyleft, justifycenter,justifyright,justifyfull,|,link,unlink,wp_more,|,spellchecker,wp_adv'; 
		}
		
		return $initArray;
	}
	
	
	/*
	 * Setup the TinyMCE button
	 */
	function tinymce_add_buttons() {
		// return false if the user does not have WYSIWYG editing privileges
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) { return false; }
	
		// only load if the user is on a admin editing post/page
		if (in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php'))) {
			// register the modal window stylesheet
			wp_register_style('slidetabs-dialog-ui-css', $this->url('/css/jquery_ui-slidetabs_dialog.css'));
			
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('slidetabs-tinymce-dialog', $this->url('/js/slidetabs_tinymce_dialog.js'), array('jquery-ui-dialog'), $this->version, true);
			
			wp_enqueue_style('slidetabs-dialog-ui-css');
			
			if (get_user_option('rich_editing') == 'true') {
				add_filter('mce_external_plugins', array($this, 'tinymce_add_plugin'));
				add_filter('mce_buttons', array($this, 'tinymce_register_button'));
			}
		}
	}
	
	
	/*
	 * Add the TinyMCE plugin to the plugins list
	 */
	function tinymce_add_plugin($plugin_array) {
		if (!$this->slidetabs_is_current()) {
			$plugin_array['slidetabs'] = $this->url('/js/tinymce3/editor_plugin.js');
		}
	
		return $plugin_array;
	}
	
	
	/*
	 * Create the modal window for the TinyMCE plugin
	 */
	function tinymce_plugin_dialog() {
		// only render the modal window if the user is on the post/page editing admin pages
		if (in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php'))) {
			global $slidetabs_class;
			
			$slidetabs = $this->load();
			
			include($this->dir('/includes/tinymce_dialog.php'));
		}
	}		
	
	
	/*
	 * Add the SlideTabs button to the TinyMCE interface
	 */
	function tinymce_register_button($buttons) {
		array_push($buttons, 'separator', 'slidetabs');
		return $buttons;
	}
	
	
	/*
	 * Check which SlideTabs are included on the page
	 */
	function wp_hook() {
		global $posts, $slidetabs_template_included;
		
		//$slidetabs_template_included[$this->default_template] = true;
		$slidetabs_template_included = array(); // Make sure this is an array
		
		if (isset($posts) && !empty($posts)) {
			$slidetabs_ids = array();
					
			// look for SlideTabs in $posts
			foreach ((array) $posts as $post) {
				$matches = array();
				preg_match_all('/\[slidetabs( ([a-zA-Z0-9]+)\=\'([a-zA-Z0-9\%\-_\.]+)\')*\]/', $post->post_content, $matches);
				
				if (!empty($matches[0])) {
					foreach($matches[0] as $match) {
						$str = $match;
						$str_pieces = explode( ' ', $str);
						foreach($str_pieces as $piece) {
							$attrs = explode('=', $piece);
							if ($attrs[0] == 'id') {								
								$slidetabs_ids[] = intval(str_replace( "'", '', $attrs[1]));
							}
						}
					}
				}
			}
			
			if (!empty($slidetabs_ids)) {
				// load SlideTabs used on this URL by passing the array of id's
				$slidetabs = $this->load($slidetabs_ids);
				
				// loop through the SlideTabs used on this page and add their templates to the $slidetabs_template_included array for later use
				foreach((array) $slidetabs as $slidetab) {
					$template_slug = isset($slidetab['template']) && !empty($slidetab['template']) ? $slidetab['template'] : $this->default_template;
					$slidetabs_template_included[$template_slug] = ($slidetab['dynamic'] == '1') ? 'dynamic' : 'static';
				}
			}
		}
		
		// get the option with templates that should be preloaded
		$preload_templates = get_option('slidetabs_preload_templates', array());
		
		foreach($preload_templates as $template) {
			if (!array_key_exists($template[0], $slidetabs_template_included)) {
				// add the template to the global variable if it's not already added
				$slidetabs_template_included[$template[0]] = $template[1];
			}
		}
	}
	
	/*
	 * Print the template files
	 */
	function print_styles() {
		global $slidetabs_template_included;
				
		foreach ((array) $slidetabs_template_included as $template_slug => $val) {
			if ($val == 'dynamic') {
				$template = $this->get_dynamic_template($template_slug);
			} else {
				$template = $this->get_template($template_slug);
			}
			
			$id = 'slidetabs_template-' . $template_slug;
			$version = isset($template['meta']['Version']) && !empty($template['meta']['Version']) ? $template['meta']['Version'] : $this->version;
			
			wp_register_style($id, $template['url'], false, $version);
			wp_enqueue_style($id);
		}
	}
	
	/*
	 * Print the SlideTabs related JavaScript files
	 */	
	function print_scripts() {
		global $slidetabs_footer_scripts;
		global $slidetabs_template_included;
		global $is_IE;
		
		// only print the scripts if the tabs are included on the page		
		if (in_array('plugin', $this->scripts_to_load)) {
			
			wp_register_script('cookie-js', $this->url('/js/plugins/jquery.cookie.min.js'));
			wp_print_scripts('cookie-js');
			
			if ($this->plugin_settings['enqueue_jquery_mousewheel']) { 
				wp_register_script('mousewheel-js', $this->url('/js/plugins/jquery.mousewheel.min.js'), false, '3.0.6');
				wp_print_scripts('mousewheel-js'); 
			}
			
			if ($this->plugin_settings['enqueue_jquery_easing']) { 
				wp_register_script('easing-js', $this->url('/js/plugins/jquery.easing.1.3.js'), false, '1.3');
				wp_print_scripts('easing-js');
			}
						
			wp_register_script('slidetabs-js', $this->url('/js/jquery.slidetabs.min.js'), false, $this->version);
			wp_print_scripts('slidetabs-js');
						
			if (in_array('touch', $this->scripts_to_load)) {
				wp_register_script('slidetabs-touch-js', $this->url('/js/jquery.slidetabs.touch.min.js'), array('slidetabs-js'), $this->version);
				wp_print_scripts('slidetabs-touch-js');
			}
						
			echo $slidetabs_footer_scripts; // print the plugin activation code
			
			foreach ((array) $slidetabs_template_included as $template_slug => $val) {
				if ($val == 'static') { $template = $this->get_template($template_slug); }
				else { $template = $this->get_dynamic_template($template_slug); }
				
				if (isset($template['script_url'])  && !empty($template['script_url'])) {
					wp_register_script('slidetabs-template-js-' . $template_slug, $template['script_url'], array('slidetabs-js'), $this->version);
					wp_print_scripts('slidetabs-template-js-' . $template_slug);
				}
			}
		}
	}
	
	
	/*
	 * Process the SlideTabs shortcode
	 */
	function slidetabs_shortcode($atts) {
		extract(shortcode_atts(array(
			'id'     => false,
			'width'  => NULL,
			'height' => NULL
		), $atts));        
		
		if ($id !== false) { return $this->process_template($id, array('width' => $width, 'height' => $height)); } 
		else { return ''; }		
	}
	
	
	/*
	 * Get the meta data for the available templates	
	 */
	function get_templates($type = 'static') {
		$templates = array();
		$all_template_files = array();
		$folder = ($type == 'static') ? '/templates' : '/templates_dynamic';
		
		$template_files = glob($this->dir($folder . '/*/template.css'));
		
		foreach ((array) $template_files as $template_file) {
			$key = basename(dirname($template_file));
			$all_template_files[$key] = $template_file;
		}
		
		foreach ((array) array_values($all_template_files) as $template_file) {
			if (is_readable($template_file)) {
				$template_meta = $this->get_template_meta($template_file);				
				$templates[$template_meta['slug']] = $template_meta;
			}
		}
		
		return $templates;
	}
	
	
	/*
	 * Get a specific template
	 */
	function get_template($name = 'clean') {
		$template_file = glob($this->dir('/templates/' . $name . '/template.css'));
		
		$template = $this->get_template_meta($template_file[0]);
		
		return $template;
	}
	
	
	/*
	 * Get a specific dynamic template
	 */
	function get_dynamic_template($name = 'clean_rounded') {
		$template_file = glob($this->dir('/templates_dynamic/' . $name . '/template.css'));
		
		$template = $this->get_template_meta($template_file[0]);
		
		return $template;
	}
	
	
	/*
	 * Process template meta data from a template file
	 */
	function get_template_meta($template_file) {
		$template_data = file_get_contents($template_file);
		$template_folder = dirname($template_file);
		$template_slug = basename($template_folder);
		
		// get the meta data from the css file
		$meta_raw = substr($template_data, strpos($template_data, '/*') + 2);
		$meta_raw = trim(substr($meta_raw, 0, strpos($meta_raw, '*/')));
		
		if (!empty($meta_raw)) {
			$template_meta = array();						
					
			// create an array from the raw meta data
			foreach(preg_split("/(\r?\n)/", $meta_raw) as $row) {
				$key_val = explode(':', $row);
				$template_meta[trim($key_val[0])] = trim($key_val[1]);
			}						
			
			$template_url = site_url(str_replace(ABSPATH, '', $template_folder));
			
			$template = array(
				'url'       => $template_url . '/template.css',
				'thumbnail' => $template_url . '/thumbnail.jpg',
				'slug'      => $template_slug,
				'meta'      => $template_meta
			);
						
			if (file_exists($template_folder . '/js/template.js')) { $template['script_url'] = $template_url . '/js/template.js'; }
			if (file_exists($template_folder . '/layout.thtml')) {
				$template['dynamic_layout'] = array();
				
				foreach (glob($template_folder . '/layout.thtml') as $dyn_template) {
					$dyn_template_data = file_get_contents($dyn_template);
					$dyn_template_raw = substr($dyn_template_data, strpos($dyn_template_data, '/*') + 4);
					$dyn_template_raw = trim(substr($dyn_template_raw, 0, strpos($dyn_template_raw, '*/')));
					
					$dyn_template_meta = array();
					$dyn_template_meta['slug'] = str_replace('.thtml', '', basename($dyn_template));
					$dyn_template_meta['file'] = $dyn_template;
					
					foreach (explode("\n", $dyn_template_raw) as $row) {
						$key_val = explode(":", $row);
						$dyn_template_meta[trim($key_val[0])] = trim($key_val[1]);
					}
					
					$template['dynamic_layout'] = $dyn_template_meta;
				}
			}
		}
		
		return $template;
	}
	
	
	/*
	 * Create the HTML for the CSS tags
	 */
	function get_template_css($template) {
		$version = isset($template['meta']['Version']) && !empty($template['meta']['Version']) ? $template['meta']['Version'] : $this->version;
		
		$template_css_tags = '<link rel="stylesheet" type="text/css" href="' . $template['url'] . '?v=' . $version . '" media="screen" />';
		
		return $template_css_tags;
	}
	
	
	/*
	 * Process template data
	 */
	function process_template($slidetabs_id, $dimensions = array('width' => NULL, 'height' => NULL), $ajax = false) {
		global $slidetabs_template_included, $slidetabs_options_json, $slidetabs_footer_scripts;
		
		// load the SlideTabs entry
		$slidetabs = $this->load_single($slidetabs_id);
			
		if (isset($slidetabs) && !empty($slidetabs)) {
			$is_dynamic = (boolean) $slidetabs['dynamic'];
			
			// set the option to convert extra line breaks to HTML <br /> tags
			$br = ($slidetabs['slidetabs_options']['textConversion'] == 'p') ? false : true;
			
			if ($is_dynamic === true) {
				// get the template
				$template = $this->get_dynamic_template((isset($slidetabs['template']) && !empty($slidetabs['template'])) ? $slidetabs['template'] : 'clean_dynamic');
				
				$meta_title_set = false;
								
				$args = array(
					'post_type'      => $slidetabs['dynamic_options']['post_type'],
					'post_status'    => 'publish',
					'posts_per_page' => $slidetabs['dynamic_options']['total']
				);
				
				// check if post filtering has been enabled	
				if ($slidetabs['dynamic_options']['filter_by_category'] == '1') {
					$args['cat'] = implode(',', $slidetabs['dynamic_options']['filter_categories']);
				}
				
				switch ($slidetabs['dynamic_options']['type']) {
					case 'all':
						$args['orderby'] = $slidetabs['dynamic_options']['orderby'];
						$args['order']   = $slidetabs['dynamic_options']['order'];
					break;
										
					case 'featured':
						$args['meta_key']   = '_slidetabs_post_featured';
						$args['meta_value'] = '1';
						$args['orderby']    = $slidetabs['dynamic_options']['orderby'];
						$args['order']      = $slidetabs['dynamic_options']['order'];
					break;					
				}
												
				$dynamic_posts = new WP_Query($args);
				
				$tabs = array();
				foreach($dynamic_posts->posts as $post) {
					$post_id = $post->ID;
					
					$tab = array();
					$tab_nodes = array();
										
					$post_content = $post->post_content;
					$post_excerpt = false;
					
					if (!empty($post->post_excerpt)) {
						$post_excerpt = $post->post_excerpt;
					}
					
					switch($this->get_dynamic_option($slidetabs, 'image_source')) {
						case 'none':
							$tab_nodes['image_src'] = NULL;
						break;
						case 'featured':
							if (function_exists('get_post_thumbnail_id')) {
								$featured_image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'single-post-thumbnail');
							
								if ($featured_image) {
									$title = explode('.', basename($featured_image[0]));
									
									$tab_nodes['image_src']    = $featured_image[0];
									$tab_nodes['image_title']  = $title[0];
									$tab_nodes['image_width']  = $featured_image[1];
									$tab_nodes['image_height'] = $featured_image[2];
								}
							}
						break;
						default:
						case 'content':
							$content_image = $this->parse_html_for_images($post_content, $this->get_dynamic_option($slidetabs, 'validate_images'));
							
							if ($content_image) {
								$tab_nodes['image_src']    = $content_image['src'];
								$tab_nodes['image_title']  = $content_image['title'];
								$tab_nodes['image_width']  = $content_image['width'];
								$tab_nodes['image_height'] = $content_image['height'];
							}
						break;
					}
					
					$image_posts_only = false;
										
					if ($tab_nodes['image_src'] != NULL) {
						// set the image aspect ratio
						$tab_nodes = $this->calc_image_aspect_ratio($slidetabs['dynamic_options']['max_image_width'], $slidetabs['dynamic_options']['max_image_height'], $tab_nodes);
						
						$title_length = $slidetabs['dynamic_options']['title_length_with_image'];
						$excerpt_length = $slidetabs['dynamic_options']['excerpt_length_with_image'];
					} else {
						$title_length = $slidetabs['dynamic_options']['title_length'];
						$excerpt_length = $slidetabs['dynamic_options']['excerpt_length'];
						
						if ($slidetabs['dynamic_options']['image_posts_only'] == '1') { $image_posts_only = true; }
					}
					
					$tab_nodes['author']     = get_the_author_meta('display_name', $post->post_author);
					$tab_nodes['author_url'] = get_the_author_meta('user_url', $post->post_author);
					$tab_nodes['categories'] = $this->get_post_category_links($post_id);
					$tab_nodes['permalink']  = get_permalink($post_id);
					$tab_nodes['tags']       = $this->get_post_tag_links($post_id);
					$tab_nodes['timesince']  = human_time_diff(get_the_time('U', $post_id), current_time('timestamp'));
					$tab_nodes['timestamp']  = get_the_time('U', $post_id);
					$tab_nodes['title']      = html_entity_decode(get_the_title($post_id), ENT_QUOTES, 'UTF-8');
					$tab_nodes['type']       = $slidetabs['dynamic_options']['type'];
					$tab_nodes['date']       = date_i18n(get_option('date_format'), $tab_nodes['timestamp']);
					$tab_nodes['time']       = date_i18n(get_option('time_format'), $tab_nodes['timestamp']);
					
					if ($post_excerpt === false) {
						$the_excerpt = strip_shortcodes($post_content);
					} else {
						$the_excerpt = $post_excerpt;                    	
					}
					
					$tab_title = $tab_nodes['title']; // set the tab title
					
					$tab_nodes['title'] = !empty($tab_nodes['title']) ? $this->prepare_dynamic_title($tab_nodes['title'], $title_length) : '(no title)'; // set the content title
					$tab_nodes['excerpt'] = $this->prepare_excerpt($the_excerpt, $excerpt_length, $slidetabs['slidetabs_options']['textConversion'], $br);
										
					// process content nodes through template to create the content
					ob_start();
		
						foreach ($tab_nodes as $node => $val) {
							$$node = $val;
						}
												
						if ($image_posts_only) {
							// keep looking if no image is found in the post
							continue;
						} else {
							include($template['dynamic_layout']['file']); // include the dynamic layout file
							$tab['content'] = ob_get_contents();
						}
		
						foreach ($tab_nodes as $node => $val) {
							$$node = null;
						}
		
					ob_end_clean();
					
					$tab['timestamp'] = $tab_nodes['timestamp'];
					
					if ($slidetabs['dynamic_options']['tab_content_type'] == 'dates') {
						$tab['title'] = date_i18n($slidetabs['dynamic_options']['date_format'], $tab['timestamp']);
					} else {
						// use the custom meta title if one has been set
						$meta_title = get_post_meta($post_id, '_slidetabs_tab_title', true);
						
						if (!empty($meta_title)) {
							$tab['title'] = $meta_title;
							$meta_title_set = true;
						} else {
							$tab['title'] = $tab_title;
						}
					}
					
					$tabs[] = $tab;
				}
				
				// sort the tabs array if a custom title has been set				
				if ($slidetabs['dynamic_options']['orderby'] == 'title' && $meta_title_set == true) {
					$dir = ($slidetabs['dynamic_options']['order'] == 'ASC') ? SORT_ASC : SORT_DESC;
					
					$this->sort_dynamic_tabs_array($tabs, 'title', $dir);
				}
			} else {			
				// get the template
				$template = $this->get_template((isset($slidetabs['template']) && !empty($slidetabs['template'])) ? $slidetabs['template'] : $this->default_template);
				
				// get the tabs content
				$tabs = $this->load_tabs($slidetabs_id);
			}
			
			// decide what javascript files will need to be included in public view based on the settings
			if (!in_array('plugin', $this->scripts_to_load)) {
				array_push($this->scripts_to_load, 'plugin');
			}
			if ($slidetabs['slidetabs_options']['touchSupport'] == 'true') {
				array_push($this->scripts_to_load, 'touch');
			}
			
			// create the HTML markup
			$template_str = $this->create_markup($slidetabs, $tabs, $br);
			
			if ($dimensions['height'] !== NULL) { $slidetabs['slidetabs_options']['totalHeight'] = $dimensions['height']; }
			if ($dimensions['width'] !== NULL) { $slidetabs['slidetabs_options']['totalWidth'] = $dimensions['width']; }
			
			// create the plugin json string
			$slidetabs_options_json = "{ ";
			$sep = "";
						
			foreach ((array) $slidetabs['slidetabs_options'] as $key => $val) {
				$slidetabs_options_json.= $sep . $key . ": ";

				if ($val == 'true' || $val == 'false') { $slidetabs_options_json.= $val; }
				elseif ($key == 'totalHeight' || $key == 'totalWidth') { $slidetabs_options_json.= (string) "'{$val}'"; }				
				elseif (is_numeric($val)) { $slidetabs_options_json.= $val; }
				else { $slidetabs_options_json.= (string) "'{$val}'"; }
				
				$sep = ", ";
			}
			$slidetabs_options_json.= " }";
							
            $slidetabs_footer_scripts.= '<script type="text/javascript">';
			$slidetabs_footer_scripts.= 'jQuery(document).ready(function() {';
			$slidetabs_footer_scripts.= 'jQuery("#slidetabs_' . $slidetabs_id . '").slidetabs(' . $slidetabs_options_json . ');';
            $slidetabs_footer_scripts.= '});';
			$slidetabs_footer_scripts.= '</script>';
							
			if (!isset($slidetabs_template_included[$template['slug']])) {
				if ($ajax == true) {
					global $ajaxFiles;
					$ajaxFiles = array(
						'css' => $template['url'],
						'js'  => isset($template['script_url']) ? $template['script_url'] : ''
					);
				} else {
					$slidetabs_template_included[$template['slug']] = ($is_dynamic === true) ? 'dynamic' : 'static';
						
					$template_css_tags = $this->get_template_css($template);
					$template_str = $template_css_tags . $template_str;
				}
			}
		} else {
			$template_str = '';
		}
		
		return $template_str;
	}
	
	
	/*
	 * Get the post category links
	 */
	function get_post_category_links($post_id) {
		$post_catgs = wp_get_post_categories($post_id);
		$catgs = '';
							
		foreach($post_catgs as $c) {
			$catg = get_category($c);
			$catgs .= '<a href="' . get_category_link($c) . '">' . $catg->name . '</a>, ';
		}
		
		return rtrim($catgs, ', ');
	}
	
	
	/*
	 * Get the post tag links
	 */
	function get_post_tag_links($post_id) {
		$post_tags = wp_get_post_tags($post_id);
		$tags = '';
		
		if ($post_tags != NULL) {
			foreach ($post_tags as $tag) {
				$tags .= '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a>, ';
			}
		}
		
		return rtrim($tags, ', ');
	}					
	
	
	/*
	 * Sort the dynamic tabs array
	 */
	function sort_dynamic_tabs_array(&$arr, $col, $dir = SORT_ASC) {
		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}
	
		array_multisort($sort_col, $dir, $arr);
	}
	
	
	/*
	 * Create the HTML markup
	 */
	function create_markup($slidetabs, $tabs, $br) {
		$name = (isset($slidetabs['template']) && !empty($slidetabs['template'])) ? $slidetabs['template'] : $this->default_template;
		
		if (isset($slidetabs['slidetabs_options']['totalWidth'])) {
			$width = ($slidetabs['slidetabs_options']['totalWidth'] == 'auto') ? '100%' : $slidetabs['slidetabs_options']['totalWidth'] . 'px';
			$width = ' style="width:' . $width . ';"';
		}
		
		$inc = 1;
		
		$template_str = '<div id="slidetabs_' . $slidetabs['id'] . '" class="slidetabs ' . $name . ' ' . $name . '-' . $slidetabs['slidetabs_options']['orientation'] . ' ' . $slidetabs['slidetabs_options']['tabsAlignment'] . '" ' . $width . '><div class="st_tabs"><a href="#" class="st_prev">prev</a><a href="#" class="st_next">next</a><div class="st_tabs_wrap"><ul class="st_tabs_ul">';		
		$content = '';
				
		foreach ((array) $tabs as $tab) {
			if ($inc == 1) { $first_li = 'st_li_first'; $first_tab = ' st_tab_first';  $first_tab_view = ' st_view_first'; }
			else { $first_li = ''; $first_tab = '';  $first_tab_view = ''; }
			
			if ($inc == $slidetabs['slidetabs_options']['tabActive']) { $active = ' st_tab_active'; $active_li = ' st_li_active'; }
			else { $active = ''; $active_li = ''; }
			
			if ($inc == count($tabs)) { $last_li = 'st_li_last'; $last_tab = ' st_tab_last'; }
			else { $last_li = ''; $last_tab = ''; }
			
			$li_class = ($first_li !== '' || $active_li !== '' || $last_li !== '') ? ' class="' . $first_li . $last_li . $active_li . '"' : '';
						
			if ($tab['tab_type'] == $this->ajax_post_type) {
				$href = $tab['content'];
				$tab['content'] = '';
				$data_attr = ' data-target="' . $tab['ext_link'] . '"';
			} else {
				if (isset($tab['ext_link'])) {
					$href = '#' . $tab['ext_link'];
				} else {
					$href = '#tab-' . $inc;
					$tab['ext_link'] = 'tab-' . $inc;
				}
								
				$data_attr = '';
			}
			
			// create the tab markup
			$template_str.= '<li' . $li_class . '><a href="'. $href .'" rel="' . $tab['ext_link'] . '"' . $data_attr . ' class="st_tab st_tab_' . $inc . $first_tab . $active . $last_tab . '">'. $tab['title'] .'</a></li>';
			
			// create the content markup
			$bg = (isset($tab['bg_url']) && $tab['bg_url'] !== '') ? ' style="background-image:url(' . $tab['bg_url'] . ');"' : '';
			$content.= '<div class="' . $tab['ext_link'] . ' st_view' . $first_tab_view . '"' . $bg . '><div class="st_view_inner">' . $this->process_tab_content($tab['content'], $slidetabs['slidetabs_options']['textConversion'], $br) . '</div></div>';
		
			$inc++;
		}
					
		$template_str.= '</ul></div></div><div class="st_views">' . $content . '</div></div>';
		
		return $template_str;
	}
	
	
	/*
	 * Return an array of images from a HTML string
	 */
	function parse_html_for_images($html_string, $validate = false) {
		$html_string = str_replace(array("\n", "\r"), array(" ", " "), $html_string);
		
		$image_raw = substr($html_string, strpos($html_string, '<img '));
		$image_raw = substr($image_raw, 0, strpos($image_raw, '>'));
		
		$image_strs = array();
		preg_match_all('/<img(\s*([a-zA-Z]+)\=\"([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)\")+\s*\/?>/', $html_string, $image_strs);
		
		$images = array();
		if (isset($image_strs[0]) && !empty($image_strs[0])) {
			foreach ((array) $image_strs[0] as $image_str) {
				$image_attr = array();
				$image_substr = preg_match_all('/([a-zA-Z]+)\=\"([a-zA-Z0-9\/\#\&\=\|\-_\+\%\!\?\:\;\.\(\)\~\s\,]*)\"/', $image_str, $image_attr);
				
				if (in_array( 'src', $image_attr[1])) {
					$images[] = array_combine($image_attr[1], $image_attr[2]);
				}
			}
		}
		
		$output = false;
		$output_set = false;
		$threshold = 2;
		
		if (!empty($images)) {
			// if validation is enabled, the filtering threshold will be upped to 120 pixels wide. This helps to filter out most advertisements. 1 pixel images will always be removed (unless a width is not defined).
			if ((boolean) $validate === true) {
				$threshold = 120;
			}
			
			foreach ($images as $image) {
				$valid = true;
				
				if ($output_set === false) {
					// validate against width if it is present
					if (isset($image['width'] ) && !empty( $image['width'])) {
						if (intval($image['width']) < $threshold) {
							$valid = false;
						}
					}
										
					if ((boolean) $validate === true) {
						// look for common ad network keywords
						if(preg_match('/(tweetmeme|stats|advertisement|commindo|valueclickmedia|imediaconnection|adify|traffiq|premiumnetwork|advertisingz|gayadnetwork|vantageous|networkadvertising|advertising|digitalpoint|viraladnetwork|decknetwork|burstmedia|doubleclick).|feeds\.[a-zA-Z0-9\-_]+\.com\/~ff|wp\-digg\-this|feeds\.wordpress\.com|\/media\/post_label_source/i', $image['src'])) {
							$valid = false;
						}
					}					
					if ($valid === true) {
						$output = $image;
						$output_set = true;
					}
				}
			}
		}
		
		return $output;
	}
	
	
	/*
	 * Calculate the image aspect ratio based on the maximum width and height
	 */
	function calc_image_aspect_ratio($max_image_width = 0, $max_image_height = 0, $tab_nodes) {
		$max_image_width = ($max_image_width == 0) ? $tab_nodes['image_width'] : $max_image_width;
		$max_image_height = ($max_image_height == 0) ? $tab_nodes['image_height'] : $max_image_height;
						
		if ($tab_nodes['image_width'] != NULL) {
			if ($tab_nodes['image_width'] > $max_image_width) {
				$w_ratio = $max_image_width / $tab_nodes['image_width'];
				$tab_nodes['image_width'] = floor($w_ratio * $tab_nodes['image_width']);
				if ($tab_nodes['image_height'] != NULL) {
					$tab_nodes['image_height'] = floor($w_ratio * $tab_nodes['image_height']);
				}
			}
		}
				
		if ($tab_nodes['image_height'] != NULL) {
			if ($tab_nodes['image_height'] > $max_image_height) {
				$h_ratio = $max_image_height / $tab_nodes['image_height'];
				$tab_nodes['image_width'] = floor($h_ratio * $tab_nodes['image_width']);
				$tab_nodes['image_height'] = floor($h_ratio * $tab_nodes['image_height']);
			}
		}
		
		return $tab_nodes;
	}
	
	
	/*
	 * Truncate the title string
	 */
	function prepare_dynamic_title($text, $length = 100, $ending = '&hellip;') {
		$truncated = mb_substr(strip_tags($text), 0, $length, 'UTF-8');

		if (function_exists('mb_strlen')) {
			$original_length = mb_strlen($text, 'UTF-8');
		} else {
			$original_length = strlen($text);
		}
		
		if ($original_length > $length) {
			$truncated.= $ending;
		}
		
		return $truncated;
	}
	
	
	/*
	 * Trim the HTML down to a certain length
	 */
	function prepare_excerpt($input, $limit, $convert, $br) {
		if ($limit > 0) {
		    $the_excerpt = trim(strip_tags($input));
			$the_excerpt_pieces = explode(" ", $the_excerpt);
			
			// remove empty values from the array.
			foreach ($the_excerpt_pieces as $key => $value) {
			    if (empty($value)) {
			        unset($the_excerpt_pieces[$key]);
			    }
			}
			
			// only return if there's something to return.
			if (count($the_excerpt_pieces) > 0) {
				$slidetabs_trimmed_excerpt = implode(" ", array_slice($the_excerpt_pieces, 0, $limit));
				
				if (count($the_excerpt_pieces) > $limit) {
					$slidetabs_trimmed_excerpt.= '&hellip;';
				}								
				
				if ($convert == 'false') {
					return html_entity_decode($slidetabs_trimmed_excerpt, ENT_QUOTES, 'UTF-8');
				} elseif ($convert == 'br') {
					return nl2br(html_entity_decode($slidetabs_trimmed_excerpt, ENT_QUOTES, 'UTF-8'));
				} else {
					return wpautop(html_entity_decode($slidetabs_trimmed_excerpt, ENT_QUOTES, 'UTF-8'), $br);
				}
			}
		}
	}			
	
	
	/*
	 * Add the custom sidebar meta box to the post and page views
	 */
	function add_custom_box() {
		if (function_exists('add_meta_box')) {
			add_meta_box('slidetabs_meta_box', 'SlideTabs Dynamic', array(&$this, 'custom_box'), 'post', 'side', 'core', array('view' => 'post'));
			add_meta_box('slidetabs_meta_box', 'SlideTabs Dynamic', array(&$this, 'custom_box'), 'page', 'side', 'core', array('view' => 'page'));
			wp_register_style('slidetabs-meta-css', $this->url('css/slidetabs_meta_box.css'), array(), $this->version, 'screen');
			wp_enqueue_style('slidetabs-meta-css');
		}
	}
	
	
	/*
	 * Include content for the custom meta box on post and page views
	 */
	function custom_box($post, $metabox) {
		global $post;
		
		$slidetabs_post_meta = array(
			'_slidetabs_tab_title'     => '',
			'_slidetabs_post_featured' => 0
		);
		
		foreach ($slidetabs_post_meta as $meta_key => $meta_val) {
			$post_meta_value = get_post_meta($post->ID, $meta_key, true);
			
			if (!empty($post_meta_value)) {
				$slidetabs_post_meta[$meta_key] = $post_meta_value;
			}
		}
		
		include($this->dir('/includes/meta_box.php'));
	}
	
	
	/*
	 * Save data from the posts and pages custom meta box
	 */
	function save_dynamic_meta() {
		if (isset($_POST['slidetabs-for-wordpress-dynamic-meta_wpnonce'] ) && !empty($_POST['slidetabs-for-wordpress-dynamic-meta_wpnonce'])) {
			if (!wp_verify_nonce($_POST['slidetabs-for-wordpress-dynamic-meta_wpnonce'], 'slidetabs-for-wordpress')) {
				return false;
			}
			
			$slidetabs_post_meta = array('_slidetabs_tab_title', '_slidetabs_post_featured');
			
			foreach ($slidetabs_post_meta as $meta_key) {
				if (isset($_POST[$meta_key]) && !empty($_POST[$meta_key])) {
					update_post_meta($_POST['ID'], $meta_key, $_POST[$meta_key]);
				} else {
					delete_post_meta($_POST['ID'], $meta_key);
				}
			}
		}
	}
	
	
	/*
	 * AJAX function for previewing the tabs
	 */
	function slidetabs_preview() {
		global $slidetabs_class, $slidetabs_options_json;
		
		$slidetabs_id = $_POST['slidetabs_id'];
		
		echo $this->process_template($slidetabs_id);
		$this->print_scripts();
		
		die();
	}
	
	
	/*
	 * AJAX function for adding a new tab
	 */
	function slidetabs_add_tab() {   
		global $slidetabs_class, $post_ID;
		
		$count = $_GET['count'];
		$tab = array(
			'clean_title'  => $_GET['title'],
			'content'      => "",
			'ext_link'     => $_GET['slug'],
			'tab_order'    => $count,
			'tabs_post_id' => $_GET['tabs_post_id'],
			'title'        => $_GET['title']
		);
		
		$post_ID = $_GET['tabs_post_id'];
		
		if ($_GET['ajaxTab'] == 'true') {
			include($this->dir('/includes/edit_form-ajax_tab_panel.php'));
		} else {
			include($this->dir('/includes/edit_form-tab_panel.php'));
		}
		exit;
	}
	
	
	/*
	 * AJAX function for loading the background image dialog
	 */
	function slidetabs_add_background() {   
		global $slidetabs_class;
		
		$tab_editor = $_GET['tab_editor'];
		$images_total_height = $_GET['height'];
		$date = isset($_GET['date']) ? $_GET['date'] : 'all';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		$table = $this->db->posts;
	
		$total_images_query = "SELECT COUNT(id) FROM $table WHERE post_type='attachment' AND post_mime_type LIKE 'image%'";
		if ($date !== 'all') { $total_images_query.= " AND post_date LIKE '$date%'"; }
			
		$total_images = $this->db->get_var($total_images_query);
		
		$images = array();
		$dates = array();
		
		if ($total_images !== '0') {
			// number of images to load at once
			$images_to_load = floor($images_total_height / 50);		
			
			$total_pages = ceil($total_images / $images_to_load);
			
			// calculate the first visible image
			$start_image = ($page - 1) * $images_to_load;		
			if ($start_image > $total_images) { $start_image = ($total_pages - 1) * $images_to_load; }
			
			// get the available dates
			$dates_all = $this->db->get_results("SELECT post_date FROM $table WHERE post_type='attachment' AND post_mime_type LIKE 'image%' ORDER BY post_date DESC", ARRAY_A);
	
			$images_query = "SELECT guid, post_title, post_date FROM $table WHERE post_type='attachment' AND post_mime_type LIKE 'image%'";
			if ($date != 'all') { $images_query.= " AND post_date LIKE '$date%'"; }
			$images_query.= " ORDER BY post_date DESC LIMIT $start_image, $images_to_load";
			
			$images = $this->db->get_results($images_query, ARRAY_A);
						
			$months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
			
			// format the dates and create an array of the values
			foreach($dates_all as $date_entry) {
				$year = substr($date_entry['post_date'], 0, 4);
				$month_raw = substr($date_entry['post_date'], 5, 2);
				$month = $months[$month_raw];
				
				$date_raw = $year . '-' . $month_raw;
				$date_formatted = $month . ' ' . $year;
	
				if (!in_array($date_formatted, $dates)) { $dates[$date_raw] = $date_formatted; }
			}
		}

		include($this->dir('/includes/image_dialog.php'));
		
		die();
	}
	
	
	/*
	 * Register the custom post types
	 */
	function register_post_types() {
		if (function_exists('register_post_type')) {
			register_post_type($this->post_type,
				array(
					'labels' => array(
						'name'          => $this->post_type,
						'singular_name' => __('SlideTabs')
					),
					'public' => false
				)
			);
			
			register_post_type($this->content_post_type,
				array(
					'labels' => array(
						'name'          => $this->content_post_type,
						'singular_name' => __('SlideTabs Content')
					),
					'public' => false
				)
			);
			
			register_post_type($this->content_post_type,
				array(
					'labels' => array(
						'name'          => $this->ajax_post_type,
						'singular_name' => __('SlideTabs AJAX')
					),
					'public' => false
				)
			);
		}
	}
	
	
	/*
	 * Help/documentation page
	 */
	function admin_page_help() {
		global $slidetabs_class;
		include($this->dir('/includes/help.php'));
	}

}


global $slidetabs_class;
$slidetabs_class = new SlideTabs();


/*
 * Public template function
 */
function slidetabs($slidetabs_id, $dimensions = array('width' => NULL, 'height' => NULL)) {
	global $slidetabs_class;
	$template_str = $slidetabs_class->process_template($slidetabs_id, $dimensions);

	echo $template_str;		
}


/*
 * Public template function (for AJAX requests)
 */
function slidetabs_ajax($slidetabs_id, $dimensions = array('width' => NULL, 'height' => NULL)) {
	global $slidetabs_class, $ajaxFiles, $slidetabs_options_json;
	$template_str = $slidetabs_class->process_template($slidetabs_id, $dimensions, true);
		
	$jsonArr = array(
		'html'     => $template_str,
		'css'      => $ajaxFiles['css'],
		'js'       => $ajaxFiles['js'],
		'settings' => $slidetabs_options_json
	);
	
	$json = json_encode($jsonArr);
		
	echo $json;
	
	die();
}


/*
 * Hook into admin_print_footer_scripts action to preload the TinyMCE editor dialogs (only needed for WP < 3.2)
 */
function slidetabs_wp_tiny_mce_preload_dialogs() {
	if (function_exists('wp_tiny_mce_preload_dialogs') && $slidetabs_class->use_old_tinymce_editor === true) {
		wp_tiny_mce_preload_dialogs();
	}
}