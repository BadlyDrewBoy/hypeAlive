<?php

elgg_load_js('alive.admin.js');

$title = elgg_echo('hj:alive:admin:upgrade');

$body = '<p class="mam">' . elgg_echo('hj:alive:admin:upgrade_stats', array($vars['count'])) . '</p>';
$body .= elgg_view('output/url', array(
	'id' => 'hj-alive-admin-upgrade',
	'text' => elgg_echo('hj:alive:admin:upgrade_start'),
	'class' => 'elgg-button elgg-button-action float mam',
	'rel' => elgg_echo('hj:alive:admin:upgrade_warning'),
	'data-count' => $vars['count']
));
$body .= '<div id="upgrade-progress" class="mam"></div>';

echo elgg_view_module('widget', $title, $body);