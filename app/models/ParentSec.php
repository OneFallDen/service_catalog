<?php

    namespace app\models;

    use app\core\Model;

    class ParentSec extends Model {

        public function getParentLevel() {
            $result = $this->factory->getPerentSec();
            return $result;
        }

    }