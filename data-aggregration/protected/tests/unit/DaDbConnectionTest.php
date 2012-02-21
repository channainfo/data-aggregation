<?php
   class DaDbConnectionTest extends CTestCase{
     
     public function testConnectionSuccess(){
       $host = "WORK-NIPH";
       $user = "sa" ;
       $passwrod = "123456" ;
       $databases = array("master", "server_oi");
       
       foreach($databases as $database){
          $db = new DaDbConnection();
          $db->connect($host,$user,$passwrod, $database);
          
          $this->assertEquals(true, $db->isConnected());
          unset($db);
       }
     }
     
     public function testConnectionFailed(){
       $host = "WORK-NIPH";
       $user = "sa" ;
       $passwrod = "" ;
       $database = "server_oi";
       $db = new DaDbConnection($host,$user,$passwrod, $database);
       $db->connect($host,$user,$passwrod, $database);
       $this->assertEquals(false, $db->isConnected());
     }
    
     public function testRestoreSite(){
       echo "----------------------------------resotre\n\n";
       $server = array(
              "host" => "localhost",
              "db" => "element",
              "user" => "sa",
              "password" => "123456");
       $file = dirname(__FILE__)."/../data/server_oi.bak";
   
       $db =  new DaDbConnection();
       $db->restoreFromBakFile($server["host"], $server["user"], $server["password"], $server["db"], $file);
       
       
     }   
   }