<?php

class Model {

  function __construct() {
    $this->db = new Database(DB_TYPE, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD);
  }

}