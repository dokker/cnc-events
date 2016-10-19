<?php
namespace cncEV;

class Model
{
	private $current_date;
	
	function __construct()
	{
		// Register hooks
		add_filter('cnc_is_before_today', [$this, 'is_before_today'], 10, 1);
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
			$week[] = $wp_locale->get_weekday_abbrev($dayname);
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
			$day_data['events'] = $this->eventsByDate($year . '-' . $month . '-' . sprintf("%02d", $day) . ' 00:00:00');

			$calendar['days'][$day] = $day_data;
		}

		$calendar['year_new'] = $year;
		$calendar['month_new'] = $month;
		$calendar['success'] = true;
		return $calendar;
	}

	/**
	 * Get events on specified date
	 * @param  int $date Date in 'Y-m-d H:i:s' format
	 * @return array       Event post list
	 */
	public function eventsByDate($date)
	{
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
					'value' => $date,
					),
				array(
					'key' => 'event_date_end',
					'compare' => '>=',
					'type' => 'DATETIME',
					'value' => $date,
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

}
