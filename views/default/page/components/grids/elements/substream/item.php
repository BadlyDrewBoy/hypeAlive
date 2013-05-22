<?php

$item = elgg_extract('entity', $vars);
$class = elgg_extract('class', $vars);

if (!$item) {
	return true;
}

$type = $item->getType();
$subtype = $item->getSubtype();

$class = "$class elgg-item elgg-$type elgg-$type-$subtype";

$id = false;

if (elgg_instanceof($item)) {
	$id = "elgg-entity-$item->guid";
	$uid = $item->guid;
	$ts = max(array($item->time_created, $item->time_updated, $item->last_action));
} elseif ($item instanceof ElggRiverItem) {
	$id = "elgg-river-{$item->id}";
	$uid = $item->id;
	$ts = $item->posted;
} elseif ($item instanceof ElggAnnotation) { // Thanks to Matt Beckett for the fix
	$id = "item-{$item->name}-{$item->id}";
	$uid = $item->id;
	$ts = $item->time_created;
}

if (!$id) {
	return true;
}

$attr = array(
	'id' => $id,
	'class' => $class,
	'data-uid' => $uid,
	'data-ts' => $ts
);

$attributes = elgg_format_attributes($attr);

// This is a nested stream
// Hacking to avoid global catch
global $_elgg_special_river_catch;
$_elgg_special_river_catch = false;

$item_view = elgg_view_list_item($item, $vars);

echo "<li $attributes>$item_view</li>";
