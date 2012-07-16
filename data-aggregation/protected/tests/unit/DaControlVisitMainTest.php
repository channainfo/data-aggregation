<?php
 class DaControlVisitMainTest extends CTestCase {
   public function testCheckDateVisit(){
     $avmain = new DaControlAvMain();

     $avmain->setRecord(array("DateVisit" => "2001-09-09"));
     $result = $avmain->checkDateVisit();
     $this->assertEquals($result, true);
     
     $avmain = new DaControlAvMain();

     $avmain->setRecord(array("DateVisit" => "1900-09-09"));
     $result = $avmain->checkDateVisit();
     $this->assertEquals($result, false);
     
     $errors = $avmain->getErrors();
     $this->assertEquals(count($errors), 1) ;
     $exist = strpos($errors[0], "[DateVisit] invalid") !==false;
     $this->assertTrue($exist);
   }
   
   public function testCheckARTNumber(){
     $avmain = new DaControlAvMain();
     $avmain->setRecord(array("ARTNum"=> "123456789"));
     $result = $avmain->checkARTNumber();
     $this->assertEquals($result, true);
     $this->assertEquals(count($avmain->getErrors()), 0);
     
     $avmain = new DaControlAvMain();
     $avmain->setRecord(array("ARTNum"=> "12345678909"));
     $result = $avmain->checkARTNumber();
     $this->assertEquals($result, false);
     $errors = $avmain->getErrors();
     $this->assertEquals(count($errors), 1);
     $exist = strpos($errors[0], "[ARTNum] invalid") !== false ;
     $this->assertTrue($exist);
     
     
   }
 }