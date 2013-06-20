<?php

$language = elgg_extract('language', $vars, get_current_language());

$dbprefix = elgg_get_config('dbprefix');

$types = get_data("SELECT DISTINCT(type) FROM {$dbprefix}river");

foreach($types as $std) {
	$subtypes = get_data("SELECT DISTINCT(subtype) FROM {$dbprefix}river WHERE type='$std->type'");

	foreach ($subtypes as $std2) {
		$pairs[$std->type][] = $std2->subtype;
	}
}



