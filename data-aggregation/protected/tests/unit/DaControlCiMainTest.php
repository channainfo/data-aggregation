<?php
  class DaControlCiMainTest extends CDbTestCase {
   public $valid = array( "OffYesNo"=>"Yes",
                          "OfficeIn" => "yes", 
       
                          "DateARV" => "2009-09-09", 
                          "ARVNumber" => "1234567890" ,
       
                          "DateVisit" => "2009-09-09"  
                    );
   
   public $instance ;
   public function setUp() {
      $this->instance = new DaControlCiMain();
   }
    
   public function testCheck(){
     $this->instance->setRecord($this->valid);
     $success = $this->instance->check();
     $this->assertEquals($success, true);
     $this->assertEquals($this->instance->errors, array());
   }
   
   public function testCheckOfficeIn(){
     $record = array( "OffYesNo" => "Yes", "OfficeIn" => "");
     $this->instance->setRecord($record);
     $success = $this->instance->checkOfficeIn();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
   
   public function testCheckDateVisit(){
     $record = array("DateVisit" => "1900-09-09");
     $this->instance->setRecord($record);
     $success = $this->instance->checkDateVisit();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
   
   public function testCheckDateARV(){
     $record = array("ARVNumber" => "", "DateARV" => "2000-09-09");
     $this->instance->setRecord($record);
     $success = $this->instance->checkDateARV();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
   
   
  }