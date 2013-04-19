<?php

$list_id = elgg_extract('list_id', $vars);

$list_options = elgg_extract('list_options', $vars);
$getter_options = elgg_extract('getter_options', $vars);

$offset = abs((int) elgg_extract('offset', $getter_options, 0));
$offset_key = elgg_extract('offset_key', $list_options, 'offset');

$limit_key = elgg_extract('limit_key', $list_options, 'limit');
if (!$limit = (int) elgg_extract('limit', $getter_options, 10)) {
	$limit = get_input($limit_key);
}

$count = (int) elgg_extract('count', $vars, 0);

$base_url = elgg_extract('base_url', $vars, full_url());
$base_url = hj_framework_http_remove_url_query_element($base_url, '__goto');

if ($count <= $limit && $offset == 0) {
	// no need for pagination
	//return true;
} else {
	
	$pages = new stdClass();
	$pages->next = array(
		'text' => elgg_echo('pagination:infinite:next', array($limit)),
		'href' => elgg_http_add_url_query_elements($base_url, array($offset_key => $limit + $offset, $limit_key => $limit)),
		'is_trusted' => true,
	);

	$pager .= "<ul class=\"elgg-pagination hj-alive-comments-pagination\">";

	if ($pages->next['href']) {
		$link = elgg_view('output/url', $pages->next);
		$pager .= "<li>$link</li>";
	}

	$pager .= '</ul>';
}

// Loader placeholder
echo '<div class="hj-ajax-loader hj-loader-indicator hidden"></div>';
echo $pager;