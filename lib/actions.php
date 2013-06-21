<?php

$shortcuts = hj_framework_path_shortcuts('hypeAlive');

elgg_register_action('hypeAlive/settings/save', $shortcuts['actions'] . 'settings/save.php', 'admin');
elgg_register_action('hypeAlive/usersettings/save', $shortcuts['actions'] . 'usersettings/save.php');

elgg_register_action('alive/admin/upgrade', $shortcuts['actions'] . 'admin/upgrade.php', 'admin');
elgg_register_action('alive/admin/import', $shortcuts['actions'] . 'admin/import.php', 'admin');
elgg_register_action('alive/admin/graph', $shortcuts['actions'] . 'admin/graph.php', 'admin');

if (HYPEALIVE_COMMENTS) {
	elgg_register_action('comment/save', $shortcuts['actions'] . 'alive/comment/save.php');
	elgg_register_action('reply/save', $shortcuts['actions'] . 'alive/comment/save.php');
}

if (HYPEALIVE_FORUM_COMMENTS) {
	elgg_register_action('discussions/save', $shortcuts['actions'] . 'alive/discussions/save.php');
}

if (HYPEALIVE_LIKES) {
	elgg_register_action('alive/like', $shortcuts['actions'] . 'alive/like/default.php');
	elgg_register_action('alive/like/create', $shortcuts['actions'] . 'alive/like/create.php');
	elgg_register_action('alive/like/remove', $shortcuts['actions'] . 'alive/like/remove.php');
}

if (HYPEALIVE_BOOKMARKS) {
	elgg_register_action('alive/bookmark', $shortcuts['actions'] . 'alive/bookmark/default.php');
	elgg_register_action('alive/bookmark/create', $shortcuts['actions'] . 'alive/bookmark/create.php');
	elgg_register_action('alive/bookmark/remove', $shortcuts['actions'] . 'alive/bookmark/remove.php');
}

if (HYPEALIVE_SUBSCRIPTIONS) {
	elgg_register_action('alive/subscription', $shortcuts['actions'] . 'alive/subscription/default.php');
	elgg_register_action('alive/subscription/create', $shortcuts['actions'] . 'alive/subscription/create.php');
	elgg_register_action('alive/subscription/remove', $shortcuts['actions'] . 'alive/subscription/remove.php');
}

if (HYPEALIVE_SHARES) {
	elgg_register_action('alive/share', $shortcuts['actions'] . 'alive/shares/share.php');
}

if (HYPEALIVE_SEARCH) {
	elgg_register_action('livesearch/parse', $shortcuts['actions'] . 'hj/livesearch/parse.php', 'public');
}

elgg_register_action('alive/attachments/upload', $shortcuts['actions'] . 'alive/attachments/upload.php');
elgg_register_action('alive/attachments/detach', $shortcuts['actions'] . 'alive/attachments/detach.php');