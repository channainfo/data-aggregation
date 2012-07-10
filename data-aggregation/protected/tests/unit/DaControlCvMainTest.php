<?php
 require_once dirname(__FILE__).DIRECTORY_SEPARATOR."DaDbTestCase.php";
 class DaControlCvMainTest extends DaDbTestCase{
   private $key ;
   private $valid;
   
   public function setUp() {
     $this->key = "ClinicID"; 
     $this->valid = array( $this->key   => "P000001   ",
                     "ARTNum"     => "P190100010  ",
                     "DateVisit"  => "2011-01-01 "
     );
     parent::setUp();
   }
   
   public function testCheckARTInCARTTable(){
     $elements = array(
                      array($this->key => "P000001" , "ARTNum"=> "P190100010   " , "result" => true),
                      array($this->key => "P000002 ", "ARTNum"=> "P190100002  " , "result" => true),
                      array($this->key => "P000006" , "ARTNum"=> "P190100006   " , "result" => true),
                      array($this->key => "P000007" , "ARTNum"=> "P19010000x" , "result" => false),
                      array($this->key => "P000009 ", "ARTNum"=> "P030100267" , "result" => true)
     );
     
     foreach($elements as $element){
       $cvmain = new DaControlCvMain();
       $cvmain->setRecord($element);
       $result = $cvmain->checkARTInCARTTable($this->dbx);
       //echo "\n result: {$result}-expected: {$element["result"]}";
       $this->assertEquals($result, $element["result"]);
       $errors = $cvmain->getErrors();
       if(count($errors)){
         $this->assertNotEquals(strpos($errors[0], "[ARTNum] P19010000x does not exist"), false);
       }
     }
   }
    public function testCheckWithValidRecord(){
     $evMain = new DaControlCvMain();
     $evMain->setRecord($this->valid);
     
     $result = $evMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, true);
     $errors = $evMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 0);
   }
   
   public function testCheckInValidDateVisit(){
     $this->valid["DateVisit"] = "";
     $evMain = new DaControlCvMain();
     $evMain->setRecord($this->valid);
     
     $result = $evMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $evMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[DateVisit] invalid:"), false);
   }
   
   public function testCheckInvalidARTNum(){
     $this->valid["ARTNum"] = "pp123456789";
     $evMain = new DaControlCvMain();
     $evMain->setRecord($this->valid);
     
     $result = $evMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $evMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[ARTNum] invalid pp123456789"), false);
   }
   
   public function testCheckInvalidARTinARTTable(){
     $this->valid[$this->key] = "0000012200";
     $evMain = new DaControlCvMain();
     $evMain->setRecord($this->valid);
     
     $result = $evMain->check(array("dbx"=>$this->dbx));
     $this->assertEquals($result, false);
     $errors = $evMain->getErrors();
     //print_r($errors);
     $this->assertEquals(count($errors), 1);
     $this->assertNotEquals(strpos($errors[0],"[ARTNum] P190100010 does not exist in tblcart ."), false); 
   }
 }
