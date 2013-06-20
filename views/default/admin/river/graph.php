<?php

$lang = get_input('lang', get_current_language());

echo elgg_view('admin/river/graph/filter', array(
	'filter_context' => $lang
));

echo elgg_view('admin/river/graph/form', array(
	'language' => $lang
));
