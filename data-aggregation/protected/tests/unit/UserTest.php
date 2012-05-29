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
    $success = $user->save();
    $this->assertEquals(true, $success  );
    $this->assertEquals($count+1, User::model()->count());
    $this->assertEquals($user->password, $user->encrypt($this->attributes["password"], $user->salt) );
    
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
  
  
  public function testRequirePasswordOnlyOnCreate(){
      $user = new User();
      $user->setAttributes($this->attributes);
      $user->save();
      
      $oldPwd = $user->password;
      $user1 = User::model()->findByPk($user->id);
      
      /** Update only name, and email so password should be unchanged **/
      $user->setAttribute("name", "New name");
      $user->setAttribute("email", "Email@email.com");
      
      $success = $user1->save();
      $this->assertEquals(empty($user1->errors), true);
      $this->assertEquals($success, true);
      $this->assertEquals($oldPwd, $user1->password);
      
      /** update password **/
      $user->setAttribute("password", "123456");
      $user->setAttribute("name", "New name");
      $user->setAttribute("email", "Email@email.com");
      $success =  $user->save();
      $this->assertEquals($success, true);
      $this->assertEquals( $user->password , $user->encrypt("123456", $user->salt) );
      
  }
 }
?>
