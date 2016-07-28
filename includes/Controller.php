<?php
namespace cncEV;

class Controller
{

	private $google_maps_api = 'AIzaSyDzFr49Ko_k2h_Z2Um9DAM-4cTyzImrx88';
	
	function __construct()
	{
		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
		add_action('wp_enqueue_scripts', [$this, 'registerStyles']);
		add_filter('acf/fields/google_map/api', 'googleMapAPI');
	}

	/**
	 * Register scripts for events related pages
	 */
	public function registerScripts() {
		if (true) {
			wp_register_script('cnc-events-script', CNC_PROJECT_URL . CNC_DS . 'assets/js/main.js', array('jquery'));
			wp_enqueue_script('cnc-events-script');
			wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $google_maps_api, null, null, true);  
			wp_enqueue_script('googlemaps');
		}
	}

	/**
	 * Register styles for events related pages
	 */
	public function registerStyles() {
		if (true) {
			wp_enqueue_style( 'cnc-events-style' , CNC_PROJECT_URL . CNC_DS . 'assets/css/main.css');
		}
	}

	public function googleMapAPI($api)
	{
		$api['key'] = $google_maps_api;
		return $api;
	}
}
