<?php

elgg_load_js('framework.attachments.js');
elgg_load_css('framework.attachments.css');

$value = elgg_extract('value', $vars, array());

echo elgg_view('output/url', array(
	'text' => '',
	'href' => 'stream/attach',
	'class' => 'hj-alive-attach',
	'is_trusted' => true
));

echo '<ul class="hj-alive-attachments-list">';
foreach ($value as $guid) {
	$entity = get_entity($guid);
	echo '<li data-guid="' . $guid . '">';
	echo elgg_view('framework/alive/attachments/item', array(
		'entity' => $entity,
		'use_checkbox' => false
	));
	echo elgg_view('input/hidden', array(
		'name' => 'attachments[]',
		'value' => $guid
	));
	echo '</li>';
}
echo '</ul>';