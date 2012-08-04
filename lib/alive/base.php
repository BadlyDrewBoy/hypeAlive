<?php

function hj_alive_prepare_view_params($entity, $annotation_name = 'generic_comment') {
	
	$params = array(
		'entity' => $entity,
		'aname' => $annotation_name
	);
	
	if ($entity->getType() == 'river') {
		$object = $entity->getObjectEntity();
		if (elgg_instanceof($object, 'object')) {
			$guid = $object->guid;
			$params['selector_id'] = "$guid-$entity->id";
			$params['container_guid'] = $guid;
			$params['instance_id'] = $entity->id;
		} else {
			$river_id = $entity->id;
			$params['selector_id'] = $river_id;
			$params['river_id'] = $river_id;
		}
	} else {
		$guid = $entity->guid;
		$params['selector_id'] = $guid;
		$params['container_guid'] = $guid;
	}

	return $params;
}


function hj_alive_view_comments_list($entity, $params) {
	$container_guid = elgg_extract('container_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);
	$selector_id = elgg_extract('selector_id', $params, null);
	$annotation_name = elgg_extract('aname', $params, 'generic_comment');

	$options = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => $annotation_name),
			array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
		),
		'count' => false,
		'limit' => 3,
		'order_by' => 'e.time_created desc'

	);

	if ($container_guid) {
		$options['container_guid'] = $container_guid;
	}

	if ($river_id) {
		$options['metadata_name_value_pairs'][] = array('name' => 'river_id', 'value' => $river_id);
	}

	$options['count'] = true;
	$count = elgg_get_entities_from_metadata($options);

	$options['count'] = false;
	$comments = elgg_get_entities_from_metadata($options);

	$comments = array_reverse($comments);

	if ($annotation_name == 'generic_comment') {
		unset($params['aname']);
	}
	foreach ($params as $key => $option) {
		if ($option) {
			$data[$key] = $option;
		}
	}

	$data = array_merge($data, $options);
	unset($data['count']);

	$vars['data-options'] = $data;
	$vars['sync'] = true;
	$vars['pagination'] = true;
	$vars['position'] = 'before';
	$vars['list_type'] = 'list';
	$vars['base_url'] = 'hj/sync/metadata';
	$vars['list_class'] = 'hj-comments';
	$vars['count'] = $count;
	$vars['limit'] = 0;
	$vars['limit_prev'] = 3;
	$vars['offset'] = $options['offset'];
	$vars['class'] = 'hj-view-list';
	$vars['inverse_order'] = true;

	$vars['list_id'] = "hj-comments-$selector_id";
	$vars['class'] = "hj-annotation-list-$annotation_name";

	$visible = elgg_view_entity_list($comments, $vars);

//	$limit = elgg_extract('limit', $options, 0);
//	if ($count > 0 && $count > $limit) {
//		$remainder = $count - $limit;
//		if ($limit > 0) {
//			$summary = elgg_echo('hj:alive:comments:remainder', array($remainder));
//		} else {
//			$summary = elgg_echo('hj:alive:comments:viewall', array($remainder));
//		}
//	}
	return $visible;
}

function hj_alive_count_comments($entity, $params) {
	$container_guid = elgg_extract('container_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);
	$annotation_name = elgg_extract('aname', $params, 'generic_comment');

	$options = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => $annotation_name),
			array('name' => 'annotation_value', 'value' => '', 'operand' => '!='),
		),
		'count' => true,
	);

	if ($container_guid) {
		$options['container_guid'] = $container_guid;
	}

	if ($river_id) {
		$options['metadata_name_value_pairs'][] = array('name' => 'river_id', 'value' => $river_id);
	}

	$count = elgg_get_entities_from_metadata($options);

	return $count;
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
			$owners[] = $like->owner_guid;
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

function hj_alive_does_user_like($params) {
	$container_guid = elgg_extract('container_guid', $params, null);
	$river_id = elgg_extract('river_id', $params, null);
	$owner_guid = elgg_get_logged_in_user_guid();

	$options = array(
		'type' => 'object',
		'subtype' => 'hjannotation',
		'owner_guid' => $owner_guid,
		'container_guid' => $container_guid,
		'metadata_name_value_pairs' => array(
			array('name' => 'annotation_name', 'value' => 'likes'),
			array('name' => 'annotation_value', 'value' => '1'),
			array('name' => 'river_id', 'value' => $river_id)
		),
		'count' => false,
		'limit' => 0
	);

	$likes = elgg_get_entities_from_metadata($options);

	if ($likes && sizeof($likes) > 0) {
		return array('self' => true, 'likes' => $likes);
	}
	return false;
}
