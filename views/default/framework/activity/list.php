<?php

elgg_load_js('alive.river.js');
elgg_load_css('alive.river.css');

$options = array();

$user = elgg_get_page_owner_entity();

$page_type = elgg_extract('page_type', $vars, HYPEALIVE_RIVER_DEFAULT);
$type = elgg_extract('type', $vars, 'all');
$subtype = elgg_extract('subtype', $vars, '');

if (!$pair = get_input('type_subtype_pairs', false)) {
	if ($type != 'all') {
		$options['type'] = $type;
		if ($subtype) {
			$options['type_subtype_pairs'] = array($type => $subtype);
		}
	}
} else {
	$options['type_subtype_pairs'] = $pair;
}

if (elgg_instanceof($user, 'user')) {

	$dbprefix = elgg_get_config('dbprefix');

	$options['joins'][] = "JOIN {$dbprefix}entities object ON object.guid = rv.object_guid";

	$hidden_types_subtypes = elgg_get_plugin_user_setting('river_hidden_types_subtypes', $user->guid, 'hypeAlive');
	if ($hidden_types_subtypes) {
		$value = unserialize($hidden_types_subtypes);
		if (!empty($value)) {
			$in = implode(',', $value);
			$options['wheres'][] = "object.subtype NOT IN ($in)";
		}
	}
	$hidden_users = elgg_get_plugin_user_setting('river_hidden_users', $user->guid, 'hypeAlive');
	if ($hidden_users) {
		$value = unserialize($hidden_users);
		if (!empty($value)) {
			$in = implode(',', $value);
			$options['wheres'][] = "rv.subject_guid NOT IN ($in)";
		}
	}

	$hidden_groups = elgg_get_plugin_user_setting('river_hidden_groups', $user->guid, 'hypeAlive');
	if ($hidden_groups) {
		$value = unserialize($hidden_groups);
		if (!empty($value)) {
			$in = implode(',', $value);
			$options['wheres'][] = "object.container_guid NOT IN ($in)";
		}
	}

	switch ($page_type) {
		case 'mine':
			$options['subject_guid'] = $user->guid;
			break;

		case 'friends':
			$options['relationship_guid'] = $user->guid;
			$options['relationship'] = 'friend';
			break;

		case 'groups' :
			$group_guids = array(-1);
			$options = array(
				'relationship' => 'member',
				'relationship_guid' => $user->guid,
				'inverse_relationship' => false,
				'limit' => 0
			);
			$groups = elgg_get_entities_from_relationship($options);
			if ($groups) {
				foreach ($groups as $group) {
					$group_guids[] = $group->guid;
				}
			}
			$in_groups = implode(',', $group_guids);
			$options['wheres'][] = "(object.container_guid IN ($in_groups))";
			break;

		case 'subscriptions' :

			break;

		default:
			break;
	}
}

$list_id = elgg_extract('list_id', $vars, 'activity');
if (!get_input("__lim_$list_id", false)) {
	set_input("__lim_$list_id", 25);
}

$getter_options = $options;

$list_options = array(
	'list_class' => 'elgg-river-layout',
	'pagination' => true,
	'limit_select_options' => array(25, 50, 100, 200)
);

$viewer_options = array(
	'full_view' => false
);

$content .= hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_river');

echo $content;