<?php

if (elgg_is_logged_in()) {
	echo elgg_view('framework/activity/filters/tsp', $vars);
	echo elgg_view('framework/activity/filters/users', $vars);
	echo elgg_view('framework/activity/filters/groups', $vars);
}

echo elgg_view('core/river/sidebar');