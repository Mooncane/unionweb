<?php

class member extends Controller {

  function __construct() {
    parent::__construct();
    
  }
  
  public function index(){
    $this->view->render('member/index');
  }

}
