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
	$stream->subtype = 'hjstream';
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
		'subtypes' => 'hjstream',
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

function hj_alive_language_key_exists($message_key) {
	global $CONFIG;
	static $CURRENT_LANGUAGE;

	if (!isset($CONFIG->translations)) {
		register_translations(dirname(dirname(dirname(__FILE__))) . "/languages/");
	}

	if (!$CURRENT_LANGUAGE) {
		$CURRENT_LANGUAGE = get_language();
	}
	if (!$language) {
		$language = $CURRENT_LANGUAGE;
	}

	return (isset($CONFIG->translations[$language][$message_key])
			|| isset($CONFIG->translations["en"][$message_key]));
}

function hj_alive_prepare_river_item_vars($item) {

	return array(
		'item' => $item,
		'summary' => hj_alive_get_river_summary($item),
		'message' => hj_alive_get_river_message($item),
		'responses' => hj_alive_get_river_responses($item),
		'attachments' => hj_alive_get_river_attachments($item)
	);
}

function hj_alive_get_river_summary($item) {

	if (!$item instanceof ElggRiverItem) {
		return '';
	}

	$subject = $item->getSubjectEntity();
	$object = $item->getObjectEntity();

	$type = $item->type;
	$subtype = $item->subtype;
	if (empty($subtype)) {
		$subtype = 'default';
	}
	$action = $item->action_type;

	$subject_str = hj_alive_get_river_subject_string($subject, $action, $object);
	$action_str = hj_alive_get_river_action_string($subject, $action, $object);
	$object_str = hj_alive_get_river_object_string($subject, $action, $object);
//	$container_str = hj_alive_get_river_container_string($object);

	return "$subject_str $action_str $object_str";
}

function hj_alive_get_river_subject_string($subject = null, $action = null, $object = null) {

	if (!elgg_instanceof($subject)) {
		return;
	}

	$link = elgg_view('output/url', array(
		'text' => $subject->name,
		'href' => $subject->getURL(),
		'is_trusted' => true
			));

	if (elgg_instanceof($subject)) {
		if (!$gender = $subject->gender) {
			$gender = 'neutral';
		}

		if ($subject->guid == elgg_get_logged_in_user_guid()) {
			$rel = 'self';
		} else {
			$rel = 'user';
		}

		$subj = "$rel";
		$subj_ns = "$gender:$rel";
	}

	if (elgg_instanceof($object)) {
		$type = $object->getType();
		$subtype = $object->getSubtype();
		if (!$subtype)
			$subtype = 'default';
		$obj_ns = "item:$type:$subtype";
	}

	$str = elgg_echo("river:subject::undefined");

	$keys = array(
		"river:subject::$obj_ns",
		"river:subject::$subj",
		"river:subject::$subj_ns",
		"river:subject::$subj::$obj_ns",
		"river:subject::$subj_ns::$obj_ns",
		"river:subject::$action",
		"river:subject::$action::$obj_ns",
		"river:subject::$action::$subj",
		"river:subject::$action::$subj_ns",
		"river:subject::$action::$subj::$obj_ns",
		"river:subject::$action::$subj_ns::$obj_ns",
	);

	foreach ($keys as $key) {
		if (hj_alive_language_key_exists($key)) {
			$str = elgg_echo($key, array($link));
		}
	}

	$str = ucfirst($str);

	return elgg_trigger_plugin_hook('river:subject', 'framework:alive', array('subject' => $subject, 'action' => $action, 'object' => $object), $str);
}

function hj_alive_get_river_action_string($subject = null, $action = null, $object = null) {

	if (!$action) {
		return elgg_echo('river:action:undefined');
	}

	if (elgg_instanceof($subject)) {
		if (!$gender = $subject->gender) {
			$gender = 'neutral';
		}

		if ($object->owner_guid == $subject->guid) {
			$rel = 'self';
		} else {
			$rel = 'user';
		}

		$subj = "$rel";
		$subj_ns = "$gender:$rel";
	}

	if (elgg_instanceof($object)) {
		$type = $object->getType();
		$subtype = $object->getSubtype();
		if (!$subtype)
			$subtype = 'default';
		$obj_ns = "item:$type:$subtype";
	}

	$str = elgg_echo("river:action::undefined");

	$keys = array(
		"river:action::$action",
		"river:action::$action::$obj_ns",
		"river:action::$action::$subj",
		"river:action::$action::$subj_ns",
		"river:action::$action::$subj::$obj_ns",
		"river:action::$action::$subj_ns::$obj_ns",
	);

	foreach ($keys as $key) {
		if (hj_alive_language_key_exists($key)) {
			$str = elgg_echo($key);
		}
	}

	return elgg_trigger_plugin_hook('river:action', 'framework:alive', array('subject' => $subject, 'action' => $action, 'object' => $object), $str);
}

function hj_alive_get_river_object_string($subject = null, $action = null, $object = null) {

	if (!elgg_instanceof($object)) {
		return;
	}

	if ($action == 'stream:comment' || $action == 'stream:reply') {
		$comment = $object;
		$object = $object->getContainerEntity();
	}
	$owner = $object->getOwnerEntity();

	if (elgg_instanceof($object)) {
		$type = $object->getType();
		$subtype = $object->getSubtype();
		if (!$subtype)
			$subtype = 'default';
		$obj_ns = "item:$type:$subtype";
	}

	if (elgg_instanceof($subject)) {
		if (!$gender = $subject->gender) {
			$gender = 'neutral';
		}

		if ($owner->guid == $subject->guid) {
			$rel = 'self';
		} else {
			$rel = 'user';
		}

		if (elgg_instanceof($owner)) {
			if ($subject->guid == $owner->guid) {
				$subj = "$rel:own";
			} else {
				$subj = "$rel";
			}

			if ($subj == 'user') {
				$owner_link = elgg_view('output/url', array(
					'text' => $owner->name,
					'href' => $owner->getURL(),
					'is_trusted' => true
						));
				$prefix = elgg_echo('river:owner', array($owner_link));
			} else {
				$prep_keys = array(
					"river:preposition::default",
					"river:preposition::$subj",
					"river:preposition::$gender:$subj",
					"river:preposition::$subj::$obj_ns",
					"river:preposition::$gender:$subj::$obj_ns",
					"river:preposition::$action",
					"river:preposition::$action::$obj_ns",
				);
				foreach ($prep_keys as $key) {
					if (hj_alive_language_key_exists($key)) {
						$prefix = elgg_echo($key);
					}
				}
			}
		}

		$subj_ns = "$gender:$rel";
	}

	if (elgg_instanceof($object, 'object')) {
		if ($object->title != '') {
			$title = elgg_view('output/url', array(
				'text' => $object->title,
				'href' => $object->getURL(),
				'is_trusted' => true
					));
		}
	} else {
		$title = elgg_view('output/url', array(
			'text' => $object->name,
			'href' => $object->getURL(),
			'is_trusted' => true
				));
	}


	if (elgg_instanceof($object, 'object', 'hjcomment')) {
		$suffix = elgg_echo('river:thread', array(hj_alive_get_river_object_string(null, null, $object->getOriginalContainer())));
	}

	if (!elgg_in_context('substream-view')) {
		$str = elgg_echo("river:object::default", array($prefix, elgg_echo("river:object::$obj_ns"), $title, $suffix));
		$keys = array(
			"river:object::$subj",
			"river:object::$subj_ns",
			"river:object::$subj::$obj_ns",
			"river:object::$subj_ns::$obj_ns",
			"river:object::$action",
			"river:object::$action::$obj_ns",
			"river:object::$action::$subj",
			"river:object::$action::$subj_ns",
			"river:object::$action::$subj::$obj_ns",
			"river:object::$action::$subj_ns::$obj_ns",
		);

		foreach ($keys as $key) {
			if (hj_alive_language_key_exists($key)) {
				$str = elgg_echo($key, array($prefix, $title, $suffix));
			}
		}
	} else {
		if ($comment) {
			$desc = elgg_get_excerpt($comment->description, 50);
			if (elgg_instanceof($object, 'object', 'hjcomment')) {
				$str = elgg_echo("river:object::substream:comment", array($prefix, elgg_echo("river:object::$obj_ns"), $desc));
			} else {
				$str = elgg_echo("river:object::substream:desc", array(elgg_echo("river:object::$obj_ns"), $desc));
			}
		} else if ($action == 'stream:like' && elgg_instanceof($object, 'object', 'hjcomment')) {
				$desc = elgg_get_excerpt($object->description, 50);
				$str = elgg_echo("river:object::substream:comment", array($prefix, elgg_echo("river:object::$obj_ns"), $desc));
		}
		else {
			$str = elgg_echo("river:object::substream:default", array(elgg_echo("river:object::$obj_ns")));
		}
	}

	return elgg_trigger_plugin_hook('river:object', 'framework:alive', array('subject' => $subject, 'action' => $action, 'object' => $object), $str);
}

function hj_alive_get_river_message() {

}

function hj_alive_get_river_attachments($item) {

	$object = $item->getObjectEntity();
	if (!elgg_instanceof($object)) {
		return false;
	}

	$action_type = $item->action_type;

	$type = $object->getType();
	$subtype = $object->getSubtype();

	$view_action = str_replace(':', '_', "river/attachment/$action_type/$type/$subtype");
	$view_object = "river/attachment/$type/$subtype";

	if (elgg_view_exists($view_action)) {
		return elgg_view($view_action, array('entity' => $object));
	} else if (elgg_view_exists($view_object)) {
		return elgg_view($view_object, array('entity' => $object));
	} else {
		return elgg_view_entity($object, array('full_view' => false, 'list_type' => 'gallery'));
	}

	return false;
}

function hj_alive_get_river_responses($item) {

	$object = hj_alive_get_river_stream_object($item);

	if (!$object) {
		return false;
	}

	switch ($object->getSubtype()) {

		case 'hjstream' :
			return false;
			break;

		case 'hjcomment' :
			return false;
			break;

		default :
			return null;
			break;
	}
	return false;
}