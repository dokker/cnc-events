<?php
namespace cncEV;
class ACF {
	function __construct () {
		$this->registerFields();
	}

	public function registerFields()
	{
		
		if(function_exists("register_field_group"))
		{
			register_field_group(array (
				'id' => 'acf_esemeny-adatai',
				'title' => 'Esemény adatai',
				'fields' => array (
					array (
						'key' => 'field_576bbe7f2e4e6',
						'label' => 'Dátum',
						'name' => 'event_date',
						'type' => 'date_picker',
						'date_format' => 'yymmdd',
						'display_format' => 'yy.mm.dd.',
						'first_day' => 1,
					),
					array (
						'key' => 'field_576bdeb813502',
						'label' => 'Időpont',
						'name' => 'event_time',
						'type' => 'date_time_picker',
						'show_date' => 'false',
						'date_format' => 'm/d/y',
						'time_format' => 'HH:mm',
						'show_week_number' => 'false',
						'picker' => 'select',
						'save_as_timestamp' => 'true',
						'get_as_timestamp' => 'true',
					),
					array (
						'key' => 'field_576bc3f9d528f',
						'label' => 'Helyszín megadása',
						'name' => 'event_location_set',
						'type' => 'true_false',
						'message' => 'Az eseményhez tartozik helyszín',
						'default_value' => 0,
					),
					array (
						'key' => 'field_576bc3a5d528e',
						'label' => 'Helszín neve',
						'name' => 'event_location_name',
						'type' => 'text',
						'conditional_logic' => array (
							'status' => 1,
							'rules' => array (
								array (
									'field' => 'field_576bc3f9d528f',
									'operator' => '==',
									'value' => '1',
								),
							),
							'allorany' => 'all',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'formatting' => 'none',
						'maxlength' => '',
					),
					array (
						'key' => 'field_576bafaab913f',
						'label' => 'Helyszín',
						'name' => 'event_location',
						'type' => 'google_map',
						'conditional_logic' => array (
							'status' => 1,
							'rules' => array (
								array (
									'field' => 'field_576bc3f9d528f',
									'operator' => '==',
									'value' => '1',
								),
							),
							'allorany' => 'all',
						),
						'center_lat' => '47.8993771',
						'center_lng' => '20.3815556',
						'zoom' => '',
						'height' => '',
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'event',
							'order_no' => 0,
							'group_no' => 0,
						),
					),
				),
				'options' => array (
					'position' => 'normal',
					'layout' => 'no_box',
					'hide_on_screen' => array (
					),
				),
				'menu_order' => 0,
			));
		}
	}
}

