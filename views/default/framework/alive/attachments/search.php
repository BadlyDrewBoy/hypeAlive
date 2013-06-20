<?php

$body = elgg_view('input/text', array(
	'name' => "__q",
	'value' => get_input("__q", ''),
	'placeholder' => elgg_echo('hj:framework:filter:keywords'),
		));

// Reset all offsets so that lists return to first page
$query = elgg_parse_str(full_url());
foreach ($query as $key => $val) {
	if (strpos($key, '__off') === 0) {
		$footer .= elgg_view('input/hidden', array(
			'name' => $key,
			'value' => 0
				));
	}
}

$footer .= elgg_view('input/submit', array(
	'value' => elgg_echo('filter'),
		));

$filter = elgg_view_image_block('', $body, array(
	'image_alt' => $footer,
	'class' => 'hj-alive-attachments-filter'
		));

$search = elgg_view('input/form', array(
	'method' => 'GET',
	'action' => 'stream/attach',
	'disable_security' => true,
	'body' => $filter
		));



$body = '<span><a>' . elgg_echo('hj:alive:attach:upload') . '</a><i></i></span>';
$body .= elgg_view('input/file', array(
	'name' => 'attachments[]',
	'multiple' => true
		));

$upload = elgg_view('input/form', array(
	'action' => 'action/alive/attachments/upload',
	'enctype' => 'multipart/form-data',
	'class' => 'hj-alive-attachments-upload',
	'body' => $body
		));

echo '<div class="mbm clearfix">';

echo '<div class="hj-alive-attach-upload float-alt">';
echo $upload;
echo '</div>';

echo '<div class="hj-alive-attach-search float">';
echo $search;
echo '</div>';

echo '</div>';