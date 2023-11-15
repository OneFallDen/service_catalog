<?php

namespace app\controllers;

use app\core\Controller;

class AccountController extends Controller{

    public function show_ordersAction(){
        echo 'Show orders';
    }

    public function show_history_ordersAction(){
        echo 'History orders';
    }
    
}