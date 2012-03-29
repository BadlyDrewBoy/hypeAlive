<?php

/**
 * Main activity stream list page
 */
$options = array();

$page_type = preg_replace('[\W]', '', get_input('page_type', 'all'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

if ($subtype) {
	$selector = "type=$type&subtype=$subtype";
} else {
	$selector = "type=$type";
}

if (!$pair = get_input('type_subtype_pairs', false)) {
	if ($type != 'all') {
		$options['type'] = $type;
		if ($subtype) {
			$options['type_subtype_pairs'] = array($type => $subtype);
		}
	}
} else {
	$options['type_subtype_pairs'] = $pair;
}
switch ($page_type) {
	case 'mine':
		$title = elgg_echo('river:mine');
		$page_filter = 'mine';
		$options['subject_guid'] = elgg_get_logged_in_user_guid();
		break;
	case 'friends':
		$title = elgg_echo('river:friends');
		$page_filter = 'friends';
		$options['relationship_guid'] = elgg_get_logged_in_user_guid();
		$options['relationship'] = 'friend';
		break;
	default:
		$title = elgg_echo('river:all');
		$page_filter = 'all';
		break;
}

if (!elgg_is_xhr()) {
	$options['data-options'] = $options;
	$options['limit'] = 10;
	$options['pagination'] = true;
	$options['base_url'] = 'activity';
	$options['list_id'] = 'elgg-river-main';

	$activity = elgg_list_river($options);

	$content = elgg_view('core/river/filter', array('selector' => $selector));
	$sidebar = elgg_view('core/river/sidebar');

	$params = array(
		'title' => $title,
		'content' => $content . $activity,
		'sidebar' => $sidebar,
		//'filter' => '',
		'filter_context' => $page_filter,
		'class' => 'elgg-river-layout',
	);

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($title, $body);
} else {
	$data = get_input('listdata');

	$sync = elgg_extract('sync', $data, 'new');
	$guid = elgg_extract('items', $data, 0);
	if (is_array($guid)) {
		if ($sync == 'new') {
			$guid = max($guid);
		} else {
			$guid = min($guid);
		}
	} else {
		$guid = 0;
	}
	$options = elgg_extract('options', $data, array());
	$options = array_map('htmlspecialchars_decode', $options);

	$limit = elgg_extract('limit', $data['pagination'], 10);
	$offset = elgg_extract('offset', $data['pagination'], 0);

	if ($sync == 'new') {
		$options['wheres'] = array("rv.id > {$guid}");
		$options['order_by'] = 'rv.posted asc';
		$options['limit'] = 0;
	} else {
		$options['wheres'] = array("rv.id < {$guid}");
		$options['order_by'] = 'rv.posted desc';
	}
	$defaults = array(
		'offset' => (int) $offset,
		'limit' => (int) $limit,
		'pagination' => true,
		'base_url' => 'activity',
		'list_class' => 'elgg-list-river elgg-river',
	);

	$options = array_merge($defaults, $options);

	$items = elgg_get_river($options);

	if (is_array($items) && count($items) > 0) {
		foreach ($items as $key => $item) {
			$id = "item-{$item->getType()}-{$item->id}";
			$time = $item->posted;

			$html = "<li id=\"$id\" class=\"elgg-item\">";
			$html .= elgg_view_list_item($item, $vars);
			$html .= '</li>';

			$output[] = array('guid' => $item->id, 'html' => $html);
		}
	}
	header("Content-Type: application/json");
	print(json_encode(array('output' => $output)));
	exit;
}