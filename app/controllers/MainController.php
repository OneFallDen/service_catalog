<?php

    namespace app\controllers;

    use app\core\Controller;

    class MainController extends Controller{

        public function indexAction() {
            $result = $this->model->getUpperLevel();
            $this->view->render('Main page', $result);
        }
        
    }