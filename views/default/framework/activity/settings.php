<?php

$user = elgg_get_page_owner_entity();

$hidden_types_subtypes = elgg_get_plugin_user_setting('river_hidden_types_subtypes', $user->guid, 'hypeAlive');
$hidden_users = elgg_get_plugin_user_setting('river_hidden_users', $user->guid, 'hypeAlive');
$hidden_groups = elgg_get_plugin_user_setting('river_hidden_groups', $user->guid, 'hypeAlive');

$registered_entities = elgg_get_config('registered_entities');

if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		if (!count($subtypes)) {
			//$label = elgg_echo("item:$type");
			//$options[$label] = $type;
		} else {
			foreach ($subtypes as $subtype) {
				$label = elgg_echo("item:$type:$subtype");
				$options[$label] = get_subtype_id($type, $subtype);
			}
		}
	}
}

if ($options) {
	$title = elgg_echo('hj:alive:river:hidden_types_subtypes');
	$body = elgg_view('input/checkboxes', array(
		'name' => 'params[river_hidden_types_subtypes]',
		'options' => $options,
		'value' => ($hidden_types_subtypes) ? unserialize($hidden_types_subtypes) : null
			));
	$form_body .= elgg_view_module('info', $title, $body);
}

$title = elgg_echo('hj:alive:river:hidden_users');
$body = elgg_view('input/userpicker', array(
	'value' => $hidden_users ? unserialize($hidden_users) : null
		));
$form_body .= elgg_view_module('info', $title, $body);

$groups = elgg_get_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false,
	'limit' => 0
		));
if ($groups) {
	foreach ($groups as $group) {
		$group_options[$group->name] = $group->guid;
	}
}
if ($group_options) {
	$title = elgg_echo('hj:alive:river:hidden_groups');
	$body = elgg_view('input/checkboxes', array(
		'name' => 'params[river_hidden_groups]',
		'options' => $group_options,
		'value' => ($hidden_groups) ? unserialize($hidden_groups) : null
			));
	$form_body .= elgg_view_module('info', $title, $body);
}

$title = elgg_echo('hj:alive:river:river_grouping');
$body = elgg_view('input/dropdown', array(
	'name' => 'params[river_grouping]',
	'options_values' => array(
		'default' => elgg_echo('hj:alive:river:river_grouping:default'),
		'grouped' => elgg_echo('hj:alive:river:river_grouping:grouped')
	),
	'value' => elgg_get_plugin_user_setting('river_grouping', $user->guid, 'hypeAlive')
));
$form_body .= elgg_view_module('info', $title, $body);

$form_body .= elgg_view('input/hidden', array(
	'name' => 'user_guid',
	'value' => $user->guid
		));

$form_body .= elgg_view('input/submit');

echo elgg_view('input/form', array(
	'action' => 'action/hypeAlive/usersettings/save',
	'body' => $form_body
));

