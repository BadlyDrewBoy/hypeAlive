<?php

$guid = get_input('guid');

if (check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $guid)) {
	action('alive/subscription/remove');
} else {
	action('alive/subscription/create');
}

forward(REFERER);
