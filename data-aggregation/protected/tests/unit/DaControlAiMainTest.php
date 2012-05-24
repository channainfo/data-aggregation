<?php
 
 class DaControlAiMainTest extends CDbTestCase {
   public $valid = array("OffYesNo"=>"Yes",
                          "OffTransferin" => "yes", 
                          "DateFirstVisit" => "2009-09-09", 
                          "DateStaART" => "2009-08-08" ,
                          "ArtNumber" => "123456789" ,
                          );
   
   public $instance ;
   public function setUp() {
      $this->instance = DaControlImport::getControlInstance("tblaimain");
   }
    
   public function testCheck(){
     $this->instance->setRecord($this->valid);
     $success = $this->instance->check();
     $this->assertEquals($success, true);
     $this->assertEquals($this->instance->errors, array());
   }
   
   public function testAiMainErrOffTran(){
     $record = array("OffTransferin" => "", "OffYesNo" => "yes");
     $this->instance->setRecord($record);
     $success = $this->instance->checkTranIn();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
   
   public function testAiMainErrDateFirstVisit(){
     $record = array("DateFirstVisit" => "1900-09-09");
     $this->instance->setRecord($record);
     $success = $this->instance->checkDateFirstVisit();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
   
   public function testAiMainErrDateStaART(){
     $record = array("ArtNumber" => "", "DateStaART" => "2000-09-09");
     $this->instance->setRecord($record);
     $success = $this->instance->checkDateStartART();
     $this->assertEquals($success, false);
     $this->assertEquals(count($this->instance->errors),1);
   }
}