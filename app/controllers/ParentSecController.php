<?php

    namespace app\controllers;

    use app\core\Controller;

    class ParentSecController extends Controller{

        public function parentSecAction() {
            $result = $this->model->getParentLevel();
            if ($result)
                $this->view->render('Parent page', $result);
            else
                $this->view->errorCode(404);
        }
        
    }