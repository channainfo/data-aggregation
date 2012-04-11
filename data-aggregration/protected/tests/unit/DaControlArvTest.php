<?php
 class DaControlArvTest extends CDbTestCase {
   public function testInvalidArv(){
     $instance = new DaControlAvArv(array("ARV" => "not found"));
     $success = $instance->check();
     $this->assertEquals($success,  false);
     $this->assertEquals(count($instance->errors),1);
   }
   public function testValidArv(){
     $instance = new DaControlAvArv(array("ARV" => "ddI"));
     $success = $instance->check();
     $this->assertEquals($success,  true);
     $this->assertEquals($instance->errors, array());
   }
 }