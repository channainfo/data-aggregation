<?php
  class UserIdentityTest extends CDbTestCase{
    
    public $user ;
    public $userAttr ;
    public function setUp() {
      $this->userAttr = array(
          "name" => "blah",
          "login" => "admin",
          "password" => "123456",
          "password_repeat" => "123456",
          "group_id" => 1
      );
      $this->user = new User();
      $this->user->setAttributes($this->userAttr);
      $this->user->save();
      parent::setUp();
    }


    public function testAuthenticateValidUserAndPassword(){
      $userIdentity = new UserIdentity($this->userAttr["login"], $this->userAttr["password"]);
      $userIdentity->authenticate();
      $this->assertEquals(UserIdentity::ERROR_NONE, $userIdentity->errorCode);

    }
    
    public function testAuthenticateInvalidPassword(){
      $userIdentity = new UserIdentity($this->userAttr["login"], "{$this->userAttr["password"]}bla-blah");
      $userIdentity->authenticate();
      $this->assertEquals(UserIdentity::ERROR_PASSWORD_INVALID, $userIdentity->errorCode);
    }
    
    public function testAuthenticateInvalidUser(){
      $userIdentity = new UserIdentity("{$this->userAttr["login"]}-blh blah", $this->userAttr["password"] );
      $userIdentity->authenticate();
      $this->assertEquals(UserIdentity::ERROR_USERNAME_INVALID, $userIdentity->errorCode);
    }
    
    
  }
