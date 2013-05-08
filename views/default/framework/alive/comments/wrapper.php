<?php

$extend = elgg_view('framework/alive/comments/extend', $vars);

$attr = array(
	'class' => 'hj-stream hj-comments-stream clearfix',
	'data-streamid' => elgg_extract('container_guid', $vars, null)
);
$attr = elgg_format_attributes($attr);

echo <<<__HTML
<div $attr>
	{$vars['menu']}{$vars['likes']}{$vars['comments']}{$extend}
</div>
__HTML;
