<?php

$ia = elgg_set_ignore_access(true);

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '500');

run_function_once('hj_alive_1365760418');
run_function_once('hj_alive_1366567715');

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

function hj_alive_1366567715() {

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

	$subtypeIdComment = get_subtype_id('object', 'hjcomment');
	$subtypeIdGroupTopicPost = get_subtype_id('object', 'hjgrouptopicpost');
	$subtypeIdAnnotation = get_subtype_id('object', 'hjannotation');

	$dbprefix = elgg_get_config('dbprefix');

	/**
	 * Upgrade :
	 * 1. Convert hjAnnotation objects for generic_comment handlers to hjComment object
	 * 2. Convert hjAnnotation object for group_topic_post handlers to hjGroupTopicPost object
	 */
	$query = "	UPDATE {$dbprefix}entities e
				JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid
				JOIN {$dbprefix}metastrings msn ON msn.id = md.name_id
				JOIN {$dbprefix}metastrings msv ON msv.id = md.value_id
				SET e.subtype = $subtypeIdComment
				WHERE e.subtype = $subtypeIdAnnotation AND msn.string = 'handler' AND msv.string = 'generic_comment'	";

	update_data($query);

	$query = "	UPDATE {$dbprefix}entities e
				JOIN {$dbprefix}metadata md ON md.entity_guid = e.guid
				JOIN {$dbprefix}metastrings msn ON msn.id = md.name_id
				JOIN {$dbprefix}metastrings msv ON msv.id = md.value_id
				SET e.subtype = $subtypeIdGroupTopicPost
				WHERE e.subtype = $subtypeIdAnnotation AND msn.string = 'handler' AND msv.string = 'group_topic_post'	";

	update_data($query);

	elgg_delete_metadata(array(
		'types' => 'object',
		'subtypes' => array('hjcomment', 'hjgrouptopicpost'),
		'metadata_names' => 'handler',
		'limit' => 0
	));

	
}