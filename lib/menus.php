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
//elgg_register_plugin_hook_handler('register', 'menu:commentshead', 'hj_alive_commentshead_menu');


function hj_alive_comments_menu($hook, $type, $return, $params) {

	$entity = elgg_extract('entity', $params, false);
	$container_guid = elgg_extract('container_guid', $params['params'], null);
	$river_id = elgg_extract('river_id', $params['params'], null);

	if (!$guid = $container_guid) {
		$guid = $river_id;
	}

	if (!$entity) {
		return $return;
	}

	unset($return);

	/**
	 * TimeStamp
	 */
	if (elgg_instanceof($entity, 'object', 'hjannotation') && $timestamp = $entity->time_created) {
		$time = array(
			'name' => 'time',
			'entity' => $entity,
			'text' => elgg_view_friendly_time($timestamp),
			'href' => false,
			'priority' => 100
		);
		$return[] = ElggMenuItem::factory($time);
	}

	/**
	 * Like / Unlike
	 */
	if ($entity->getType() == 'river') {
		$object = $entity->getObjectEntity();
		if (!elgg_instanceof($object, 'object')) {
			$show_like = true;
		} elseif ($object->canComment() || $object->canAnnotate()) {
			$show_like = true;
		}
	} else if (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		$container = get_entity($entity->container_guid);
		$show_like = $container->canWriteToContainer();
	} else if ($entity->canAnnotate()) {
		$show_like = true;
	}
	
	if ($show_like) {
		unset($params['entity']);
		$likes_owner = hj_alive_does_user_like($params['params']);
		$likes_owner = $likes_owner['self'];

		if ($likes_owner) {
			$likes_class = "hidden";
			$unlikes_class = "visible";
		} else {
			$unlikes_class = "hidden";
			$likes_class = "visible";
		}
		if (elgg_get_plugin_setting('plusone', 'hypeAlive') == 'on') {
			$like_button = elgg_view_icon('plusone');
			$like_text = elgg_echo('hj:alive:comments:plusonebutton');
			$unlike_button = elgg_view_icon('minusone');
			$unlike_text = elgg_echo('hj:alive:comments:minusonebutton');
		} else {
			$like_text = $like_button = elgg_echo('hj:alive:comments:likebutton');
			$unlike_text = $unlike_button = elgg_echo('hj:alive:comments:unlikebutton');
		}

		if (elgg_instanceof($entity, 'object', 'hjannotation')) {
			$likes_view = hj_alive_view_likes_list($params['params']);
			$likes_inline = "<div class=\"likes likes-inline\">$likes_view</div>";
			$likes_count = array(
				'name' => 'likes_count',
				'text' => $likes_inline,
				'href' => false,
				'priority' => 310
			);
			$return[] = ElggMenuItem::factory($likes_count);
		}
		$likes = array(
			'name' => 'like',
			'text' => $like_button,
			'entity' => $entity,
			'title' => $like_text,
			'class' => $likes_class,
			'rel' => 'like',
			'priority' => 300
		);
		$unlikes = array(
			'name' => 'unlike',
			'text' => $unlike_button,
			'entity' => $entity,
			'title' => $unlike_text,
			'class' => $unlikes_class,
			'rel' => 'unlike',
			'priority' => 305
		);


		$return[] = ElggMenuItem::factory($likes);
		$return[] = ElggMenuItem::factory($unlikes);
	}

	/**
	 * Comment
	 */
	if ($entity->getType() == 'river') {
		$object = $entity->getObjectEntity();
		if (!elgg_instanceof($object, 'object')) {
			$show_comment = true;
		} elseif ($object->canComment() || $object->canAnnotate()) {
			$show_comment = true;
		}
	} else if (elgg_instanceof($entity, 'object', 'groupforumtopic')) {
		$container = get_entity($entity->container_guid);
		$show_comment = $container->canWriteToContainer();
	} else if ($entity->canComment() || $entity->canAnnotate()) {
		$show_comment = true;
	}

	if (elgg_instanceof($entity, 'object', 'hjannotation')) {
		if (!$max_depth = elgg_get_plugin_setting('max_comment_depth', 'hypeAlive')) {
			$max_depth = 3;
			elgg_set_plugin_setting('max_comment_depth', $max_depth);
		}
		if ($entity->depthToOriginalContainer() > $max_depth) {
			$show_comment = false;
		}
	}


	if ($show_comment) {
		$comment = array(
			'name' => 'comment',
			'text' => elgg_echo('hj:alive:comments:commentsbutton'),
			'entity' => $entity,
			'priority' => 200
		);
		$return[] = ElggMenuItem::factory($comment);
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
			'class' => 'hj-ajaxed-comment-edit',
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