<?php

elgg_load_js('alive.admin.js');

$title = elgg_echo('hj:alive:admin:import:comments');

$body = '<p class="mam">' . elgg_echo('hj:alive:admin:import_stats', array($vars['count'])) . '</p>';
$body .= '<strong class="mam">' . elgg_echo('hj:alive:admin:import_warning') . '</strong>';
$body .= elgg_view('output/url', array(
	'id' => 'hj-alive-admin-import-comments',
	'text' => elgg_echo('hj:alive:admin:import_start'),
	'class' => 'elgg-button elgg-button-action float mam',
	'rel' => elgg_echo('hj:alive:admin:import_confirmation'),
	'data-count' => $vars['count']
));
$body .= '<div id="import-comments-progress" class="mam"></div>';

echo elgg_view_module('widget', $title, $body);