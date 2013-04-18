<?php

if (!elgg_is_logged_in()) {
	return true;
}

global $HYPEALIVE_RIVER_TABS;

$filter_context = elgg_extract('filter_context', $vars, $HYPEALIVE_RIVER_TABS[0]);

foreach ($HYPEALIVE_RIVER_TABS as $key => $tab) {
	$tabs[$tab] = array(
		'text' => elgg_echo("hj:alive:river:tab:$tab"),
		'href' => "activity/$tab",
		'selected' => ($filter_context == $tab),
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
