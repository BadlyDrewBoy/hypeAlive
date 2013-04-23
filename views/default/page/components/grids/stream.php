<?php

elgg_push_context('list-view');

$list_id = elgg_extract('list_id', $vars);
$entities = elgg_extract('entities', $vars);
$list_options = elgg_extract('list_options', $vars);

$viewer_options = elgg_extract('viewer_options', $vars, array());
$vars = array_merge($vars, $viewer_options);

$class = "elgg-list hj-framework-list-view";
$item_class = trim("elgg-item " . elgg_extract('item_class', $list_options, ''));

if (isset($list_options['list_class'])) {
	$class = "$class {$list_options['list_class']}";
}

$list_head = elgg_view('page/components/grids/elements/stream/head', $vars);

if (is_array($entities) && count($entities) > 0) {
	foreach ($entities as $entity) {
		$vars['entity'] = $entity;
		$vars['class'] = $item_class;
		$list_body .= elgg_view('page/components/grids/elements/stream/item', $vars);
	}
}

$show_pagination = elgg_extract('pagination', $list_options, true);

$pagination_type = elgg_extract('pagination_type', $list_options, 'paginate');

if ($show_pagination) {
	$pagination = elgg_view("page/components/grids/elements/pagination/$pagination_type", $vars);
}

$pagination = '<li class="hj-framework-list-pagination-wrapper" for="' . $list_id . '">' . $pagination . '</li>';

$position = elgg_extract('pagination_position', $list_options, 'after');

if ($position == 'both') {
	$list_body = "$pagination$list_body$pagination";
} else if ($position == 'before') {
	$list_body = "$pagination$list_body";
} else {
	$list_body = "$list_body$pagination";
}

$list = "$list_head<ul id=\"$list_id\" class=\"$class\">$list_body</ul>";

echo $list;

elgg_pop_context();