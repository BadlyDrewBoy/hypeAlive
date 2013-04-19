<?php

if (HYPEALIVE_COMMENTS || HYPEALIVE_LIKES || HYPEALIVE_DISLIKES) {
	elgg_register_plugin_hook_handler('view', 'river/elements/responses', 'hj_alive_river_responses_view');
}

if (HYPEALIVE_SEARCH) {
	elgg_extend_view('css/elements/modules', 'css/hj/livesearch/base');
}

function hj_alive_river_responses_view($hook, $type, $output, $params) {
	return elgg_view('framework/river/elements/responses', $params['vars'], false, false, $params['viewtype']);

}