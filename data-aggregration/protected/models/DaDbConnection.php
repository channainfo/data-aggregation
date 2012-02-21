<?php
  class DaDbConnection {
    private $connection = false ;
    const MASTER = "master" ;
    public function __construct() {
    }

    public function connect($host, $user, $password , $db){
       if($this->connection)
         unset($this->connection);
       $this->connection = $this->establishConnection($host, $user, $password, $db);
    }
    
    public function establishConnection($host, $user, $password , $db){
      $connection = false;     
      if( !empty($host) && !empty($user) && !empty($db)){
        $connOptions = array("Database"=>$db, "UID"=> $user, "PWD"=> $password);
        $connection = sqlsrv_connect($host, $connOptions);
      }
      return $connection;
    }
    
    public function isConnected(){
      return $this->connection ;
    }
    
    public function __destruct() {
      unset($this->connection);
    }
    
    public function query($sql, $connection=false){
      if(!$connection)
        $connection = $this->$connection;
      
      return  sqlsrv_query($connection, $sql);
    }
    
    public function mssqlConfigure(){
      sqlsrv_configure( "WarningsReturnAsErrors", 0 );
    }
    
    
    public function checkDbState($host, $user, $password,  $db){
      $sql = "SELECT state, state_desc, FROM sys.databases WHERE name = '{$db}' " ;
      $connection =  $this->establishConnection($host, $user, $password, "master" );
      $stmt = $this->query($sql, $connection);
      
      
    }
    
    
    public function restoreFromBakFile($host, $user, $password,  $db, $backup, &$connection){
       
       $connection =  $this->establishConnection($host, $user, $password, "master");
       $this->mssqlConfigure();
       
       // TODO update backup table to set state to pending
       $errors = array();
       if($connection){
          // start doing restoring
          $sql = <<<EOT
         RESTORE DATABASE $db FROM DISK = '$backup' WITH REPLACE, RECOVERY ;
         
EOT;
          $stmt = $this->query($sql, $connection );
          
          if($stmt === false)
            $errors = sqlsrv_errors();
          
          //Put DB into usable state.
          $useSql = " USE {$db}; ";
          $stmt = $this->query($useSql, $connection);
          
          if($stmt === false)
            $errors = sqlsrv_errors();       
       }
       else{
         $errors[] = "could not connect to {master db } with user: {$user} and password: {$password} for host: {$host} ";
       }
       return $errors;
     }
      
    
    
    
  }
?>
