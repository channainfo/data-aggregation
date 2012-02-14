<?php
  class SiteConfigTest extends WebTestCase {
    
    public function testCreateSiteConfig(){
      $this->open("?r=siteconfig/create");
      $this->assertTextPresent("Create site config");

      $this->assertElementPresent("name=SiteConfig[code]");
      $this->assertElementPresent("name=SiteConfig[name]");
      $this->assertElementPresent("name=SiteConfig[host]");
      $this->assertElementPresent("name=SiteConfig[password]");
      $this->assertElementPresent("name=SiteConfig[user]");
      $this->assertElementPresent("name=SiteConfig[db]");
      $this->assertElementPresent("//input[@value='Save']");
    }
    
    public function testCreateSiteConfigPostSuccess(){
      $this->open("?r=siteconfig/create");
      $this->type("name=SiteConfig[code]", "0001");
      $this->type("name=SiteConfig[name]", "Battambong");
      $this->type("name=SiteConfig[host]", "WORK-NIPH");
      $this->type("name=SiteConfig[user]", "sa" );
      $this->type("name=SiteConfig[db]", "SERVER_OI");
      $this->type("name=SiteConfig[password]", "001");
      $this->click("//input[@value='Save']");
      $this->waitForTextPresent("Site configuration has been saved successfully");
    }
    
    
    
    public function testCreateSiteConfigPostFailed(){
      $this->open("?r=siteconfig/create");
      $this->open("?r=siteconfig/create");
      $this->type("name=SiteConfig[code]", "");
      $this->type("name=SiteConfig[name]", "Battambong");
      $this->type("name=SiteConfig[host]", "WORK-NIPH");
      $this->type("name=SiteConfig[user]", "sa" );
      $this->type("name=SiteConfig[db]", "SERVER_OI");
      $this->type("name=SiteConfig[password]", "001");
      $this->click("//input[@value='Save']");
      $this->waitForTextPresent("Failed to save site configuration");
    }
    
  }

?>
