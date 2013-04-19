<?php

$container_guid = elgg_extract('container_guid', $vars, null);
$river_id = elgg_extract('river_id', $vars, null);
$annotation_name = elgg_extract('aname', $vars, 'generic_comment');

$options = array(
	'types' => 'object',
	'subtypea' => array('hjannotation'),
	'metadata_name_value_pairs' => array(
		array('name' => 'annotation_name', 'value' => $annotation_name),
	),
	'count' => false,
	'order_by' => 'e.time_created desc'
);

if ($container_guid) {
	$list_id = "enc$container_guid";
	$options['container_guids'] = $container_guid;
}

if ($river_id) {
	$list_id = "ric$river_id";
	$options['metadata_name_value_pairs'][] = array('name' => 'river_id', 'value' => $river_id);
}

if (elgg_is_xhr()) {
	set_input("__lim_$list_id", 10);
} else if (!get_input("__lim_$list_id", false)) {
	set_input("__lim_$list_id", 3);
}

$annotation_guid = elgg_extract('annotation_guid', $vars, false);
if ($annotation_guid) {
	set_input("__lim_$list_id", 0);
	set_input("__off_$list_id", 0);
}
$getter_options = $options;

$list_options = array(
	'list_class' => 'hj-comments-list',
	'pagination' => true,
	'pagination_type' => 'comments',
);

$viewer_options = array(
	'full_view' => true
);

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities_from_metadata');

echo $content;