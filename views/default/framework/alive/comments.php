<?php

/**
 *  Display the comments bar with all comments and likes
 *
 *  @uses $vars['entity'] Entity that is being commented on
 *  @uses $vars['aname'] Name of the annotation generic_comment|group_topic_post
 */

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

elgg_load_css('alive.stream.css');
elgg_load_js('alive.stream.js');

$params = hj_alive_prepare_view_params($entity, $aname);

$menu = elgg_view_menu('comments', array(
	'entity' => $params['entity'],
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
	'params' => $params
		));

$comments = elgg_view('framework/alive/comments/comments', $params);
$likes = elgg_view('framework/alive/likes/wrapper', $params);
$dislikes = elgg_view('framework/alive/dislikes/wrapper', $params);

$params['menu'] = $menu;
$params['comments'] = $comments;
$params['likes'] = $likes;
$params['dislikes'] = $dislikes;

echo elgg_view('framework/alive/comments/wrapper', $params);
