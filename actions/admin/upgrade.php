<?php

/**
 * Upgrade script
 */
$subtypeIdComment = get_subtype_id('object', 'hjcomment');
$subtypeIdGroupTopicPost = get_subtype_id('object', 'hjgrouptopicpost');

$dbprefix = elgg_get_config('dbprefix');

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$annotations = elgg_get_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => array('hjannotation'),
	'metadata_names' => array('annotation_name'),
	'metadata_values' => array('generic_comment', 'group_topic_post', 'likes'),
	'limit' => $limit,
	'offset' => $offset,
	'order_by' => 'e.time_created ASC'
		));

if (!$annotations) {
	elgg_set_plugin_setting('upgrade_1-9', true, 'hypeAlive');
	print json_encode(array('complete' => true));
	forward(REFERER);
}

foreach ($annotations as $annotation) {

	// Create stream objects for river items without an object
	if ($annotation->river_id) {
		$river = elgg_get_river(array('ids' => $annotation->river_id));
		$stream = hj_alive_get_river_stream_object($river[0]);
		$annotation->container_guid = $stream->guid;
	}

	switch ($annotation->annotation_name) {

		case 'generic_comment':
		case 'group_topic_post' :
			// Convert annotation_value metadata into an entity description
			$annotation->description = $annotation->annotation_value;

			if ($annotation->save()) {

				if ($annotation->annotation_name == 'generic_comment') {
					$subtypeId = $subtypeIdComment;
				} else {
					$subtypeId = $subtypeIdGroupTopicPost;
				}

				// Delete duplicates of comments stored as old Elgg annotations
				if ($annotation->annotation_id) {
					elgg_delete_annotation_by_id($annotation->annotation_id);
				}

				elgg_delete_metadata(array(
					'guid' => $annotation->guid,
					'metadata_names' => array('river_id', 'annotation_id', 'annotation_name', 'annotation_value'),
					'limit' => 0
				));
				
				$query = "UPDATE {$dbprefix}entities SET subtype = $subtypeId WHERE guid = $annotation->guid";
				update_data($query);

			} else {
				$offset++;
				error_log("Annotation $annotation->guid could not be updated");
			}
			break;

		case 'likes' :
			// Get rid of likes as entities; they should be simple annotations
			if (!$annotation->annotation_id) {
				create_annotation($annotation->container_guid, 'likes', $annotation->annotation_value, '', $annotation->owner_guid, $annotation->access_id);
			}
			if (!$annotation->delete()) {
				$offset++;
				error_log("Annotation (like) $annotation->guid could not be deleted");
			}
			break;
	}
}

print json_encode(array(
	'offset' => $offset
));
forward(REFERER);