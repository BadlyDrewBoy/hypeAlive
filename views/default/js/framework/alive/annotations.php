<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.alive');
	elgg.provide('framework.alive.annotations');
	elgg.provide('framework.alive.annotations.comments');
	elgg.provide('framework.alive.annotations.likes');
	elgg.provide('framework.alive.annotations.dislikes');

	framework.alive.annotations.comments.init = function() {

		if (window.ajaxcommentsready === undefined) {
			window.ajaxcommentsready = true;
		}

		var bar_loader = '<div class="hj-ajax-loader hj-loader-bar"></div>';

		// Show comment input on click
		$('.elgg-menu-item-comment')
		.live('click', function(event) {
			event.preventDefault();

			var $comments_block = $(this).closest('.hj-annotations-bar').find('.hj-annotations-comments-block').eq(0);
		
			$comments_block
			.fadeIn()
			.find('.hj-comments-input').last()
			.toggle('fast', function() {
				$(this)
				.find('[name="annotation_value"]')
				.focus();
			})
		});

		$('.hj-ajaxed-comment-edit')
		.live('click', framework.alive.annotations.comments.editComment);

		$('.hj-ajaxed-comment-save')
		.removeAttr('onsubmit')
		.live('submit', framework.alive.annotations.comments.saveComment);

		$('.hj-alive-comments-pagination a')
		.live('click', framework.alive.annotations.comments.loadMore);
	};

	framework.alive.annotations.comments.editComment = function(event) {
		event.preventDefault();

		var
		params = $(this).data('options').params,
		item = $('#elgg-object-' + params.entity_guid),
		item_html = item.html(),
		value = item.find('.annotation-value').html(),
		id = params.entity_guid,
		form = item.closest('.annotations').siblings('.hj-comments-input').last().find('form').html();

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

	framework.alive.annotations.comments.saveComment = function(event) {

		event.preventDefault();

		$form = $(this);
		$input = $('[name="annotation_value"]', $form);
		
		elgg.action($form.attr('action'), {
			data : $form.serialize(),
			beforeSend : function() {
				$input.addClass('hj-input-processing');
				elgg.system_message(elgg.echo('hj:framework:ajax:saving'));
			},
			success : function(response) {

				if (response.status >= 0) {
					var updatedLists = response.output.body.lists;

					$.each(updatedLists, function(key, updatedList) {

						var $currentList = $('#' + updatedList.list_id),
						$currentListItems = $('.elgg-item', $currentList);

						var $listBody = $currentList;

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

						$('.hj-framework-list-pagination-wrapper[for=' + updatedList.list_id + ']').replaceWith(updatedList.pagination);

					})

				}
				
				$input.val('');

				
			},
			complete : function() {
				$input.removeClass('hj-input-processing');
			}
		});
	}

	framework.alive.annotations.comments.loadMore = function(event) {

		event.preventDefault();

		$elem = $(this);

		elgg.post($elem.attr('href'), {
			data : {
				'view' : 'xhr',
				'endpoint' : 'list',
				'list_id' : $elem.closest('.hj-framework-list-pagination-wrapper').attr('for')
			},
			dataType : 'json',
			beforeSend : function() {
				$elem.addClass('hj-alive-pagination-loading');
				elgg.system_message(elgg.echo('hj:framework:ajax:loading'));
			},
			success : function(response) {
				if (response.status >= 0) {
					var updatedLists = response.output.body.lists;

					$.each(updatedLists, function(key, updatedList) {

						var $currentList = $('#' + updatedList.list_id),
						$currentListItems = $('.elgg-item', $currentList);

						var $listBody = $currentList;

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
							} else {
								var $append = $new;
							}
							$newList.append($append);
						})
						$listBody.replaceWith($newList);

						$('.hj-framework-list-pagination-wrapper[for=' + updatedList.list_id + ']').replaceWith(updatedList.pagination);

					})

				}
			},
		});
	}

	framework.alive.annotations.likes.init = function() {
		if(window.ajaxlikessready === undefined) {
			window.ajaxlikesready = true;
		}
		framework.alive.annotations.likes.triggerRefresh();

		$('.elgg-menu-item-like')
		.unbind('click')
		.bind('click', framework.alive.annotations.likes.saveLike);

		$('.elgg-menu-item-unlike')
		.unbind('click')
		.bind('click', framework.alive.annotations.likes.saveLike);

	};

	framework.alive.annotations.likes.triggerRefresh = function() {
		var time = 25000;
		if (!window.likestimer) {
			window.likestimer = true;
			var refresh_likes = window.setTimeout(function(){
				var ref = new Array();
				// Let's get the timestamp of the first item in the list (newest comment)
				$('.hj-likes-summary')
				.each(function() {
					var data = $(this).data('options');
					ref.push(data);
				});
				if (window.ajaxlikesready) {
					//elgg.system_message(elgg.echo('hj:comments:refreshing'));
					framework.alive.annotations.likes.refresh(ref);
				}
				window.likestimer = false;
			}, time);
		}
	}

	framework.alive.annotations.likes.refresh = function(data) {
		if (!data.length) return true;
		if (window.ajaxlikesready) {
			window.ajaxlikesready = false;
			elgg.action('action/like/get', {
				data : {
					data : data
				},
				success : function(data) {
					if (data && data.output != 'null') {
						$.each(data.output, function(key, val) {
							var container = $('#hj-annotations-'+ val.selector_id);
							var likesList = container.find('.likes').first();
							if (val.likes && val.likes.length > 0) {
								likesList.html(val.likes);
								if (likesList.find('.hj-likes-summary').text().length > 0) {
									likesList.closest('.hj-annotations-likes-block').show();
								} else {
									likesList.closest('.hj-annotations-likes-block').hide();
								}
							} else {
								likesList.closest('.hj-annotations-likes-block').hide();
							}
							var menu = container.find('.hj-annotations-menu').first();
							if (val.self) {
								$('.elgg-menu-item-like:first > a', menu)
								.addClass('hidden');
								$('.elgg-menu-item-unlike:first > a', menu)
								.removeClass('hidden');
							} else {
								$('.elgg-menu-item-like:first > a', menu)
								.removeClass('hidden');
								$('.elgg-menu-item-unlike:first > a', menu)
								.addClass('hidden');
							}
						});
					}
					window.ajaxlikesready = true;
					elgg.trigger_hook('success', 'hj:framework:ajax');
				}
			});
		}
	}

	framework.alive.annotations.likes.saveLike = function(event) {
		event.preventDefault();

		var action_type = $(this).find('a:first').attr('rel');
		var likesList = $(this)
		.closest('.hj-annotations-bar')
		.find('.hj-likes-summary')
		.first();

		var data = new Object();
		var ref = new Array();

		data = likesList.data('options');

		data.action_type = action_type;
		ref.push(data);

		elgg.system_message(elgg.echo('hj:framework:processing'));
		elgg.action('action/like/save', {
			data : data,
			success : function(output) {
				framework.alive.annotations.likes.refresh(ref);
			}
		});
	}

	elgg.register_hook_handler('init', 'system', framework.alive.annotations.comments.init);
	elgg.register_hook_handler('init', 'system', framework.alive.annotations.likes.init);

<?php if (FALSE) : ?></script><?php endif; ?>