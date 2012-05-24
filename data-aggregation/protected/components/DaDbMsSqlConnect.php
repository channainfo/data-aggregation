<?php
 class DaDbMsSqlConnect {
   /**
    *
    * @param string $host
    * @param string $db
    * @param string $user
    * @param string $password
    * @return \CDbConnection
    * @throws DaInvalidSiteDatabaseException 
    */
   public static function connect($host, $db, $user, $password){
      $dsn = "sqlsrv:Server={$host};Database={$db}";
      $dbX = new CDbConnection($dsn,$user,$password);
      try{
        $dbX->active=true;
      }
      catch(CDbException $ex){
        throw  new DaInvalidSiteDatabaseException("Could not connect to : {$host} " . $ex->getMessage());
      }
      return $dbX;
   }
 }