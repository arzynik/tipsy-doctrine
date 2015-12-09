<?php

namespace Tipsy\Doctrine;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Db extends \Tipsy\Db {

	protected $_entityManager;

	public function connect($args = null) {
		if (!$args) {
			throw new \Exception('Invalid DB config.');
		}
		$options = [];

		// will overwrite any existing args
		if ($args['url']) {
			$args = array_merge($this->parseUrl($args['url']), $args);
		}

		if ($args['persistent']) {
			$options[\PDO::ATTR_PERSISTENT] = true;
		}

		if ($args['sslca']) {
			$options[\PDO::MYSQL_ATTR_SSL_CA] = $args['sslca'];
			$options[\PDO::ATTR_TIMEOUT] = 4;
			$options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
		}

		if (!$args['driver']) {
			$args['driver'] = 'mysql';
		}

		if ($args['driver'] == 'postgres' || $args['driver'] == 'postgres') {
			$args['driver'] = 'pdo_pgsql';
		} elseif ($args['driver']) {
			$args['driver'] = 'pdo_mysql';
		}

		if ($args['driver'] == 'mysql') {
			$args['charset'] = 'utf8';
		}

		if ($args['pass']) {
			$args['password'] = $args['pass'];
		}

		if ($args['database']) {
			$args['dbname'] = $args['database'];
		}

		if ($options) {
			$args['driverOptions'] = $options;
		}

		$config = Setup::createAnnotationMetadataConfiguration([__DIR__.'/../../../../'.$this->tipsy()->config()['doctrine']['model']], true);

		$this->entityManager(EntityManager::create($args, $config));
		$this->db($this->entityManager()->getConnection());

		return $this;
	}

	public function exec($query) {
		return $this->db()->exec($query);
	}

	public function query($query, $args = null) {
		$stmt = $this->db()->prepare($query);
		if ($args) {
			$stmt->execute($args);
		} else {
			$stmt->execute();
		}
		return $stmt;
	}

	public function get($query, $args = null, $type = 'object') {
		$stmt = $this->query($query, $args);
		return $stmt->fetchAll($type == 'object' ? \PDO::FETCH_OBJ : \PDO::FETCH_ASSOC);
	}

	public function entityManager($em = null) {
		if (!is_null($em)) {
			$this->_entityManager = $em;
		}
		return $this->_entityManager;
	}
}
