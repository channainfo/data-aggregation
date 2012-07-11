<?php 
 /**
  * @property  DaControlEvMain $instance
  */
 class DaControlEvMainTest extends CDbTestCase {
   public $valid ;
   public $instance ;
   
   public function setUp() {
     $this->valid = array(
         "DateVisit" => "2010-10-10"
     );
     $this->instance = new DaControlEvMain();  
   }
   
   public function testCheckWithValidRecord(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check(array("dob"=> "2010-01-01"));
     $this->assertEquals($result, true);
     $this->assertEquals(count($this->instance->errors), 0);
   }
   
   public function testCheckWithInvaliDateVisit(){
     $record = array_merge($this->valid, array("DateVisit" => "1900-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check(array("dob"=> "2005-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1 );
     $this->assertNotEquals(strpos($this->instance->errors[0],"[DateVisit] invalid"), false);
   }
   
   public function testCheckWithInvalidAge(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check(array("dob"=> "2000-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1 );
     $this->assertNotEquals(strpos($this->instance->errors[0],"Patient is not under 2 years old"), false);
   }
   
   
   
   
   
   public function testCheckInvalidAge(){
     $record = array_merge($this->valid, array("DateVisit" => "2010-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check(array("dob"=> "2005-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/Patient/i", $this->instance->errors[0]), 1);
   }
 }