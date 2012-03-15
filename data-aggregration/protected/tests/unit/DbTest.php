<?php
 class DbTest extends CTestCase{
   public function testConnection(){
     
//     DaTool::debug(Yii::app()->db->connectionString,0,0);
//     DaTool::debug(Yii::app()->db->username,0,0);
//     DaTool::debug(Yii::app()->db->password,0,0);
     
     $this->assertNotEquals(NULL, Yii::app()->db);   
     
   }
 }
?>
