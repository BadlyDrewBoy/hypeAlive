<?php

$entity = elgg_extract('entity', $vars, false);
if (!elgg_is_logged_in()) {
	return true;
}
if (!$entity) {
	return true;
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'annotation_guid'
		));

$form_body .= elgg_view('input/hidden', array(
	'name' => 'aname',
	'value' => elgg_extract('aname', $vars, 'generic_comment')
		));

if ($container_guid = elgg_extract('container_guid', $vars, false)) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => 'container_guid',
		'value' => $container_guid
			));
}

if ($instance_id = elgg_extract('instance_id', $vars, false)) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => 'instance_id',
		'value' => $instance_id
			));
}

if ($river_id = elgg_extract('river_id', $vars, false)) {
	$form_body .= elgg_view('input/hidden', array(
		'name' => 'river_id',
		'value' => $river_id
			));
}

if (!$access = $entity->access_id) {
	$access = ACCESS_DEFAULT;
}

$form_body .= elgg_view('input/hidden', array(
	'name' => 'access_id',
	'value' => $access
		));

if (elgg_get_plugin_setting('comment_form', 'hypeAlive') == 'advanced') {
	$form_body .= elgg_view('input/plaintext', array(
		'name' => 'description',
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
	$form_body .= elgg_view('input/text', array(
		'name' => 'description'
			));

	$form_body .= elgg_view('input/submit', array(
		'value' => 'submit',
		'class' => 'hidden'
			));
}

$form = elgg_view('input/form', array(
	'body' => $form_body,
	'enctype' => 'application/json',
	'action' => 'action/comment/save',
	'class' => 'hj-comments-save'
		));

echo $form;