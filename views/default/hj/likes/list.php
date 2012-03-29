<?php
$string = elgg_extract('value', $vars, '');
$count = elgg_extract('count', $vars, 0);
$params = elgg_extract('params', $vars, array());
$params = htmlentities(json_encode($params), ENT_QUOTES, 'UTF-8');

$output = <<<HTML
    <div class="hj-likes-summary hj-comments-bubble" data-options="$params">$string</div>
HTML;

echo $output;