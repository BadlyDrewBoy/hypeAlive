<?php
$entity = elgg_extract('entity', $vars);

$river = elgg_get_river(array(
	'ids' => $entity->river_id,
		));
$stream = $river[0];

if (!$stream instanceof ElggRiverItem) {
	return;
}

elgg_push_context('no-comments');
echo elgg_view_river_item($stream);
elgg_pop_context();