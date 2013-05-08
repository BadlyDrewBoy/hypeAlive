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