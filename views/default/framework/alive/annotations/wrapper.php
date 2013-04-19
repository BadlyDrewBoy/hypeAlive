<?php

$extend = elgg_view('framework/alive/annotation/extend', $vars);

$attr = array(
	'class' => 'hj-annotations-bar clearfix',
	'data-cuid' => elgg_extract('container_guid', $vars, null),
	'data-ruid' => elgg_extract('river_id', $vars, null)
);
$attr = elgg_format_attributes($attr);

echo <<<__HTML
<div $attr>
	<div class="hj-annotations-header hj-annotations-menu clearfix">
		{$vars['menu']}
    </div>
	<div class="hj-annotations-body">
		{$vars['likes']}{$vars['dislikes']}{$vars['comments']}{$extend}
	</div>
</div>
__HTML;
