<?php

    namespace app\core;

    use app\lib\Factory;

    abstract class Model {

        public $factory;
        
        public function __construct() {
            $this->factory = new Factory;
        }

    }