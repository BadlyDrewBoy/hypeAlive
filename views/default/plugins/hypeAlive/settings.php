<?php

$reset_label = "If you are upgrading from the previous version, it is recommended that you reset the plugin to re-run the initial setup";
$reset_link = elgg_view('output/url', array(
	'href' => 'action/alive/reset',
	'is_action' => true,
	'text' => 'Reset'
		));

$import_label = "hypeAlive treats comments and group forum posts as entities, and not annotations. For this reason, previously added comments will not show by default. You can however import these comments and topic posts (if you have too many comments, this action might time out)";
$import_link = elgg_view('output/url', array(
	'href' => 'action/comment/import?type=generic_comment',
	'is_action' => true,
	'text' => 'Import Comments'
		));

$forum_import_link = elgg_view('output/url', array(
	'href' => 'action/comment/import?type=group_topic_post',
	'is_action' => true,
	'text' => 'Import Forum Posts'
		));

$river_label = "Enable hypeAlive comments and likes for river";
$river_input = elgg_view('input/dropdown', array(
	'name' => 'params[river_comments]',
	'value' => $vars['entity']->river_comments,
	'options_values' => array('on' => 'Yes', 'off' => 'No')
		));

$plusone_label = "Use +1 instead of likes";
$plusone_input = elgg_view('input/dropdown', array(
    'name' => 'params[plusone]',
    'value' => $vars['entity']->plusone,
    'options_values' => array('on' => 'Yes', 'off' => 'No')
));

$entity_label = "Enable hypeAlive comments and likes for content items (overwrites the defaul elgg_view_comments())";
$entity_input = elgg_view('input/dropdown', array(
	'name' => 'params[entity_comments]',
	'value' => $vars['entity']->entity_comments,
	'options_values' => array('on' => 'Yes', 'off' => 'No')
		));

$forum_label = "Enable hypeAlive comments and likes for group forum topics";
$forum_input = elgg_view('input/dropdown', array(
	'name' => 'params[forum_comments]',
	'value' => $vars['entity']->forum_comments,
	'options_values' => array('on' => 'Yes', 'off' => 'No')
		));

$notifications_label = "By default, notifications are sent, when generic comment, group topic post, or like is created. Remove any of the following to stop sending notifications";
$notifications_input = elgg_view('input/text', array(
	'name' => 'params[notifications]',
	'value' => $vars['entity']->notifications
		));

$livesearch_label = "Enable/disable live search";
$livesearch_input = elgg_view('input/dropdown', array(
	'name' => 'params[livesearch]',
	'value' => $vars['entity']->livesearch,
	'options_values' => array('on' => 'Enabled', 'off' => 'Disabled')
		));

$depth_label = "Maximum Depth of the Comment Tree";
$depth_input = elgg_view('input/text', array(
	'name' => 'params[max_comment_depth]',
	'value' => $vars['entity']->max_comment_depth
		));

$comment_form_label = "Comment Form Appearance";
$comment_form_input = elgg_view('input/dropdown', array(
	'name' => 'params[comment_form]',
	'value' => $vars['entity']->comment_form,
	'options_values' => array(
		'simple' => 'Simple (no buttons, comment submitted when Enter is pressed)',
		'advanced' => 'Advanced (multiple lines, buttons for submit and clear)')
		));


$settings = <<<__HTML

    <h3>Import</h3>
    <div>
        <p><i>$import_label</i></p>
        <p>$import_link</p>
        <p>$forum_import_link</p>
    </div>
    <hr>

    <h3>Settings</h3>
    <div>
        <p><i>$river_label</i><br />$river_input</p>
        <p><i>$plusone_label</i><br />$plusone_input</p>
        <p><i>$entity_label</i><br />$entity_input</p>
        <p><i>$forum_label</i><br />$forum_input</p>
		<p><i>$depth_label</i><br />$depth_input</p>
        <p><i>$notifications_label</i><br />$notifications_input</p>
		<p><i>$livesearch_label</i><br />$livesearch_input</p>
		<p><i>$comment_form_label</i><br />$comment_form_input</p>
    </div>
    </hr>

    <h3>Reset</h3>
    <div>
        <p><i>$reset_label</i></p><p>$reset_link</p>
    </div>
    <hr>
</div>
__HTML;

echo $settings;