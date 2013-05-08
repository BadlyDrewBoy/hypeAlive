<?php

class hjComment extends hjObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "hjcomment";
	}

	public function save() {
		if (!$this->guid) {
			$return = parent::save();

			if ($return) {
				$origin = $this->getOriginalContainer();
				if (!check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $origin->guid)) {
					add_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $origin->guid);
				}
				$this->notifySubscribedUsers();
			}

			return $return;
		}

		return parent::save();
	}

	public function getURL() {
		return elgg_get_site_url() . "stream/view/{$this->getOriginalContainer()->guid}?comment=$this->guid";
	}

	public function getEditURL() {
		return elgg_get_site_url() . "stream/edit/$this->guid";
	}

	public function getOriginalContainer() {
		$check = true;
		$container = $this;
		while ($check) {
			if (!elgg_instanceof($container, 'object', 'hjcomment')) {
				$check = false;
			} else {
				$container = $container->getContainerEntity();
			}
		}
		return $container;
	}

	public function getDepthToOriginalContainer() {
		$check = true;
		$container = $this;
		$count = 1;
		while ($check) {
			if (!elgg_instanceof($container, 'object', 'hjcomment')) {
				$check = false;
			} else {
				$container = $container->getContainerEntity();
				$count = $count + 1;
			}
		}
		return $count;
	}

	public function getOriginalOwner() {
		return $this->findOriginalContainer()->getOwnerEntity();
	}

	public function notifySubscribedUsers() {
		return hj_alive_notify_subscribed_users($this->guid);
	}

}

