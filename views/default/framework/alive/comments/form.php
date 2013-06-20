<?php

$entity = elgg_extract('entity', $vars, false);
$params = elgg_clean_vars($vars);

if (!elgg_is_logged_in() || !$entity || !$entity->canComment()) {
	return true;
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => elgg_extract('guid', $vars)
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'list_id',
	'value' => elgg_extract('list_id', $vars)
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars, false)
		));

if (HYPEALIVE_COMMENT_FORM == 'advanced') {
	$class = 'hj-comments-form-advanced';
	$form_body .= elgg_view('input/plaintext', array(
		'name' => 'description',
		'rows' => '2',
		'value' => $entity->description
			));
	$form_body .= elgg_view('input/framework/attachments');
	$form_body .= elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
		'class' => 'hidden'
			));
} else {
	$class = 'hj-comments-form-simple';
	$form_body .= elgg_view('input/text', array(
		'name' => 'description',
		'value' => $entity->description
			));
	$buttons .= elgg_view('input/framework/attachments');
	$buttons .= elgg_view('input/submit', array(
		'value' => elgg_echo('save'),
		'class' => 'hidden'
			));
}


$comments_count = hj_alive_count_comments($entity, $params);
if ($comments_count <= 0 || elgg_instanceof($entity, 'object', 'hjcomment')) {
	$class .= ' hidden';
}

$icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny', array('use_hover' => false));
$form_body = elgg_view_image_block($icon, $form_body, array(
	'image_alt' => $buttons
		));

$form = elgg_view('input/form', array(
	'body' => $form_body,
	'enctype' => 'application/json',
	'action' => 'action/comment/save',
	'class' => "hj-comments-form $class"
		));

echo $form;