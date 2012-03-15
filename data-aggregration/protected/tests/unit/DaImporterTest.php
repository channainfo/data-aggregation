<?php
  class DaImporterTest extends CDbTestCase {
    
    public function testGetDbX(){
      $importer = new DaImporter(Yii::app()->db);
      $code = "0002";
      $importer->_getDbX($code);
    }
  }