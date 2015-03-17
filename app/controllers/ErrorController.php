<?php

/**
 * Gestisce i routes errati, per ora c'è solo il 404
 */
class ErrorController extends \Phalcon\Mvc\Controller {
	
	public function route404Action() {
		$this->response->setStatusCode(404 , 'Not Found');
	}
    
    public function apiAction() {
        $pars = $this->dispatcher->getParams();
        $this->response = new \Test\Controllers\API\ApiResponse();
        $this->response->setResponseError($pars['message']);
        return $this->response;
    }
	
}
