<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.alive');
	elgg.provide('framework.alive.stream');
	elgg.provide('framework.alive.actions');

	elgg.provide('framework.alive.comments.order');
	elgg.provide('framework.alive.comments.load_style');

	elgg.provide('framework.alive.river.order');
	elgg.provide('framework.alive.river.load_style');

	framework.alive.comments.order = '<?php echo HYPEALIVE_COMMENTS_ORDER ?>';
	framework.alive.river.order = '<?php echo HYPEALIVE_RIVER_ORDER ?>';

	framework.alive.stream.init = function() {

		// Show comment input on click
		$('.elgg-menu-item-comment')
		.live('click', function(event) {
			event.preventDefault();

			$(this)
			.closest('.hj-stream')
			.children('.hj-stream-comments-block')
			.fadeIn()
			.children('.hj-comments-form')
			.fadeIn()
			.find('[name="description"]')
			.focus()
		});

		$('.elgg-menu-item-reply')
		.live('click', function(event) {
			event.preventDefault();

			$block = $(this)
			.closest('.hj-stream')
			.children('.hj-stream-replies-block')

			$block
			.find('.hj-replies-placeholder')
			.trigger('click');

			$block
			.fadeIn()
			.children('.hj-comments-form')
			.fadeIn()
			.find('[name="description"]')
			.focus()

		});

		$('.hj-replies-placeholder')
		.hide()
		.live('click', function(event) {
			event.preventDefault();

			$elem = $(this);
			$elem.show();
			$loader = $('<span>').addClass('hj-ajax-loader hj-loader-bar');

			elgg.get($elem.attr('href'), {
				data : {
					'view' : 'xhr',
					'endpoint' : 'content'
				},
				dataType : 'json',
				beforeSend : function() {
					$elem.prepend($loader);
				},
				success : function(response) {
					if (response.status >= 0) {
						$elem.replaceWith(response.output.body.content);
					}
				},
				complete : function() {
					$loader.remove()
				}
			});

		});

		$('.hj-comments-edit')
		.live('click', framework.alive.stream.editComment);

		$('.hj-comments-form')
		.removeAttr('onsubmit')
		.live('submit', framework.alive.stream.saveComment);

		$('.hj-stream-pagination a')
		.live('click', framework.alive.stream.loadMore);
	};

	framework.alive.stream.editComment = function(event) {
		event.preventDefault();

		var
		params = $(this).data('options').params,
		item = $('#elgg-object-' + params.entity_guid),
		item_html = item.html(),
		value = item.find('.annotation-value').html(),
		id = params.entity_guid,
		form = item.closest('.annotations').siblings('.hj-comments-form').last().find('form').html();

		item.html($('<form>').html(form));
		var input = item.find('[name="description"]');
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
					description : value
				},
				success : function() {
					item.html(item_html);
					item.find('.annotation-value').html(value);
					elgg.trigger_hook('success', 'hj:framework:ajax');
				}
			})
		});


	}

	framework.alive.stream.saveComment = function(event) {

		event.preventDefault();

		$form = $(this);
		$input = $('[name="description"]', $form);

		elgg.action($form.attr('action'), {
			data : $form.serialize(),
			beforeSend : function() {
				$input.addClass('hj-input-processing');
				elgg.system_message(elgg.echo('hj:framework:ajax:saving'));
			},
			success : function(response) {

				if (response.status >= 0) {
					var list_id = $('[name=list_id]', $form).val();
					$item = $(response.output.view).addClass('hj-comment-new');
					if (framework.alive.comments.order == 'asc') {
						$('#'+list_id).append($item);
					} else {
						$('#'+list_id).prepend($item);
					}
					$input.val('').closest('.hj-comments-form').fadeOut();

					var streamid = response.output.container_guid;

					$('.elgg-menu-item-comments-count > a[data-streamid=' + streamid + ']').text(response.output.stats.comments);
					$('.elgg-menu-item-likes-count > a[data-streamid=' + streamid + ']').text(response.output.stats.likes);
					$('.elgg-menu-item-bookmarks-count > a[data-streamid=' + streamid + ']').text(response.output.stats.bookmarks);
					$('.elgg-menu-item-shares-count > a[data-streamid=' + streamid + ']').text(response.output.stats.shares);
					$('[id=substream-' + streamid + ']').replaceWith(response.output.stats.substream);
				}
				
			},
			complete : function() {
				$input.removeClass('hj-input-processing');
			}
		});
	}

	framework.alive.stream.loadMore = function(event) {

		event.preventDefault();

		$elem = $(this);
		$loader = $('<span>').addClass('hj-ajax-loader hj-loader-bar');
		
		elgg.get($elem.attr('href'), {
			data : {
				'view' : 'xhr',
				'endpoint' : 'list',
				'list_id' : $elem.closest('.hj-framework-list-pagination-wrapper').attr('for')
			},
			dataType : 'json',
			beforeSend : function() {
				$elem.prepend($loader);
			},
			success : function(response) {
				if (response.status >= 0) {
					var updatedLists = response.output.body.lists;
					elgg.trigger_hook('refresh:stream', 'framework:alive', { lists : updatedLists });
				}
			},
			complete : function() {
				$loader.remove();
			}
		});
	}

	framework.alive.stream.refreshLists = function(hook, type, params) {

		var updatedLists = params.lists;

		if (!updatedLists) return false;

		$.each(updatedLists, function(key, updatedList) {

			var $currentList = $('#' + updatedList.list_id),
			$currentListItems = $('.elgg-item', $currentList);

			var $listBody = $currentList;

			if (!updatedList.items) {
				updatedList.items = new Array();
			}

			$('[rel=placeholder]', $currentList).remove();

			$.each(updatedList.items, function(pos, itemView) {
				var itemUid = $(itemView).data('uid');

				var $new = $(itemView).addClass('hj-framework-list-item-new');
				var $existing = $listBody.find('.elgg-item[data-uid=' + itemUid + ']').eq(0);

				var $first = $listBody.find('.elgg-item').first();
				var $last = $first.siblings('.elgg-item').andSelf().last();

				if (!$first.length) {
					$listBody.append($new);
					$first = $last = $existing = $new;
				}

				if (updatedList.list_id == 'activity') {
					var order = framework.alive.river.order;
				} else {
					var order = framework.alive.comments.order;
				}
				if (order == 'asc') {
					if ($existing.length > 0) {
						if ($existing.data('ts') < $new.data('ts')) {
							$existing.replaceWith($new.fadeIn());
						}
					} else {
						if ($first.data('ts') >= $new.data('ts')) {
							$first.before($new.fadeIn());
						} else if ($last.data('ts') <= $new.data('ts')) {
							$last.after($new.fadeIn());
						} else {
							$first.siblings('.elgg-item').andSelf().each(function() {
								if ($new.data('ts') > $(this).data('ts') && $new.data('ts') <= $(this).nextAll('.elgg-item').eq(0).data('ts')) {
									$(this).after($new.fadeIn());
								}
							})
						}
					}
				} else {
					if ($existing.length > 0) {
						if ($existing.data('ts') < $new.data('ts')) {
							$existing.replaceWith($new.fadeIn());
						}
					} else {
						if ($new.data('ts') >= $first.data('ts')) {
							$first.before($new.fadeIn());
						} else if ($new.data('ts') <= $last.data('ts')) {
							$last.after($new.fadeIn());
						} else {
							$first.siblings('.elgg-item').andSelf().each(function() {
								if ($new.data('ts') <= $(this).data('ts') && $new.data('ts') > $(this).nextAll('.elgg-item:first').data('ts')) {
									$(this).after($new.fadeIn());
								}
							})
						}
					}
				}
			})


			$('.hj-framework-list-pagination-wrapper[for=' + updatedList.list_id + ']').replaceWith(updatedList.pagination);

		})
		return true;
	}

	framework.alive.actions.init = function() {

		$('.elgg-menu-item-likes > a')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:alive:like:remove')) {
							$element.text(elgg.echo('hj:alive:like:create'));
							$element.removeClass('elgg-state-active');
						} else {
							$element.text(elgg.echo('hj:alive:like:remove'));
							$element.addClass('elgg-state-active');
						}

						var streamid = response.output.container_guid;

						$('.elgg-menu-item-comments-count > a[data-streamid=' + streamid + ']').text(response.output.stats.comments);
						$('.elgg-menu-item-likes-count > a[data-streamid=' + streamid + ']').text(response.output.stats.likes);
						$('.elgg-menu-item-bookmarks-count > a[data-streamid=' + streamid + ']').text(response.output.stats.bookmarks);
						$('.elgg-menu-item-shares-count > a[data-streamid=' + streamid + ']').text(response.output.stats.shares);
						$('[id=substream-' + streamid + ']').replaceWith(response.output.stats.substream);
					}
				}
			})
		})

		$('.elgg-menu-comments .elgg-menu-item-subscription > a')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:alive:subscription:remove')) {
							$element.text(elgg.echo('hj:alive:subscription:create'));
							$element.removeClass('elgg-state-active');
						} else {
							$element.text(elgg.echo('hj:alive:subscription:remove'));
							$element.addClass('elgg-state-active');
						}
					}
				}
			})
		})

		$('.elgg-menu-comments .elgg-menu-item-bookmark > a')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:alive:bookmark:remove')) {
							$element.text(elgg.echo('hj:alive:bookmark:create'));
							$element.removeClass('elgg-state-active');
						} else {
							$element.text(elgg.echo('hj:alive:bookmark:remove'));
							$element.addClass('elgg-state-active');
						}

						var streamid = response.output.container_guid;

						$('.elgg-menu-item-comments-count > a[data-streamid=' + streamid + ']').text(response.output.stats.comments);
						$('.elgg-menu-item-likes-count > a[data-streamid=' + streamid + ']').text(response.output.stats.likes);
						$('.elgg-menu-item-bookmarks-count > a[data-streamid=' + streamid + ']').text(response.output.stats.bookmarks);
						$('.elgg-menu-item-shares-count > a[data-streamid=' + streamid + ']').text(response.output.stats.shares);
						$('[id=substream-' + streamid + ']').replaceWith(response.output.stats.substream);
					}
				}
			})
		})

		$('.elgg-menu-comments .elgg-menu-item-shares > a')
		.live('click', function(e) {
			e.preventDefault();

			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						$element.attr('href', 'javascript:void(0)');

						var streamid = response.output.container_guid;

						$('.elgg-menu-item-comments-count > a[data-streamid=' + streamid + ']').text(response.output.stats.comments);
						$('.elgg-menu-item-likes-count > a[data-streamid=' + streamid + ']').text(response.output.stats.likes);
						$('.elgg-menu-item-bookmarks-count > a[data-streamid=' + streamid + ']').text(response.output.stats.bookmarks);
						$('.elgg-menu-item-shares-count > a[data-streamid=' + streamid + ']').text(response.output.stats.shares);
						$('[id=substream-' + streamid + ']').replaceWith(response.output.stats.substream);
					}
				}
			})
		})

	}

	elgg.register_hook_handler('init', 'system', framework.alive.stream.init);
	elgg.register_hook_handler('init', 'system', framework.alive.actions.init);

	elgg.register_hook_handler('refresh:stream', 'framework:alive', framework.alive.stream.refreshLists);
	
<?php if (FALSE) : ?></script><?php endif; ?>