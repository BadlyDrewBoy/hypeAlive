<?php


// Create a stream object for river items with no annotable object
function hj_alive_create_river_stream_object($event, $type, $river) {

	if (!$river instanceof ElggRiverItem) {
		return true;
	}

	if ($river->getObjectEntity() instanceof ElggObject) {
		return true;
	}

	$ia = elgg_set_ignore_access(true);

	$stream = new ElggObject();
	$stream->subtype = 'stream';
	$stream->owner_guid = $river->subject_guid;
	$stream->container_guid = $river->subject_guid;
	$stream->access_id = $river->access_id;
	$stream->title = '';
	$stream->description = '';
	$stream->river_id = $river->id;
	$stream->save();

	elgg_set_ignore_access($ia);

	
	return $stream;
}

function hj_alive_get_river_stream_object($river) {

	if (!$river instanceof ElggRiverItem) {
		return false;
	}

	$object = $river->getObjectEntity();

	if (elgg_instanceof($object, 'object')) {
		return $object;
	}

	$ia = elgg_set_ignore_access(true);

	$stream = elgg_get_entities_from_metadata(array(
		'types' => 'object',
		'subtypes' => 'stream',
		'metadata_name_value_pairs' => array(
			array(
				'name' => 'river_id',
				'value' => $river->id,
				'operand' => '='
			)
		)
	));

	elgg_set_ignore_access($ia);

	return ($stream) ? $stream[0] : hj_alive_create_river_stream_object('', '', $river);
}