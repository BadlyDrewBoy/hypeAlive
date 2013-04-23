<?php if (FALSE) : ?>
	<script type="text/javascript">
<?php endif; ?>
elgg.provide('hj.comments');

/**
 *  Initialize hypeAlive JS
 */
hj.comments.init = function() {
	if(window.ajaxcommentsready === undefined) {
		window.ajaxcommentsready = true;
	}
	//hj.comments.triggerRefresh();

	var bar_loader = '<div class="hj-ajax-loader hj-loader-bar"></div>';

	// Show comment input on click
	$('.elgg-menu-item-comment')
	.unbind('click')
	.bind('click', function(event) {
		event.preventDefault();

		var comments_block = $(this).closest('.hj-stream').find('.hj-stream-comments-block').first();
		comments_block
		.find('.hj-comments-form')
		.last()
		.toggle('fast', function() {
			if (comments_block.hasClass('hidden')) {
				if ($(this).css('display') == 'none') {
					comments_block.hide();
				}
				if ($(this).css('display') == 'block') {
					comments_block.show();
				}
			}

			$(this)
			.find('[name="annotation_value"]')
			.focus();
		})
	});

	$('.hj-comments-edit')
	.unbind('click')
	.bind('click', hj.comments.editComment);

	$('.hj-comments-save')
	.removeAttr('onsubmit')
	.unbind('submit')
	.bind('submit', hj.comments.saveComment);

};

hj.comments.editComment = function(event) {
	event.preventDefault();

	var
	params = $(this).data('options').params,
	item = $('#elgg-object-' + params.entity_guid),
	item_html = item.html(),
	value = item.find('.annotation-value').html(),
	id = params.entity_guid,
	form = item.closest('.annotations').siblings('.hj-comments-form').last().find('form').html();
		
	item.html($('<form>').html(form));
	var input = item.find('[name="annotation_value"]');
	input.focus();
	input.val(value);
	item.find('[name="annotation_guid"]').val(id);

	item
	.find('form')
	.bind('submit', function(event) {
		event.preventDefault();
		input.addClass('hj-input-processing');
		value = input.val();
		elgg.action('action/comment/save', {
			data : {
				annotation_guid : id,
				annotation_value : value
			},
			success : function() {
				item.html(item_html);
				item.find('.annotation-value').html(value);
				elgg.trigger_hook('success', 'hj:framework:ajax');
			}
		})
	});


}

hj.comments.saveComment = function(event) {
	event.preventDefault();

	var     values = $(this).serialize(),
	action = $(this).attr('action'),
	container_guid, river_id, id, container, commentsList, instance_id;

	container_guid = $('input[name="container_guid"]', $(this)).val();
	river_id = $('input[name="river_id"]', $(this)).val();
	instance_id = $('input[name="instance_id"]', $(this)).val();

	if (river_id) {
		id = river_id
	} else {
		if (instance_id && instance_id.length > 0) {
			id = container_guid + '-' + instance_id
		} else {
			id = container_guid
		}
	}
	container = $('#hj-stream-'+ id);
	commentsList = container.find('.hj-comments').last();

	var input = $('[name="annotation_value"]', $(this));

	input
	.addClass('hj-input-processing');

	elgg.action(action + '?' + values, {
		contentType : 'application/json',
		success : function(output) {
			hj.framework.ajax.base.listRefresh("hj-comments-" + id);

			input
			.removeClass('hj-input-processing')
			.val('')
			.parents('.hj-comments-form')
			.last()
			.toggle();
		}
	});
}

elgg.register_hook_handler('init', 'system', hj.comments.init);
elgg.register_hook_handler('success', 'hj:framework:ajax', hj.comments.init, 500);
<?php if (FALSE) : ?></script><?php endif; ?>