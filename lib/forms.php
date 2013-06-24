<?php

elgg_register_plugin_hook_handler('init', 'form:edit:plugin:hypealive', 'hj_alive_init_plugin_settings_form');

function hj_alive_init_plugin_settings_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	$river_tabs = ($entity->river_tabs) ? unserialize($entity->river_tabs) : array();

	$config = array(
		'fields' => array(
			'params[river]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->river,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river]:hint')
			),
			'params[river_comments]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->river_comments,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_comments]:hint')
			),
			'params[river_grouping]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'default' => elgg_echo('hj:alive:river:river_grouping:default'),
					'grouped' => elgg_echo('hj:alive:river:river_grouping:grouped')
				),
				'value' => $entity->river_grouping,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_grouping]:hint')
			),
			'params[river_order]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'asc' => elgg_echo('hj:alive:river_order:chronological'),
					'desc' => elgg_echo('hj:alive:river_order:reverse_chronological'),
				),
				'value' => $entity->river_order,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_order]:hint')
			),
			'params[river_load_style]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'load_newer' => elgg_echo('hj:alive:river_load_style:newer'),
					'load_older' => elgg_echo('hj:alive:river_load_style:older'),
				),
				'value' => $entity->river_load_style,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_load_style]:hint')
			),
			'params[river_limit]' => array(
				'value' => $entity->river_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_limit]:hint')
			),
			'params[river_load_limit]' => array(
				'value' => $entity->river_load_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_load_limit]:hint')
			),
			'params[river_tabs][all]' => array(
				'value' => $river_tabs['all'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			'params[river_tabs][mine]' => array(
				'value' => $river_tabs['mine'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			'params[river_tabs][friends]' => array(
				'value' => $river_tabs['friends'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			'params[river_tabs][groups]' => array(
				'value' => $river_tabs['groups'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			'params[river_tabs][bookmarks]' => array(
				'value' => $river_tabs['bookmarks'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			'params[comments]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->comments,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments]:hint')
			),
			'params[max_comment_depth]' => array(
				'input_type' => 'dropdown',
				'options' => array(5, 4, 3, 2, 1),
				'value' => $entity->max_comment_depth,
				'hint' => elgg_echo('edit:plugin:hypealive:params[max_comment_depth]:hint')
			),
			'params[comment_form]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'simple' => elgg_echo('hj:alive:comment_form:simple'),
					'advanced' => elgg_echo('hj:alive:comment_form:advanced'),
				),
				'value' => $entity->comment_form,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comment_form]:hint')
			),
			'params[comment_form_position]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'before' => elgg_echo('hj:alive:comment_form_position:before'),
					'after' => elgg_echo('hj:alive:comment_form_position:after'),
				),
				'value' => $entity->comment_form_position,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comment_form_position]:hint')
			),
			'params[comments_order]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'asc' => elgg_echo('hj:alive:comment_order:chronological'),
					'desc' => elgg_echo('hj:alive:comment_order:reverse_chronological'),
				),
				'value' => $entity->comments_order,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments_order]:hint')
			),
			'params[comments_load_style]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'load_newer' => elgg_echo('hj:alive:comments_load_style:newer'),
					'load_older' => elgg_echo('hj:alive:comments_load_style:older'),
				),
				'value' => $entity->comments_load_style,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments_load_style]:hint')
			),
			'params[comments_limit]' => array(
				'value' => $entity->comments_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments_limit]:hint')
			),
			'params[comments_load_limit]' => array(
				'value' => $entity->comments_load_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments_load_limit]:hint')
			),
			'params[forum_comments]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->forum_comments,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comments]:hint')
			),
			'params[max_forum_comment_depth]' => array(
				'input_type' => 'dropdown',
				'options' => array(5, 4, 3, 2, 1),
				'value' => $entity->max_forum_comment_depth,
				'hint' => elgg_echo('edit:plugin:hypealive:params[max_forum_comment_depth]:hint')
			),
			'params[forum_comment_form]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'simple' => elgg_echo('hj:alive:forum_comment_form:simple'),
					'advanced' => elgg_echo('hj:alive:forum_comment_form:advanced'),
				),
				'value' => $entity->forum_comment_form,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comment_form]:hint')
			),
			'params[forum_comment_form_position]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'before' => elgg_echo('hj:alive:forum_comment_form_position:before'),
					'after' => elgg_echo('hj:alive:forum_comment_form_position:after'),
				),
				'value' => $entity->forum_comment_form_position,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comment_form_position]:hint')
			),
			'params[forum_comments_order]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'asc' => elgg_echo('hj:alive:forum_comment_order:chronological'),
					'desc' => elgg_echo('hj:alive:forum_comment_order:reverse_chronological'),
				),
				'value' => $entity->forum_comments_order,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comments_order]:hint')
			),
			'params[forum_comments_load_style]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'load_newer' => elgg_echo('hj:alive:forum_comments_load_style:newer'),
					'load_older' => elgg_echo('hj:alive:forum_comments_load_style:older'),
				),
				'value' => $entity->forum_comments_load_style,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comments_load_style]:hint')
			),
			'params[forum_comments_limit]' => array(
				'value' => $entity->forum_comments_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comments_limit]:hint')
			),
			'params[forum_comments_load_limit]' => array(
				'value' => $entity->forum_comments_load_limit,
				'hint' => elgg_echo('edit:plugin:hypealive:params[forum_comments_load_limit]:hint')
			),
			'params[likes]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->likes,
				'hint' => elgg_echo('edit:plugin:hypealive:params[likes]:hint')
			),
			'params[subscriptions]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->subscriptions,
				'hint' => elgg_echo('edit:plugin:hypealive:params[subscriptions]:hint')
			),
			'params[bookmarks]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->bookmarks,
				'hint' => elgg_echo('edit:plugin:hypealive:params[bookmarks]:hint')
			),
			'params[shares]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->shares,
				'hint' => elgg_echo('edit:plugin:hypealive:params[shares]:hint')
			),
			'params[livesearch]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->livesearch,
				'hint' => elgg_echo('edit:plugin:hypealive:params[livesearch]:hint')
			),
		),
		'buttons' => false
	);

	return $config;
}
