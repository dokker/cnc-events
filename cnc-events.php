<?php
/*
Plugin Name: CNC Events
Plugin URI: http://colorandcode.hu
Description: Event calendar
Version: 1.0
Author: docker
Author URI: https://hu.linkedin.com/in/docker
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initial settings
 */
define('CNC_DS', DIRECTORY_SEPARATOR);
define('CNC_PROJECT_PATH', realpath(dirname(__FILE__)));
define('CNC_PROJECT_URL', plugins_url() . CNC_DS . 'cnc-events');
define('CNC_TEMPLATE_DIR', CNC_PROJECT_PATH . CNC_DS . 'templates');
define('CNC_THEME', get_stylesheet_directory());

/**
 * Autoload
 */
$vendorAutoload = CNC_PROJECT_PATH . CNC_DS . 'vendor' . CNC_DS . 'autoload.php';
if (is_file($vendorAutoload)) {
	require_once($vendorAutoload);
}

function __cnc_events_load_plugin()
{
	// load translations
	load_plugin_textdomain( 'cnc-events', false, 'cnc-events/languages' );

	$events = new cncEV\ContentType('event', 
		['menu_icon' => 'dashicons-calendar-alt', 'has_archive' => true, 'supports' => ['title', 'editor', 'thumbnail']], 
		['singular_name' => __('Event', 'cnc-events'), 'plural_name' => __('Events', 'cnc-events')],
		_x('events', 'events archive slug', 'cnc-events'));

	// instantiate classes to register hooks
	$model = new cncEV\Model();
	$view = new cncEV\View();

}

add_action('plugins_loaded', '__cnc_events_load_plugin');

function __cnc_events_archive_template( $template ) {
	if(is_post_type_archive('event')) {
		$template = CNC_TEMPLATE_DIR . CNC_DS. 'archive-events.php';
	}
	return $template;
}

add_filter( 'archive_template', '__cnc_events_archive_template' );

function __cnc_change_sort_order($query){
    if(is_post_type_archive('event')) {
    	$query->set('meta_key', 'event_date');
    	$query->set('order', 'DESC');
    	$query->set('orderby', 'meta_value_num');
    }
};

add_action( 'pre_get_posts', '__cnc_change_sort_order'); 

// Actualize rewrite rules on plugin state change
register_activation_hook( __FILE__, function () {
    flush_rewrite_rules();
} );
register_deactivation_hook( __FILE__, function () {
    flush_rewrite_rules();
} );
