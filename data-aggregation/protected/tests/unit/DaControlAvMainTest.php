<?php
 require_once dirname(__FILE__).DIRECTORY_SEPARATOR."DaDbTestCase.php";
 
 class DaControlAvMainTest extends DaDbTestCase{
   private $key ;
   private $valid ;
   public function setUp() {
     $this->key = "ClinicID";
     $this->valid = array( $this->key   => "000001    ",
                     "ARTNum"     => "190100001  ",
                     "DateVisit"  => "2011-01-01 "
     );
     parent::setUp();
   }
   
   public function testCheckARTInARTTable(){
     $elements = array(
                      array($this->key => "000001" , "ARTNum"=> "190100001" , "result" => true),
                      array($this->key => "000002" , "ARTNum"=> "123456000 " , "result" => false),
                      array($this->key => "000004" , "ARTNum"=> "19010000x " , "result" => false),
                      array($this->key => "000005" , "ARTNum"=> "190100005 " , "result" => false),
                      array($this->key => "000011" , "ARTNum"=> "190100011   " , "result" => true),
     );
     
     foreach($elements as $element){
       $avmain = new DaControlAvMain();
       $avmain->setRecord($element);
       $result = $avmain->checkARTInARTTable($this->dbx);
       //echo "\n result: {$result}-expected: {$element["result"]}";
       $this->assertEquals($result, $element["result"]);
     }
   }
   
   public function testCheckWithValidRecord(){
     $avMain = new DaControlAvMain();
     $avMain->setRecord($this->valid);
     
     $result = $avMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, true);
     $this->assertEquals(count($avMain->getErrors()), 0);
   }
   
   public function testCheckInValidDateVisit(){
     $this->valid["DateVisit"] = "";
     $avMain = new DaControlAvMain();
     $avMain->setRecord($this->valid);
     
     $result = $avMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $avMain->getErrors();
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[DateVisit] invalid:"), false);
   }
   
   public function testCheckInvalidARTNum(){
     $this->valid["ARTNum"] = "pp123456789";
     $avMain = new DaControlAvMain();
     $avMain->setRecord($this->valid);
     
     $result = $avMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $avMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[ARTNum] invalid pp123456789"), false);
   }
   
   public function testCheckInvalidARTinARTTable(){
     $this->valid[$this->key] = "0000012200";
     $avMain = new DaControlAvMain();
     $avMain->setRecord($this->valid);
     
     $result = $avMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $avMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[ARTNum]: 190100001 does exist in table tblart"), false); 
   }
   
   
   
   
   
 }