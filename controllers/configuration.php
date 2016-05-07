<?php

class configuration extends Controller {

  function __construct() {
    parent::__construct();
    
    require 'models/configuration_model.php';
    
    $this->model = new configuration_model();
    $this->view->js = array('configuration/js/default.js');
  }
  
  function index(){
    //$this->view->js = array('login/js/default.js');
    
    header('Location: '.URL."configuration/users");
    $this->view->render('configuration/index');
    
  }
  
  function users(){
    $this->view->userlist = $this->model->getUserList();
    $this->view->render('configuration/users');
    
  }
  
  function other(){
    $this->view->render('configuration/other');
  }
  
  function standards(){
    $this->view->render('configuration/standards');
  }

}

