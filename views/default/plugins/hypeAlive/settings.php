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