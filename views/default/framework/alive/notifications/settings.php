<?php

$user = elgg_get_page_owner_entity();

$title = elgg_echo('hj:alive:notifications:autosubscribe');
$body = '<label>' . elgg_echo('hj:alive:notifications:autosubscribe:instructions') . '</label>';
$body .= elgg_view('input/dropdown', array(
	'name' => 'params[comments_autosubscribe]',
	'options_values' => array(
		1 => elgg_echo('hj:alive:notifications:autosubscribe:enable'),
		0 => elgg_echo('hj:alive:notifications:autosubscribe:disable')
	),
	'value' => elgg_get_plugin_user_setting('comments_autosubscribe', $user->guid, 'hypeAlive')
));
$form_body .= elgg_view_module('info', $title, $body);

$title = elgg_echo('hj:alive:notifications:about');
$body = '<label>' . elgg_echo('hj:alive:notifications:about:comments') . '</label>';
$body .= elgg_view('input/dropdown', array(
	'name' => 'params[notify_comments]',
	'options_values' => array(
		1 => elgg_echo('hj:alive:notifications:about:enable'),
		0 => elgg_echo('hj:alive:notifications:about:disable')
	),
	'value' => elgg_get_plugin_user_setting('notify_comments', $user->guid, 'hypeAlive')
));

$body .= '<label>' . elgg_echo('hj:alive:notifications:about:likes') . '</label>';
$body .= elgg_view('input/dropdown', array(
	'name' => 'params[notify_likes]',
	'options_values' => array(
		1 => elgg_echo('hj:alive:notifications:about:enable'),
		0 => elgg_echo('hj:alive:notifications:about:disable')
	),
	'value' => elgg_get_plugin_user_setting('notify_likes', $user->guid, 'hypeAlive')
));

$form_body .= elgg_view_module('info', $title, $body);


$form_body .= elgg_view('input/hidden', array(
	'name' => 'user_guid',
	'value' => $user->guid
		));

$title = elgg_echo('hj:alive:notifications:subscriptions');
$body = elgg_view('framework/alive/notifications/settings_list');

$form_body .= elgg_view_module('info', $title, $body);

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));

echo elgg_view('input/form', array(
	'action' => 'action/hypeAlive/usersettings/save',
	'body' => $form_body
));

