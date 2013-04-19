<?php if (FALSE) : ?><style type="text/css"><?php endif; ?>

	<?php
	$base_url = elgg_get_site_url();
	$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
	?>

	.elgg-menu-comments {
		font-size:11px;
	}

	.elgg-menu-comments li.elgg-menu-item-comment {
		padding:0 5px;
	}

	.elgg-menu-comments li a {
	}

	li.elgg-menu-item-showallcomments {
		font-weight:bold;
	}
	li.elgg-menu-item-time {
		font-size:10px;
		font-style:italic;
		font-color:#666;
		margin:0 10px;
	}

	.hj-comments-bubble,
	.hj-annotations-list .elgg-list > li
	{
		background:#f4f4f4;
		border-bottom:1px solid #ddd;
		padding:2px 5px;
		margin-bottom:2px;
	}
	.hj-comments-bubble {
		font-size:10px;
	}
	.elgg-menu-comments .hj-comments-bubble {
		background:none;
		border:0;
		padding:0;
		margin:0;
	}
	.hj-annotations-list .hj-annotations-list .hj-comments-bubble {
		border-bottom:0;
	}

	.hj-annotations-list .hj-annotations-list .elgg-list > li {
		border-bottom:0;
	}

	.hj-annotations-list .elgg-list {
		border:0px;
		margin:0;
	}

	.hj-annotations-bar form {
		background:0;
		border:#ddd;
		height:auto;
		overflow:hidden;
		padding:0;
	}

	.hj-comments-input {
		font-size:10px;
	}

	.hj-comments-bubble-pointer {
		background:transparent url(<?php echo $graphics_url . 'pointer.png' ?>) no-repeat 8px 0;
		height:8px;
		display:block;
	}

	.elgg-menu-comments li a.hidden {
		display:none;
	}

	.annotations .hj-pagination-next {
		margin:0;
		padding-left:15px;
		font-size:10px;
		text-align:left;
	}

	.hj-annotations-bar textarea {
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
	<?php if (FALSE) : ?></style><?php endif; ?>