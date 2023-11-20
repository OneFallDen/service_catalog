<?php

namespace application\core;

use application\lib\Factory;

abstract class Model {

	public $factory;
	
	public function __construct() {
		$this->factory = new factory;
	}

}