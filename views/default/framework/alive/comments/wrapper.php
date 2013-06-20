<?php

$extend = elgg_view('framework/alive/comments/extend', $vars);

$class = 'hj-stream hj-comments-stream clearfix';
if (isset($vars['class'])) {
	$class = "{$class} {$vars['class']}";
}

$attr = array(
	'class' => $class,
	'data-streamid' => elgg_extract('container_guid', $vars, null)
);
$attr = elgg_format_attributes($attr);

echo <<<__HTML
<div $attr>
	{$vars['menu']}{$vars['likes']}{$vars['comments']}{$extend}
</div>
__HTML;
