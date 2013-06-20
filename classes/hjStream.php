<?php

class hjStream extends hjObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "hjstream";
	}

	public function getURL() {
		return elgg_get_site_url() . "stream/view/$this->guid";
	}

}

