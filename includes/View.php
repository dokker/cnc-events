<?php
namespace cncEV;

class View {

	private $data = array();

	function __construct()
	{
		// Register hooks
		add_filter('cnc_smart_link', [$this, 'smart_link'], 10, 3);
		add_filter('cnc_format_date_field', [$this, 'format_date_field'], 10, 2);
		add_filter('cnc_format_time_field', [$this, 'format_time_field'], 10, 2);
		add_filter('cnc_limit_string', [$this, 'limit_string'], 10, 3);

		add_image_size( 'event-medium', 750, 450, true );

		add_shortcode('cnc_events_latest', [$this, 'shortcodeLatest']);
		add_shortcode('cnc_events_calendar', [$this, 'shortcodeCalendar']);
		add_filter('widget_text', 'do_shortcode');
	}

	/**
	 * Assign new value to data stack
	 * @param  string $variable Variable name
	 * @param  mixed $value    Value of the variable
	 */
	public function assign($variable, $value)
	{
		$this->data[$variable] = $value;
	}

	/**
	 * Render content using the given template file
	 * @param  string $template Template file name without extension
	 * @return string           Generated HTML markup
	 */
	public function render($template)
	{
		extract($this->data);
		$file = CNC_TEMPLATE_DIR . CNC_DS . $template . '.tpl.php';
		if (!file_exists($file)) {
			throw new \Exception("File doesn't exist");
		}
		ob_start();
		include($file);
		return ob_get_clean();
	}

	/**
	 * Render smart link button
	 * @param  string $url     Link url
	 * @param  string $label   Smart link text
	 * @param  string $classes Additional CSS classes
	 * @return string          HTML markup of the button
	 */
	public function smart_link($url, $label, $classes = '')
	{
	  $smartlink = sprintf('<a class="smart-link %s" href="%s">', $classes, $url);
	  $smartlink .= sprintf('<span class="smart-link__label">%s</span>', $label);
	  $smartlink .= '<span class="smart-link__arrow-wrap"><span class="smart-link__arrow">&#9658; &#9658;</span></span>';
	  $smartlink .= '</a>';
	  return $smartlink;
	}

	/**
	 * Format date field value to given format
	 * @param  int $datefield Date strored in field
	 * @param  string $format    Output date format
	 * @return string            Formatted date
	 */
	public function format_date_field($datefield, $format = 'Y.m.d')
	{
	  $format_in = 'Ymd'; // the format your value is saved in (set in the field options)
	  $format_out = $format; // the format you want to end up with

	  $date = \DateTime::createFromFormat($format_in, $datefield);

	  return $date->format($format_out);
	}

	/**
	 * Format time field value to given format
	 * @param  int $timestamp Time strored in field
	 * @param  string $format    Output time format
	 * @return string            Formatted time
	 */
	public function format_time_field($timestamp, $format = 'H:m')
	{
	  $format_out = $format; // the format you want to end up with

	  $date = new \DateTime();
	  $date->setTimestamp($timestamp);

	  return $date->format($format_out);
	}

	/**
	 * Get current date formatted in specified format
	 * @param string $format Date format to return
	 * @return string Formatted date
	 */
	public function get_current_date($format = 'Ymd')
	{
		$date = new \DateTime();
		return $date->format($format);
	}

	/**
	 * Trim given text to specified length
	 * @param  string $string   Text to trim
	 * @param  int $length   Length in characters
	 * @param  string $replacer Pattern to end with
	 * @return string           Trimmed text
	 */
	public function limit_string($string, $length, $replacer = '...') {
	  if (strlen($string) > $length)
	     $string = mb_substr($string, 0, $length-3) . $replacer;
	  return $string;
	}

	/**
	 * Generate latest events shortcode
	 * @param  array $args Given attributes
	 */
	public function shortcodeLatest($args)
	{
	    // extract the attributes into variables
	    $atts = shortcode_atts(array(
	        'num' => -1,
	    ), $args);

	    $model = new \cncEV\Model();
	    $model->set_current_date($this->get_current_date('Ymd'));
	    $events = $model->getLatestEvents($atts['num']);
	    $this->assign('events_query', $events);

	    return $this->render('shortcode-latest');
	}

	/**
	 * Generate calendar shortcode
	 * @return string       Generated shortcode
	 */
	public function shortcodeCalendar()
	{
	    $model = new \cncEV\Model();
	    $calendar = $model->generateCalendar(2016, 10);

	    $this->assign('events_calendar', $calendar);

	    return $this->render('shortcode-calendar');
	}
}
