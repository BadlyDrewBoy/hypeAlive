<?php

$ia = elgg_set_ignore_access(true);

ini_set('memory_limit', '512M');
set_time_limit(0);

run_function_once('hj_alive_1365760418');
run_function_once('hj_alive_1366567725');

elgg_set_ignore_access($ia);

function hj_alive_1365760418() {

	$setting = elgg_get_plugin_setting('notifications', 'hypeAlive');
	elgg_set_plugin_setting('notifications', serialize(array_map('trim', explode(',', $setting))), 'hypeAlive');

	$setting = elgg_get_plugin_setting('river_comments', 'hypeAlive');
	elgg_set_plugin_setting('river_comments', ($setting == 'on') ? true : false, 'hypeAlive');

	$setting = elgg_get_plugin_setting('entity_comments', 'hypeAlive');
	elgg_set_plugin_setting('comments', ($setting == 'on') ? true : false, 'hypeAlive');
	elgg_unset_plugin_setting('entity_comments', 'hypeAlive');

	$setting = elgg_get_plugin_setting('forum_comments', 'hypeAlive');
	elgg_set_plugin_setting('forum_comments', ($setting == 'on') ? true : false, 'hypeAlive');

	$setting = elgg_get_plugin_setting('livesearch', 'hypeAlive');
	elgg_set_plugin_setting('livesearch', ($setting == 'on') ? true : false, 'hypeAlive');

	$setting = elgg_get_plugin_setting('plusone', 'hypeAlive');
	elgg_set_plugin_setting('plusone', ($setting == 'on') ? true : false, 'hypeAlive');
}

function hj_alive_1366567725() {

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
	
}