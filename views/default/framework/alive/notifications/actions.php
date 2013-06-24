<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view('output/url', array(
	'class' => 'elgg-button elgg-button-action subscriptions-options-action',
	'text' => elgg_echo('hj:alive:subscription:remove'),
	'href' => "action/alive/subscription?guid=$entity->guid",
	'is_action' => true,
));