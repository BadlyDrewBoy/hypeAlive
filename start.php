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

define('HYPEALIVE_RELEASE', 1365760418);

define('HYPEALIVE_COMMENTS', elgg_get_plugin_setting('comments', 'hypeAlive'));
define('HYPEALIVE_RIVER_COMMENTS', elgg_get_plugin_setting('river_comments', 'hypeAlive'));
define('HYPEALIVE_ENTITY_COMMENTS', elgg_get_plugin_setting('entity_comments', 'hypeAlive'));
define('HYPEALIVE_FORUM_COMMENTS', elgg_get_plugin_setting('forum_comments', 'hypeAlive'));
define('HYPEALIVE_MAX_COMMENT_DEPTH', elgg_get_plugin_setting('max_comment_depth', 'hypeAlive'));

define('HYPEALIVE_LIKES', elgg_get_plugin_setting('likes', 'hypeAlive'));
define('HYPEALIVE_DISLIKES', elgg_get_plugin_setting('dislikes', 'hypeAlive'));
define('HYPEALIVE_LIKES_STYLE', elgg_get_plugin_setting('likes_style', 'hypeAlive'));
define('HYPEALIVE_LIKES_LIMIT', elgg_get_plugin_setting('likes_limit', 'hypeAlive'));

define('HYPEALIVE_SEARCH', elgg_get_plugin_setting('livesearch', 'hypeAlive'));
define('HYPEALIVE_RIVER', elgg_get_plugin_setting('river', 'hypeAlive'));

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
		'hooks'
	);

	foreach ($libraries as $lib) {
		$path = "{$shortcuts['lib']}{$lib}.php";
		if (file_exists($path)) {
			elgg_register_library("alive:library:$lib", $path);
			elgg_load_library("alive:library:$lib");
		}
	}
}