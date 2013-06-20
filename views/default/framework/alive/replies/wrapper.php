<?php

$entity = elgg_extract('entity', $vars, false);
$extend = elgg_view('framework/alive/replies/extend', $vars);

$attr = array(
	'class' => 'hj-stream hj-stream-replies clearfix',
	'data-streamid' => $entity->guid
);
$attr = elgg_format_attributes($attr);

echo <<<__HTML
<div $attr>
	{$vars['menu']}{$vars['likes']}{$vars['replies']}{$extend}
</div>
__HTML;
