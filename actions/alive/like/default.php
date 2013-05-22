<?php

$guid = get_input('guid');
$entity = get_entity($guid);

if (hj_alive_does_user_like($entity)) {
	action('alive/like/remove');
} else {
	action('alive/like/create');
}