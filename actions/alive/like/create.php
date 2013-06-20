<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!hj_alive_does_user_like($entity)) {

	$id = create_annotation($entity->guid, 'likes', 1, '', elgg_get_logged_in_user_guid(), $entity->access_id);

	if ($id) {

		add_to_river('framework/river/stream/like', 'stream:like', elgg_get_logged_in_user_guid(), $entity->guid, $entity->access_id, time(), $id);

		// Subscribe user to this item if the autosubscribe setting is not turned off
		if (!$entity instanceof hjComment && $entity->owner_guid != elgg_get_logged_in_user_guid()
				&& !check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $entity->guid)
//				&& !check_entity_relationship(elgg_get_logged_in_user_guid(), 'unsubscribed', $entity->guid)
				&& elgg_get_plugin_user_setting('comments_autosubscribe', elgg_get_logged_in_user_guid(), 'hypeAlive') !== false) {
			add_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $entity->guid);
		}

		// Notify entity owner
		$subject = elgg_echo('hj:alive:like:email:subject');
		$body = elgg_view('framework/alive/notifications/like', array(
			'entity' => $entity,
			'user' => elgg_get_logged_in_user_entity()
				));
		if (elgg_get_plugin_user_setting('notify_likes', $entity->owner_guid, 'hypeAlive') !== false)
			notify_user($entity->owner_guid, elgg_get_logged_in_user_guid(), $subject, $body);

		if (elgg_is_xhr()) {
			print json_encode(array(
						'container_guid' => $entity->guid,
						'stats' => hj_alive_get_stream_stats($entity)
					));
		}

		system_message(elgg_echo('hj:alive:like:create:success'));
		forward(REFERER);
	}
}

register_error(elgg_echo('hj:alive:like:create:error'));
forward(REFERER);