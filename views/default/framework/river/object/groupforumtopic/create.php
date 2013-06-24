<?php

/**
 * Group forum topic create river view.
 */
$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$responses = elgg_view('framework/alive/discussions', array(
	'entity' => $object
		));

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],
	'message' => $excerpt,
	'responses' => $responses,
));
