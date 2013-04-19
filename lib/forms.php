<?php

elgg_register_plugin_hook_handler('init', 'form:edit:plugin:hypealive', 'hj_alive_init_plugin_settings_form');

function hj_alive_init_plugin_settings_form($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	$river_tabs = ($entity->river_tabs) ? unserialize($entity->river_tabs) : array();

	// Types and subtypes to rate
	$dbprefix = elgg_get_config('dbprefix');
	$data = get_data("SELECT type AS type, subtype AS subtype
								FROM {$dbprefix}entity_subtypes");

	foreach ($data as $r) {
		$type = $r->type;
		$subtype = $r->subtype;

		$types[$type][] = $subtype;

		$str = elgg_echo("item:$type:$subtype");
		$subtype_options[$str] = "$type:$subtype";
	}

	if (!array_key_exists('user', $types)) {
		$str = elgg_echo("item:user");
		$subtype_options[$str] = "user:default";
	}

	if (!array_key_exists('group', $types)) {
		$str = elgg_echo("item:group");
		$subtype_options[$str] = "group:default";
	}

	$config = array(
		'fields' => array(
			'params[comments]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->comments,
				'hint' => elgg_echo('edit:plugin:hypealive:params[comments]:hint')
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
			'params[entity_comments]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->entity_comments,
				'hint' => elgg_echo('edit:plugin:hypealive:params[entity_comments]:hint')
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
			'params[max_comment_depth]' => array(
				'input_type' => 'dropdown',
				'options' => array(0,5,4,3,2,1),
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
			'params[likes]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->likes,
				'hint' => elgg_echo('edit:plugin:hypealive:params[likes]:hint')
			),
			'params[dislikes]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->likes,
				'hint' => elgg_echo('edit:plugin:hypealive:params[dislikes]:hint')
			),
			'params[likes_style]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'like' => elgg_echo('hj:alive:likes:style:like'),
					'plusone' => elgg_echo('hj:alive:likes:style:plusone'),
					'kudo' => elgg_echo('hj:alive:likes:style:kudo'),
					'vote' => elgg_echo('hj:alive:likes:style:vote'),
				),
				'value' => $entity->likes,
				'hint' => elgg_echo('edit:plugin:hypealive:params[likes_style]:hint')
			),
			'params[likes_limit]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					'one_only' => elgg_echo('hj:alive:likes:limit:one_only'),
					'one_each' => elgg_echo('hj:alive:likes:limit:one_each')
				),
				'value' => $entity->likes,
				'hint' => elgg_echo('edit:plugin:hypealive:params[likes_limit]:hint')
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
			'params[river]' => array(
				'input_type' => 'dropdown',
				'options_values' => array(
					0 => elgg_echo('disable'),
					1 => elgg_echo('enable')
				),
				'value' => $entity->river,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river]:hint')
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
			'params[river_tabs][subscriptions]' => array(
				'value' => $river_tabs['subscriptions'],
				'maxlength' => 2,
				'hint' => elgg_echo('edit:plugin:hypealive:params[river_tabs]:hint')
			),
			
		),
		'buttons' => false
	);

	return $config;
}
