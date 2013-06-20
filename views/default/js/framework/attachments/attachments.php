<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.attachments');

	framework.attachments.init = function() {

		$('.hj-alive-attach')
		.live('click', framework.attachments.getForm);

		$('.hj-alive-attachments-upload input[type="file"]')
		.live('change', framework.attachments.upload);

		$('.hj-alive-attachment-checkbox')
		.live('click', function(e) {
			$list = $('.elgg-form.alive-comments-active .hj-alive-attachments-list');
			var guid = $(this).data('guid');
			var img = $(this).data('img');
			var title = $(this).data('title');
			if ($(this).is(':checked') && !$("li[data-guid=" + guid + "]", $list).length) {
				$list.append(
				$('<li>')
				.attr('data-guid', guid)
				.append('<span>')
				.append(
				$('<img>')
				.attr('src', img)
				.attr('title', title)
			)
				.append(
				$('<input>')
				.attr('type', 'hidden')
				.attr('name' , 'attachments[]')
				.attr('value', guid)
			)
			);
			} else if ($("li[data-guid=" + guid + "]", $list).length) {
				$("li[data-guid=" + guid + "]", $list).remove();
			}
		})

		$('.hj-alive-attachments-list > li')
		.live('click', function(e) {
			var guid = $(this).data('guid');
			$(this).remove();
			$('.hj-alive-attachment-checkbox[data-guid=' + guid + ']:checked').attr('checked', false);
		})

		$('.hj-comments-attachments-detach')
		.live('click', function(e) {
			e.preventDefault();
			$elem = $(this);
			elgg.action($elem.attr('href'), {
				beforeSend : function() {
					$elem.addClass('loading');
				},
				complete : function() {
					$elem.removeClass('loading');
				},
				success : function(response) {
					if (response.status >= 0) {
						$elem.closest('li').remove();
					}
				}
			})
		})
		
	}

	framework.attachments.getForm = function(event) {

		event.preventDefault();
		
		$element = $(this);
		$('.elgg-form').removeClass('alive-comments-active');
		$(this).closest('.elgg-form').addClass('alive-comments-active');

		var data = $(this).data();
		data['X-Requested-With'] = 'XMLHttpRequest';
		data['view'] = 'xhr';
		data['endpoint'] = 'content';
		
		elgg.post($element.attr('href'), {
			data : data,
			dataType: 'json',
			beforeSend : function() {
				$dialog = $('<div id="dialog">')
				.appendTo('body')
				.html(framework.loaders.circle)
				.appendTo('body')
				.dialog({
					dialogClass: 'hj-framework-dialog',
					title : elgg.echo('hj:framework:ajax:loading'),
					buttons : false,
					//modal : true,
					//autoResize : true,
					width : 650,
					height : 500,
					close: function(event, ui)
					{
						$(this).dialog('destroy').remove();
					}
				});
			},
			complete : function() {

			},
			success : function(response) {

				$content = $(response.output.body.content);
				title = elgg.echo('hj:alive:attach');

				var buttons = new Array();

				$dialog
				.html($content)
				.dialog({
					title: title
				});

				$footer = $dialog.find('.elgg-foot');

				$('input[type="submit"], input[type="button"], .elgg-button', $footer).each(function() {
					var $button = $(this).hide();
					if ($button.hasClass('elgg-button-cancel-trigger')) {
						buttons.push({
							text : ($button.attr('value')) ? $button.attr('value') : elgg.echo('cancel'),
							click : function() {
								$dialog.dialog('close');
							}
						})
					} else {
						buttons.push({
							text : ($button.attr('value')) ? $button.attr('value') : $button.text(),
							click : function(e) {
								$button.trigger('click');
								e.preventDefault();
							}
						})
					}
				})

				$dialog
				.dialog({
					buttons : buttons
				})

				var params = new Object();
				params.event = 'getForm';
				params.response = response;
				params.data = data;
				params.pushState = false;

				elgg.trigger_hook('ajax:success', 'framework', params, true);

				if (!$element.data('callback')) {
					return true;
				}

				// atttaching the triggering element to form so that we can use it's parameters
				$('form', $dialog).data('trigger', $element).submit(framework.ajax.submit);

			}
		})
	}

	framework.attachments.upload = function(event) {

		var $form = $(this).closest('form');

		var data = {};
		data['X-Requested-With'] = 'XMLHttpRequest';
		data['X-PlainText-Response'] = true;

		var params = ({
			dataType : 'json',
			data : data,
			iframe : true,
			beforeSend : function() {
				$('span i', $form).addClass('loading');
			},
			complete : function() {
				$('span i', $form).removeClass('loading');
			},
			success : function(response, status, xhr) {

				if (response.status >= 0) {
					elgg.trigger_hook('refresh:lists', 'framework', { element : $form, href : elgg.get_site_url() + 'stream/attach', pushState : false });
				}

				if (response.system_messages.success) {
					elgg.system_message(response.system_messages.success);
				}
				if (response.system_messages.error) {
					elgg.system_message(response.system_messages.success);
				}

				$form.resetForm();
			}

		});

		$form.ajaxSubmit(params);

		return false;

	}

	elgg.register_hook_handler('init', 'system', framework.attachments.init);

<?php if (FALSE) : ?></script><?php endif; ?>