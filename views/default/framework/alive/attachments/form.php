<?php

$body .= elgg_view('framework/alive/attachments/list');

$body .= '<div class="elgg-foot">';
$body .= elgg_view('input/submit', array(
	'value' => elgg_echo('hj:alive:done'),
	'class' => 'elgg-button-cancel-trigger'
));
$body .= '</div>';

echo $body;