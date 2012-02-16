<?php
   class DaDbConnectionTest extends CTestCase{
     
     public function testConnectionSuccess(){
       $host = "WORK-NIPH";
       $user = "sa" ;
       $passwrod = "123456" ;
       $databases = array("test", "server_oi");
       
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
       $site = new SiteConfig;
       $site->setAttributes(array(
              "code" => "0001",
              "host" => "localhost",
              "db" => "test",
              "user" => "sa",
              "password" => "123456",
        ));
       
       $site->save();
       
       
       $db =  new DaDbConnection();
       $db->restoreFromBakFile(1);
       
     }
     
     private function createSite(){
       
     }
     
     
   }