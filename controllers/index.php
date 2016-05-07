<?php

class index extends Controller {

  function __construct() {
    parent::__construct();
    
    
  }
  
  public function index(){
    require 'models/index_model.php';
    
    $model = new index_model();
    $this->view->userlist = $model->db->select('SELECT * FROM posts where id = :id', array(':id' => 2));
        
    $this->view->render('index/index');
    
  }
  

}
