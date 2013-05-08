<?php

$user = elgg_get_page_owner_entity();

if (!$user) {
	return true;
}

$river_selector = get_input('__tsp', false);

$hidden_types_subtypes = elgg_get_plugin_user_setting('river_hidden_types_subtypes', $user->guid, 'hypeAlive');
if ($hidden_types_subtypes) {
	$hidden_types_subtypes = unserialize($hidden_types_subtypes);
} else {
	$hidden_types_subtypes = array();
}

$registered_entities = elgg_get_config('registered_entities');
if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		if (!count($subtypes)) {
			$label = elgg_echo("item:$type");
			$options[$label] = $type;
			$default[] = $type;
		} else {
			foreach ($subtypes as $subtype) {
				if (in_array(get_subtype_id($type, $subtype), $hidden_types_subtypes)) {
					continue;
				}
				$label = elgg_echo("item:$type:$subtype");
				$options[$label] = "$type:$subtype";
				$default[] = "$type:$subtype";
			}
		}
	}
}

$body .= elgg_view('input/checkboxes', array(
	'name' => "__tsp",
	'value' => ($river_selector) ? $river_selector : $default,
	'default' => false,
	'options' => $options
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

$footer .= elgg_view('input/reset', array(
	'value' => elgg_echo('reset'),
	'class' => 'elgg-button-reset'
));

$filter = elgg_view_module('form', '', $body, array(
	'footer' => $footer
));

$form = elgg_view('input/form', array(
	'method' => 'GET',
	'action' => "activity/{$vars['page_type']}",
	'disable_security' => true,
	'body' => $filter,
	'class' => 'float-alt'
));
$body = '<div class="hj-framework-list-filter">' . $form . '</div>';

echo elgg_view_module('aside', elgg_echo('hj:alive:filter:tsp'), $body);