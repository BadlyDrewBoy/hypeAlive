<?php

if (HYPEALIVE_RIVER_COMMENTS) {
	//elgg_unregister_plugin_hook_handler('register', 'menu:river', 'elgg_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'likes_river_menu_setup');
	elgg_unregister_plugin_hook_handler('register', 'menu:river', 'discussion_add_to_river_menu');
	if (elgg_get_context() == 'activity') {
		elgg_unregister_plugin_hook_handler('register', 'menu:entity', 'likes_entity_menu_setup');
	}
	elgg_register_plugin_hook_handler('register', 'menu:river', 'hj_alive_river_menu_setup');
}

if (HYPEALIVE_RIVER) {
	if (elgg_is_logged_in()) {
		$user = elgg_get_logged_in_user_entity();
		elgg_register_menu_item('page', array(
			'name' => 'river:settings',
			'text' => elgg_echo('hj:alive:river:settings'),
			'href' => "activity/settings/$user->username",
			'contexts' => array('settings')
		));
	}
}
elgg_register_plugin_hook_handler('register', 'menu:comments', 'hj_alive_comments_menu');
elgg_register_plugin_hook_handler('register', 'menu:replies', 'hj_alive_replies_menu');

function hj_alive_comments_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	/**
	 * Comment
	 */
	if (HYPEALIVE_COMMENTS) {
		if ($entity->canComment()) {
			$items['comment'] = array(
				'text' => elgg_echo('comment'),
				'href' => '#',
				'priority' => 200
			);
		}
		$items['comments-count'] = array(
			'text' => hj_alive_count_comments($entity),
			'href' => false,
			'priority' => 200,
			'section' => 'stats'
		);
	}

	/**
	 * Like / Unlike
	 */
	if (HYPEALIVE_LIKES) {
		if ($entity->canAnnotate()) {
			$style = HYPEALIVE_LIKES_STYLE;
			$items['likes'] = array(
				'text' => (hj_alive_does_user_like($entity)) ? elgg_echo("hj:alive:like:$style") : elgg_echo("hj:alive:unlike:$style"),
				'href' => 'action/likes/toggle',
				'class' => (hj_alive_does_user_like($entity)) ? "elgg-button-like-$style elgg-state-active" : "elgg-button-like-$style",
				'priority' => 300
			);
		}
		$items['likes-count'] = array(
			'text' => hj_alive_count_likes($entity),
			'href' => false,
			'priority' => 300,
			'section' => 'stats'
		);
	}

	/**
	 * Subscriptions / Bookmarks
	 */
	if (HYPEALIVE_SUBSCRIPTIONS) {
		$subscribed = check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $entity->guid);
		$items['subscription'] = array(
			'text' => ($subscribed) ? elgg_echo('hj:alive:subscription:remove') : elgg_echo('hj:alive:subscription:create'),
			'href' => 'action/alive/subscription',
			'class' => ($subscribed) ? 'elgg-state-active' : false,
			'priority' => 400
		);
	}

	if (HYPEALIVE_BOOKMARKS) {
		$bookmarked = check_entity_relationship(elgg_get_logged_in_user_guid(), 'bookmarked', $entity->guid);
		$items['bookmark'] = array(
			'text' => ($bookmarked) ? elgg_echo('hj:alive:bookmark:remove') : elgg_echo('hj:alive:bookmark:create'),
			'href' => 'action/alive/bookmark',
			'class' => ($bookmarked) ? 'elgg-state-active' : false,
			'priority' => 500
		);
		$items['bookmarks-count'] = array(
			'text' => hj_alive_count_bookmarks($entity),
			'href' => false,
			'priority' => 500,
			'section' => 'stats'
		);
	}

	/**
	 * Shared
	 */
	if (HYPEALIVE_SHARES) {
		$shared = check_entity_relationship(elgg_get_logged_in_user_guid(), 'shared', $entity->guid);
		if (!$shared) {
			$items['shares'] = array(
				'text' => elgg_echo('hj:alive:share'),
				'href' => '#',
				'priority' => 600
			);
		}
		$items['shares-count'] = array(
			'text' => hj_alive_count_shares($entity),
			'href' => false,
			'priority' => 500,
			'section' => 'stats'
		);
	}

	if ($items) {
		foreach ($items as $name => $item) {
			foreach ($return as $key => $val) {
				if (!$val instanceof ElggMenuItem) {
					unset($return[$key]);
				}
				if ($val instanceof ElggMenuItem && $val->getName() == $name) {
					unset($return[$key]);
				}
			}
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}

	return $return;
}

function hj_alive_replies_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);

	/**
	 * TimeStamp
	 */
	if (elgg_instanceof($entity, 'object', 'hjcomment') && $timestamp = $entity->time_created) {
		$items['timestamp'] = array(
			'text' => elgg_view_friendly_time($timestamp),
			'href' => false,
			'priority' => 100
		);
	}

	/**
	 * Comment
	 */
	if (HYPEALIVE_COMMENTS) {
		if ($entity->canComment()) {
			$items['comment'] = array(
				'text' => elgg_echo('comment'),
				'href' => false,
				'priority' => 200
			);
		}
		$items['comments-count'] = array(
			'text' => hj_alive_count_comments($entity),
			'href' => false,
			'priority' => 200,
			'section' => 'stats'
		);
	}

	/**
	 * Like / Unlike
	 */
	if (HYPEALIVE_LIKES) {
		if ($entity->canAnnotate()) {
			$style = HYPEALIVE_LIKES_STYLE;
			$items['likes'] = array(
				'text' => (hj_alive_does_user_like($entity)) ? elgg_echo("hj:alive:like:$style") : elgg_echo("hj:alive:unlike:$style"),
				'href' => 'action/likes/toggle',
				'class' => (hj_alive_does_user_like($entity)) ? "elgg-button-like-$style elgg-state-active" : "elgg-button-like-$style",
				'priority' => 300
			);
		}
		$items['likes-count'] = array(
			'text' => hj_alive_count_likes($entity),
			'href' => false,
			'priority' => 300,
			'section' => 'stats'
		);
	}

	if ($items) {
		foreach ($items as $name => $item) {
			foreach ($return as $key => $val) {
				if (!$val instanceof ElggMenuItem) {
					unset($return[$key]);
				}
				if ($val instanceof ElggMenuItem && $val->getName() == $name) {
					unset($return[$key]);
				}
			}
			$item['name'] = $name;
			$return[$name] = ElggMenuItem::factory($item);
		}
	}

	return $return;
}

function hj_alive_commentshead_menu($hook, $type, $return, $params) {
	$entity = elgg_extract('entity', $params, false);

	if (!$entity && !elgg_instanceof($entity, 'object', 'hjannotation')) {
		return $return;
	}
	unset($return);

	$params = hj_framework_extract_params_from_entity($entity, $params);
	$params = hj_framework_json_query(array('params' => $params));
	/**
	 * Delete
	 */
	if ($entity->canEdit()) {
		$edit = array(
			'name' => 'edit',
			'text' => elgg_echo('hj:framework:edit'),
			'class' => 'hj-comments-edit',
			'href' => "javascript:void(0)",
			'data-options' => htmlentities($params, ENT_QUOTES, 'UTF-8'),
			'priority' => 800,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($edit);

		$delete = array(
			'name' => 'delete',
			'text' => elgg_echo('hj:framework:delete'),
			'class' => 'hj-ajaxed-remove',
			'id' => "hj-ajaxed-remove-$entity->guid",
			'href' => "action/framework/entities/delete?e=$entity->guid",
			'data-options' => htmlentities($params, ENT_QUOTES, 'UTF-8'),
			'is_action' => true,
			'priority' => 1000,
			'section' => 'dropdown'
		);
		$return[] = ElggMenuItem::factory($delete);
	}

	return $return;
}

function hj_alive_river_menu_setup($hook, $type, $return, $params) {

	foreach ($return as $key => $item) {
		if ($item instanceof ElggMenuItem && $item->getName() == 'comment') {
			unset($return[$key]);
		}
	}

	return $return;
}