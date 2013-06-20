<?php if (FALSE) : ?><style type="text/css"><?php endif; ?>

	<?php
	$base_url = elgg_get_site_url();
	$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
	?>

	.elgg-menu-comments > li {
		margin: 3px 10px;
		display: inline-block;
		vertical-align: middle;
	}
	.elgg-menu-comments > li > ul > li {
		background: #f;
		padding: 0px 6px;
		border: 1px solid transparent;
		font-size: 0.85em;
		font-weight: bold;
		margin: 0px 5px;
		position: relative;
		vertical-align: middle;
		background: #f4f4f4;
		border: 1px solid #e8e8e8;
	}
	.elgg-menu-comments {
		margin-bottom: 5px;
	}
	.elgg-menu-comments > li > a {
		margin: 0;
	}
	.elgg-menu-comments > li ul a {
		color: inherit;
		font-weight: bold;
	}
	.elgg-menu-comments > li > ul {
		display: inline-block;
	}

	.elgg-menu-replies {
		font-size:0.9em;
	}
	.elgg-menu-replies > li > ul > li {
		border: 1px solid #e8e8e8;
		font-size: 0.8em;
	}
	.elgg-menu-replies .elgg-menu-item-time {
		font-size:0.85em;
		font-style:italic;
		font-color:#666;
		margin:0 10px;
	}


	.hj-comments-list {
		border: none;
		margin: 0;
		font-size:0.9em;
	}
	.hj-comments-list > li.elgg-item {
		padding: 3px 0 5px 8px;
		border-bottom:1px solid #f4f4f4;
		border-left:1px solid #f4f4f4;
	}
	.hj-comments-list > li.elgg-item .hj-comments-list > li.elgg-item {
		border: none;
		padding: 5px 0 5px 10px;
	}

	.hj-comments-stream {
		margin-top:10px;
		padding-top:10px;
		border-top:1px solid #e8e8e8;
	}
	.hj-comments-stream.hj-comments-river-stream {
		margin-top:0;
		padding-top:0;
		border:0;
	}
	.hj-stream-header {
		margin: 5px 0 0;
	}
	.hj-stream-comments-block {
		margin: 0;
	}
	.hj-stream-comments-block .hj-stream-comments-block {
		padding: 2px 0 2px 2px;
		margin: 3px;
	}

	.hj-stream textarea {
		height:auto;
	}

	.elgg-icon.elgg-icon-plusone,
	.elgg-icon.elgg-icon-plusone:hover {
		background:transparent url(<?php echo $graphics_url . 'oneplus.png' ?>) no-repeat;
	}

	.elgg-icon.elgg-icon-minusone,
	.elgg-icon.elgg-icon-minusone:hover {
		background:transparent url(<?php echo $graphics_url . 'oneminus.png' ?>) no-repeat;
	}

	.hj-likes-popup {
		width:250px;
		margin-left: 125px;
		margin-top: 3px;
	}

	.likes-inline {
		margin-right:5px;
	}
	.likes-inline .hj-likes-summary {
		display:inline-block;
	}

	.likes-inline .hj-likes-summary:after {

	}

	.likes-inline .hj-likes-summary.hidden:after {
		content:"";
	}

	.hj_plusone_popup_link_text {
		padding-right:5px;
	}
	.hj-comments-list li.hj-framework-list-pagination-wrapper {
		padding: 0;
		border:none;
	}
	.hj-stream-pagination {
		margin: 0;
	}
	.hj-stream-pagination a, .hj-stream-pagination a:hover {
		margin: 0;
		padding: 2px;
		display: block;
		background: #f4f4f4;
		border:none;
		color: #999;
		font-size: 0.9em;
	}
	.hj-stream-pagination a[rel="previous"] {
		border-bottom: 1px solid #e8e8e8;
		padding: 5px 10px;
	}
	.hj-stream-pagination a[rel="next"] {
		border-bottom: 1px solid #e8e8e8;
		padding: 0 10px;
		line-height:24px;
	}
	.hj-stream-list li[rel="placeholder"] {
		display: none;
	}

	.hj-replies-placeholder {
		display: none;
		padding: 5px 15px;
		margin:20px 10px 0;
		background: #fafafa;
		text-shadow: 1px 1px  #fff;
		vertical-align: middle;
	}
	.hj-replies-list {
		border: none;
		margin: 0;
	}
	.hj-replies-list > li {
		padding: 5px;
		border:none;
		border-left:1px solid #f4f4f4;
		border-bottom:1px solid #f4f4f4;
	}
	.hj-replies-list > li:last-child {
		border: none;
	}
	.hj-stream .hj-loader-bar {
		display:inline-block;
		margin-right:10px;
		vertical-align:middle;
	}
	.elgg-river li.hj-framework-list-pagination-wrapper {
		padding: 0;
		border: 0;
	}
	.elgg-river-substream {
		border: none;
		margin: 0;
	}
	.elgg-river-substream > li {
		border: none;
		margin: 0 10px 0;
		padding: 2px 0 2px 10px;
		line-height: 25px;
		border-bottom: 1px solid #f4f4f4;
		border-left: 1px solid #f4f4f4;
		font-size: 0.85em;
	}

	.elgg-river-substream > li.hj-framework-list-pagination-wrapper {
		padding:0;
		border-bottom:0;
	}
	form.hj-comments-form {
		border: 1px solid #f4f4f4;
		height: auto;
		padding: 0;
		background:none;
		-webkit-border-radius:0;
		-moz-border-radius:0;
		border-radius:0;
	}
	form.hj-comments-form fieldset {
		padding: 0;
		margin: 0;
	}
	form.hj-comments-form .elgg-image-block {
		padding: 0;
		margin: 0;
	}
	form.hj-comments-form .elgg-image {
		background: #f4f4f4;
		height: 25px;
		padding: 5px;
		margin:0;
	}
	form.hj-comments-form input[type="text"],
	form.hj-comments-form input[type="text"]:focus {
		height: 25px;
		line-height: 25px;
		width: 100%;
		border: 0;
		background:none;
		padding:0;
	}

	form.hj-comments-form textarea,
	form.hj-comments-form textarea:focus {
		line-height: 25px;
		width: 100%;
		border: 0;
		background:none;
	}
	form.hj-comments-form .elgg-body {
		padding: 5px;
		margin: 0;
		border-left:1px solid #f4f4f4;
		border-right:1px inset #e8e8e8;
	}
	form.hj-comments-form .elgg-image-alt {
		height: 31px;
		padding: 0;
		margin: 0;
	}
	form.hj-comments-form fieldset input[type="submit"] {
		border: 0;
		color: transparent;
		text-shadow: none;
		box-shadow: none;
		right: 0;
		font-weight: normal;
		margin: 0;
		height: 35px;
		padding: 5px;
		box-sizing: content-box;
		border-radius: 0;
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/comment.png) no-repeat 50% 50%;
		background-size: 15px;
		display:inline-block;
		box-sizing:border-box;
		position:relative;
	}
	form.hj-comments-form fieldset input[type="submit"].hidden {
		display:none;
	}
	form.hj-comments-form fieldset input[type="submit"].loading {
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/ajax-loader.gif) no-repeat 50% 50%;
	}
	.hj-comments-attach {
		width: 25px;
		height: 19px;
		padding: 8px;
		display: block;
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypealive/graphics/clip.png) no-repeat 50% 50%;
		background-size: 18px;
		border-right: 1px inset #e8e8e8;
		display:inline-block;
		vertical-align:top;
	}

	.notifications-options tr {
		border-bottom: 1px solid #e8e8e8;
		background:none;
	}
	.notifications-options {
		border: none;
	}
	.notifications-options td {
		border: none;
		line-height:25px;
		padding:8px;
		width:auto;
	}
	.notifications-options tr:nth-child(even) td {
		background:#f4f4f4;
	}
	.elgg-table.notifications-options td.table-cell-icon {
		width:25px;
	}
	<?php if (FALSE) : ?></style><?php endif; ?>