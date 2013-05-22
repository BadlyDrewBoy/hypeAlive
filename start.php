<?php

/* hypeAlive
 *
 * Live feeds, comments and search Elgg
 * @package hypeJunction
 * @subpackage hypeAlive
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyrigh (c) 2011-2013, Ismayil Khayredinov
 */
define('HYPEALIVE_RELEASE', 1366567725);

define('HYPEALIVE_COMMENTS', elgg_get_plugin_setting('comments', 'hypeAlive'));
define('HYPEALIVE_MAX_COMMENT_DEPTH', (int)elgg_get_plugin_setting('max_comment_depth', 'hypeAlive'));
define('HYPEALIVE_COMMENT_FORM', elgg_get_plugin_setting('comment_form', 'hypeAlive'));
define('HYPEALIVE_COMMENT_FORM_POSITION', elgg_get_plugin_setting('comment_form_position', 'hypeAlive'));
define('HYPEALIVE_COMMENTS_ORDER', elgg_get_plugin_setting('comments_order', 'hypeAlive'));
define('HYPEALIVE_COMMENTS_LOAD_STYLE', elgg_get_plugin_setting('comments_load_style', 'hypeAlive'));
define('HYPEALIVE_COMMENTS_LIMIT', (int)elgg_get_plugin_setting('comments_limit', 'hypeAlive'));
define('HYPEALIVE_COMMENTS_LOAD_LIMIT', (int)elgg_get_plugin_setting('comments_load_limit', 'hypeAlive'));

define('HYPEALIVE_LIKES', elgg_get_plugin_setting('likes', 'hypeAlive'));

define('HYPEALIVE_SEARCH', elgg_get_plugin_setting('livesearch', 'hypeAlive'));

define('HYPEALIVE_RIVER', elgg_get_plugin_setting('river', 'hypeAlive'));
define('HYPEALIVE_RIVER_COMMENTS', elgg_get_plugin_setting('river_comments', 'hypeAlive'));
define('HYPEALIVE_RIVER_ORDER', elgg_get_plugin_setting('river_order', 'hypeAlive'));
define('HYPEALIVE_RIVER_LOAD_STYLE', elgg_get_plugin_setting('river_load_style', 'hypeAlive'));
define('HYPEALIVE_RIVER_LIMIT', (int)elgg_get_plugin_setting('river_limit', 'hypeAlive'));
define('HYPEALIVE_RIVER_LOAD_LIMIT', (int)elgg_get_plugin_setting('river_load_limit', 'hypeAlive'));

define('HYPEALIVE_FORUM_COMMENTS', elgg_get_plugin_setting('forum_comments', 'hypeAlive'));

define('HYPEALIVE_SUBSCRIPTIONS', elgg_get_plugin_setting('subscriptions', 'hypeAlive'));
define('HYPEALIVE_BOOKMARKS', elgg_get_plugin_setting('bookmarks', 'hypeAlive'));
define('HYPEALIVE_SHARES', elgg_get_plugin_setting('shares', 'hypeAlive'));

elgg_register_event_handler('init', 'system', 'hj_alive_init');

function hj_alive_init() {

	$plugin = 'hypeAlive';

	if (!is_callable('hj_framework_path_shortcuts')) {
		register_error(elgg_echo('framework:error:plugin_order', array($plugin)));
		disable_plugin($plugin);
		forward('admin/plugins');
	}

	// Run upgrade scripts
	hj_framework_check_release($plugin, HYPEALIVE_RELEASE);

	$shortcuts = hj_framework_path_shortcuts($plugin);

	// Helper Classes
	elgg_register_classes($shortcuts['classes']);

	// Libraries
	$libraries = array(
		'base',
		'forms',
		'page_handlers',
		'actions',
		'assets',
		'views',
		'menus',
		'hooks',
		'stream'
	);

	foreach ($libraries as $lib) {
		$path = "{$shortcuts['lib']}{$lib}.php";
		if (file_exists($path)) {
			elgg_register_library("alive:library:$lib", $path);
			elgg_load_library("alive:library:$lib");
		}
	}

}

if (HYPEALIVE_RIVER_COMMENTS) {
	elgg_register_event_handler('created', 'river', 'hj_alive_create_river_stream_object');
	/** @todo: Add a cron job to remove hjstream objects for deleted river items */
}