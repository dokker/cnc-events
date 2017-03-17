<?php
namespace cncEV;

class Model
{
	private $current_date;
	private $list_order;
	private $list_show_past;
	
	function __construct()
	{
		// Register hooks
		add_filter('cnc_is_before_today', [$this, 'is_before_today'], 10, 1);

		add_action('pre_get_posts', [$this, 'modify_events_archive_query']);
		$this->list_order = get_field('cnc-events-order-method', 'option');
		$this->list_show_past = get_field('cnc-events-show-past', 'option');
	}

	/**
	 * Check if given date day is before today
	 * @param  int $datefield Datefield value
	 * @return bool            True when before
	 */
	public function is_before_today($datefield)
	{
		if (strlen($datefield) == 8) {
		  $format = 'Y-m-d H:i:s';
		  $stored_date = \DateTime::createFromFormat($format, $datefield);
		  $today = new \DateTime();
		  if ($stored_date->format($format) < $today->format('Y-m-d') . ' 00:00:00') {
		    return true;
		  } else {
		    return false;
		  }
		}
	}

	public function getLatestEvents($num)
	{
		$args = [
			'post_type' => 'event',
			'posts_per_page' => $num,
			'meta_key' => 'event_date_start',
			'meta_value' => $this->current_date,
			'meta_compare' => '>=',
			'meta_type'	=> 'DATETIME',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		];
		$query = new \WP_Query($args);
		return $query;
	}

	/**
	 * Set current date field
	 * @param string $date Current date in Ymd format
	 */
	public function set_current_date($date)
	{
		$this->current_date = $date;
	}

	/**
	 * Get current year
	 * @return int A full numeric representation of a year, 4 digits (2003)
	 */
	public function thisYear()
	{
		$ts = current_time('timestamp');
		return gmdate('Y', $ts);
	}

	/**
	 * Get current month
	 * @return int Numeric representation of a month, with leading zeros (01 through 12)
	 */
	public function thisMonth()
	{
		$ts = current_time('timestamp');
		return gmdate('m', $ts);
	}

	/**
	 * Get calendar structure
	 * @param  int $year  Year in Y format
	 * @param  int $month Month in m format
	 * @return array        Array of a month
	 */
	public function generateCalendar($year, $month)
	{
		if (false === ($calendar = get_transient('cnc_events_cal_'.$year.$month))) {
			global $wp_locale, $wpdb;
			// current time
			$unixmonth = mktime( 0, 0 , 0, $month, 1, $year );
			$ts = current_time( 'timestamp' );
			$week_begins = (int) get_option( 'start_of_week' );
			$last_day = date( 't', $unixmonth );
			$daysinmonth = (int) date( 't', $unixmonth );
			// caption for calendar
			$caption = date_i18n('Y F', $unixmonth);
			// abbrevated day names of the week

			$week = [];
			for ( $wdcount = 0; $wdcount <= 6; $wdcount++ ) {
				$dayname = $wp_locale->get_weekday( ( $wdcount + $week_begins ) % 7 );
				$weekday_abbr = $wp_locale->get_weekday_abbrev($dayname);
				// get the first letter only
				$week[] = $weekday_abbr[0];
			}

			// strarting day of the week
			(int)$daycntr = calendar_week_mod( date( 'w', $unixmonth ) - $week_begins );

			// calendar data
			$calendar = [];
			// generate current date label
			$calendar['date_label'] = date_i18n('Y F', mktime( 0, 0 , 0, $month, 1, $year ));
			$calendar['days'] = [];
			for ($day= 1; $day<= $daysinmonth; $day++) {
				$day_actual = date('Ymd', strtotime( "{$year}-{$month}-{$day}"));
				$day_data = [];
				$day_data['date'] = $day_actual;
				// Check for today
				if ( $day== gmdate( 'j', $ts ) &&
					$month == gmdate( 'm', $ts ) &&
					$year == gmdate( 'Y', $ts ) ) {
					$day_data['today'] = true;
				}

				// day of the week label
				$day_data['dow'] = $week[$daycntr];
				if ($daycntr == 6) {
					$day_data['weekend'] = true;
					$daycntr = 0;
				} else {
					$daycntr++;
				}

				// collect events on this day
				$events = $this->eventsByDate($year . '-' . $month . '-' . sprintf("%02d", $day));
				$day_data['events'] = $this->injectEventsData($events);

				$calendar['days'][$day] = $day_data;
			}

			$calendar['year_new'] = $year;
			$calendar['month_new'] = $month;
			$calendar['success'] = true;
			set_transient('cnc_events_cal_'.$year.$month, $calendar, 15 * MINUTE_IN_SECONDS);
		}
		return $calendar;
	}

	/**
	 * Get events on specified date
	 * @param  int $date Date in 'Y-m-d H:i:s' format
	 * @return array       Event post list
	 */
	public function eventsByDate($date)
	{
		$day_start = $date . ' 00:00:00';
		$day_end = $date . ' 23:59:59';
		$args = [
			'post_type' => 'event',
			'posts_per_page' => -1,
			'status' => 'published',
			'meta_query'		=> array(
				'relation' => 'AND',
				array(
					'key' => 'event_date_start',
					'compare' => '<=',
					'type' => 'DATETIME',
					'value' => $day_end,
					),
				array(
					'key' => 'event_date_end',
					'compare' => '>=',
					'type' => 'DATETIME',
					'value' => $day_start,
				)
			),
			'order'			=> 'ASC',
			'orderby'		=> 'meta_value',
			'meta_key'		=> 'event_date_start',
			'meta_type'		=> 'DATE',
		];
		$query = new \WP_Query($args);
		return $query->get_posts();
	}

	/**
	 * Add date fields and excerpt to events query result
	 * @param  array $events Events query result
	 * @return array         Modified events list
	 */
	private function injectEventsData($events)
	{
		foreach ($events as $key=>$event) {
			$events[$key]->c_date_start = get_field('event_date_start', $event->ID);
			$events[$key]->c_date_end = get_field('event_date_end', $event->ID);
			$events[$key]->c_permalink = get_permalink($event->ID);
			$events[$key]->c_excerpt = $this->createExcerpt($event->post_content);
		}
		return $events;
	}


    /**
     * Shorten string to given length
     *
     * @param {string} $text String to shorten
     * @param {int} $length Shorten length
     *
     * @return {string} Shortened string
     */
    public function strToExcerpt($string, $int) {
    	$str_excerpt = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $int));
    	return $str_excerpt;
    }

    /**
     * Shorten string to given length with dots
     *
     * @param {string} $text String to shorten
     * @param {int} $length Shorten length
     *
     * @return {string} Shortened string
     */
    public function strToExcerptDots($string, $int) {
    	if (strlen($string) > $int) {
    		$str_excerpt = $this->strToExcerpt($string, $int);
    		$str_excerpt .= "...";
    	} else {
    		$str_excerpt = $string;
    	}
    	return $str_excerpt;
    }

	/**
	 * Create simple excerpt from given content
	 * @param  string $content Mixed content string
	 * @return string          Simple excerpt
	 */
	private function createExcerpt($content)
	{
		$excerpt = $this->strToExcerptDots(wp_strip_all_tags($content), 200);
		return $excerpt;
	}

	/**
	 * generate archive query
	 * @return object        Query object
	 */
	public function events_archive_query() {
		$args = [
			'post_type' => 'event',
			'orderby' => 'meta_value',
			'meta_type' => 'DATE',
			'meta_key' => 'event_date_start',
		];
		if ($this->list_order != 'desc') {
			$args['order'] = 'ASC';
		} else {
			$args['order'] = 'DESC';
		}
		if ($this->list_show_past != 1) {
			$args['meta_value'] = $this->get_current_date('Y-m-d') . ' 00:00:00';
			$args['meta_compare'] = '>=';
		}

		$query = new \WP_Query($args);
		return $query;
	}

	public function modify_events_archive_query( $query ) {
		if ( is_post_type_archive('event') && $query->is_main_query() && !is_admin() ) {
		set_query_var( 'orderby', 'meta_value' );
		if ($this->list_order != 'desc') {
			set_query_var( 'order', 'ASC' );
		} else {
			set_query_var( 'order', 'DESC' );
		}
		set_query_var( 'meta_type', 'DATE');
		    set_query_var( 'meta_key', 'event_date_start' );
		    if ($this->list_show_past != 1) {
		      set_query_var( 'meta_value', $this->get_current_date('Y-m-d') . ' 00:00:00' );
		      set_query_var( 'meta_compare', '>=' );
		    }
		}
	}

	/**
	 * Get current date formatted in specified format
	 * @param string $format Date format to return
	 * @return string Formatted date
	 */
	public function get_current_date($format = 'Y-m-d')
	{
		$date = new \DateTime();
		return $date->format($format);
	}
}
