<?php

$shortcuts = hj_framework_path_shortcuts('hypeAlive');

elgg_register_action('hypeAlive/settings/save', $shortcuts['actions'] . 'settings/save.php', 'admin');
elgg_register_action('hypeAlive/usersettings/save', $shortcuts['actions'] . 'usersettings/save.php');

elgg_register_action('alive/admin/upgrade', $shortcuts['actions'] . 'admin/upgrade.php', 'admin');
elgg_register_action('alive/admin/import', $shortcuts['actions'] . 'admin/import.php', 'admin');

elgg_register_action('alive/bookmark', $shortcuts['actions'] . 'alive/bookmark/default.php');
elgg_register_action('alive/bookmark/create', $shortcuts['actions'] . 'alive/bookmark/create.php');
elgg_register_action('alive/bookmark/remove', $shortcuts['actions'] . 'alive/bookmark/remove.php');

elgg_register_action('alive/subscription', $shortcuts['actions'] . 'alive/subscription/default.php');
elgg_register_action('alive/subscription/create', $shortcuts['actions'] . 'alive/subscription/create.php');
elgg_register_action('alive/subscription/remove', $shortcuts['actions'] . 'alive/subscription/remove.php');

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