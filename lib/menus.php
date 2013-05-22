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

elgg_register_menu_item('page', array(
	'name' => 'stream:notifications',
	'text' => elgg_echo('hj:alive:stream:notifications'),
	'href' => "stream/notifications/$user->username",
	'contexts' => array('settings')
));

elgg_register_plugin_hook_handler('register', 'menu:comments', 'hj_alive_comments_menu');
elgg_register_plugin_hook_handler('register', 'menu:replies', 'hj_alive_replies_menu');

function hj_alive_comments_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$viewer = elgg_get_logged_in_user_entity();

	/**
	 * Comment
	 */
	if (HYPEALIVE_COMMENTS) {
		$items['comment'] = array(
			'text' => ($entity->canComment()) ? elgg_echo('comment') : elgg_echo('comments'),
			'href' => (elgg_is_logged_in()) ? '#' : false,
			'priority' => 200
		);
		$items['comments-count'] = array(
			'text' => hj_alive_count_comments($entity),
			'href' => '#',
			'priority' => 200,
			'parent_name' => 'comment',
			'data-streamid' => $entity->guid
		);
	}

	/**
	 * Like / Unlike
	 */
	if (HYPEALIVE_LIKES) {
		$style = HYPEALIVE_LIKES_STYLE;
		$items['likes'] = array(
			'text' => (elgg_is_logged_in()) ? (hj_alive_does_user_like($entity)) ? elgg_echo('hj:alive:like:remove') : elgg_echo('hj:alive:like:create')  : elgg_echo('hj:alive:likes'),
			'href' => ($entity->canAnnotate()) ? "action/alive/like?guid=$entity->guid" : false,
			'is_action' => true,
			'class' => (hj_alive_does_user_like($entity)) ? 'elgg-state-active' : '',
			'priority' => 300
		);

		$items['likes-count'] = array(
			'text' => hj_alive_count_likes($entity),
			'href' => '#',
			'priority' => 300,
			'parent_name' => 'likes',
			'data-streamid' => $entity->guid
		);
	}

	/**
	 * Subscriptions / Bookmarks
	 */
	if (HYPEALIVE_SUBSCRIPTIONS && $viewer->guid != $entity->owner_guid && elgg_is_logged_in()) {
		$subscribed = check_entity_relationship($viewer->guid, 'subscribed', $entity->guid);
		$items['subscription'] = array(
			'text' => ($subscribed) ? elgg_echo('hj:alive:subscription:remove') : elgg_echo('hj:alive:subscription:create'),
			'href' => "action/alive/subscription?guid=$entity->guid",
			'is_action' => true,
			'class' => ($subscribed) ? 'elgg-state-active' : false,
			'priority' => 900
		);
	}

	if (HYPEALIVE_BOOKMARKS && elgg_is_logged_in()) {
		$bookmarked = check_entity_relationship($viewer->guid, 'bookmarked', $entity->guid);
		$items['bookmark'] = array(
			'text' => ($bookmarked) ? elgg_echo('hj:alive:bookmark:remove') : elgg_echo('hj:alive:bookmark:create'),
			'href' => "action/alive/bookmark?guid=$entity->guid",
			'is_action' => true,
			'class' => ($bookmarked) ? 'elgg-state-active' : false,
			'priority' => 500
		);
		$items['bookmarks-count'] = array(
			'text' => hj_alive_count_bookmarks($entity),
			'href' => '#',
			'priority' => 500,
			'parent_name' => 'bookmark',
			'data-streamid' => $entity->guid
		);
	}

	/**
	 * Shared
	 */
	if (HYPEALIVE_SHARES) {
		$shared = check_entity_relationship($viewer->guid, 'shared', $entity->guid);
		$items['shares'] = array(
			'text' => (elgg_is_logged_in() && !$shared) ? elgg_echo('hj:alive:share') : elgg_echo('hj:alive:shares'),
			'href' => (elgg_is_logged_in() && !$shared) ? "action/alive/share?guid=$entity->guid" : false,
			'priority' => 600
		);
		$items['shares-count'] = array(
			'text' => hj_alive_count_shares($entity),
			'href' => '#',
			'priority' => 500,
			'parent_name' => 'shares',
			'data-streamid' => $entity->guid
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
		$replies_count = hj_alive_count_comments($entity);
		if (elgg_is_logged_in() || $replies_count) {
			$items['reply'] = array(
				'text' => (elgg_is_logged_in()) ? elgg_echo('hj:alive:reply') : elgg_echo('hj:alive:replies'),
				'href' => '#',
				'priority' => 200
			);
			$items['comments-count'] = array(
				'text' => $replies_count,
				'href' => '#',
				'priority' => 200,
				'parent_name' => 'reply',
				'data-streamid' => $entity->guid
			);
		}
	}

	/**
	 * Like / Unlike
	 */
	if (HYPEALIVE_LIKES) {
		$style = HYPEALIVE_LIKES_STYLE;
		$likes_count = hj_alive_count_likes($entity);
		if (elgg_is_logged_in() || $likes_count) {
			$items['likes'] = array(
				'text' => (elgg_is_logged_in()) ? (hj_alive_does_user_like($entity)) ? elgg_echo('hj:alive:like:remove') : elgg_echo('hj:alive:like:create')  : elgg_echo('hj:alive:likes'),
				'href' => (elgg_is_logged_in()) ? "action/alive/like?guid=$entity->guid" : false,
				'is_action' => true,
				'class' => (hj_alive_does_user_like($entity)) ? 'elgg-state-active' : '',
				'priority' => 300
			);
			$items['likes-count'] = array(
				'text' => $likes_count,
				'href' => '#',
				'priority' => 300,
				'parent_name' => 'likes',
				'data-streamid' => $entity->guid
			);
		}
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