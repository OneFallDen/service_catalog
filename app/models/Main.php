<?php

    namespace app\models;

    use app\core\Model;

    class Main extends Model {

        public function getUpperLevel() {
            $result = $this->db->row('SELECT title, description FROM news');
            return $result;
        }

    }