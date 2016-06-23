<?php
namespace cncEV;

class ContentType {
	public $type;
	public $options = [];
	public $labels = [];

	public function __construct($type, $options = [], $labels = [], $slug = '')
	{
		$this->type = $type;
		$default_options = [
			'public' => true,
			'supports' => ['title', 'editor', 'thumbnail'],
		];

		$required_labels = [
			'singular_name' => ucwords(__($this->type, 'cnc-events')),
			'plural_name' => ucwords(__($this->type, 'cnc-events'))
		];

		// merge with default
		$this->options = $options + $default_options;
		$this->labels = $labels + $required_labels;

		// in case of archive
		if (isset($this->options['has_archive']) && $this->options['has_archive'] && !empty($slug)) {
			if (!empty($slug)) {
				$this->options['rewrite'] = ['slug' => $slug];
			} else {
				$this->options['rewrite'] = ['slug' => strtolower($this->labels['plural_name'])];
			}
		}

		// append labels
		$this->options['labels'] = $this->labels + $this->default_labels();

		add_action('init', array($this, 'register'));
	}

	public function register()
	{
		register_post_type($this->type, $this->options);
	}

	public function default_labels()
	{
		return [
			'name' => $this->labels['plural_name'],
			'singular_name' => $this->labels['singular_name'],
			'plural_name' => $this->labels['plural_name'],
			'add_new' => sprintf(__('Add new %s', 'cnc-events'), $this->labels['singular_name']),
			'add_new_item' => sprintf(__('Add new %s', 'cnc-events'), $this->labels['singular_name']),
			'edit' => __('Edit', 'cnc-events'),
			'edit_item' => sprintf(__('Edit %s', 'cnc-events'), $this->labels['singular_name']),
			'new_item' => sprintf(__('New %s', 'cnc-events'), $this->labels['singular_name']),
			'view' => sprintf(__('View %s Page', 'cnc-events'), $this->labels['singular_name']),
			'view_item' => sprintf(__('View %s', 'cnc-events'), $this->labels['singular_name']),
			'search_items' => sprintf(__('Search %s', 'cnc-events'), $this->labels['plural_name']),
			'not_found' => sprintf(__('No matching %s found', 'cnc-events'), strtolower($this->labels['plural_name'])),
			'not_found_in_trash' => sprintf(__('No %s found in Trash', 'cnc-events'), strtolower($this->labels['plural_name'])),
			'parent_item_colon' => sprintf(__('Parent %s', 'cnc-events'), $this->labels['singular_name']),
		];
	}
}
