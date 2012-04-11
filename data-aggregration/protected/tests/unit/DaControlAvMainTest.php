<?php
 class DaControlAvMainTest extends CTestCase {
   public function testInvalidARTNumber(){
     $instance = new DaControlAvMain(array("ARTNum" => "01234567890"));
     $success = $instance->check();
     $this->assertEquals($success,  false);
     $this->assertEquals(count($instance->errors),1);
   }
   public function testValidARTNumber(){
     $instance = new DaControlAvMain(array("ARTNum" => "123456789"));
     $success = $instance->check();
     $this->assertEquals($success,  true);
     $this->assertEquals(count($instance->errors), 0);
   }
 }