<?php

class Session {

  function __construct() { }
  
  
  public static function init(){
    // Inits a SESSION
    @session_start();
  }
  public static function set($name, $value){
    // Sets a SESSION variable
    $_SESSION[$name] = $value;
  }
  
  public static function get($name){
    // Gets a SESSION variable
    if (isset($_SESSION[$name])){
      return $_SESSION[$name];
    } else {
      return false;
    }
  }
  
  public static function destroy(){
    // Destroy a SESSION
    unset($_SESSION);
    @session_destroy();
    
  }
  
  public static function show(){
    if (isset($_SESSION)){
      print_r($_SESSION);
    }
  }

}