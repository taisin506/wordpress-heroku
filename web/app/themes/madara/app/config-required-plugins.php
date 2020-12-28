<?php

	$madara_required_plugins = array(
		array(
			'name'     => 'Option Tree',
			'slug'     => 'option-tree',
			'required' => true
		),

		array(
			'name'     => 'Madara - Shortcodes',
			'slug'     => 'madara-shortcodes',
			'source'   => get_template_directory() . '/app/plugins/packages/madara-shortcodes.zip',
			'required' => true,
			'version'  => '1.5.0.1'
		),

		array(
			'name'     => 'Madara - Core',
			'slug'     => 'madara-core',
			'source'   => get_template_directory() . '/app/plugins/packages/madara-core.zip',
			'required' => true,
			'version'  => '1.5.1.2'
		),

		array(
			'name'     => 'Widget Logic',
			'slug'     => 'widget-logic',
			'required' => false
		),

	);