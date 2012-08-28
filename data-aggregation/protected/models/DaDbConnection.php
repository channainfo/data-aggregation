<?php
  class DaDbConnection {
    private $connection = false ;
    const MASTER = "master" ;
    public function __construct() {
    }
    /**
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $db
     * @throws DaDbConnectionException 
     */
    public function connect($host, $user, $password , $db){
       if($this->connection)
         unset($this->connection);
       $this->connection = $this->establishConnection($host, $user, $password, $db);
    }
    /**
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $db
     * @return boolean
     * @throws Exception 
     */
    public function establishConnection($host, $user, $password , $db){
      $connection = false;     
      if( !empty($host) && !empty($user) && !empty($db)){
        $connOptions = array("Database"=>$db, "UID"=> $user, "PWD"=> $password);
        $connection = sqlsrv_connect($host, $connOptions);
      }
      if(!$connection){
        $messages = array() ;
        $errors = sqlsrv_errors();
        foreach($errors as $error){
          $messages[] = $error["message"];
        }
        throw new DaDbConnectionException( implode("\n", $messages));
      }
      return $connection; 
    }
    /**
     *
     * @return boolean 
     */
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
          $useSql = " USE master; ";
          $stmt = $this->query($useSql, $connection);
          if($stmt === false)
            $errors["message"] = $this->errorMessage(sqlsrv_errors());
         
          
         
          // start doing restoring
          $sql = <<<EOT
         RESTORE DATABASE $db FROM DISK = '$backup' WITH REPLACE, RECOVERY ;
         
EOT;
          $stmt = $this->query($sql, $connection );
          
          if($stmt === false)
            $errors["message"] = $this->errorMessage(sqlsrv_errors());
          
             
          $useSql = " USE master; ";
          $stmt = $this->query($useSql, $connection);
          if($stmt === false)
            $errors["message"] = $this->errorMessage(sqlsrv_errors());
          
//          $useSql = " alter database {$db} set offline; ";
//          $stmt = $this->query($useSql, $connection);
//          if($stmt === false)
//            $errors["message"] = $this->errorMessage(sqlsrv_errors());
//          
//          $useSql = " alter database {$db} set online; ";
//          $stmt = $this->query($useSql, $connection);
//          if($stmt === false)
//            $errors["message"] = $this->errorMessage(sqlsrv_errors());
          
          //Put DB into usable state.
//          $useSql = " USE {$db}; ";
//          $stmt = $this->query($useSql, $connection);
//          
//          if($stmt === false)
//            $errors["message"] = $this->errorMessage(sqlsrv_errors());
//          
       }
       else{
         $errors["message"] = "could not connect to {master db } with user: {$user} and password: {$password} for host: {$host} ";
       }
       return $errors;
     }
     
     public function errorMessage($errors, $glue ="\n"){
       $messages = array();
       foreach($errors as $error){
         $messages[] = $error["message"];
       }
       return implode($glue, $messages);
     }
  }
?>
