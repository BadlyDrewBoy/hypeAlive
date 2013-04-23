<?php

$list_id = elgg_extract('list_id', $vars);

$list_options = elgg_extract('list_options', $vars);
$getter_options = elgg_extract('getter_options', $vars);

$offset = abs((int) elgg_extract('offset', $getter_options, 0));
$offset_key = elgg_extract('offset_key', $list_options, 'offset');

$limit_key = elgg_extract('limit_key', $list_options, 'limit');
if (!$limit = (int) elgg_extract('limit', $getter_options, HYPEALIVE_COMMENTS_LIMIT)) {
	$limit = get_input($limit_key);
}

$count = (int) elgg_extract('count', $vars, 0);

if ($count < HYPEALIVE_COMMENTS_LOAD_LIMIT) {
	$next_limit = HYPEALIVE_COMMENTS_LOAD_LIMIT;
}

$base_url = elgg_normalize_url(elgg_extract('base_url', $list_options, full_url()));

if (($offset == 0 && $count <= $limit) || ($offset > 0 && $count <= $offset + $limit)) {
	return true;
} else {

	if ((HYPEALIVE_COMMENTS_ORDER == 'asc' && HYPEALIVE_COMMENTS_LOAD_STYLE == 'load_older')
			|| (HYPEALIVE_COMMENTS_ORDER == 'desc' && HYPEALIVE_COMMENTS_LOAD_STYLE == 'load_newer')) {
		$nav = "previous";
	} else {
		$nav = "next";
	}

	if ($count - $offset - $limit <= $next_limit) {
		$text = elgg_echo("hj:alive:comments:load:$nav:remaining", array($count - $offset - $limit));
	} else {
		$text = elgg_echo("hj:alive:comments:load:$nav", array($next_limit));
	}

	$attr = elgg_format_attributes(array(
		'data-count' => $count,
		'data-limit' => $limit,
		'data-offset' => $offset
			));

	$pager .= "<div class=\"hj-stream-pagination\" $attr>";
	$pager .= elgg_view('output/url', array(
		'text' => $text,
		'href' => elgg_http_add_url_query_elements($base_url, array($offset_key => $limit + $offset, $limit_key => $next_limit)),
		'is_trusted' => true,
		'rel' => $nav
			));
	$pager .= '</div>';
}

echo $pager;