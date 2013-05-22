<?php

$item = elgg_extract('item', $vars);

$object = $vars['item']->getObjectEntity();
$subject = $vars['item']->getSubjectEntity();

if (elgg_in_context('substream-view')) {
	$friendly_time = elgg_get_friendly_time($item->posted);

	$text = elgg_echo('hj:alive:river:substream:subscription', array($subject->name, $friendly_time));

	echo elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => elgg_view('output/img', array(
			'src' => $subject->getIconURL('small')
		)),
		'encode_text' => false,
		'title' => $text
	));
} else {
	elgg_push_context('substream-view');
	$subject_link = elgg_view('output/url', array(
		'href' => $subject->getURL(),
		'text' => $subject->name,
		'class' => 'elgg-river-subject',
		'is_trusted' => true,
			));

	if (elgg_instanceof($object, 'object', 'hjstream')) {
		$river = elgg_get_river(array(
			'ids' => $object->river_id,
		));
		$stream = $river[0];
		if (!$stream instanceof ElggRiverItem) {
			return true;
		}
		$stream_subject_link = elgg_view('output/url', array(
			'text' => $stream->getSubjectEntity()->name,
			'href' => $stream->getSubjectEntity()->getURL()
		));
		$object_link = elgg_echo('hj:alive:stream:desc', array($stream_subject_link));
		$attachment = elgg_view_river_item($stream, array('full_view' => false));
	} else {
		$object_link = elgg_view('output/url', array(
			'href' => $object->getURL(),
			'text' => $object->title,
			'class' => 'elgg-river-object',
			'is_trusted' => true,
				));
		$attachment = elgg_view_entity($object, array('full_view' => false));
	}

	$summary = elgg_echo('hj:alive:river:stream:subscription', array($subject_link, $object_link));

	echo elgg_view('river/elements/layout', array(
		'item' => $vars['item'],
		'attachments' => $attachment,
		'summary' => $summary,
	));
	elgg_pop_context();
}