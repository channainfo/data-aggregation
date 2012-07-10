<?php
 /**
  * @property DaControlART $instance 
  */
  class DaControlARTTest extends CTestCase {
   public $valid ;
   public $instance ;
   
   public function setUp(){
     $this->valid = array(
         "ARTDate" => "2000-10-10" ,
         "ART" => "123456789"
     );
     $this->instance = new DaControlART();
   }
   
   public function testCheckWithValidRecord(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check($this->valid);    
     $this->assertEquals($result, true);
     $this->assertEquals($this->instance->errors, array());
   }
   
   public function testCheckWithEmptyART(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check($this->valid);    
     $this->assertEquals($result, true);
     $this->assertEquals($this->instance->errors, array());
   }
   
   
   public function testCheckWithInvalidARTNum(){
     $record = array_merge($this->valid, array("ART" =>  "12345678909"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[ART\]/i", $this->instance->errors[0]), 1 ); // $this->instance->errors[0])
   }
   
   public function testCheckWithInvalidARTDate(){
     $record = array_merge($this->valid, array("ARTDate" =>  "1900-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[ARTDate]/i", $this->instance->errors[0]), 1 ); // $this->instance->errors[0])
   }
   
   public function testCheckWithARTNumARTDate() {
     $record = array_merge($this->valid, array("ARTDate" =>  "1900-09-09", "ART" => "123456789012"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[ART]/i", $this->instance->errors[0]), 1 );
   }
   
 }