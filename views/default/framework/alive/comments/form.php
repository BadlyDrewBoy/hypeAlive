<?php

$entity = elgg_extract('entity', $vars, false);
$params = elgg_clean_vars($vars);

if (!elgg_is_logged_in() || !$entity) {
	return true;
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'annotation_guid'
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'aname',
	'value' => elgg_extract('aname', $vars, 'generic_comment')
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'list_id',
	'value' => elgg_extract('list_id', $vars)
		));

if ($container_guid = elgg_extract('container_guid', $vars, false)) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $container_guid
			));
}

if ($river_id = elgg_extract('river_id', $vars, false)) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => 'river_id',
		'value' => $river_id
			));
}

if (HYPEALIVE_COMMENT_FORM == 'advanced') {
	$class = 'hj-comments-form-advanced';
	$form_body .= elgg_view('input/plaintext', array(
		'name' => 'annotation_value',
		'rows' => '2'
			));

	$form_body .= elgg_view('input/submit', array(
		'value' => elgg_echo('submit')
			));

	$form_body .= elgg_view('input/button', array(
		'type' => 'reset',
		'value' => elgg_echo('hj:alive:button:clear')
			));
} else {
	$class = 'hj-comments-form-simple';
	$form_body .= elgg_view('input/text', array(
		'name' => 'annotation_value'
			));

	$button = elgg_view('input/submit', array(
		'value' => 'submit',
			//'class' => 'hidden'
			));
}

$comments_count = hj_alive_count_comments($entity, $params);
if ($comments_count <= 0 || elgg_instanceof($entity, 'object', 'hjannotation')) {
	$class .= ' hidden';
}

$icon = elgg_view_entity_icon(elgg_get_logged_in_user_entity(), 'tiny', array('use_hover' => false));
$form_body = elgg_view_image_block($icon, $form_body, array(
	'image_alt' => $button
		));

$form = elgg_view('input/form', array(
	'body' => $form_body,
	'enctype' => 'application/json',
	'action' => 'action/comment/save',
	'class' => "hj-comments-form $class"
		));

echo $form;