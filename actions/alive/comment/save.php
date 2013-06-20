<?php

/**
 * Add a new comment
 */
$description = get_input('description', false);
if (!$description || empty($description)) {
	register_error(elgg_echo('hj:alive:comments:valuecantbeblank'));
	forward(REFERER);
}

$guid = get_input('guid', null);

$container_guid = get_input('container_guid', null);
if (!empty($container_guid)) {
	$container = get_entity($container_guid);
} else {
	$container = elgg_get_logged_in_user_entity();
}

$list_id = "comments-$container_guid";

$annotation = new hjComment($guid);
$annotation->description = $description;
if (!$guid) {
	$annotation->title = get_input('title', '');
	$annotation->owner_guid = elgg_get_logged_in_user_guid();
	$annotation->container_guid = $container_guid;
	$annotation->access_id = $container->access_id;
}

if ($annotation->save()) {

	$commenter = $annotation->getOwnerEntity();
	$commentee = $container->getOwnerEntity();
	$original_container = $annotation->getOriginalContainer();

	hj_framework_process_file_upload('attachments', $annotation);

	$attachments = get_input('attachments', false);

	if (is_array($attachments)) {
		foreach ($attachments as $attachment) {
			if (!check_entity_relationship($attachment, 'attached', $annotation->guid)) {
				add_entity_relationship($attachment, 'attached', $annotation->guid);
			}
		}
	}

	// Subscribe user to this item if the autosubscribe setting is not turned off
	if ($original_container->owner_guid != $annotation->owner_guid
			&& !check_entity_relationship($annotation->owner_guid, 'subscribed', $original_container->guid)
//			&& !check_entity_relationship($annotation->owner_guid, 'unsubscribed', $original_container->guid)
			&& elgg_get_plugin_user_setting('comments_autosubscribe', $annotation->owner_guid, 'hypeAlive') !== false) {
		add_entity_relationship($annotation->owner_guid, 'subscribed', $original_container->guid);
	}

	if (!$guid) {
		if ($annotation->owner_guid != $container->owner_guid) {
			if (elgg_instanceof($container, 'object', 'hjcomment')) {
				// Notify about a reply
				$subject = elgg_echo('hj:alive:reply:email:subject');
				$body = elgg_view('framework/alive/notifications/reply', array(
					'entity' => $annotation
						));
				if (elgg_get_plugin_user_setting('notify_comments', $commentee->guid, 'hypeAlive') !== false)
					notify_user($commentee->guid, $commenter->guid, $subject, $body);
			} else {
				// Notify about a comment
				$subject = elgg_echo('hj:alive:comment:email:subject', array($commenter->name));
				$body = elgg_view('framework/alive/notifications/comment', array(
					'entity' => $annotation
						));
				if (elgg_get_plugin_user_setting('notify_comments', $commentee->guid, 'hypeAlive') !== false)
					notify_user($commentee->guid, $commenter->guid, $subject, $body);
			}
		}

		// Notify the owner of the original content
		if ($original_container->owner_guid != $commenter->guid && $original_container->owner_guid != $commentee->guid) {
			$subject = elgg_echo('hj:alive:thread:email:subject:owner');
			$body = elgg_view('framework/alive/notifications/thread', array(
				'entity' => $annotation
					));
			if (elgg_get_plugin_user_setting('notify_comments', $original_container->owner_guid, 'hypeAlive') !== false)
				notify_user($original_container->owner_guid, $commenter->guid, $subject, $body);
		}

		// Notify subscribed users
		$subscribers = $annotation->getSubscribedUsers();

		foreach ($subscribers as $key => $subscriber) {
			if ($subscriber->guid == $annotation->owner_guid) {
				// no need to notify the commenting user
				unset($subscribers[$key]);
			}
			if ($subscriber->guid == $original_container->owner_guid) {
				// just in case we accidentally subscribed the original content owner (receives separate notification)
				unset($subscribers[$key]);
			}
			if (check_entity_relationship($subscriber->guid, 'unsubscribed', $original_container->guid)) {
				// user change notification settings for this thread
				unset($subscribers[$key]);
			}
			if (elgg_get_plugin_user_setting('notify_comments', $subscriber->guid, 'hypeAlive') == false) {
				unset($subscribers[$key]);
			}
		}

		if (count($subscribers)) {
			foreach ($subscribers as $subscriber) {
				$to[] = $subscriber->guid;
			}
			$subject = elgg_echo('hj:alive:thread:email:subject');
			$body = elgg_view('framework/alive/notifications/thread', array(
				'entity' => $annotation
					));
			notify_user($to, $commenter->guid, $subject, $body);
		}

		if (elgg_instanceof($container, 'object', 'hjcomment')) {
			add_to_river('framework/river/stream/comment', 'stream:reply', $annotation->owner_guid, $annotation->guid, $container->access_id, $annotation->time_created);
		} else {
			add_to_river('framework/river/stream/comment', 'stream:comment', $annotation->owner_guid, $annotation->guid, $container->access_id, $annotation->time_created);
		}
	}


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