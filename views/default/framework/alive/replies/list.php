<?php

$container_guid = elgg_extract('container_guid', $vars, null);
$annotation_name = elgg_extract('aname', $vars, 'generic_comment');
$list_id = elgg_extract('list_id', $vars);

$options = array(
	'types' => 'object',
	'subtypes' => array('hjannotation'),
	'metadata_name_value_pairs' => array(
		array('name' => 'annotation_name', 'value' => $annotation_name),
	),
	'count' => true
);

if ($container_guid) {
	if (!$list_id) {
		$list_id = "comments-$container_guid";
	}
	$options['container_guids'] = $container_guid;
}

$list_options = array(
	'list_type' => 'stream',
	'list_class' => 'hj-replies-list',
	'pagination' => true,
	'pagination_type' => 'stream',
	'pagination_position' => 'after',
	'base_url' => "stream/replies/$container_guid",
);

$count = elgg_get_entities($options);
$options['count'] = false;

$limit = (int) get_input("__lim_$list_id", false);

if (!$limit) {
	$limit = HYPEALIVE_COMMENTS_LOAD_LIMIT;
	set_input("__lim_$list_id", $limit);
}

if (HYPEALIVE_COMMENTS_ORDER == 'asc') {
	if (HYPEALIVE_COMMENTS_LOAD_STYLE == 'load_older') {
		$options['order_by'] = 'e.time_created DESC';
		$list_options['pagination_position'] = 'before';
		$list_options['reverse_list'] = true;
	} else {
		$options['order_by'] = 'e.time_created ASC';
	}
} else {
	if (HYPEALIVE_COMMENTS_LOAD_STYLE == 'load_newer') {
		$options['order_by'] = 'e.time_created ASC';
		$list_options['pagination_position'] = 'before';
		$list_options['reverse_list'] = true;
	} else {
		$options['order_by'] = 'e.time_created DESC';
	}
}

$getter_options = $options;

$viewer_options = array(
	'full_view' => true
);

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities_from_metadata');

echo $content;