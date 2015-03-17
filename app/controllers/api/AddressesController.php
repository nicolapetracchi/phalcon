<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Controllers\API;

class AddressesController extends \Test\Controllers\API\ApiControllerBase {

    public function indexAction() {
        echo json_decode([1, 2, 3]);
    }

    public function listMe() {
        $this->response->setResponse([1, 2, 3, 4]);
        return $this->response;
    }

}
