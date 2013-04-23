<?php

$comments_list = elgg_view('framework/alive/replies/placeholder', $vars);
$comments_input = elgg_view('framework/alive/replies/form', $vars);

if (HYPEALIVE_COMMENT_FORM_POSITION == 'before') {
	$comments = "$comments_input$comments_list";
} else {
	$comments = "$comments_list$comments_input";
}

echo <<<__HTML
		<div class="hj-stream-replies-block">
			$comments
		</div>
__HTML;
