<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) return;

$list_id = "comments-attachment-$entity->guid";

$options = array(
	'guids' => $entity->guid
);

$list_options = array(
	'list_type' => 'stream',
	'list_class' => 'hj-comments-list',
	'pagination' => false,
);

$viewer_options = array(
	'full_view' => true
);

$content = hj_framework_view_list($list_id, $options, $list_options, $viewer_options, 'elgg_get_entities');

echo $content;