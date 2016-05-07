<?php

class Bootstrapper {

  function __construct() {

    $urlstring = rtrim(filter_input(INPUT_GET, 'url', FILTER_SANITIZE_STRING), '/');
    $url = explode('/', $urlstring);
    Session::init();
    if (!Session::get('auth') && $url[0] != 'login') {
      header('Location: ' . URL . 'login');
    }
    if (!Session::get('citizenshowdate')) {
      Session::set('citizenshowdate', date('d-m-Y'));
    }
    if (!Session::get('planningshowdate')) {
      Session::set('planningshowdate', date('d-m-Y'));
    }
    
    if (empty($url[0])) {
      $url[0] = "index";
    }

    if (file_exists("controllers/" . $url[0] . ".php")) {
      require "controllers/" . $url[0] . ".php";
    } else {
      echo "Error in reading controller <b>$url[0].php</b>";
    }
    
    $controller = new $url[0];

    if (!empty($url[1])) {
      if (!empty($url[2])) {
        if (!empty($url[3])) {
          $controller->{$url[1]}($url[2], $url[3]);
        } else {
          $controller->{$url[1]}($url[2]);
        }
      } else {
        $controller->{$url[1]}();
      }
    } else {
      // Default to index method
      $controller->index();
    }
  }

}
