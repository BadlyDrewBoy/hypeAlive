<?php
/**
 *  Display the comments bar with all comments and likes
 *
 *  @uses $vars['entity'] Entity that is being commented on
 *  @uses $vars['aname'] Name of the annotation generic_comment|group_topic_post
 */

elgg_load_css('hj.comments.base');
elgg_load_js('hj.comments.base');
elgg_load_js('hj.likes.base');

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$aname = elgg_extract('aname', $vars, null);
$params = hj_alive_prepare_view_params($entity, $aname);

$comments_count = hj_alive_count_comments($entity, $params);
if ($comments_count <= 0) {
	$comments_block_hidden = 'hidden';
}

$comments_view = hj_alive_view_comments_list($entity, $params);

if (elgg_is_logged_in()) {
	$menu = elgg_view_menu('comments', array(
		'entity' => $entity,
		'class' => 'elgg-menu-hz',
		'sort_by' => 'priority',
		'params' => $params
			));

	$comments_input = elgg_view('hj/comments/input', $params);
}

unset($params['aname']);
unset($params['entity']);

if (!elgg_instanceof($entity, 'object', 'hjannotation')) {
	$likes_count = hj_alive_get_likes($params, true);
	if ($likes_count <= 0) {
		$likes_block_hidden = "hidden";
	}
	$likes_view = hj_alive_view_likes_list($params);
	$likes_block = "<div class=\"likes likes-block\">$likes_view</div>";
}

$extend = elgg_view('hj/comments/bar/extend', $vars);

echo <<<__COMMENTS
<div id="hj-annotations-{$params['selector_id']}" class="hj-annotations-bar clearfix">
	<div class="hj-annotations-header hj-annotations-menu clearfix">
		$menu
    </div>
	<div class="hj-annotations-body">
		<div class="hj-annotations-likes-block $likes_block_hidden">
			$likes_block
		</div>
		<div class="hj-annotations-comments-block $comments_block_hidden">
			<div class="hj-annotations-list">
				<div class="annotations">
					$comments_view
				</div>
				<div class="hj-comments-bubble hj-comments-input hidden">
					$comments_input
				</div>
			</div>
		</div>
		$extend
	</div>
</div>
__COMMENTS;
