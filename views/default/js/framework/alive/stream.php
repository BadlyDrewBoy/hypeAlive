<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.alive');
	elgg.provide('framework.alive.stream');
	elgg.provide('framework.alive.likes');

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
		.live('click', function(event) {
			event.preventDefault();

			$elem = $(this);
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

	framework.alive.likes.init = function() {
		if(window.ajaxlikessready === undefined) {
			window.ajaxlikesready = true;
		}
		framework.alive.likes.triggerRefresh();

		$('.elgg-menu-item-like')
		.unbind('click')
		.bind('click', framework.alive.likes.saveLike);

		$('.elgg-menu-item-unlike')
		.unbind('click')
		.bind('click', framework.alive.likes.saveLike);

	};

	framework.alive.likes.triggerRefresh = function() {
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
					framework.alive.likes.refresh(ref);
				}
				window.likestimer = false;
			}, time);
		}
	}

	framework.alive.likes.refresh = function(data) {
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
							var container = $('#hj-stream-'+ val.selector_id);
							var likesList = container.find('.likes').first();
							if (val.likes && val.likes.length > 0) {
								likesList.html(val.likes);
								if (likesList.find('.hj-likes-summary').text().length > 0) {
									likesList.closest('.hj-stream-likes-block').show();
								} else {
									likesList.closest('.hj-stream-likes-block').hide();
								}
							} else {
								likesList.closest('.hj-stream-likes-block').hide();
							}
							var menu = container.find('.hj-stream-menu').first();
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

	framework.alive.likes.saveLike = function(event) {
		event.preventDefault();

		var action_type = $(this).find('a:first').attr('rel');
		var likesList = $(this)
		.closest('.hj-stream')
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
				framework.alive.likes.refresh(ref);
			}
		});
	}

	elgg.register_hook_handler('init', 'system', framework.alive.stream.init);
	elgg.register_hook_handler('init', 'system', framework.alive.likes.init);

	elgg.register_hook_handler('refresh:stream', 'framework:alive', framework.alive.stream.refreshLists);
	
<?php if (FALSE) : ?></script><?php endif; ?>