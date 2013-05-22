<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!hj_alive_does_user_like($entity)) {

	$id = create_annotation($entity->guid, 'likes', 1, '', elgg_get_logged_in_user_guid(), $entity->access_id);

	if ($id) {

		add_to_river('framework/river/feed/like', 'feed:like', elgg_get_logged_in_user_guid(), $entity->guid, $entity->access_id, time(), $id);
		
		// Subscribe user to this item
		$container = $entity;
		while ($check) {
			if (!elgg_instanceof($container, 'object', 'hjcomment')) {
				$check = false;
			} else {
				$container = $container->getContainerEntity();
			}
		}
		if (!check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $container->guid)
				&& !check_entity_relationship(elgg_get_logged_in_user_guid(), 'unsubscribed', $container->guid)) {
			add_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $container->guid);
		}

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