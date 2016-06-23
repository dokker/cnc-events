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

}

add_action('plugins_loaded', '__cnc_events_load_plugin');
