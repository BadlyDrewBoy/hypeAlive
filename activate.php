<?php

$defaults = array(
	'comments' => true,
	'likes' => true,
	'livesearch' => true,
	'river' => true,

	'river_comments' => true,
	'entity_comments' => true,
	'forum_comments' => false,

	'river_tabs' => serialize(array('all' => 1, 'mine' => 2, 'friends' => 3))
);

foreach ($defaults as $name => $value) {
	if (!elgg_get_plugin_setting($name, 'hypeAlive')) {
		elgg_set_plugin_setting($name, $value, 'hypeAlive');
	}
}