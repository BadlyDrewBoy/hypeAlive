<?php

if (!HYPEALIVE_RIVER_COMMENTS) {
	echo elgg_view('river/elements/comments', $vars);
	return true;
}

$entity = elgg_extract('entity', $vars);
$params = elgg_clean_vars($vars);

$comments_count = hj_alive_count_comments($entity, $params);
if ($comments_count <= 0) {
	$comments_block_hidden = 'hidden';
}

$comments_view = hj_alive_view_comments_list($entity, $params);
$comments_input = elgg_view('framework/alive/comments/form', $params);

echo <<<__HTML
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
__HTML;
