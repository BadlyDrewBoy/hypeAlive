<?php

$guid_one = get_input('guid_one');
$guid_two = get_input('guid_two');

$target = get_entity($guid_two);

if (elgg_instanceof($target) && $target->canEdit() && $guid_one && check_entity_relationship($guid_one, 'attached', $guid_two)) {
	remove_entity_relationship($guid_one, 'attached', $guid_two);
	system_message(elgg_echo('hj:alive:attachments:detach:success'));
	forward(REFERER);
}

register_error(elgg_echo('hj:alive:attachments:detach:error'));
forward(REFERER);