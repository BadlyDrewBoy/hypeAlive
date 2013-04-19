<?php
$annotation_value = get_input('annotation_value', false);
if (!$annotation_value || empty($annotation_value)) {
	register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
	forward(REFERER);
}

$annotation_name = get_input('aname', 'generic_comment');
$annotation_guid = get_input('annotation_guid', null);

$container_guid = get_input('container_guid', null);
if ($container_guid) {
	$container = get_entity($container_guid);
	$list_id = "enc$container_guid";
}

$river_id = get_input('river_id', false);
if ($river_id) {
	$river = elgg_get_river(array('ids' => array($river_id)));
	$river_item = $river[0];
	$list_id = "ric$river_id";
}

if (!$annotation_guid && !$river_item instanceof ElggRiverItem && !elgg_instanceof($container)) {
	register_error(elgg_echo('hj:comments:cantfind'));
	forward(REFERER);
}

$annotation = new hjAnnotation($annotation_guid);
$annotation->annotation_value = $annotation_value;
if (!$annotation_guid) {
	$annotation->annotation_name = $annotation_name;
	$annotation->title = get_input('title', '');
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	$annotation->container_guid = $container_guid;
	$annotation->river_id = $river_id;
	$annotation->access_id = ($container) ? $container->access_id : $river_item->access_id;
}

if ($annotation->save()) {
	system_message(elgg_echo('hj:comments:savesuccess'));

	set_input('view', 'xhr');
	set_input('endpoint', 'list');
	set_input('list_id', $list_id);
	
	$list = elgg_view('framework/alive/comments/list', array(
		'container_guid' => $container_guid,
		'river_id' => $river_id,
		'aname' => $annotation_name,
		'annotation_guid' => $annotation->guid
	));
	$layout = elgg_view_layout('default', array(
		'content' => $list
	));
	echo elgg_view_page('', $layout);
	forward(REFERER);
} else {
	register_error(elgg_echo('hj:comments:saveerror'));
	forward(REFERER);
}