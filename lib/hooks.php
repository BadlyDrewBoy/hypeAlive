<?php

// Custom activity filter clause
if (HYPEALIVE_RIVER) {
	elgg_register_plugin_hook_handler('custom_sql_clause', 'framework:lists', 'hj_alive_filter_activity');
}
if (HYPEALIVE_COMMENTS || HYPEALIVE_LIKES || HYPEALIVE_DISLIKES) {
	// Register default comments bar
	elgg_register_plugin_hook_handler('comments', 'all', 'hj_alive_comments_replacement');
}

if (HYPEALIVE_LIKES) {
	elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
}

if (HYPEALIVE_COMMENTS) {
// Search comments
	elgg_unregister_plugin_hook_handler('search', 'comments', 'search_comments_hook');
	elgg_register_plugin_hook_handler('search', 'comments', 'hj_alive_search_comments_hook');
}


elgg_register_plugin_hook_handler('hj:notification:setting', 'annotation', 'hj_alive_notification_settings');

function hj_alive_filter_activity($hook, $type, $options, $params) {

	$query = get_input("__tsp", false);

	if ($query && is_array($query) && !empty($query)) {
		foreach ($query as $tsp) {
			list($type, $subtype) = explode(':', $tsp);
			$type_subtype_pairs[$type][] = $subtype;
		}
		foreach ($type_subtype_pairs as $type => $subtypes) {
			if (!is_array($subtypes)) {
				$type_subtype_pairs[$type] = true;
			}
		}
		$options['type_subtype_pairs'] = $type_subtype_pairs;
	}

	$query = get_input("members", false);

	if ($query && is_array($query) && !empty($query)) {
		$options['subject_guids'] = $query;
	}


	return $options;
}

function hj_alive_comments_replacement($hook, $entity_type, $returnvalue, $params) {
	return elgg_view('framework/alive/annotations', $params);
}

function hj_alive_search_comments_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	$limit = sanitise_int($params['limit']);
	$offset = sanitise_int($params['offset']);

	$params['type_subtype_pairs'] = array('object' => 'hjannotation');
	$params['metadata_name_value_pairs'] = array(
		'name' => 'annotation_name', 'value' => array('generic_comment', 'group_topic_post'), 'operand' => '='
	);

	$params['joins'] = array(
		"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
		"JOIN {$db_prefix}metastrings msn_n on md.name_id = msn_n.id",
		"JOIN {$db_prefix}metastrings msv_n on md.value_id = msv_n.id"
	);

	$fields = array('string');
	$params['wheres'] = array(
		"(msn_n.string = 'annotation_value')",
		search_get_where_sql('msv_n', $fields, $params, FALSE)
	);

	$params['count'] = TRUE;
	$count = elgg_get_entities_from_metadata($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	$params['count'] = FALSE;
	$entities = elgg_get_entities_from_metadata($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $key => $entity) {
		$desc = search_get_highlighted_relevant_substrings($entity->annotation_value, $params['query']);
		$entity->setVolatileData('search_annotation_value', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

function hj_alive_notification_settings($hook, $type, $return, $params) {

	$notify_settings = elgg_get_plugin_setting('notifications', 'hypeAlive');
	$notify_settings = explode(',', $notify_settings);
	foreach ($notify_settings as $key => $setting) {
		$return[] = trim($setting);
	}

	return $return;
}