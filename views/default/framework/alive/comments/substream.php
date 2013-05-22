<?php

$options = array();
$entity = elgg_extract('entity', $vars);

$user = elgg_get_logged_in_user_entity();

$dbprefix = elgg_get_config('dbprefix');

$options['joins'][] = "JOIN {$dbprefix}entities object ON object.guid = rv.object_guid";
$options['wheres'][] = "rv.object_guid = $entity->guid";
$options['wheres'][] = get_access_sql_suffix('object');
$options['wheres'][] = 'rv.action_type LIKE "feed:%"';

$list_id = "substream-$entity->guid";

$limit = (int) get_input("__lim_$list_id", false);

if (!$limit) {
	$limit = HYPEALIVE_RIVER_LIMIT;
	set_input("__lim_$list_id", $limit);
}

$list_options = array(
	'list_type' => 'substream',
	'list_class' => 'elgg-river-substream',
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