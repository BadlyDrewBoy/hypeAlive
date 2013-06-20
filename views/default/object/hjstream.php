<?php

$entity = elgg_extract('entity', $vars);

$river = elgg_get_river(array(
	'ids' => $entity->river_id,
		));
$stream = $river[0];

if (!$stream instanceof ElggRiverItem) {
	return;
}

echo elgg_view_river_item($stream, array('full_view' => false));
