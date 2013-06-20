<?php

/**
 * Import Elgg comments and group discussions
 */

$limit = get_input('limit', 10);
$offset = get_input('offset', 0);

$annotation_name = get_input('annotation_name');
if (!$annotation_name) die();

$annotations = elgg_get_annotations(array(
	'annotation_names' => $annotation_name,
	'limit' => $limit,
	'offset' => $offset
		));

if (!$annotations) {
	print json_encode(array('complete' => true));
	forward(REFERER);
}

foreach ($annotations as $annotation) {
	
	switch ($annotation->name) {

		case 'generic_comment' :
			$obj = new hjComment();
			break;

		case 'group_topic_post' :
			$obj = new hjGroupTopicPost();
			break;

	}

	if (!$obj) {
		continue;
	}

	$obj->container_guid = $annotation->entity_guid;
	$obj->owner_guid = $annotation->owner_guid;
	$obj->access_id = $annotation->access_id;
	$obj->description = $annotation->value;
	$obj->time_created = $annotation->time_created;
	$obj->enabled = $annotation->enabled;

	if ($obj->save()) {
		$annotation->delete();
	} else {
		$offset++;
	}

}
print json_encode(array(
	'offset' => $offset
));
forward(REFERER);