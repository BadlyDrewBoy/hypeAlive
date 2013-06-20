<?php

$entity = elgg_extract('entity', $vars);

echo elgg_view('input/checkbox', array(
	'checked' => in_array($entity->guid, get_input('guids', array())),
	'data-guid' => $entity->guid,
	'data-img' => $entity->getIconURL('tiny'),
	'data-title' => $entity->title,
	'class' => 'hj-alive-attachment-checkbox'
));
