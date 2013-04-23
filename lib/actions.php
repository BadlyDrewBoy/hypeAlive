<?php

$shortcuts = hj_framework_path_shortcuts('hypeAlive');

elgg_register_action('hypeAlive/settings/save', $shortcuts['actions'] . 'settings/save.php', 'admin');
elgg_register_action('hypeAlive/usersettings/save', $shortcuts['actions'] . 'usersettings/save.php');

elgg_register_action('alive/admin/comments/import', $shortcuts['actions'] . 'admin/import.php', 'admin');

if (HYPEALIVE_COMMENTS) {
	elgg_register_action('comment/save', $shortcuts['actions'] . 'comment/save.php');
	elgg_register_action('reply/save', $shortcuts['actions'] . 'comment/save.php');
}

if (HYPEALIVE_LIKES) {
	elgg_register_action('like/get', $shortcuts['actions'] . 'like/get.php', 'public');
	elgg_register_action('like/save', $shortcuts['actions'] . 'like/save.php');
}

if (HYPEALIVE_SEARCH) {
	elgg_register_action('livesearch/parse', $shortcuts['actions'] . 'hj/livesearch/parse.php', 'public');
}