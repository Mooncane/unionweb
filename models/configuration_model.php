<?php

class configuration_model extends Model {
    
    
  function __construct() {
    parent::__construct();

  }
  
  /**
   * Returns all users registered in system
   * 
   * @return array An array consisting of all user data
   */
  public function getUserList(){
    return $this->db->select("SELECT * FROM ".DB_PREFIX."user");

  }
    
}
