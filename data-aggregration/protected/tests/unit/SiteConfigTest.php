<?php
  class SiteConfigTest extends CDbTestCase{
    public $fixtures = array(
        "site_configs" => "SiteConfig", 
       /*
        * fixname => "Active record model name" then we can call $this->site_configs 
        * to return all the fixtures rows created $this->site_configs[] 
        */ 
    );
    
    public $validAttributes, $siteconfig;

    public function tearDown(){
      
    }
    public function setUp() {
     $this->attributes =  array(
                              "code" => "0001",
                              "name" => "Battambong",
                              "db" => "SERVER_OI",
                              "host" => "WORK-NIPH",
                              "user" => "sa",
                              "password" => "123456"
                          );
     $this->siteconfig = new SiteConfig();
    }

    public function testCreateSiteConfigWithValidAttribute(){
      $count = $this->siteconfig->count();
      $this->siteconfig->setAttributes($this->attributes);
      $this->assertEquals($this->siteconfig->save(), true );
      $this->assertEquals($this->siteconfig->count(), $count+1 );
    }
    
    public function testCreateSiteConfigRequiredValidAttributes(){
      /**
       * It required code, db, host, user
       */
       $requires = array("code", "host", "db", "user");
       foreach($requires as $key){
         // attributes does not exist

         $invalidUnset = $this->getAttributeInvalid($key, true);
         $this->siteconfig->unsetAttributes();
         $this->siteconfig->setAttributes($invalidUnset);
         $this->assertEquals(false, $this->siteconfig->save());
   
         
         // attributes does exist but empty
         $invalidEmpty = $this->getAttributeInvalid($key,false);
         $this->siteconfig->unsetAttributes();
         $this->siteconfig->setAttributes($invalidEmpty);
         $this->assertEquals(false, $this->siteconfig->save());
       }
    }
    
    private function getAttributeInvalid($key, $destroy = false){
      $valid = $this->attributes ;
      if($destroy == true)
        unset($valid[$key]);
      else 
        $valid[$key] = "" ;
        
      return $valid;
    }
    
  }
?>
