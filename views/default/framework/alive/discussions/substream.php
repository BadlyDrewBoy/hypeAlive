<?php

if (elgg_in_context('widgets')) {
	return;
}

if (elgg_in_context('activity') && elgg_get_plugin_user_setting('river_grouping', elgg_get_logged_in_user_guid(), 'hypeAlive') !== 'grouped') {
	return;
}

$options = array();
$entity = elgg_extract('entity', $vars);
$item = elgg_extract('item', $vars, false);
$user = elgg_get_logged_in_user_entity();

$dbprefix = elgg_get_config('dbprefix');

$options['joins'][] = "JOIN {$dbprefix}entities object ON object.guid = rv.object_guid";
$options['wheres'][] = "(rv.object_guid = $entity->guid) OR (rv.subtype = 'hjcomment' AND object.container_guid = $entity->guid)";
if ($item->id > 0) {
	$options['wheres'][] = "rv.id != $item->id";
}
$options['wheres'][] = get_access_sql_suffix('object');
//$options['wheres'][] = 'rv.action_type LIKE "discussions:%"';

$list_id = "subdiscussions-$entity->guid";

$limit = (int) get_input("__lim_$list_id", false);

if (!$limit) {
	//$limit = HYPEALIVE_RIVER_LIMIT;
	$limit = 5;
	set_input("__lim_$list_id", $limit);
}

$list_options = array(
	'list_type' => 'subdiscussions',
	'list_class' => 'elgg-river-subdiscussions',
	'pagination' => true,
	'pagination_type' => 'river',
	'pagination_position' => 'after',
	'base_url' => "discussions/subdiscussions/$entity->guid"
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