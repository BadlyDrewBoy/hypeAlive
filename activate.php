<?php

$defaults = array(
	'comments' => true,
	'likes' => true,
	'livesearch' => true,
	'river' => true,
	'river_comments' => true,
	'entity_comments' => true,
	'forum_comments' => false,
	'comments_order' => 'asc',
	'comments_load_style' => 'load_older',
	'comments_limit' => 5,
	'comments_load_limit' => 100,
	'river_tabs' => serialize(array('all' => 1, 'mine' => 2, 'friends' => 3)),
);

foreach ($defaults as $name => $value) {
	if (!elgg_get_plugin_setting($name, 'hypeAlive')) {
		elgg_set_plugin_setting($name, $value, 'hypeAlive');
	}
}

$subtypes = array(
	'hjcomment' => 'hjComment',
	'hjgrouptopicpost' => 'hjGroupTopicPost'
);

foreach ($subtypes as $subtype => $class) {
	if (get_subtype_id('object', $subtype)) {
		update_subtype('object', $subtype, $class);
	} else {
		add_subtype('object', $subtype, $class);
	}
}