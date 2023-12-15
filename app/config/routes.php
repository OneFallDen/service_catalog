<?php

return [

    '' => [
        'controller' => 'main',
        'action' => 'index',
    ],

    'account/orders' => [
        'controller' => 'account',
        'action' => 'show_orders',
    ],

    'account/history' => [
        'controller' => 'account',
        'action' => 'show_history_orders',
    ],

    'parent/select' => [
        'controller' => 'parentSec',
        'action' => 'parentSec',
    ],

    'parent/service' => [
        'controller' => 'parentService',
        'action' => 'parentService',
    ]

];