<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
	return true;
}

$owner = get_entity($entity->owner_guid);

if (!elgg_instanceof($owner)) {
	return true;
}

$icon = elgg_view_entity_icon($owner, 'tiny', array('use_hover' => false));

$author = elgg_view('output/url', array(
	'text' => $owner->name,
	'href' => $owner->getURL(),
	'class' => 'hj-comments-item-comment-owner'
		));

$comment = "<span class=\"annotation-value\" data-uid=\"$entity->guid\">" . elgg_view('output/text', array(
			'value' => $entity->description
		)) . '</span>';

$comment = elgg_echo('hj:alive:comments:commentcontent', array($author, $comment));

$extras = elgg_view('framework/alive/comments/attachments', $vars);

$bar = elgg_view_comments($entity, true, $vars);

$content = <<<HTML
    <div class="clearfix">
        $comment
    </div>
	$extras
    $bar
HTML;

echo elgg_view_image_block($icon, $content);