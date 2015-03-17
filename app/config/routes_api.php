<?php

$api = new \Phalcon\Mvc\Router\Group(array(
    'namespace' => 'Test\Controllers\API',
        ));

// --- Base API URL prefix
$api->setPrefix('/api');

$api->addGet('/addresses', array('controller' => 'addresses', 'action' => 'listMe'));
