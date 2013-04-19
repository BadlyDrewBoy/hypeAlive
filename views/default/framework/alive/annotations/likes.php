<?php

if (!HYPEALIVE_LIKES) {
	return true;
}

$entity = $vars['entity'];
$params = elgg_clean_vars($vars);

if (!elgg_instanceof($entity, 'object', 'hjannotation')) {
	$likes_count = hj_alive_get_likes($params, true);
	if ($likes_count <= 0) {
		$likes_block_hidden = "hidden";
	}
	$likes_view = hj_alive_view_likes_list($params);
	$likes_block = "<div class=\"likes likes-block\">$likes_view</div>";
}

echo <<<__HTML
<div class="hj-annotations-likes-block $likes_block_hidden">
	$likes_block
</div>
__HTML;
