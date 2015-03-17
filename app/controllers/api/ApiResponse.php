<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Test\Controllers\API;

/**
 * Description of ApiResponse
 *
 * @author eszik
 */
class ApiResponse extends \Phalcon\Http\Response {

    public function __construct($content = null, $code = null, $status = null) {
        parent::__construct($content, $code, $status);
        $this->setHeader('Content-Type', 'application/json');
    }

    public function setResponse($response, $limit = null, $processitems = null) {
        // .... some manipulations of data from controllers ... building of arrays ....
        $this->setContent(json_encode(array('error' => false) + $response));
    }

    public function setResponseError($description, $error = true) {
        //Set status code
        $this->setStatusCode(200, 'OK');
        $this->setContent(json_encode(array('error' => $error, 'error_description' => $description)));
    }

}
