<?php

class Date extends DateTime {
  
  function __construct($time = "now", $object = NULL) { 
    parent::__construct($time, $object);
  }
  
  /**
   * Converts a date in allowed PHP format into danish local format (dd-mm-yyyy)
   * @param Date $date A date in allowed PHP format.
   * @return type The date converted to danish local format.
   */
  public static function dkDate($d){
    $date = new Date($d);
    return $date->format("d-m-Y");
  }
  
  /**
   * Converts a danish local format date into standard DB date format (yyyy-mm-dd)
   * @param Date $date A date in danish local format.
   * @return type The date converted to standard db format.
   */
  public static function dbDate($d) {
    $date = new Date($d);
    return $date->format("Y-m-d");
  }
  
}
