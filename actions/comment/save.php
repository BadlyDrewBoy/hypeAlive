<?php

$description = get_input('description', false);
if (!$description || empty($description)) {
	register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
	forward(REFERER);
}

$guid = get_input('guid', null);

$container_guid = get_input('container_guid', null);
if ($container_guid && !empty($container_guid)) {
	$container = get_entity($container_guid);
	$list_id = "comments-$container_guid";
}

$annotation = new hjComment($guid);
$annotation->description = $description;
if (!$guid) {
	$annotation->title = get_input('title', '');
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	$annotation->container_guid = $container_guid;
	$annotation->access_id = $container->access_id;
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