<?php
 class DaDbHelperTest extends CDbTestCase {
   public function testPrimaryKey(){
     $columnName = DaDbHelper::primaryKey(Yii::app()->db, "tblclinic");
     $this->assertEquals($columnName, "ART");
   }
   
   public function testCountRecord(){
     $total = DaDbHelper::countRecord(Yii::app()->db, "da_drug_controls");
     $this->assertEquals($total, 17);
   }
 }