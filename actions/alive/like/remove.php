<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (hj_alive_does_user_like($entity)) {

	$likes = elgg_get_annotations(array(
		'guids' => $guid,
		'annotation_owner_guid' => elgg_get_logged_in_user_guid(),
		'annotation_names' => 'likes',
			));

	foreach ($likes as $like) {
		$like->delete();
	}

	if (elgg_is_xhr()) {
		print json_encode(array(
					'container_guid' => $entity->guid,
					'stats' => hj_alive_get_stream_stats($entity)
				));
	}

	system_message(elgg_echo('hj:alive:like:remove:success'));
	forward(REFERER);
}

register_error(elgg_echo('hj:alive:like:remove:error'));
forward(REFERER);