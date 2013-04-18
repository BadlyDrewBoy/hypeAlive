<?php

$user = elgg_get_page_owner_entity();

if (!$user) {
	return true;
}

$members = get_input('members', false);

$body .= elgg_view('input/userpicker', array(
	'value' => $members,
		));

// Reset all offsets so that lists return to first page
$query = elgg_parse_str(full_url());
foreach ($query as $key => $val) {
	if (strpos($key, '__off') === 0) {
		$footer .= elgg_view('input/hidden', array(
			'name' => $key,
			'value' => 0
				));
	}
}

$footer .= '<div class="hj-ajax-loader hj-loader-indicator hidden"></div>';

$footer .= elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
		));

//$footer .= elgg_view('input/reset', array(
//	'value' => elgg_echo('reset'),
//	'class' => 'elgg-button-reset'
//));

$filter = elgg_view_module('form', '', $body, array(
	'footer' => $footer
));

$form = elgg_view('input/form', array(
	'method' => 'GET',
	'action' => "activity/{$vars['page_type']}",
	'disable_security' => true,
	'body' => $filter,
	'class' => 'pull-right'
));
$body = '<div class="hj-framework-list-filter">' . $form . '</div>';

echo elgg_view_module('aside', elgg_echo('hj:alive:filter:users'), $body);