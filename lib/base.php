<?php

function hj_alive_prepare_view_params($entity) {

	if ($entity->getType() == 'river') {
		$entity = hj_alive_get_river_stream_object($entity);
	}

	$params = array(
		'entity' => $entity,
		'list_id' => "comments-$entity->guid"
	);

	return $params;
}

function hj_alive_view_comments_list($entity, $params) {
	$params['entity'] = $entity;
	return elgg_view('framework/alive/comments/list', $params);
}

function hj_alive_view_discussion_replies_list($entity, $params) {
	$params['entity'] = $entity;
	return elgg_view('framework/alive/discussions/list', $params);
}

function hj_alive_count_comments($entity) {

	$options = array(
		'type' => 'object',
		'subtype' => 'hjcomment',
		'container_guid' => $entity->guid,
		'count' => true,
	);

	$count = elgg_get_entities_from_metadata($options);

	return $count;
}


function hj_alive_count_discussion_replies($entity) {

	$options = array(
		'type' => 'object',
		'subtype' => 'hjgrouptopicpost',
		'container_guid' => $entity->guid,
		'count' => true,
	);

	$count = elgg_get_entities_from_metadata($options);

	return $count;
}

function hj_alive_count_likes($entity) {

	$options = array(
		'annotation_names' => 'likes',
		'annotation_values' => 1,
		'guids' => $entity->guid,
		'annotation_calculation' => 'count'
	);

	$count = elgg_get_annotations($options);

	return $count;
}

function hj_alive_count_bookmarks($entity) {

	$options = array(
		'relationship' => 'bookmarked',
		'relationship_guid' => $entity->guid,
		'inverse_relationship' => true,
		'count' => true
	);

	$count = elgg_get_entities_from_relationship($options);

	return $count;
}

function hj_alive_count_shares($entity) {

	$options = array(
		'relationship' => 'shared',
		'relationship_guid' => $entity->guid,
		'inverse_relationship' => true,
		'count' => true
	);

	$count = elgg_get_entities_from_relationship($options);

	return $count;
}

function hj_alive_get_stream_stats($entity) {

	return array(
		'counter' => array(
			'comments' => (elgg_instanceof($entity, 'object', 'groupforumtopic')) ? hj_alive_count_discussion_replies($entity) : hj_alive_count_comments($entity),
			'likes' => hj_alive_count_likes($entity),
			'bookmarks' => hj_alive_count_bookmarks($entity),
			'shares' => hj_alive_count_shares($entity),
		),
		'rels' => array(
			'likes' => hj_alive_does_user_like($entity),
			'bookmarked' => check_entity_relationship(elgg_get_logged_in_user_guid(), 'bookmarked', $entity->guid),
			'shared' => check_entity_relationship(elgg_get_logged_in_user_guid(), 'shared', $entity->guid),
			'subscribed' => check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $entity->guid),
		),
		'substream' => elgg_view('framework/alive/comments/substream', array('entity' => $entity))
	);
}

function hj_alive_get_likes($params, $count = false) {
	$container_guid = elgg_extract('container_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);
	$options = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'owner_guid' => null,
		'container_guid' => $container_guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => 'likes'),
			array('name' => 'annotation_value', 'value' => '1'),
			array('name' => 'river_id', 'value' => $river_id)
		),
		'count' => $count,
		'limit' => 0
	);

	return $likes = elgg_get_entities_from_metadata($options);
}

function hj_alive_does_user_like($entity, $user = null) {

	if (!elgg_is_logged_in() || !elgg_instanceof($entity)) {
		return false;
	}

	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}

	$likes = elgg_get_annotations(array(
		'guids' => $entity->guid,
		'annotation_owner_guids' => $user->guid,
		'annotation_names' => 'likes',
		'annotation_values' => 1,
		'annotation_calculation' => 'count'
			));


	return ($likes > 0) ? true : false;
}

function hj_alive_import_annotations($annotation_name) {

	$annotations = elgg_get_annotations(array(
		'annotation_names' => array($annotation_name)
			));

	foreach ($annotations as $annotation) {
		if (!hj_alive_annotation_match_exists($annotation)) {
			$import = new hjAnnotation();
			$import->annotation_id = $annotation->id;
			$import->annotation_name = $annotation->name;
			$import->annotation_value = $annotation->value;
			$import->owner_guid = $annotation->owner_guid;
			$import->container_guid = $annotation->entity_guid;
			$import->access_id = $annotation->access_id;
			$import->save(false);
		}
	}

	return true;
}

function hj_alive_annotation_match_exists($annotation) {
	$match = elgg_get_entities_from_metadata(array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'count' => true,
		'owner_guid' => $annotation->owner_guid,
		'container_guid' => $annotation->entity_guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_id', 'value' => $annotation->id)
			)));

	if ($match > 0) {
		return true;
	}
	return false;
}
