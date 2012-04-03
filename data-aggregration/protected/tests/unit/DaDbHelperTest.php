<?php
 class DaDbHelperTest extends CDbTestCase {
   public function testPrimaryKey(){
     $columnName = DaDbHelper::primaryKey(Yii::app()->db, "tblclinic");
     $this->assertEquals($columnName, "ART");
   }
 }