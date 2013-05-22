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
				if (!check_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $origin->guid)
						&& !check_entity_relationship(elgg_get_logged_in_user_guid(), 'unsubscribed', $origin->guid)) {
					add_entity_relationship(elgg_get_logged_in_user_guid(), 'subscribed', $origin->guid);
				}
			}

			return $return;
		}

		return parent::save();
	}

	public function getURL() {
		$path = implode('/', $this->getAncestry());
		return elgg_get_site_url() . "comments/view/$path";
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

	public function getAncestry() {
		$check = true;
		$container = $this;
		$ancestry = array($container->guid);
		while ($check) {
			if (!elgg_instanceof($container, 'object', 'hjcomment')) {
				$check = false;
			} else {
				$container = $container->getContainerEntity();
				array_unshift($ancestry, $container->guid);
			}
		}
		return $ancestry;
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

}

