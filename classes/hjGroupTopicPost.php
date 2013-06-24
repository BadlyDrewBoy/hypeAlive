<?php

class hjGroupTopicPost extends hjComment {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "hjgrouptopicpost";
	}

	public function getEditURL() {
		return elgg_get_site_url() . "ajax/view/framework/alive/discussions/form?comment_guid=$this->guid";
	}
}

