<?php

$entity = elgg_extract('entity', $vars);

$attachments = elgg_get_entities_from_relationship(array(
	'relationship' => 'attached',
	'relationship_guid' => $entity->guid,
	'inverse_relationship' => true,
	'limit' => 0,
		));

if ($attachments) {

	$extras .= '<ul class="hj-comments-attachments">';
	foreach ($attachments as $attachment) {
		$at_icon = elgg_view('output/img', array(
			'src' => $attachment->getIconURL('tiny')
				));

		if ($attachment instanceof ElggFile) {
			$prefix = $attachment->simpletype;
		} else {
			$type = $attachment->getType();
			$subtype = $attachment->getSubtype();
			$prefix = elgg_echo("item:$type:$subtype");
		}
		$at_link = elgg_view('output/url', array(
			'text' => ($attachment->title) ? "$prefix: $attachment->title" : "$prefix",
			'href' => $attachment->getURL()
				));

		if ($entity->canEdit()) {
			$at_alt = elgg_view('output/url', array(
				'text' => elgg_echo('hj:alive:attachments:detach'),
				'title' => elgg_echo('hj:alive:attachments:detach'),
				'href' => "action/alive/attachments/detach?guid_one=$attachment->guid&guid_two=$entity->guid",
				'is_action' => true,
				'class' => 'hj-comments-attachments-detach'
					));
		}

		$extras .= '<li>' . elgg_view_image_block($at_icon, $at_link, array('image_alt' => $at_alt)) . '</li>';
	}

	$extras .= '</ul>';
}

echo $extras;