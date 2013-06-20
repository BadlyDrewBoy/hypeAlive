<?php

$filter_context = elgg_extract('filter_context', $vars, get_current_language());

$languages = elgg_get_config('translations');

foreach ($languages as $key => $options) {
	$tabs[$key] = array(
		'text' => elgg_echo("$key"),
		'href' => "admin/translation/river/graph?lang=$key",
		'selected' => ($filter_context == $key),
		'priority' => $key * 100
	);
}

foreach ($tabs as $name => $tab) {
	if ($tab) {
		$tab['name'] = $name;
		elgg_register_menu_item('filter', $tab);
	}
}

echo elgg_view_menu('filter', array('sort_by' => 'priority', 'class' => 'elgg-menu-hz'));