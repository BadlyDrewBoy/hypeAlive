<?php

$subtypes = array(
	'hjcomment' => 'hjComment',
	'hjgrouptopicpost' => 'hjGroupTopicPost'
);

foreach ($subtypes as $subtype => $class) {
	update_subtype('object', $subtype);
}
