<?php
  class DaControlCvMainTest extends CTestCase {
    public function testCheck(){
      $instance = new DaControlCvMain(array("ARTNum" => "0123456789"));
      $success = $instance->check();
      $this->assertEquals($success, true);
      $this->assertEquals($instance->errors,array());
    }
    
    public function testCheckART(){
      $instance = new DaControlCvMain(array("ARTNum" => "0123456"));
      $success = $instance->check();
      $this->assertEquals($success, false);
      $this->assertEquals(count($instance->errors), 1);
    }
  }