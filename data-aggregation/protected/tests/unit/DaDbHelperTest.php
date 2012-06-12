<?php
 class DaDbHelperTest extends CDbTestCase {
   public function testPrimaryKey(){
     $columnName = DaDbHelper::primaryKey(Yii::app()->db, "tblclinic");
     $this->assertEquals($columnName, "ART");
   }
   
   public function testCountRecord(){
     
     $total = DaDbHelper::countRecord(Yii::app()->db, "tblaimain");
     $this->assertEquals($total, 5);
   }
 }