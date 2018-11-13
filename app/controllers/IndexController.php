<?php

class IndexController extends ControllerBase
{

    public function indexAction($error='')
    {
    	
    	$this->view->pass = $this->security->hash("pass");
    	$this->view->auth = $this->session->auth;
    	$this->view->error = $error;
    	if($error == null){
    		$this->view->error = "error is null";
    	}
    }
}

