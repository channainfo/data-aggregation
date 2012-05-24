<?php
  class DaImporterTest extends CDbTestCase {
    public function testLoadSiteConfig(){
      $site = new SiteConfig();
      $site->setAttributes(array(
          "name" => "Battambong",
          "code" => "0001",
          "host" => "localhost",
          "db" => "server_test",
          "user" => "blah"
      ));
      
      $site->save();
      $importer = new DaImporter(Yii::app()->db, "0001");
    }
    
 }