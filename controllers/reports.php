<?php

class reports extends Controller {

  function __construct() {
    parent::__construct();
    
  }
  
  public function index(){
    
    require 'models/reports_model.php';
    $this->model = new Reports_Model();
    
    
    $this->view->render('reports/index');
  }

}