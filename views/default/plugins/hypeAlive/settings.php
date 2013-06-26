<?php

$old_annotations_count = elgg_get_entities_from_metadata(array(
	'types' => 'object',
	'subtypes' => array('hjannotation'),
	'metadata_names' => array('annotation_name'),
	'metadata_values' => array('generic_comment', 'group_topic_post', 'likes'),
	'count' => true
		));

if ($old_annotations_count) {
	echo elgg_view('framework/alive/admin/upgrade', array(
		'count' => $old_annotations_count
	));
} else {
	elgg_set_plugin_setting('upgrade_1-9', 'hypeAlive');
}

if (elgg_get_plugin_setting('upgrade_1-9', 'hypeAlive')) {
	$old_comments_count = elgg_get_annotations(array(
		'annotation_names' => 'generic_comment',
		'count' => true
			));

	if ($old_comments_count) {
		echo elgg_view('framework/alive/admin/import_comments', array(
			'count' => $old_comments_count
		));
	}

	$old_posts_count = elgg_get_annotations(array(
		'annotation_names' => 'group_topic_post',
		'count' => true
			));

	if ($old_posts_count) {
		echo elgg_view('framework/alive/admin/import_posts', array(
			'count' => $old_posts_count
		));
	}
}

if (elgg_get_plugin_setting('upgrade_1-9', 'hypeAlive')) {
	$params = elgg_clean_vars($vars);
	echo hj_framework_view_form_body('edit:plugin:hypealive', $params);
}

return true;

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


$notifications_label = "By default, notifications are sent, when generic comment, group topic post, or like is created. Remove any of the following to stop sending notifications";
$notifications_input = elgg_view('input/text', array(
	'name' => 'params[notifications]',
	'value' => $vars['entity']->notifications
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