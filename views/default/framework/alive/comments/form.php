<?php

$class = elgg_extract('class', $vars);

$comment_guid = elgg_extract('comment_guid', $vars, false);
$comment = get_entity($comment_guid);

$container = elgg_extract('entity', $vars, false);

if (!$container) {
	$container = get_entity($comment->container_guid);
}

if (!elgg_is_logged_in() || !$container || !$container->canComment()) {
	return true;
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'guid',
	'value' => $comment_guid
));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'list_id',
	'value' => elgg_extract('list_id', $vars)
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => ($comment) ? $comment->container_guid : $container->guid
		));

if (HYPEALIVE_COMMENT_FORM == 'advanced') {
	$class = 'hj-comments-form-advanced';
	$form_body .= elgg_view('input/plaintext', array(
		'name' => 'description',
		'rows' => '2',
		'value' => $comment->description
			));
	$form_body .= elgg_view('input/framework/attachments');
	$form_body .= elgg_view('input/submit', array(
		'value' => elgg_echo('save')
			));
} else {
	$class = 'hj-comments-form-simple';
	$form_body .= elgg_view('input/text', array(
		'name' => 'description',
		'value' => $comment->description
			));
	$buttons .= elgg_view('input/framework/attachments', array('entity' => $comment));
	$buttons .= elgg_view('input/submit', array(
		'value' => elgg_echo('save')
			));
}

$params = elgg_clean_vars($vars);

$comments_count = hj_alive_count_comments($container, $params);
if ($comments_count <= 0 || elgg_instanceof($container, 'object', 'hjcomment')) {
	$class .= ' hidden';
}

if ($comment) {
	$owner = get_entity($comment->owner_guid);
} else {
	$owner = elgg_get_logged_in_user_entity();
}
$icon = elgg_view_entity_icon($owner, 'tiny', array('use_hover' => false));
$form_body = elgg_view_image_block($icon, $form_body, array(
	'image_alt' => $buttons
		));

$form = elgg_view('input/form', array(
	'body' => $form_body,
	'enctype' => 'application/json',
	'action' => 'action/comment/save',
	'class' => "hj-comments-form $class",
	'rel' => ($comment) ? 'edit' : 'new'
		));

echo $form;