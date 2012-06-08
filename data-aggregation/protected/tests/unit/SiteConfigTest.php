<?php
/**
 * @property SiteConfig $siteconfig 
 */
  class SiteConfigTest extends CDbTestCase{
    public $fixtures = array(
        "siteConfigs" => "daSiteConfigTest", 
       /*
        * fixname => "Active record model name" then we can call $this->siteConfigs 
        * to return all the fixtures rows created $this->siteConfigs[] 
        */ 
    );
    
    public $validAttributes, $siteconfig;
    public function setUp() {
     $this->attributes =  array(
                              "code" => "",
                              "name" => "",
                              "db" => "site2",
                              "host" => "localhost",
                              "user" => "sa",
                              "password" => "123456"
                          );
     $this->siteconfig = new SiteConfig();
     
    }
    public function testUpdateSiteAttributes(){
      $this->siteconfig->attributes = $this->attributes;
      $this->siteconfig->save();
      $this->siteconfig->updateSiteAttributes();
      $this->assertEquals(empty($this->siteconfig->name), false);
      $this->assertEquals(empty($this->siteconfig->code), false);
      
      $this->assertEquals($this->siteconfig->status, SiteConfig::INIT );
      $this->assertEquals($this->siteconfig->last_imported, NULL);
      
    }
    
    public function testCreateSiteConfigWithValidAttribute(){
      $count = $this->siteconfig->count();
      $this->siteconfig->setAttributes($this->attributes);
      $this->assertEquals($this->siteconfig->save(), true );
      $this->assertEquals($this->siteconfig->count(), $count+1 );
    }
    
    public function testCreateSiteConfigRequiredValidAttributes(){
      /**
       * It required db, host, user
       */
       $requires = array( "host", "db", "user");
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
    
    public function testLastBackup(){
       $this->siteconfig->setAttributes($this->attributes);
       $this->siteconfig->save();
       
       
       
       $backupAttributes = array(
          "filename" => "test.bak" ,
          "siteconfig_id" => $this->siteconfig->id,
       );
       $backup1 = new Backup();
       $backup1->setAttributes($backupAttributes);
       $backup1->save();
       
       $backup2 = new Backup();
       $backup2->setAttributes(array(
          "siteconfig_id" => $this->siteconfig->id,
          "filename" => "blah.bak" 
       ));
       $backup2->save();
       
       $lastBackup = $this->siteconfig->lastBackup();
       $this->assertEquals($lastBackup->modified_at, $backup2->modified_at);
       $this->assertEquals($lastBackup->filename, "blah.bak");
       
       
       $backup3= new Backup();
       $backup3->setAttributes(array(
           "siteconfig_id" => $this->siteconfig->id,
           "filename" => "bloo.bak",
           "status" => 1
       ));
       $backup3->save();
       
       $lastBackup = $this->siteconfig->lastBackup(false);

       $this->assertEquals($lastBackup->filename, "bloo.bak");
       $this->assertEquals($lastBackup->status, 1);
    }
    
    public function testIsImportStatus(){
      $this->siteconfig->status = SiteConfig::PENDING;
      $this->siteconfig->save();
      
      $this->assertEquals( $this->siteconfig->isImportable(), false);
      $this->assertEquals( $this->siteconfig->isImporting(), true );
      
      
      $this->siteconfig->status = SiteConfig::START;
      $this->siteconfig->save();
      
      $this->assertEquals( $this->siteconfig->isImportable(), true);
      $this->assertEquals( $this->siteconfig->isImporting(), false );
      
      
      
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
