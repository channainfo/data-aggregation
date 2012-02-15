<?php
  class DaDbConnection {
    private $connection = false ;
    
    public function __construct($host, $user, $pwd , $db) {
      $this->connect($host, $user, $pwd , $db);
    }

    public function connect($host, $user, $pwd , $db){
      if( !empty($host) && !empty($user) && !empty($db)){
        $connOptions = array("Database"=>$db, "UID"=> $user, "PWD"=> $pwd);
        $this->connection = sqlsrv_connect($host, $connOptions);
      }
    }
    
    public function isConnected(){
      return $this->connection ;
    }
    
    public function __destruct() {
      unset($this->connection);
    }
    
    
  }
?>
