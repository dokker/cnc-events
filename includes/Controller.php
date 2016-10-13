<?php
namespace cncEV;

class Controller
{

	private $google_maps_api = 'AIzaSyDzFr49Ko_k2h_Z2Um9DAM-4cTyzImrx88';
	
	function __construct()
	{
		$this->model = new \cncEV\Model();
		$this->view = new \cncEV\View();
		$this->acf = new \cncEV\ACF();

		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
		add_action('wp_enqueue_scripts', [$this, 'registerStyles']);
		add_filter('acf/fields/google_map/api', [$this, 'googleMapAPI']);
		add_action('pre_get_posts', [$this, 'modify_events_archive_query']);
	}

	/**
	 * Register scripts for events related pages
	 */
	public function registerScripts() {
		if (true) {
			wp_register_script('cnc-events-script', CNC_PROJECT_URL . CNC_DS . 'assets/js/main.js', array('jquery'));
			wp_enqueue_script('cnc-events-script');
			wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $this->google_maps_api, null, null, true);
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
		$api['key'] = $this->google_maps_api;
		return $api;
	}

	public function modify_events_archive_query( $query ) {
		if ( is_post_type_archive('event') && is_main_query() && !is_admin() ) {
			set_query_var( 'orderby', 'meta_value_num' );
			set_query_var( 'order', 'ASC' );
	        set_query_var( 'meta_key', 'event_date' );
	        set_query_var( 'meta_value', $this->view->get_current_date('Ymd') );
	        set_query_var( 'meta_compare', '>=' );
	    }
	}
}
