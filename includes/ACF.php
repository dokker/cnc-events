<?php
namespace cncEV;
class ACF {
	function __construct () {
		$this->registerFields();
	}

	public function registerFields()
	{
		if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key' => 'group_5802259a59fa3',
			'title' => 'Esemény részletei',
			'fields' => array (
				array (
					'key' => 'field_580225f43f2a3',
					'label' => 'Esemény kezdete',
					'name' => 'event_date_start',
					'type' => 'date_time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'Y.m.d. H:i',
					'return_format' => 'Y.m.d.',
					'first_day' => 1,
				),
				array (
					'key' => 'field_580226813f2a4',
					'label' => 'Esemény vége',
					'name' => 'event_date_end',
					'type' => 'date_time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'Y.m.d. H:i',
					'return_format' => 'Y.m.d.',
					'first_day' => 1,
				),
				array (
					'key' => 'field_5808c98ba270b',
					'label' => 'Helyszín név',
					'name' => 'event_location_name',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
						),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					),
				array (
					'key' => 'field_5808c9efa270c',
					'label' => 'Helyszín',
					'name' => 'event_location',
					'type' => 'google_map',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
						),
					'center_lat' => '',
					'center_lng' => '',
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
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		));

		acf_add_local_field_group(array (
			'key' => 'group_58c8066e2168c',
			'title' => 'General settings',
			'fields' => array (
				array (
					'key' => 'field_58c8067cc004c',
					'label' => 'Archive style',
					'name' => 'cnc-event-archive-style',
					'type' => 'radio',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
						),
					'choices' => array (
						'calendar' => 'Calendar',
						'simple' => 'Simple',
						),
					'allow_null' => 0,
					'other_choice' => 0,
					'save_other_choice' => 0,
					'default_value' => 'simple',
					'layout' => 'vertical',
					'return_format' => 'value',
					),
				),
			'location' => array (
				array (
					array (
						'param' => 'options_page',
						'operator' => '==',
						'value' => 'cnc-events',
						),
					),
				),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => 1,
			'description' => '',
		));

		endif;
	}
}

