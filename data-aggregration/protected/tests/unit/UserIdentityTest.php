<?php
  class UserIdentityTest extends CDbTestCase{
    public function testAuthenticate(){
      $ui = new \UserIdentity();
      $this->assertEquals(true, false);
    }
  }
