<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $guid)) {
	remove_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $guid);
	if (!check_entity_relationship(elgg_get_logged_in_user_guid(), 'unsubscribed', $guid)) {
		// let's keep this info so that we do not resubscribe the user after he/she explicitly unsubscribes
		add_entity_relationship(elgg_get_logged_in_user_guid(), 'unsubscribed', $guid);
	}

	elgg_delete_river(array(
		'action_type' => 'stream:subscription',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $entity->guid
	));

	if (elgg_is_xhr()) {
		print json_encode(array(
					'container_guid' => $entity->guid,
					'stats' => hj_alive_get_stream_stats($entity)
				));
	}

	system_message(elgg_echo('hj:alive:subscription:remove:success'));
} else {
	register_error(elgg_echo('hj:alive:subscription:remove:error'));
}