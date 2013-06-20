<?php

$list_id = elgg_extract('list_id', $vars, "attachments");

$list_type = get_input("__list_type_$list_id", 'table');

$getter_options = array(
	'types' => 'object',
	//'subtypes' => array_diff(get_registered_entity_types('object'), array('hjcomment')),
	'subtypes' => array('file', 'hjfile'),
	'owner_guids' => elgg_get_logged_in_user_guid()
);

$list_options = array(
	'list_type' => $list_type,
	'list_class' => 'attachments-options',
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
//				'subtype' => array(
//					'text' => elgg_echo('hj:alive:table:head:subtype'),
//					'override_view' => 'framework/alive/attachments/subtype',
//					'sortable' => true,
//					'sort_key' => 'e.subtype'
//				),
				'checkbox' => array(
					'text' => '',
					'override_view' => 'framework/alive/attachments/checkbox',
					'sortable' => false
				),
				'access' => array(
					'text' => '',
					'override_view' => 'framework/bootstrap/object/elements/access',
					'sortable' => false
				)
			),
		),
	),
	'pagination' => true,
	'limit_select_options' => array(5,10,20,50),
	'filter' => elgg_view('framework/alive/attachments/search')
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

$content = hj_framework_view_list($list_id, $getter_options, $list_options, $viewer_options, 'elgg_get_entities');

echo $content;