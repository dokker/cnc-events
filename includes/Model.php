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
	  $format = 'Ymd';
	  $stored_date = \DateTime::createFromFormat($format, $datefield);
	  $today = new \DateTime();
	  if ($stored_date->format($format) < $today->format($format)) {
	    return true;
	  } else {
	    return false;
	  }
	}
}
