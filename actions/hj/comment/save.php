<?php

if (!get_input('annotation_value')) {
	register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
	forward(REFERER);
}

$annotation_guid = get_input('annotation_guid', null);
$container_guid = get_input('container_guid', null);
$container = get_entity($container_guid);
$river_id = get_input('river_id', false);


if (!$annotation_guid && !$river_id && !elgg_instanceof($container)) {
	register_error(elgg_echo('hj:comments:cantfind'));
	forward(REFERER);
}

$annotation = new hjAnnotation($annotation_guid);
$annotation->annotation_value = get_input('annotation_value', '');
if (!$annotation_guid) {
	$annotation->annotation_name = get_input('aname', 'generic_comment');
	$annotation->title = get_input('title', '');
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	$annotation->container_guid = $container_guid;
	$annotation->river_id = $river_id;
	$annotation->access_id = get_input('access_id', ACCESS_DEFAULT);
}
if ($annotation->save()) {
	system_message(elgg_echo('hj:comments:savesuccess'));
	forward($annotation->getURL());
} else {
	register_error(elgg_echo('hj:comments:saveerror'));
	forward(REFERER);
}