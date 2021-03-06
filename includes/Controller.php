<?php
namespace cncEV;

class Controller
{

	private $google_maps_api;

	function __construct()
	{
		$this->model = new \cncEV\Model();
		$this->view = new \cncEV\View();
		$this->acf = new \cncEV\ACF();

		add_action('wp_enqueue_scripts', [$this, 'registerScripts']);
		add_action('wp_enqueue_scripts', [$this, 'registerStyles']);
		add_filter('acf/fields/google_map/api', [$this, 'googleMapAPI']);
		add_action('wp_ajax_get_calendar_month', [$this, 'ajax_get_calendar_month']);
		add_action('wp_ajax_nopriv_get_calendar_month', [$this, 'ajax_get_calendar_month']);
		// Register custom event list column
		add_filter( 'manage_event_posts_columns', [$this, 'wpcolumn_add_column'], 5 );
		add_action( 'manage_event_posts_custom_column', [$this, 'wpcolumn_column_content'], 5, 2 );
		add_action( 'manage_edit-event_sortable_columns', [$this, 'wpcolumn_column_sortable'], 5, 2 );
		add_action( 'pre_get_posts', [$this, 'wpcolumn_column_orderby'] );

		$this->add_options_pages();

		$this->google_maps_api = get_field('cnc-events-gmaps-api', 'option');
	}

	/**
	 * Add admin option pages using ACF5
	 */
	public function add_options_pages()
	{
		if( function_exists('acf_add_options_sub_page') ) {
			acf_add_options_page(array(
				'page_title' 	=> __('CnC Events Settings', 'cnc-events'),
				'menu_title'	=> __('CnC Events', 'cnc-events'),
				'menu_slug' 	=> 'cnc-events',
				'capability'	=> 'edit_posts',
				'parent_slug'	=> 'options-general.php',
			));
		}
	}

	/**
	 * Register scripts for events related pages
	 */
	public function registerScripts() {
		if (true) {
			wp_register_script('cnc-events-script', CNC_PROJECT_URL . CNC_DS . 'assets/js/main.js', array('jquery'));
			wp_enqueue_script('cnc-events-script');
			wp_register_script('cnc-events-calendar', CNC_PROJECT_URL . CNC_DS . 'assets/js/calendar.js', array('jquery'));
			wp_register_script('googlemaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $this->google_maps_api, null, null, true);
			if (is_singular( 'event' )) {
				wp_enqueue_script('googlemaps');
			}
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

	public function ajax_get_calendar_month() {
		check_ajax_referer('calendar_nonce');
		(int) $year = $_REQUEST['year'];
		(int) $month = $_REQUEST['month'];
		$calendar_data = $this->model->generateCalendar($year, sprintf("%02d", $month));
		wp_send_json($calendar_data);
		wp_die();
	}

	/**
	 * Add new custom column before the last column
	 * @param  array $columns WP list column list
	 * @return array          Appended column list
	 */
	public function wpcolumn_add_column( $columns ) {
		$ccolumns = [];
		end($columns);
		$lastkey = key($columns);
		foreach ($columns as $key => $value) {
			if ($key == $lastkey) {
				$ccolumns['start_date'] = __('Start date', 'cnc-events');
			}
			$ccolumns[$key] = $value;
		}
		return $ccolumns;
	}

	/**
	 * Generate customn column content
	 * @param  string $column Column name
	 * @param  int $id     Post ID
	 */
	public function wpcolumn_column_content( $column, $id ) {
		if( 'start_date' == $column ) {
			echo get_field('event_date_start', $id);
		}
	}

	/**
	 * Register sortable columns
	 * @param  array $columns Columns
	 * @return array          Updated columns
	 */
	public function wpcolumn_column_sortable($columns)
	{
		$columns['start_date'] = 'start_date';

		return $columns;
	}

	/**
	 * Custom column order callback
	 * @param  object $query WP query object
	 */
	public function wpcolumn_column_orderby($query)
	{
		if(!is_admin()) {
			return;
		}

		$orderby = $query->query['orderby'];

		if('start_date' == $orderby) {
			set_query_var( 'orderby', 'meta_value' );
			set_query_var( 'meta_type', 'DATE');
	        set_query_var( 'meta_key', 'event_date_start' );
		}
	}
}
