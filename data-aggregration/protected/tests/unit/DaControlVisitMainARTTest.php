<?php
 /**
  * @property DaControlVisitMainART $instance
  */
 class DaControlVisitMainARTTest extends CTestCase {
   public $instance ;
   public $valid ;
   
   public function setUp(){
     $this->instance = new DaControlVisitMainART();
     $this->valid = array(
         "ARTNum" => "123456789" ,
         "DateVisit" => "2010-10-10"
     );
   }
   
   public function testCheckWithValid(){
     $this->instance->setRecord($this->valid);
     $result = $this->instance->check();
     $this->assertEquals($result, true);
     $this->assertEquals($this->instance->errors, array() );
   }
   
   public function testCheckWithInValidARTNum(){
     $record = array_merge($this->valid, array("ARTNum" => "1234567891112"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
    
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[ART\]/i", $this->instance->errors[0]), 1);
   }
   
   public function testCheckWithInvalidDAateVisit(){
     $record = array_merge($this->valid, array("DateVisit" => "1900-10-10"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
    
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[Date]/i", $this->instance->errors[0]), 1);
   }
   
   public function testCheckWithInvalidAll(){
     $record = array_merge($this->valid, array("ARTNum" => "1234567891112", "DateVisit" => "1900-09-09"));
     $this->instance->setRecord($record);
     $result = $this->instance->check();
    
     $this->assertEquals($result, false);
     $this->assertEquals(count($this->instance->errors), 1);
     $this->assertEquals(preg_match("/\[ART\]/i", $this->instance->errors[0]), 1);
   }
   
   
 }