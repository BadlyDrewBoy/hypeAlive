<?php

$ia = elgg_set_ignore_access(true);

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '500');

run_function_once('hj_alive_1365760418');

elgg_set_ignore_access($ia);

function hj_alive_1365760418() {

	$setting = elgg_get_plugin_setting('notifications', 'hypeAlive');
	elgg_set_plugin_setting('notifications', serialize(array_map('trim', explode(',', $setting))), 'hypeAlive');

	$setting = elgg_get_plugin_setting('river_comments', 'hypeStyler');
	elgg_set_plugin_setting('river_comments', ($setting == 'on') ? true : false, 'hypeStyler');

	$setting = elgg_get_plugin_setting('entity_comments', 'hypeStyler');
	elgg_set_plugin_setting('entity_comments', ($setting == 'on') ? true : false, 'hypeStyler');

	$setting = elgg_get_plugin_setting('forum_comments', 'hypeStyler');
	elgg_set_plugin_setting('forum_comments', ($setting == 'on') ? true : false, 'hypeStyler');

	$setting = elgg_get_plugin_setting('livesearch', 'hypeStyler');
	elgg_set_plugin_setting('livesearch', ($setting == 'on') ? true : false, 'hypeStyler');

	$setting = elgg_get_plugin_setting('plusone', 'hypeStyler');
	elgg_set_plugin_setting('plusone', ($setting == 'on') ? true : false, 'hypeStyler');

}