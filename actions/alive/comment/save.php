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

	if (!$guid && $annotation->owner_guid != $container->owner_guid) {
		$commenter = $annotation->getOwnerEntity();
		$commentee = $container->getOwnerEntity();

		if (elgg_instanceof($container, 'object', 'hjcomment')) {
			// Notify about a reply
			$subject = elgg_echo('hj:alive:reply:email:subject', array($commenter->name));
			$body = elgg_view('framework/alive/notifications/reply', array(
				'entity' => $annotation
					));
			notify_user($commentee->guid, $commenter->guid, $subject, $body);
		} else {
			// Notify about a comment
			$subject = elgg_echo('hj:alive:comment:email:subject', array($commenter->name));
			$body = elgg_view('framework/alive/notifications/comment', array(
				'entity' => $annotation
					));
			notify_user($commentee->guid, $commenter->guid, $subject, $body);
		}
	}

	add_to_river('framework/river/feed/comment', 'feed:comment', $annotation->owner_guid, $annotation->getOriginalContainer()->guid, $annotation->getOriginalContainer()->access_id, $annotation->time_created);
	$output = array(
		'guid' => $annotation->guid,
		'container_guid' => $annotation->container_guid,
		'view' => elgg_view('page/components/grids/elements/stream/item', array(
			'entity' => get_entity($annotation->guid)
		)),
		'stats' => hj_alive_get_stream_stats($annotation->getContainerEntity())
	);
	
	if (elgg_is_xhr()) {
		print json_encode($output);
	}
	forward(REFERER);
} else {
	register_error(elgg_echo('hj:comments:saveerror'));
	forward(REFERER);
}