<?php

$item = elgg_extract('item', $vars);

$subject = $item->getSubjectEntity();
$subject_str = hj_alive_get_river_subject_string($subject);
$action = $item->action_type;
$time = elgg_get_friendly_time($item->posted);

$icon = elgg_view_entity_icon($subject, 'tiny');

$text = hj_alive_get_river_summary($item);
$text .= '<span class="mlm elgg-subtext">' . $time . '</span>';
echo elgg_view_image_block($icon, $text);
