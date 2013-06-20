<?php

elgg_extend_view('framework/alive/comments/extend', 'framework/alive/comments/substream');

if (HYPEALIVE_SEARCH) {
	elgg_extend_view('css/elements/modules', 'css/hj/livesearch/base');
}

elgg_register_ajax_view('framework/alive/comments/form');
elgg_register_ajax_view('framework/alive/attachments/form');