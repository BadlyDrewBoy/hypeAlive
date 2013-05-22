<?php

elgg_load_js('alive.river.js');
elgg_load_css('alive.river.css');

$options = array();

$user = elgg_get_page_owner_entity();
if (!$user) {
	$user = elgg_get_logged_in_user_entity();
}
$page_type = elgg_extract('page_type', $vars, HYPEALIVE_RIVER_DEFAULT);

if (elgg_instanceof($user, 'user')) {

	$dbprefix = elgg_get_config('dbprefix');

	$options['joins'][] = "JOIN {$dbprefix}entities object ON object.guid = rv.object_guid";
	$options['joins'][] = "JOIN {$dbprefix}entities subject ON subject.guid = rv.subject_guid";
	$options['group_by'] = "rv.object_guid";
	
	$options['wheres'][] = get_access_sql_suffix('object');
	if (elgg_is_admin_logged_in()) {
		$options['wheres'][] = "object.enabled = 'yes' AND subject.enabled = 'yes'";
	}
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
			$options['wheres'][] = 'rv.action_type NOT LIKE "feed:%"';
			break;

		case 'friends':
			$options['relationship_guid'] = $user->guid;
			$options['relationship'] = 'friend';
			$options['wheres'][] = 'rv.action_type NOT LIKE "feed:%"';
			break;

		case 'groups' :
			$group_guids = array(-1);
			$group_options = array(
				'relationship' => 'member',
				'relationship_guid' => $user->guid,
				'inverse_relationship' => false,
				'limit' => 0
			);
			$groups = elgg_get_entities_from_relationship($group_options);
			if ($groups) {
				foreach ($groups as $group) {
					$group_guids[] = $group->guid;
				}
			}
			$in_groups = implode(',', $group_guids);
			$options['wheres'][] = "(object.container_guid IN ($in_groups))";
			$options['wheres'][] = 'rv.action_type NOT LIKE "feed:%"';
			break;

		case 'subscriptions' :
			$msnid = get_metastring_id('river_id');
			$options['joins'][] = "JOIN {$dbprefix}entity_relationships er ON (er.guid_one = $user->guid AND er.relationship = 'subscribed')";
			$options['wheres'][] = "(rv.object_guid = er.guid_two OR rv.id IN (
				SELECT msv.string
				FROM {$dbprefix}metadata md
				JOIN {$dbprefix}metastrings msv ON md.value_id = msv.id
				WHERE md.entity_guid = er.guid_two AND md.name_id = $msnid
					))";
			$options['wheres'][] = 'rv.action_type LIKE "feed:%"';
			break;

		case 'bookmarks' :
			$msnid = get_metastring_id('river_id');
			$options['joins'][] = "JOIN {$dbprefix}entity_relationships er ON (er.guid_one = $user->guid AND er.relationship = 'bookmarked')";
			$options['wheres'][] = "(rv.object_guid = er.guid_two OR rv.id IN (
				SELECT msv.string
				FROM {$dbprefix}metadata md
				JOIN {$dbprefix}metastrings msv ON md.value_id = msv.id
				WHERE md.entity_guid = er.guid_two AND md.name_id = $msnid
					))";
			$options['wheres'][] = 'rv.action_type NOT LIKE "feed:%"';
			break;

		default:
		case 'everyone' :
		case 'all' :
			$options['wheres'][] = 'rv.action_type NOT LIKE "feed:%"';
			break;
	}
}

$list_id = elgg_extract('list_id', $vars, 'activity');

$limit = (int) get_input("__lim_$list_id", false);

if (!$limit) {
	$limit = HYPEALIVE_RIVER_LIMIT;
	set_input("__lim_$list_id", $limit);
}

$list_options = array(
	'list_type' => 'stream',
	'list_class' => 'elgg-river',
	'pagination' => true,
	'pagination_type' => 'river',
	'pagination_position' => 'after'
);

if (HYPEALIVE_RIVER_ORDER == 'asc') {
	if (HYPEALIVE_RIVER_LOAD_STYLE == 'load_older') {
		$options['order_by'] = 'rv.posted DESC';
		$list_options['pagination_position'] = 'before';
		$list_options['reverse_list'] = true;
	} else {
		$options['order_by'] = 'rv.posted ASC';
	}
} else {
	if (HYPEALIVE_RIVER_LOAD_STYLE == 'load_newer') {
		$options['order_by'] = 'rv.posted ASC';
		$list_options['pagination_position'] = 'before';
		$list_options['reverse_list'] = true;
	} else {
		$options['order_by'] = 'rv.posted DESC';
	}
}

$getter_options = $options;

$viewer_options = array(
	'full_view' => false
);

$content .= hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_river');

echo $content;