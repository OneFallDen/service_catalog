<?php

    namespace app\models;

    use app\core\Model;

    class Main extends Model {

        public function getUpperLevel() {
            $result = $this->factory->getUpper();
            return $result;
        }

    }