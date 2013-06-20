<?php

$entity = elgg_extract('entity', $vars);
$user = elgg_extract('user', $vars);

if (!elgg_instanceof($entity)) {
	return true;
}

$head = elgg_echo('hj:alive:like:email:head', array(
	$user->name, $entity->title
));

$body = "<blockquote>" . $entity->description . "</blockquote>";
$footer = elgg_echo('hj:alive:like:email:footer', array(
	$entity->getURL()
));

echo elgg_view('output/longtext', array(
	'value' => elgg_view_module('message', $head, $body, array('footer' => $footer))
));
