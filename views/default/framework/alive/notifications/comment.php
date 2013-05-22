<?php

$entity = elgg_extract('entity', $vars);

if (!elgg_instanceof($entity)) {
	return true;
}

$commenter = $entity->getOwnerEntity();
$object = $entity->getContainerEntity();

$head = elgg_echo('hj:alive:comment:email:head', array(
	$commenter->name, $object->title
));

$body = "<blockquote>" . $entity->description . "</blockquote>";
$footer = elgg_echo('hj:alive:comment:email:footer', array(
	$entity->getURL()
));

echo elgg_view('output/longtext', array(
	'value' => elgg_view_module('message', $head, $entity->description, array('footer' => $footer))
));
