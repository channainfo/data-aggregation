<?php
  class DaControlARTTest extends CDbTestCase {
    
    public function testValidCheckARTNumberWithP(){
      $instance = new DaControlART(array("ART" => "p123456789"));
      $success = $instance->check();
      $this->assertEquals($success,  true);
      $this->assertEquals(count($instance->errors),0);
    }
    public function testValidCheckARTNumberWithoutP(){
      $instance = new DaControlART(array("ART" => "123456789"));
      $success = $instance->check();
      $this->assertEquals($success,  true);
      $this->assertEquals(count($instance->errors),0);
    }
    
    public function testErrCheckARTNumberWithP(){
      $instance = new DaControlART(array("ART" => "p1234567890"));
      $success = $instance->check();
      $this->assertEquals($success,  false);
      $this->assertEquals(count($instance->errors),1);
      $this->assertEquals( strpos($instance->errors[0] , " 10 " ) !==false , true);
    }
    public function testErrCheckARTNumberWithoutP(){
      $instance = new DaControlART(array("ART" => "12345678900"));
      $success = $instance->check();
      $this->assertEquals($success,  false);
      $this->assertEquals(count($instance->errors),1);
      $this->assertEquals( strpos($instance->errors[0] , " 9 " ) !==false , true);
    }
  }