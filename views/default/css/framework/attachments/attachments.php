<?php if (FALSE) : ?><style type="text/css"><?php endif; ?>

	<?php
	$base_url = elgg_get_site_url();
	$graphics_url = $base_url . 'mod/hypeAlive/graphics/';
	?>

	.hj-alive-attach {
		width: 25px;
		height: 19px;
		padding: 8px;
		display: block;
		background: #f4f4f4 url(<?php echo $graphics_url ?>clip.png) no-repeat 50% 50%;
		background-size: 18px;
		border-right: 1px inset #e8e8e8;
		display:inline-block;
		vertical-align:top;
	}

	.hj-alive-attachments-list {
		display: inline-block;
		vertical-align: top;
	}

	.hj-alive-attach-upload {
		position: relative;
		display: block;
		vertical-align: top;
		width: 25%;
		margin-right:2%;
		cursor:pointer;
	}
	.hj-alive-attach-upload span {
		position: absolute;
		top: 0;
		left: 0;
		z-index: 1;
		line-height: 25px;
		border: 1px solid #f4f4f4;
		padding-left: 10px;
		width: 100%;
		text-align: right;
		height: 35px;
		background: #f4f4f4;
	}
	.hj-alive-attach-upload input[type="file"] {
		opacity: 0;
		position: absolute;
		height: 41px;
		z-index: 2;
		left: 0;
		top: 0;
	}
	.hj-alive-attach-upload i {
		height: 18px;
		width: 25px;
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/upload.png) no-repeat 50% 50%;
		background-size: 18px;
		display: inline-block;
		vertical-align: middle;
		margin-left: 15px;
		padding: 8px;
		border-left: 1px inset #e8e8e8;
	}

	.hj-alive-attach-upload i.loading {
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/ajax-loader.gif) no-repeat 50% 50%;
	}

	.hj-alive-attach-search .hj-alive-attachments-filter {
		border: 1px solid #f4f4f4;
	}
	.hj-alive-attachments-filter  {
		padding: 0;
		margin: 0;
	}
	.hj-alive-attachments-filter  .elgg-image {
		background: #f4f4f4;
		height: 25px;
		padding: 5px;
		margin:0;
	}
	.hj-alive-attachments-filter  input[type="text"],
	.hj-alive-attachments-filter  input[type="text"]:focus {
		height: 25px;
		line-height: 25px;
		width: 100%;
		border: 0;
		background:none;
	}
	.hj-alive-attachments-filter .elgg-body {
		padding: 5px;
		margin: 0;
		border-left:1px solid #f4f4f4;
		border-right:1px inset #e8e8e8;
	}
	.hj-alive-attachments-filter .elgg-image-alt {
		height: 31px;
		padding: 0;
		margin: 0;
	}
	.hj-alive-attachments-filter input[type="submit"] {
		border: 0;
		color: transparent;
		text-shadow: none;
		box-shadow: none;
		right: 0;
		font-weight: normal;
		margin: 0;
		height: 35px;
		padding: 5px;
		display: block;
		box-sizing: content-box;
		border-radius: 0;
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/search.png) no-repeat 50% 50%;
		background-size: 15px;
		display:inline-block;
		vertical-align:top;
		box-sizing:border-box;
	}
	.loading .hj-alive-attachments-filter input[type="submit"] {
		background: #f4f4f4 url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/ajax-loader.gif) no-repeat 50% 50%;
	}
	.hj-alive-attach-search {
		display: block;
		width: 70%;
	}
	.table-headers > th {
		background: none;
		border: none;
		padding: 8px;
	}
	.attachments-options tr {
		border-bottom: 1px solid #e8e8e8;
		background:none;
	}
	.attachments-options {
		border: none;
	}
	.attachments-options td {
		border: none;
		line-height:25px;
		padding:8px;
		width:auto;
	}
	.attachments-options tr:nth-child(even) td {
		background:#f4f4f4;
	}
	.elgg-table.attachments-options td.table-cell-icon {
		width:25px;
	}
	.hj-alive-attachments-list > li {
		display: inline-block;
		height: 25px;
		width: 25px;
		padding: 5px;
		vertical-align: top;
		background: #f4f4f4;
		border-right: 1px inset #e8e8e8;
	}
	.hj-alive-attachments-list {
		padding: 0;
		margin: 0;
	}
	.hj-alive-attachments-list > li img {
		width: 25px;
		height: auto;
	}
	.hj-alive-attachments-list > li:hover span {
		width: 25px;
		height: 25px;
		position: absolute;
		display: block;
		opacity: 0.5;
		background: transparent url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/remove.png) no-repeat 50% 50%;
		background-size: 25px;
		cursor: pointer;
	}
	.hj-comments-attachments {
		margin: 10px;
	}
	.hj-comments-attachments > li {
		font-size: 0.85em;
		line-height: 18px;
		padding: 2px 10px;
		background: #f9f9f4;
		margin-top: 2px;
	}
	.hj-comments-attachments > li img {
		width: 18px;
		height: auto;
	}
	.hj-comments-attachments > li .elgg-image {
		height: 18px;
	}
	.hj-comments-attachments-detach {
		width:16px;
		height:16px;
		color:transparent;
		background:transparent url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/remove.png) no-repeat 50% 50%;
		background-size:16px;
		display:block;
		opacity:0.3;
	}
	.hj-comments-attachments-detach:hover {
		color:transparent;
		opacity:0.5;
	}
	.hj-comments-attachments-detach.loading {
		background:transparent url(<?php echo elgg_get_site_url() ?>mod/hypeAlive/graphics/ajax-loader.gif) no-repeat 50% 50%;
	}

	<?php if (FALSE) : ?></style><?php endif; ?>