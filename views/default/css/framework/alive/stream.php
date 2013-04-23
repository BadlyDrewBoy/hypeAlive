<?php if (FALSE) : ?><style type="text/css"><?php endif; ?>

	<?php
	$base_url = elgg_get_site_url();
	$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
	?>

	.elgg-menu-comments, .elgg-menu-replies {
		font-size:11px;
	}

	.elgg-menu-comments > li > a, .elgg-menu-replies > li > a {
		margin-right: 10px
	}

	.elgg-menu-replies .elgg-menu-item-time {
		font-size:10px;
		font-style:italic;
		font-color:#666;
		margin:0 10px;
	}

	form.hj-comments-form {
		padding: 3px;
		border: 1px solid #f4f4f4;
		background: #f4f4f4;
		border-bottom: 1px solid #e8e8e8;
	}
	form.hj-comments-form fieldset {
		padding: 0px 3px;
		border: #f4f4f4;
		background: #fff;
		margin: 0;
	}
	form.hj-comments-form fieldset input {
		border: none;
		padding: 0px 5px;
		line-height: 25px;
		margin: 0;
	}
	.hj-comments-list {
		border: none;
		margin: 0;
		font-size:11px;
	}
	.hj-comments-list > li.elgg-item {
		padding: 3px 0 5px 6px;
		background: #f4f4f4;
		border-bottom:1px solid #e8e8e8;
	}
	.hj-comments-list > li.elgg-item .hj-comments-list > li.elgg-item {
		border: none;
		padding: 5px 0 5px 10px;
	}
	form.hj-comments-form fieldset .elgg-image {
		margin: 0;
	}
	form.hj-comments-form fieldset input[type="text"] {
		background: #fff;
	}
	form.hj-comments-form fieldset input[type="submit"] {
		background: #f4f4f4;
		color: #ccc;
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
	.hj-stream-comments-block .hj-stream-comments-block form.hj-comments-form {
		border: 1px solid #e8e8e8;
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
		font-size: 11px;
	}
	.hj-stream-pagination a[rel="previous"] {
		border-bottom: 1px solid #e8e8e8;
		padding: 5px 10px;
	}
	.hj-stream-pagination a[rel="next"] {
		border-bottom: 1px solid #e8e8e8;
		padding: 5px 10px;
	}
	.hj-stream-list li[rel="placeholder"] {
		display: none;
	}

	.hj-replies-placeholder {
		display: inline-block;
		padding: 5px 15px;
		margin:5px 10px 0;
		background: #fafafa;
		text-shadow: 1px 1px  #fff;
		vertical-align: middle;
	}
	.hj-replies-list {
		background: #fafafa;
		border: none;
		margin: 0;
	}
	.hj-replies-list > li {
		padding: 5px;
	}
	.hj-replies-list > li:last-child {
		border: none;
	}
	form.hj-replies-form {
		margin: 0;
		background: #fafafa;
	}
	.hj-stream .hj-loader-bar {
		display:inline-block;
		margin-right:10px;
		vertical-align:middle;
	}
	<?php if (FALSE) : ?></style><?php endif; ?>