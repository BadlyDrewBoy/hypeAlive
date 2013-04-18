<?php

elgg_register_js('alive.base.js', elgg_get_simplecache_url('js', 'framework/alive/base'));
elgg_register_simplecache_view('js/framework/alive/base');

elgg_register_css('alive.base.css', elgg_get_simplecache_url('css', 'framework/alive/base'));
elgg_register_simplecache_view('css/framework/alive/base');

if (HYPEALIVE_COMMENTS) {
	elgg_register_js('alive.comments.js', elgg_get_simplecache_url('js', 'framework/alive/comments'));
	elgg_register_simplecache_view('js/framework/alive/comments');

	elgg_register_css('alive.comments.css', elgg_get_simplecache_url('css', 'framework/alive/comments'));
	elgg_register_simplecache_view('css/framework/alive/comments');
}

if (HYPEALIVE_LIKES) {
	elgg_register_js('alive.likes.js', elgg_get_simplecache_url('js', 'framework/alive/likes'));
	elgg_register_simplecache_view('js/framework/alive/likes');

	elgg_register_css('alive.likes.css', elgg_get_simplecache_url('css', 'framework/alive/likes'));
	elgg_register_simplecache_view('css/framework/alive/likes');
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


