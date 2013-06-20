<?php

// Custom activity filter clause
if (HYPEALIVE_RIVER) {
	elgg_register_plugin_hook_handler('custom_sql_clause', 'framework:lists', 'hj_alive_activity_filter_clauses');
}

// Replace default comments
if (HYPEALIVE_COMMENTS) {
	// Register default comments bar
	elgg_register_plugin_hook_handler('comments', 'all', 'hj_alive_comments_replacement');
	// Remove default comments search
	elgg_unregister_plugin_hook_handler('search', 'comments', 'search_comments_hook');
	elgg_register_entity_type('object', 'hjcomment');
}

if (HYPEALIVE_RIVER) {
	elgg_register_plugin_hook_handler('view', 'river/elements/responses', 'hj_alive_river_responses_view');
}

// Clean up likes menu in case the likes plugin is enabled
if (HYPEALIVE_LIKES) {
	elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
}

if (HYPEALIVE_FORUM_COMMENTS) {
	elgg_register_entity_type('object', 'hjgrouptopicpost');
}

elgg_register_plugin_hook_handler('permissions_check:comment', 'all', 'hj_alive_can_comment');
elgg_register_plugin_hook_handler('container_permissions_check', 'all', 'hj_alive_can_write_to_container');

function hj_alive_activity_filter_clauses($hook, $type, $options, $params) {

	if (!elgg_in_context('activity')) {
		return $options;
	}

	$query = get_input("__tsp", false);

	if (!$query) {
		return $options;
	}

	if (!is_array($query)) {
		$query = array($query);
	}

	foreach ($query as $tsp) {
		list($entity_type, $entity_subtype) = explode(':', $tsp);
		if (!is_array($options['types'])) {
			$options['types'] = array($entity_type);
		} else {
			$options['types'][] = $entity_type;
		}
		if ($entity_subtype && !empty($entity_subtype)) {
			if (!is_array($options['subtypes'])) {
				$options['subtypes'] = array($entity_subtype);
			} else {
				$options['subtypes'][] = $entity_subtype;
			}
		}
	}

	$query = get_input("members", false);

	if ($query && is_array($query) && !empty($query)) {
		$options['subject_guids'] = $query;
	}

	return $options;
}

function hj_alive_comments_replacement($hook, $entity_type, $returnvalue, $params) {

	if (elgg_in_context('no-comments')) {
		return null;
	}
	
	$entity = elgg_extract('entity', $params);
	if (elgg_instanceof($entity, 'object', 'hjcomment')) {
		return elgg_view('framework/alive/replies', $params);
	}

	return elgg_view('framework/alive/comments', $params);
}

function hj_alive_can_comment($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params);

	if (elgg_instanceof($entity, 'object', 'hjcomment')) {
		return ($entity->getDepthToOriginalContainer() <= HYPEALIVE_MAX_COMMENT_DEPTH);
	}

	return $return;
}

function hj_alive_can_write_to_container($hook, $type, $return, $params) {

	if ($params['subtype'] === 'hjcomment') {
		return true;
	}

	return $return;
}

function hj_alive_river_responses_view($hook, $type, $output, $params) {

	if (elgg_in_context('no-comments')) {
		return null;
	}
	return elgg_view('framework/river/elements/responses', $params['vars'], false, false, $params['viewtype']);
}