<?php

class Login_Model extends Model {

  function __construct() {
    parent::__construct();
  }
  
  /**
   * Authentication of login attempt
   * 
   * @param string $user The username that wants to login
   * @param string $password Password in clean text
   * @return boolean Result of authentication attempt
   */
  function authenticate($user, $password){
    $loginresult = $this->db->select("SELECT * FROM ".DB_PREFIX."_user WHERE username = :username AND password = :password", ["username" => $user, "password" => md5($password)]);
    if (!empty($loginresult)){
      return true;
    } else {
      return false;
    }
  }
}