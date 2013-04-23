<?php

$entity = elgg_extract('entity', $vars);
$params = elgg_clean_vars($vars);

$comments_list = hj_alive_view_comments_list($entity, $params);
$comments_input = elgg_view('framework/alive/comments/form', $params);

if (HYPEALIVE_COMMENT_FORM_POSITION == 'before') {
	$comments = "$comments_input$comments_list";
} else {
	$comments = "$comments_list$comments_input";
}

echo <<<__HTML
		<div class="hj-stream-comments-block">
			$comments
		</div>
__HTML;
