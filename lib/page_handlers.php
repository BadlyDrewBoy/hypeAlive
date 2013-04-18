<?php

if (HYPEALIVE_RIVER) {
	elgg_unregister_page_handler('activity', 'elgg_river_page_handler');
	elgg_register_page_handler('activity', 'hj_alive_river_page_handler');
}

function hj_alive_river_page_handler($page) {
	$plugin = 'hypeAlive';
	$shortcuts = hj_framework_path_shortcuts($plugin);

	if (isset($page[1]) && $user = get_user_by_username($page[1])) {
		if (!$user->canEdit()) {
			forward('activity');
		}
		elgg_set_page_owner_guid($user->guid);
	} else {
		$user = elgg_get_logged_in_user_entity();
		elgg_set_page_owner_guid($user->guid);
	}

	switch ($page[0]) {
		default :
			if (elgg_is_logged_in()) {
				$river_tabs = unserialize(elgg_get_plugin_setting('river_tabs', 'hypeAlive'));
				asort($river_tabs);

				foreach ($river_tabs as $tab => $priority) {
					if ($priority && !empty($priority)) {
						$tabs[] = $tab;
					}
				}
			} else {
				$tabs = array('all');
			}

			global $HYPEALIVE_RIVER_TABS;
			$HYPEALIVE_RIVER_TABS = $tabs;

			$default_tab = $tabs[0];

			// make a URL segment available in page handler script
			$page_type = elgg_extract(0, $page, $default_tab);
			$page_type = preg_replace('[\W]', '', $page_type);
			if ($page_type == 'owner') {
				$page_type = 'mine';
			}

			if (!in_array($page_type, $tabs)) {
				forward("activity/$default_tab");
			}

			$type = preg_replace('[\W]', '', get_input('type', 'all'));
			$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

			elgg_register_menu_item('title', array(
				'name' => 'river:settings',
				'text' => elgg_echo('hj:alive:river:settings'),
				'href' => "activity/settings/$user->username",
				'class' => 'elgg-button',
			));

			$title = elgg_echo("river:$page_type");
			$content = elgg_view('framework/activity/list', array(
				'page_type' => $page_type,
				'type' => $type,
				'subtype' => $subtype
					));
			$filter = elgg_view('framework/activity/filter', array(
				'filter_context' => $page_type
					));
			$sidebar = elgg_view('framework/activity/sidebar', array(
				'page_type' => $page_type,
				'type' => $type,
				'subtype' => $subtype
					));
			break;

		case 'settings' :
			elgg_set_context('settings');
			$title = elgg_echo('hj:alive:river:settings');
			$content = elgg_view('framework/activity/settings');
			$filter = false;
			$sidebar = false;
			break;
	}

	$layout = elgg_view_layout('content', array(
		'title' => $title,
		'content' => $content,
		'filter' => $filter,
		'sidebar' => $sidebar
			));

	echo elgg_view_page($title, $layout);
	return true;
}