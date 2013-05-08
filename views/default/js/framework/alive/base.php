<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.alive');
	elgg.provide('framework.alive.actions');

	framework.alive.actions.init = function() {

		$('.elgg-menu-comments .elgg-menu-item-follow')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:alive:bookmark:remove')) {
							$element.text(elgg.echo('hj:alive:bookmark:create'));
						} else {
							$element.text(elgg.echo('hj:alive:bookmark:remove'));
						}
					}
				}
			})
		})

		$('.elgg-menu-comments .elgg-menu-item-bookmark')
		.live('click', function(e) {
			e.preventDefault();
			$element = $(this);
			elgg.action($(this).attr('href'), {
				success : function(response) {
					if (response.status >= 0) {
						if ($element.text() == elgg.echo('hj:alive:bookmark:remove')) {
							$element.text(elgg.echo('hj:alive:bookmark:create'));
						} else {
							$element.text(elgg.echo('hj:alive:bookmark:remove'));
						}
					}
				}
			})
		})

	}

	elgg.register_hook_handler('init', 'system', framework.alive.actions.init);

<?php if (FALSE) : ?></script><?php endif; ?>