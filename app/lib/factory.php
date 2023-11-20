<?php

namespace application\lib;

use Bitrix\Crm\Service\Container;

class Factory {

	protected $factory;
	
	public function __construct() {
		$config = require 'application/config/factory.php';
		$this->factory = Container::getInstance()->getFactory($entityTypeId);
	}

}