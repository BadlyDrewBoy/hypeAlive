<?php

$extend = elgg_view('framework/alive/replies/extend', $vars);

$attr = array(
	'class' => 'hj-stream hj-stream-replies clearfix',
	'data-streamid' => elgg_extract('container_guid', $vars, null)
);
$attr = elgg_format_attributes($attr);

echo <<<__HTML
<div $attr>
	{$vars['menu']}{$vars['likes']}{$vars['replies']}{$extend}
</div>
__HTML;
