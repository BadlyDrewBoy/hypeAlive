<?php

if (HYPEALIVE_COMMENTS || HYPEALIVE_LIKES || HYPEALIVE_DISLIKES) {
	elgg_register_js('alive.annotations.js', elgg_get_simplecache_url('js', 'framework/alive/annotations'));
	elgg_register_simplecache_view('js/framework/alive/annotations');

	elgg_register_css('alive.annotations.css', elgg_get_simplecache_url('css', 'framework/alive/annotations'));
	elgg_register_simplecache_view('css/framework/alive/annotations');
}

if (HYPEALIVE_SEARCH) {
	elgg_register_js('alive.search.js', elgg_get_simplecache_url('js', 'framework/alive/search'));
	elgg_register_simplecache_view('js/framework/alive/search');

	elgg_register_css('alive.search.css', elgg_get_simplecache_url('css', 'framework/alive/search'));
	elgg_register_simplecache_view('css/framework/alive/search');
}

if (HYPEALIVE_RIVER) {
	elgg_register_js('alive.river.js', elgg_get_simplecache_url('js', 'framework/alive/river'));
	elgg_register_simplecache_view('js/framework/alive/river');

	elgg_register_css('alive.river.css', elgg_get_simplecache_url('css', 'framework/alive/river'));
	elgg_register_simplecache_view('css/framework/alive/river');
}


