<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Controllers\API;

/**
 * Description of ApiControllerBase
 *
 * @author eszik
 */
class ApiControllerBase extends \Phalcon\Mvc\Controller {

    public function initialize() {
        $this->response = new \Test\Controllers\API\ApiResponse();
    }

}
