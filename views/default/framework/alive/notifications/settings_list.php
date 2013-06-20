<?php

$user = elgg_get_page_owner_entity();

$list_id = "subscriptions";

$list_type = get_input("__list_type_$list_id", 'table');

$getter_options = array(
	'relationship' => 'subscribed',
	'relationship_guid' => $user->guid,
	'inverse_relationship' => false
);

$list_options = array(
	'list_type' => $list_type,
	'list_class' => 'notifications-options',
	'list_view_options' => array(
		'table' => array(
			'head' => array(
				'icon' => array(
					'text' => '',
					'sortable' => false,
				),
				'details' => array(
					'colspan' => array(
						'title' => array(
							'text' => elgg_echo('hj:alive:table:head:title'),
							'sortable' => true,
							'sort_key' => 'oe.title'
						),
						'tags' => array(
							'text' => '',
							'sortable' => false,
						),
					),
				),
				'subtype' => array(
					'text' => elgg_echo('hj:alive:table:head:subtype'),
					'override_view' => 'framework/alive/attachments/subtype',
					'sortable' => true,
					'sort_key' => 'e.subtype'
				),
				'actions' => array(
					'text' => '',
					'override_view' => 'framework/alive/notifications/actions',
					'sortable' => false
				),
			),
		),
	),
	'pagination' => true,
	'limit_select_options' => array(5,10,20,50),
);

$viewer_options = array(
	'full_view' => false,
	'list_type' => $list_type,
	'size' => 'small'
);

if (!get_input("__ord_$list_id", false)) {
	set_input("__ord_$list_id", 'e.time_created');
	set_input("__dir_$list_id", 'DESC');
}

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities_from_relationship');

echo $content;