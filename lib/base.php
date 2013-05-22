<?php

function hj_alive_prepare_view_params($entity, $annotation_name = null) {

	if ($entity->getType() == 'river') {
		$entity = hj_alive_get_river_stream_object($entity);
	}

	$params = array(
		'entity' => $entity,
		'container_guid' => $entity->guid,
		'list_id' => "comments-$entity->guid"
	);

	return $params;
}

function hj_alive_view_comments_list($entity, $params) {
	$params['entity'] = $entity;
	return elgg_view('framework/alive/comments/list', $params);
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
		'comments' => hj_alive_count_comments($entity),
		'likes' => hj_alive_count_likes($entity),
		'bookmarks' => hj_alive_count_bookmarks($entity),
		'shares' => hj_alive_count_shares($entity),
		'substream' => elgg_view('framework/alive/comments/substream', array('entity' => $entity))
	);
	
}

function hj_alive_view_likes_list($params) {
	$container_guid = elgg_extract('container_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);

	$count = hj_alive_get_likes($params, true);

	if ($count > 0) {
		$likes = hj_alive_get_likes($params, false);

		if (elgg_get_plugin_setting('plusone', 'hypeAlive') == 'on') {
			$like_plusone = 'plusone';
			$popup_link_text = "+{$count}";
		} else {
			$like_plusone = 'likes';
			$popup_link_text = "";
		}

		$text_owner = elgg_echo("hj:alive:comments:lang:{$like_plusone}:you");
		$text_and = elgg_echo("hj:alive:comments:lang:{$like_plusone}:and");
		$text_others = elgg_echo("hj:alive:comments:lang:{$like_plusone}:others");
		$text_others_one = elgg_echo("hj:alive:comments:lang:{$like_plusone}:othersone");
		$text_people = elgg_echo("hj:alive:comments:lang:{$like_plusone}:people");
		$text_people_one = elgg_echo("hj:alive:comments:lang:{$like_plusone}:peopleone");
		$text_likethis = elgg_echo("hj:alive:comments:lang:{$like_plusone}:likethis");
		$text_likesthis = elgg_echo("hj:alive:comments:lang:{$like_plusone}:likesthis");
		$text_seewho = elgg_echo("hj:alive:comments:lang:{$like_plusone}:wholikesthis");

		$user = elgg_get_logged_in_user_entity();

		foreach ($likes as $like) {
			$owners[] = get_entity($like->owner_guid);
		}

		$owners = array_unique($owners);
		if (in_array($user->guid, $owners)) {
			if (sizeof($owners) > 1) {
				$key = array_search($user->guid, $owners);
				unset($owners[$key]);
			} else {
				unset($owners[0]);
			}
			$str_owner = $text_owner;
		}

		if (sizeof($owners) > 0) {
			$others = sizeof($owners);
			elgg_set_context('widgets');
			$likes_long = elgg_view_entity_list($owners);
			elgg_pop_context();
			$target = "hj-likes-popup-{$container_guid}-{$river_id}";
			$likes_long = elgg_view_module('popup', '', $likes_long, array(
				'class' => 'hj-likes-popup hidden',
				'id' => $target
					));

			$string .= $likes_long;

			if ($like_plusone) {
				$link_plus_one = elgg_view('output/url', array(
					'href' => '#' . $target,
					'rel' => 'popup',
					'text' => $popup_link_text,
					'title' => $text_seewho,
					'class' => 'hj_plusone_popup_link_text'
						));
				$string .= $link_plus_one;
			}
		}

		if (!empty($str_owner) && $others == 0) {
			$string .= $str_owner . $text_likethis;
		} else if (!empty($str_owner) && $others == 1) {
			$likes_short = "$others $text_others_one";
			if ($like_plusone == 'likes') {
				$likes_short = elgg_view('output/url', array(
					'href' => '#' . $target,
					'rel' => 'popup',
					'text' => $likes_short,
					'title' => $text_seewho,
					'class' => 'hj_likes_popup_link_text'
						));
				$string .= "$str_owner $text_and $likes_short $text_likethis";
			} else {
				$string .= "$str_owner $text_and $likes_short";
			}
		} else if (!empty($str_owner) && $others > 1) {
			$likes_short = "$others $text_others";
			if ($like_plusone == 'likes') {
				$likes_short = elgg_view('output/url', array(
					'href' => '#' . $target,
					'rel' => 'popup',
					'text' => $likes_short,
					'title' => $text_seewho,
					'class' => 'hj_likes_popup_link_text'
						));
				$string .= "$str_owner $text_and $likes_short $text_likethis";
			} else {
				$string .= "$str_owner $text_and $likes_short";
			}
		} else if (empty($prefix) && $others == 1) {
			$likes_short = "$others $text_people_one";
			if ($like_plusone == 'likes') {
				$likes_short = elgg_view('output/url', array(
					'href' => '#' . $target,
					'rel' => 'popup',
					'text' => $likes_short,
					'title' => $text_seewho,
					'class' => 'hj_likes_popup_link_text'
						));
				$string .= "$likes_short $text_likesthis";
			} else {
				$string .= "";
			}
		} else if (empty($prefix) && $others > 1) {
			$likes_short = "$others $text_people";
			if ($like_plusone == 'likes') {
				$likes_short = elgg_view('output/url', array(
					'href' => '#' . $target,
					'rel' => 'popup',
					'text' => $likes_short,
					'title' => $text_seewho,
					'class' => 'hj_likes_popup_link_text'
						));
				$string .= "$likes_short";
			} else {
				$string .= "";
			}
		}
	}
	if (!$container_guid)
		unset($params['container_guid']);
	if (!$river_id)
		unset($params['river_id']);

	return elgg_view('hj/likes/list', array('value' => $string, 'count' => $count, 'params' => $params));
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

function hj_alive_notify_subscribed_users($entity) {

	$original_container = $entity->getOriginalContainer();
	$original_owner = $original_container->getOwnerEntity();

	$container = $entity->getContainerEntity();
	$container_owner = $container->getOwnerEntity();

	$depth = $entity->getDepthToOriginalContainer();

	if ($depth > 1 && $original_owner->guid !== $container_owner->guid) {
		// Notify comment owner that he has a new reply

	} else {
		// Notify entity owner that he has a new comment

	}

	if ($depth > 1) {
		// Notify subscribed users about activity in the thread

	} else {
		// Notify subscribed users about activity on the item
	}

	$subtype = $entity->getSubtype();

	$subscribers = elgg_get_entities_from_relationship(array(
		'types' => 'user',
		'relationship' => 'subscribed',
		'relationship_guid' => $object->guid,
		'inverse_relationship' => true,
		'limit' => 0
	));


	$from = elgg_get_site_entity()->guid;

	$subject = elgg_echo("hj:forum:new:$subtype");

	$subject_link = elgg_view('framework/bootstrap/user/elements/name', array('entity' => $entity->getOwnerEntity()));
	$object_link = elgg_view('framework/bootstrap/object/elements/title', array('entity' => $entity));
	$breadcrumbs = elgg_view('framework/bootstrap/object/elements/breadcrumbs', array('entity' => $entity));
	if (!empty($breadcrumbs)) {
		$breadcrumbs_link = elgg_echo('river:in:forum', array($breadcrumbs));
	}
	$key = "river:create:object:$subtype";
	$summary = elgg_echo($key, array($subject_link, $object_link)) . $breadcrumbs_link;
	$body = elgg_view('framework/bootstrap/object/elements/description', array('entity' => $entity));
	$link = elgg_view('output/url', array(
		'text' => elgg_echo('hj:framework:notification:link'),
		'href' => $entity->getURL(),
		'is_trusted' => true
	));
	$footer = elgg_echo('hj:framework:notification:full_link', array($link));

	$message = "<p>$summary</p><p>$body</p><p>$footer</p>";

	notify_user($subscribers, $from, $subject, $message);

}