<?php

/**
 * Layout of a river item
 *
 * @uses $vars['item'] ElggRiverItem
 */
$item = $vars['item'];

$view = $item->getView();
$subject = $item->getSubjectEntity();
$object = $item->getObjectEntity();

if (!elgg_in_context('substream-view')) {
	if (elgg_view_exists($view, 'default')) {
		if (!$object || !$subject) {
			echo elgg_echo('error:default');
			return true;
		}
		echo elgg_view($view, $vars);
	} else {
		echo elgg_view('river/elements/layout', hj_alive_prepare_river_item_vars($item));
	}
} else {
	echo elgg_view('framework/river/elements/substream', $vars);
}
