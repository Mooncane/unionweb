<?php

class clients extends Controller {

  function __construct() {
    parent::__construct();
    
  }
  
  public function index(){
    $this->view->render('clients/index');
  }

}
