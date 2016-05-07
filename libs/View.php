<?php

class View {

  function __construct() {
    //echo "Here is the view main controller<br>";
  }
  
  public function render($page, $nomenu = false) {
    require "views/header.php";
    
    if (!$nomenu) {
      require "views/mainmenu/index.php";
      require "views/topmenu/index.php";
    }
    
    
    require "views/" . $page . '.php';
    require "views/footer.php";
  }

}
