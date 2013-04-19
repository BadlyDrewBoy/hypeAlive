<?php

/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */
// allow river views to override the response content
$responses = elgg_extract('responses', $vars, false);
if ($responses) {
	echo $responses;
	return true;
}

$item = elgg_extract('item', $vars, false);

if (!$item instanceof ElggRiverItem) {
	return true;
}

$object = $item->getObjectEntity();

// annotations do not have comments
if ($item->annotation_id != 0 || !$object) {
	return true;
}

$vars['entity'] = $item;
unset($vars['item']);

echo elgg_view('framework/alive/annotations', $vars);