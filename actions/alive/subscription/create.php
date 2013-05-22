<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (!check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $guid)) {
	add_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $guid);

	add_to_river('framework/river/feed/subscription', 'feed:subscription', elgg_get_logged_in_user_guid(), $entity->guid, $entity->access_id, time(), $id);

	if (elgg_is_xhr()) {
		print json_encode(array(
					'container_guid' => $entity->guid,
					'stats' => hj_alive_get_stream_stats($entity)
				));
	}

	system_message(elgg_echo('hj:alive:subscription:create:success'));
	forward(REFERER);
}

register_error(elgg_echo('hj:alive:subscription:create:error'));
forward(REFERER);