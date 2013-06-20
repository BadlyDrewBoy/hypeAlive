<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return true;
}

$commenter = $entity->getOwnerEntity();
$attachment = elgg_extract('attachment', $vars);

$head = elgg_echo('hj:alive:attachment:email:head', array(
	$commenter->name, $attachment->title
));

$body = "<blockquote>" . $entity->description . "</blockquote>";
$footer = elgg_echo('hj:alive:attachment:email:footer', array(
	$entity->getURL()
));

echo elgg_view('output/longtext', array(
	'value' => elgg_view_module('message', $head, $body, array('footer' => $footer))
));
