<?php
   class DaDbConnectionTest extends CTestCase{
     
     public function testConnectionSuccess(){
       
       $host = "localhost";
       $user = "sa" ;
       $passwrod = "123456" ;
       $databases = array("master", "site_new_oi");
       
       foreach($databases as $database){
          $db = new DaDbConnection();
          $db->connect($host,$user,$passwrod, $database);
          $this->assertEquals(true, (bool)$db->isConnected());
          unset($db);
       }
     }
     /**
     * @expectedException DaDbConnectionException
     */
     public function testConnectionFailed(){
       $host = "localhost";
       $user = "sa" ;
       $passwrod = "" ;
       $database = "server_oi";
       $db = new DaDbConnection($host,$user,$passwrod, $database);
       $db->connect($host,$user,$passwrod, $database);
       $this->assertEquals(false, $db->isConnected());
     }
    
     public function testRestoreSite(){
       $server = array(
              "host" => "localhost",
              "db" => "element",
              "user" => "sa",
              "password" => "123456");
       $file = dirname(__FILE__)."/../data/server_oi.bak";
   
       $db =  new DaDbConnection();
       $connection = false ;
       $db->restoreFromBakFile($server["host"], $server["user"], $server["password"], $server["db"], $file, $connection);
       $this->assertNotEquals(false , $connection);
       
       
     }   
   }