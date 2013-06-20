<?php

if (elgg_in_context('substream-view')) {
	echo elgg_view('framework/river/elements/substream', $vars);
} else {
	//elgg_push_context('substream-view');
	$item = elgg_extract('item', $vars);
	echo elgg_view('river/elements/layout', hj_alive_prepare_river_item_vars($item));
	//elgg_pop_context();
}