<?php

class login extends Controller {

  function __construct() {
    parent::__construct();
    
  }
  
  function index(){
    $this->view->js = array('login/js/default.js');
    $this->view->render('login/index',true);
  }
  
  function authenticate(){
    
    require 'models/login_model.php';
    $this->model = new Login_Model();
    
    $user = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    
    $res = $this->model->authenticate($user, $password);
    
    if ($res){
      // authenticate
      Session::init();
      Session::set('auth', $user);
      echo "auth";
      return true;
    } else {
      echo "notauth";
      unset($_SESSION);
      Session::destroy();
      return false;
    }
    
  }
  
  function logout(){
    Session::destroy();
    header('Location: '.URL);
  }

}

