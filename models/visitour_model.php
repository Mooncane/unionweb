<?php

class visitour_model extends Model {

  function __construct() {
    parent::__construct();
  }
  
  public function initVisitour($user, $password, $location, $uri){
    $this->visitour = new Visitour($user, $password, $location, $uri);
  }
  
  public function getNumberOfOrders($startdate, $enddate){
    if ($this->visitour->lastCallType == 'FixSchedule' && $this->visitour->lastCallParams["Start"] == $startdate && $this->visitour->lastCallParams["End"] == $enddate){
      return $this->_parseFixedCall($this->visitour->lastCallData);
    }
    $res = $this->visitour->webservice('FixSchedule', array("Start" => $startdate, "End" => $enddate, "Show" => "true"));
    return $this->_parseFixedCall($res);
  }
  
  private function _parseFixedCall($data){
    
    $calls = $data->FixedCall;
    $calllist = array();
    foreach ($calls as $call){
      //prepare date
      $date = explode('-', substr($call->Date, 0, 10));
      $formattedDate = $date[2]."-".$date[1]."-".$date[0];
      $calllist[$formattedDate][] = $call;
    }
    
    $result = array();
    ksort($calllist);
    foreach ($calllist as $date => $list){
      $result[$date] = sizeof($list);
      
    }
    
    return $result;
  }
  
  public function getNumberOfKilometers($startdate, $enddate){
    if ($this->visitour->lastCallType == 'FixSchedule' && $this->visitour->lastCallParams["Start"] == $startdate && $this->visitour->lastCallParams["End"] == $enddate){
      return $this->_parseNumberOfKilometers($this->visitour->lastCallData);
    }
    $res = $this->visitour->webservice('FixSchedule', array("Start" => $startdate, "End" => $enddate, "Show" => "true"));
    return $this->_parseNumberOfKilometers($res);
  }
  
  private function _parseNumberOfKilometers($data){
    $calls = $data->FixedCall;
    $calllist = array();
    foreach ($calls as $call){
      //prepare date
      $date = explode('-', substr($call->Date, 0, 10));
      $formattedDate = $date[2]."-".$date[1]."-".$date[0];
      $calllist[$formattedDate][] = $call;
    }
    
    $result = array();
    ksort($calllist);
    foreach ($calllist as $date => $list){
      $currentSum = 0;
      foreach ($list as $obj){
        $currentSum += $obj->Distance/1000;
      }
      if (!isset($result[$date])){
        $result[$date] = 0;
        
      }
      $result[$date] += round($currentSum);
    }
    return $result;
  }
  
  
  
}