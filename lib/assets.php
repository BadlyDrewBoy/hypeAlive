<?php

elgg_register_js('alive.admin.js', elgg_get_simplecache_url('js', 'framework/alive/admin'));
elgg_register_simplecache_view('js/framework/alive/admin');

elgg_register_css('alive.admin.css', elgg_get_simplecache_url('css', 'framework/alive/admin'));
elgg_register_simplecache_view('css/framework/alive/admin');

elgg_register_js('alive.stream.js', elgg_get_simplecache_url('js', 'framework/alive/stream'));
elgg_register_simplecache_view('js/framework/alive/stream');

elgg_register_css('alive.stream.css', elgg_get_simplecache_url('css', 'framework/alive/stream'));
elgg_register_simplecache_view('css/framework/alive/stream');

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


