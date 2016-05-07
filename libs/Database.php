<?php

class Database extends PDO {

  function __construct($dbtype, $dbname, $dbhost, $dbuser, $dbpassword) {
    parent::__construct("$dbtype:dbname=$dbname;host=$dbhost", $dbuser,  $dbpassword, array());
    
  }
  
  /**
   * Select
   * @param String $sql The sql statement
   * @param String $where
   * @param Constant $fetchMode
   */
  public function select($sql, $params = array(), $fetchMode = PDO::FETCH_ASSOC){
    $sth = $this->prepare($sql);
    $sth->setFetchMode($fetchMode);
    $sth->execute($params);
        
    return $sth->fetchAll();
    
  }
  
  public function delete($table, $where = array()){
        
    $sql = "DELETE from $table";
    if(!empty($where)){
      $sql .= " WHERE";
      foreach ($where as $key => $value){
        $sql .= " $key = '$value'";
      }
    }
    $count = $this->exec($sql);
    return $count;
  }
  
  public function showcols($table, $fetchMode = PDO::FETCH_ASSOC){
    $sql = "SHOW columns FROM $table";
    $sth = $this->prepare($sql);
    $sth->setFetchMode($fetchMode);
    $sth->execute();
    $res = $sth->fetchAll();
    $cols = array();
    foreach ($res as $col){
      $cols[] = $col['Field'];
    }
    return $cols;
    
  }
  
  public function update($table, $where = '', $params = array()){
    
    $cols = $this->showcols($table);
    $sql = "UPDATE $table SET ";
    foreach ($params as $param => $value){
      if (in_array($param, $cols)){
        $sql .= "$param = '".$value."',";
      }
    }
    $sql = rtrim($sql,',');
    if ($where != ''){
      $sql .= " WHERE $where";
    }
    //
    //echo $sql;
    return $this->exec($sql);
  }
  
  public function insert($table, $params = array()){
    
    
    $cols = $this->showcols($table);
    $sql = "INSERT INTO $table ";
    $plist = '';
    $vlist = '';
    foreach ($params as $key => $value){
      if (in_array($key, $cols)){
        $plist .= $key.",";
        $vlist .= ":$key,";
      }
    }
    $sql .= "(".rtrim($plist,',').")";
    $sql .= " VALUES (".rtrim($vlist,',').")";
    
    /*
    echo $sql."\n";
    print_r($params);
    */
    
    $sth = $this->prepare($sql);
    
    return $sth->execute($params);
    
  }
  
 
  
}