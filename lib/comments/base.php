<?php

function hj_alive_view_comments_list($entity, $params) {
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
	$vars['base_url'] = 'hj/sync/metadata';
	$vars['list_class'] = 'hj-comments';
	$vars['count'] = $count;
	$vars['limit'] = 0;
	$vars['limit_prev'] = 3;
	$vars['offset'] = $options['offset'];
	$vars['class'] = 'hj-view-list';
	$vars['inverse_order'] = true;
	
	if ($container_guid) {
		$vars['list_id'] = "hj-comments-$container_guid";
	} else {
		$vars['list_id'] = "hj-comments-$river_id";
	}

	$vars['class'] = "hj-annotation-list-$annotation_name";

	$visible = elgg_view_entity_list($comments, $vars);

	$limit = elgg_extract('limit', $options, 0);
	if ($count > 0 && $count > $limit) {
		$remainder = $count - $limit;
		if ($limit > 0) {
			$summary = elgg_echo('hj:alive:comments:remainder', array($remainder));
		} else {
			$summary = elgg_echo('hj:alive:comments:viewall', array($remainder));
		}
	}
	return $visible;
//	return elgg_view('hj/comments/list', array(
//				'summary' => $summary,
//				'visible' => $visible,
//				'hidden' => $hidden
//			));
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