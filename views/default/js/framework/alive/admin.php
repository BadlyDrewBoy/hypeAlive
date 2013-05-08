<?php if (FALSE) : ?><script type="text/javascript"><?php endif; ?>

	elgg.provide('framework.alive');
	elgg.provide('framework.alive.admin');

	framework.alive.admin.init = function() {

		$('#hj-alive-admin-upgrade')
		.click(function(e) {
			var confirmText = $(this).attr('rel');
			if (!confirm(confirmText)) {
				e.preventDefault();
				return false;
			}

			$('#upgrade-progress').progressbar({
				value : 0
			});

			$(this).hide();
			
			var params = {
				count : $(this).data('count'),
				offset : 0,
				progress : 0,
				limit : 10
			}

			elgg.trigger_hook('upgrade', 'framework:alive', params);
		})

		$('#hj-alive-admin-import-comments')
		.click(function(e) {

			var confirmText = $(this).attr('rel');
			if (!confirm(confirmText)) {
				e.preventDefault();
				return false;
			}
	
			$('#import-comments-progress').progressbar({
				value : 0
			});

			$(this).hide();

			var params = {
				count : $(this).data('count'),
				offset : 0,
				progress : 0,
				limit : 10
			}

			elgg.trigger_hook('import:comments', 'framework:alive', params);
		})

		$('#hj-alive-admin-import-posts')
		.click(function(e) {

			var confirmText = $(this).attr('rel');
			if (!confirm(confirmText)) {
				e.preventDefault();
				return false;
			}

			$('#import-posts-progress').progressbar({
				value : 0
			});

			$(this).hide();

			var params = {
				count : $(this).data('count'),
				offset : 0,
				progress : 0,
				limit : 10
			}

			elgg.trigger_hook('import:posts', 'framework:alive', params);
		})

	}

	framework.alive.admin.upgrade = function(hook, type, params) {

		elgg.action('action/alive/admin/upgrade', {
			data : params,
			success : function(data) {

				if (!data.output.complete) {
					params.offset = data.output.offset;
					params.progress = params.progress + params.limit;
					
					$('#upgrade-progress').progressbar({
						value : params.progress * 100 / params.count
					})
					
					elgg.trigger_hook('upgrade', 'framework:alive', params);
				} else {
					elgg.system_message(elgg.echo('hj:alive:admin:upgrade_complete'));
					$('#upgrade-progress').progressbar({
						value : 100
					})
					location.reload();
				}
			}
		})

	}

	framework.alive.admin.importComments = function(hook, type, params) {
		params.annotation_name = 'generic_comment';
		elgg.action('action/alive/admin/import', {
			data : params,
			success : function(data) {

				if (!data.output.complete) {
					params.offset = data.output.offset;
					params.progress = params.progress + params.limit;

					$('#import-comments-progress').progressbar({
						value : params.progress * 100 / params.count
					})

					elgg.trigger_hook('import:comments', 'framework:alive', params);
				} else {
					elgg.system_message(elgg.echo('hj:alive:admin:import_complete'));
					$('#import-comments-progress').progressbar({
						value : 100
					})
					location.reload();
				}
			}
		})
	}

	framework.alive.admin.importPosts = function(hook, type, params) {
		params.annotation_name = 'group_topic_post';
		elgg.action('action/alive/admin/import', {
			data : params,
			success : function(data) {

				if (!data.output.complete) {
					params.offset = data.output.offset;
					params.progress = params.progress + params.limit;

					$('#import-posts-progress').progressbar({
						value : params.progress * 100 / params.count
					})

					elgg.trigger_hook('import:posts', 'framework:alive', params);
				} else {
					elgg.system_message(elgg.echo('hj:alive:admin:import_complete'));
					$('#import-posts-progress').progressbar({
						value : 100
					})
					location.reload();
				}
			}
		})
	}

	elgg.register_hook_handler('init', 'system', framework.alive.admin.init);
	elgg.register_hook_handler('upgrade', 'framework:alive', framework.alive.admin.upgrade);
	elgg.register_hook_handler('import:comments', 'framework:alive', framework.alive.admin.importComments);
	elgg.register_hook_handler('import:posts', 'framework:alive', framework.alive.admin.importPosts);

<?php if (FALSE) : ?></script><?php endif; ?>