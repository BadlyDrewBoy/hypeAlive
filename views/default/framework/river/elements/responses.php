<?php

/**
 * River item footer
 *
 * @uses $vars['item'] ElggRiverItem
 * @uses $vars['responses'] Alternate override for this item
 */


if (elgg_in_context('substream-view')) {
	return true;
}

// allow river views to override the response content
$responses = elgg_extract('responses', $vars, null);

if ($responses === false) return;

if ($responses) {
	echo $responses;
	return true;
}

$item = elgg_extract('item', $vars, false);

if (!$item instanceof ElggRiverItem) {
	return true;
}

$object = $item->getObjectEntity();

if (HYPEALIVE_RIVER_COMMENTS) {
	echo elgg_view('framework/alive/comments', array(
		'entity' => (elgg_instanceof($object, 'object')) ? $object : hj_alive_get_river_stream_object($item),
		'item' => $item,
		'class' => 'hj-comments-river-stream'
	));
} else if (!$item->annotation_id && $object) {
	echo elgg_view('framework/river/elements/comments', array(
		'entity' => $object
	));
}