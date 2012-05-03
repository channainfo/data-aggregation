<?php
  class DaExportSiteTest extends CDbTestCase {
    public $site;
    public $export ;

    public function setUp() {
      return parent::setUp();
    }
    
    public function createSite2(){
     $this->site = new SiteConfig();
     $this->site->attributes = array(
         "code" => "1901",
         "name" => "site2",
         "db" => "site2",
         "user" => "sa",
         "password" => "123456",
         "host" => "localhost"
     );
     $this->site->save();     
   }
    
    public function testIsSiteTable(){
      
    }
  }