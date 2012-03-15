<?php
 $path = dirname(__FILE__).DIRECTORY_SEPARATOR."DJJob.php";
 require_once $path; 

 $connString = Yii::app()->db->connectionString ;
 $user       = Yii::app()->db->username;
 $password   = Yii::app()->db->password ;
 
 DJJob::configure($connString, array(
  "mysql_user" => $user,
  "mysql_pass" => $password,
));