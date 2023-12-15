<?php

    namespace app\lib;

    use Bitrix\Crm\Service\Container;

    require 'app/lib/factory_funcs.php';

    class Factory {

        protected $factory;
        
        public function __construct() {

            $config = require 'app/config/factory.php';
            $this->factory = Container::getInstance()->getFactory($config['entityTypeId']);
            
        }

        public function getUpper() {

            return get_upper_level();

        }

        public function getPerentSec() {
            
            return get_parent_levels();
            
        }

    }