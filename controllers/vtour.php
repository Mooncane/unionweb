<?php

class vtour extends Controller {
  
  
  function __construct() {
    parent::__construct();
  }
  
  public function index(){
    
    require 'models/visitour_model.php';
    $this->model = new visitour_model();
    
    $this->model->initVisitour(FLS, FLSPASSWORD, VTLOC, VTWSDL);
    $this->view->calls = $this->model->getNumberOfOrders("11.11.2015","07.12.2015");
    $this->view->kms = $this->model->getNumberOfKilometers("11.11.2015","07.12.2015");

    $this->view->js = array('visitour/js/default.js');
    $this->view->render('visitour/index');
    
  }
  
}

