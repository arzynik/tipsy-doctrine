<?php

namespace Tipsy\Doctrine;

class Resource implements \JsonSerializable {
	public function __construct() {
		$args = func_get_args();
		if ($args[0] && $args[0]['_tipsy']) {
			$this->tipsy($args[0]['_tipsy']);
			return;
		}

		if ($args[0]) {
			$resource = $this->load($args[0]);
			if (!$resource) {
				return;
			}
			foreach (get_object_vars($resource) as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}

	public function jsonSerialize() {
		return get_object_vars($this);
	}

	public function create($data = null) {
		$class = get_called_class();
		$resource = new $class();
		foreach ($data as $key => $value) {
			$resource->{$key} = $value;
		}
		$resource->save();
		return $resource;
	}

	public function load($id = null) {
		$resource = $this->tipsy()->db()->entityManager()->find(get_called_class(), $id);
		return $resource;
	}

	public function save() {
		$this->tipsy()->db()->entityManager()->persist($this);
		$this->tipsy()->db()->entityManager()->flush();
	}

	public function &__get($name) {
		return $this->{$name};
	}

	public function __set($name, $value) {
		return $this->{$name} = $value;
	}

	public function tipsy($tipsy = null) {
		if (!is_null($tipsy)) {
			$this->_tipsy = $tipsy;
		}
		if (is_null($this->_tipsy)) {
			$this->_tipsy = \Tipsy\Tipsy::App();
		}
		return $this->_tipsy;
	}
}
