<?php
   class DatabaseConnectionTest extends CTestCase{
     
     public function testConnectionSuccess(){
       $host = "WORK-NIPH";
       $user = "sa" ;
       $passwrod = "123456" ;
       $databases = array("test", "server_oi");
       
       foreach($databases as $database){
          $db = new DatabaseConnection($host,$user,$passwrod, $database);
          $this->assertEquals(true, $db->isConnected());
          unset($db);
       }
     }
     
     public function testConnectionFailed(){
       $host = "WORK-NIPH";
       $user = "sa" ;
       $passwrod = "" ;
       $database = "server_oi";
       $db = new DatabaseConnection($host,$user,$passwrod, $database);
       $this->assertEquals(false, $db->isConnected());
     }
     
   }