<?php

class hjGroupTopicPost extends hjComment {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "hjgrouptopicpost";
	}

}

