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
		  $format = 'Ymd';
		  $stored_date = \DateTime::createFromFormat($format, $datefield);
		  $today = new \DateTime();
		  if (intval($stored_date->format($format)) < intval($today->format($format))) {
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
			'meta_key' => 'event_date',
			'meta_value' => $this->current_date,
			'meta_compare' => '>=',
			'orderby' => 'meta_value_num',
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
		global $wp_locale;
		$unixmonth = mktime( 0, 0 , 0, $month, 1, $year );
		$last_day = date( 't', $unixmonth );
		$caption = date_i18n('Y F', $unixmonth);
		$calendar = [];
		return $calendar;
	}

}
