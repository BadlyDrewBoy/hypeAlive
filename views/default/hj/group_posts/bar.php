<?php
/**
 * Load CSS and JS libraries
 */
elgg_load_css('hj.comments.base');
if (elgg_is_logged_in()) {
	elgg_load_js('hj.comments.base');
	elgg_load_js('hj.likes.base');
}
elgg_load_js('hj.framework.ajax');

//if (!elgg_is_logged_in()) {
//	return true;
//}

$entity = elgg_extract('entity', $vars, false);

if (!$entity || !elgg_instanceof($entity, 'object', 'groupforumtopic')) {
	return true;
}

$params['container_guid'] = $entity->guid;
$params['aname'] = 'group_topic_post';

$comments_count = hj_alive_count_comments($entity, $params);
if ($comments_count <= 0) {
	$comments_block_hidden = 'hidden';
}
$comments_view = hj_alive_view_comments_list($entity, $params);

$menu = elgg_view_menu('comments', array(
	'entity' => $entity,
	'handler' => $handler,
	'class' => 'elgg-menu-hz',
	'sort_by' => 'priority',
	'params' => $params
		));

$params['entity'] = $entity;
$comments_input = elgg_view('hj/comments/input', $params);

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
?>
<div id="hj-annotations-<?php echo $entity->guid ?>" class="hj-annotations-bar clearfix">

	<!-- MENU AND EXTRAS -->
	<div class="hj-annotations-header hj-annotations-menu">
		<?php echo $menu ?>
    </div>

	<!-- ALL ANNOTATIONS -->
	<div class="hj-annotations-body">

		<!-- LIKES -->
		<div class="hj-annotations-likes-block <?php echo $likes_block_hidden ?>">
			<?php echo $likes_block ?>
		</div>

		<!-- COMMENTS -->
		<div class="hj-annotations-comments-block <?php echo $comments_block_hidden ?>">
			<div class="hj-annotations-list">
				<div class="annotations">
					<?php echo $comments_view ?>
				</div>
				<div class="hj-comments-bubble hj-comments-input hidden">
					<?php echo $comments_input ?>
				</div>
			</div>
		</div>

		<?php echo elgg_view('hj/comments/bar/extend', $vars) ?>
	</div>
</div>
