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

		$('.elgg-menu-comments .elgg-menu-item-edit')
		.live('click', framework.alive.stream.editComment);

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

		$('.hj-comments-form')
		.removeAttr('onsubmit')
		.live('submit', framework.alive.stream.saveComment);

		$('.hj-stream-pagination a')
		.live('click', framework.alive.stream.loadMore);

		$('.hj-comments-form input[name="description"]')
		.live('keyup keydown', function(e) {
			if ($(this).val() == '') {
				$(this).closest('form').find('input[type="submit"]').addClass('hidden');
			} else {
				$(this).closest('form').find('input[type="submit"]').removeClass('hidden');
			}
		})

		$('.subscriptions-options-action')
		.live('click', function(e) {
			e.preventDefault();
			$elem = $(this);
			elgg.action($elem.attr('href'), {
				beforeSend : function() {
					$elem.addClass('loading');
				},
				success : function(data) {
					$elem.remove();
				},
				error : function() {
					$elem.removeClass('loading');
				}
			})
		})
	};

	framework.alive.stream.editComment = function(event) {
		event.preventDefault();

		$comment = $(this).closest('.elgg-object-hjcomment');

		elgg.ajax('ajax/view/framework/alive/comments/form', {
			data : {
				guid : $comment.data('uid'),
				list_id : $comment.closest('.elgg-list').attr('id')
			}
		});
		
	}

	framework.alive.stream.saveComment = function(event) {

		var $form = $(this);

		var data = {};
		data['X-Requested-With'] = 'XMLHttpRequest';
		data['X-PlainText-Response'] = true;

		var params = ({
			dataType : 'json',
			data : data,
			iframe : false,
			beforeSend : function() {
				$('input[type="submit"]', $form).addClass('loading');
			},
			complete : function() {
				$('input[type="submit"]', $form).removeClass('loading');
			},
			success : function(response, status, xhr) {

				if (response.status >= 0) {
					var list_id = $('[name=list_id]', $form).val();
					$item = $(response.output.view).addClass('hj-comment-new');
					$('[id=' + list_id + ']').each(function() {
						if ($("[data-uid=" + response.output.guid + "]", $(this)).length) {
							$("[data-uid=" + response.output.guid + "]", $(this)).replaceWith($item);
						} else {
							if (framework.alive.comments.order == 'asc') {
								$(this).append($item);
							} else {
								$(this).prepend($item);
							}
						}
					});
					//$form.fadeOut();

					var streamid = response.output.container_guid;

					$('.elgg-menu-item-comments-count > a[data-streamid=' + streamid + ']').text(response.output.stats.comments);
					$('.elgg-menu-item-likes-count > a[data-streamid=' + streamid + ']').text(response.output.stats.likes);
					$('.elgg-menu-item-bookmarks-count > a[data-streamid=' + streamid + ']').text(response.output.stats.bookmarks);
					$('.elgg-menu-item-shares-count > a[data-streamid=' + streamid + ']').text(response.output.stats.shares);
					$('[id=substream-' + streamid + ']').replaceWith(response.output.stats.substream);
				}
				
				if (response.system_messages.success) {
					elgg.system_message(response.system_messages.success);
				}
				if (response.system_messages.error) {
					elgg.system_message(response.system_messages.success);
				}

				$form.resetForm();
				$form.find('.hj-alive-attachments-list').html('');
				$form.find('input[type="submit"]').addClass('hidden')
			}

		});

		$form.ajaxSubmit(params);

		return false;

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

			var $currentLists = $('[id=' + updatedList.list_id + ']');

			$currentLists.each(function() {

				var $currentList = $(this);

				var $currentListItems = $('.elgg-item', $currentList);

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

				$('.hj-framework-list-pagination-wrapper[for=' + updatedList.list_id + ']', $currentList).replaceWith(updatedList.pagination);
			})

		})
		return true;
	}

	framework.alive.stream.processUpdatedList = function(hook, type, updatedList) {

		var	listType = updatedList.list_type;

		var $currentLists = $('[id=' + updatedList.list_id + ']');

		$currentLists.each(function() {

			var $currentList = $(this);

			var $currentListItems = $('.elgg-item', $currentList);

			switch (listType) {

				case 'stream' :
					var $listBody = $currentList;
					break;

				case 'substream' :
					var $listBody = $currentList;
					break;

				default :
					return false;
					break;
			}

			var updatedListItemUids = new Array(), currentListItemUids = new Array(), updatedListItemViews = new Array();

			$currentListItems.each(function() {
				currentListItemUids.push($(this).data('uid'));
			});

			if (!updatedList.items) {
				updatedList.items = new Array();
			}

			var $newList = $listBody.clone(true).html('');
			$.each(updatedList.items, function(pos, itemView) {
				var itemUid = $(itemView).data('uid');
				updatedListItemUids.push(itemUid);
				updatedListItemViews[itemUid] = itemView;
				var $new = $(itemView).addClass('hj-framework-list-item-new');
				var $existing = $currentList.find('.elgg-item[data-uid=' + itemUid + ']:first');
				if (($existing.length == 0) || ($existing.length && $new.data('ts') > $existing.data('ts'))) {
					var $append = $new;
				} else if ($existing.length && $new.data('ts') <= $existing.data('ts')) {
					var $append = $existing;
				}
				$newList.append($append);
			})
			$listBody.replaceWith($newList);

			$('.hj-framework-list-pagination-wrapper[for=' + updatedList.list_id + ']', $currentList).replaceWith(updatedList.pagination);

		})
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
						$element.remove();
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
						$element.replaceWith(elgg.echo('hj:alive:shares'));

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

	elgg.register_hook_handler('refresh:lists:stream', 'framework', framework.alive.stream.processUpdatedList);
	elgg.register_hook_handler('refresh:lists:substream', 'framework', framework.alive.stream.processUpdatedList);
	
<?php if (FALSE) : ?></script><?php endif; ?>