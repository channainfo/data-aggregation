<?php

  //require_once 'Phactory/lib/Phactory.php';
  $path = dirname(__FILE__)."/Phactory/lib/Phactory.php" ;
  require_once $path ;
  $phactoryCon = new PDO('mysql:host=127.0.0.1; dbname=server_oi_test', 'root', '');
  Phactory::setConnection($phactoryCon);
  
  Phactory::meaning("da_site_configs", array('name' => 'John Doe', "code"=> "0101"));
  
  $blah = Phactory::create('da_site_configs');

?>
