<?php
namespace cncEV;

class Model
{
	
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
			'orderby' => 'meta_value_num',
			'order' => 'DESC',
		];
		$query = new \WP_Query($args);
		return $query;
	}
}
