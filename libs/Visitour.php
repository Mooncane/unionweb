<?php

/**
 * Module Visitour
 */


class Visitour {
  
  private $_soapparams = array();
  public $lastCallType;
  public $lastCallData;
  public $lastCallParams; 
  
  public function __construct($user, $password, $location, $uri) {
    
    $this->_soapparams['user'] = $user;
    $this->_soapparams['password'] = $password;
    $this->_soapparams['location'] = $location;
    $this->_soapparams['uri'] = $uri;
    
    $this->lastCallData = null;
    $this->lastCallType = '';
    $this->lastCallParams = array();
  }
  
  public function webservice($webservice, $xmlparams = array()){
    // TOOD - if method and params are == $this->lastCallType etc, return lastCallData
    $this->lastCallType = $webservice;
    $this->lastCallParams = $xmlparams;
    
    $client = new SoapClient(
      null, 
      array(
        'location' => $this->_soapparams['location'].$webservice,
        'uri' => $this->_soapparams['uri'],
        'trace' => 1,
        'use' => SOAP_LITERAL,
        'style' => SOAP_DOCUMENT,
        'login' => $this->_soapparams['user'],
        'password' => $this->_soapparams['password']
      )
    );
    
    $xml = "<".$webservice.">";
    
    foreach($xmlparams as $key => $value){
      $xml .= "<$key>$value</$key>";
    }
    $xml .= "</$webservice>";
    $params = new SoapVar($xml, XSD_ANYXML);
    
    $this->lastCallData = $client->$webservice($params);
    return $this->lastCallData;
  }
  
  
}
