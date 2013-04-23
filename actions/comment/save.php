<?php

$annotation_value = get_input('annotation_value', false);
if (!$annotation_value || empty($annotation_value)) {
	register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
	forward(REFERER);
}

$annotation_name = get_input('aname', 'generic_comment');
$annotation_guid = get_input('annotation_guid', null);

$container_guid = get_input('container_guid', null);
if ($container_guid && !empty($container_guid)) {
	$container = get_entity($container_guid);
	$list_id = "comments-$container_guid";
}

if (!$annotation_guid && !$river_item instanceof ElggRiverItem && !elgg_instanceof($container)) {
	register_error(elgg_echo('hj:comments:cantfind'));
	forward(REFERER);
}

$annotation = new hjComment($annotation_guid);
$annotation->annotation_value = $annotation_value;
if (!$annotation_guid) {
	$annotation->annotation_name = $annotation_name;
	$annotation->title = get_input('title', '');
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	$annotation->container_guid = $container_guid;
	$annotation->access_id = ($container) ? $container->access_id : $river_item->access_id;
}

if ($annotation->save()) {
	$output = array(
		'guid' => $annotation->guid,
		'view' => elgg_view('page/components/grids/elements/stream/item', array(
			'entity' => get_entity($annotation->guid)
		))
	);

	print json_encode($output);
	forward(REFERER);
} else {
	register_error(elgg_echo('hj:comments:saveerror'));
	forward(REFERER);
}