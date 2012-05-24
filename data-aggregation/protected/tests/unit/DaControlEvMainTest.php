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
   
   public function testCheckValidRecord(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check(array("dob"=> "2010-01-01"));
     
     $this->assertEquals($result, true);
     $this->assertEquals($this->instance->errors, array());
   }
  
   public function testCheckInvalidDateVisit(){
     $record = array_merge($this->valid, array("DateVisit" => "1900-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check(array("dob"=> "2010-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[Date\]/i", $this->instance->errors[0]), 1);
   }
   
   public function testCheckInvalidAge(){
     $record = array_merge($this->valid, array("DateVisit" => "2010-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check(array("dob"=> "2005-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/Patient/i", $this->instance->errors[0]), 1);
   }
   
   public function testCheckInvalidAll(){
     $record = array_merge($this->valid, array("DateVisit" => "1900-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check(array("dob"=> "2010-01-01"));
     
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[Date\]/i", $this->instance->errors[0]), 1);
   }
 }