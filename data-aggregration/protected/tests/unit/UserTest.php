<?php
 class UserTest extends CDbTestCase{
  public $attributes = nil;
  
  public function setUp() {
    User::model()->deleteAll();
    $this->attributes =  array(
      "name" => "name", 
      "password" => "123456",
      "password_repeat" => "123456",
      "login" => "admin",
      "email" => "",
      "group_id" => 1,
      "active" => 1
    );
    parent::setUp();
  }

  public function testCreateUser(){
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(true, $user->save() );
    $this->assertEquals($count+1, User::model()->count());
  }
  
  public function testCreaetUserRequiresName(){
    $this->attributes["name"] = "";
    
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(false, $user->save() );
    $item = User::model()->count();
    $this->assertEquals($count, $item); 
  }
  public function testCreaetUserRequiresLogin(){
    $this->attributes["login"] = "";
    
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(false, $user->save() );
    $item = User::model()->count();
    $this->assertEquals($count, $item); 
  }
  public function testCreaetUserRequiresPassword(){
    $this->attributes["password"] = "";
    
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(false, $user->save() );
    $item = User::model()->count();
    $this->assertEquals($count, $item); 
  }
  public function testCreaetUserRequiresPasswordConfirmation(){
    $this->attributes["password"] = "1234";
    $this->attributes["password_repeat"] = "123456";
    
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(false, $user->save() );
    $item = User::model()->count();
    $this->assertEquals($count, $item); 
  }
  public function testCreaetUserRequiresGroupIdAsNumber(){
    $this->attributes["group_id"] = "admin";
    
    $count = User::model()->count();
    $user = new User();
    $user->setAttributes($this->attributes);
    $this->assertEquals(false, $user->save() );
    $item = User::model()->count();
    $this->assertEquals($count, $item); 
  }
  
 }
?>
