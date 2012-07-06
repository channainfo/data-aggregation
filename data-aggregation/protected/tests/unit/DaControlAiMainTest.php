<?php
 require_once dirname(__FILE__).DIRECTORY_SEPARATOR."DaDbTestCase.php";
 /**
  * @property DaControlAiMain $instance 
  */
 class DaControlAiMainTest extends DaDbTestCase {
   public $valid = array( "OffYesNo"=>"Yes",
                          "OffTransferin" => "yes", 
                          "DateFirstVisit" => "2009-09-09", 
                          "DateStaART" => "2009-08-08" ,
                          "ArtNumber" => "190100001" ,
                          
                          );
   public $key ;
   public $instance ;
   public function setUp() {
     $this->key = DaRecordReader::getKeyFromTable("tblaimain"); 
     $this->valid[$this->key] = "000001" ;     
     $this->instance = DaControlImport::getControlInstance("tblaimain");
     parent::setUp();
   }
    
   public function testCheck(){
     $this->instance->setRecord($this->valid);
     $success = $this->instance->check(array("dbx"=>$this->dbx));
     $this->assertEquals($success, true);
     $this->assertEquals($this->instance->errors, array());
   }
   
   public function testAiMainErrOffTran(){
     $record = array("OffTransferin" => "", "OffYesNo" => "yes");
     $this->instance->setRecord($record);
     $success = $this->instance->checkTranIn($this->dbx);
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
     
   public function testExistARTInART(){
     $aimainControl = new DaControlAiMain();
     
     $elements = array(
         array("art" => "190100001"   , "clinicid" => "000001    ", "result" => true ),
         array("art" => "190100001   ", "clinicid" => "000001 ", "result" => true),
         array("art" => "000019    "   , "clinicid" => "000019   ", "result" => false ),
         array("art" => "190100020   ", "clinicid" => "000020   ", "result" => true ),
     );
     
     foreach($elements as $element){
       $result = $aimainControl->existARTInART($this->dbx, $element["art"]);
       $this->assertEquals((bool)$result, (bool)$element["result"] );
     }
   }
   
   public function testExistARTInAvMain(){
     $aimainControl = new DaControlAiMain();
     $elements = array(
         array("art" => "190100005"    , "clinicid" => "000005  ", "result" => true ),
         array("art" => "1901000060 " , "clinicid" => "000006 ", "result" => true),
         array("art" => "190100001  "   , "clinicid" => "000001 ", "result" => true ),
         array("art" => "190100006   " , "clinicid" => "000006 ", "result" => true ),
         array("art" => "xxxx" , "clinicid" => "000005  ", "result" => false ),
     );
     foreach($elements as $element){
       $result = $aimainControl->existARTInAvMain($this->dbx, $element["art"], $element["clinicid"]);
       $this->assertEquals((bool)$result, (bool)$element["result"] );
     }
   }
   
   
   public function testCheckTranIn(){
     $elements= array(
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100005",
                                              $this->key => "000005"),
                            "result" => true, "err" =>"", "count" => 0,
                             ),
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "19010000x",
                                              $this->key => "000005"),
                            "result" => false, "err" => "ArtNumber: 19010000x does not exist", "count" => 1,
                          ),
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "1900-08-08" ,
                                              "ArtNumber" => "19010000",
                                              $this->key => "000005"),
                            "result" => false, "err" => "DateStaART should not be in year 1900", "count" => 1 ),
         
         
                            
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "19010000",
                                              $this->key => "000005"),
                            "result" => false, "err" => "Invalid transferin.", "count" => 1),
         
                       
         
                      array("record" => array("OffYesNo"=>"No",
                                              "OffTransferin" => "yxxxes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "xxx",
                                              $this->key => "xxx"),
                            "result" => true, "err" => "", "count" => 0,),
         
     );
     
     foreach($elements as $element){
       $aimainControl = new DaControlAiMain();
       $aimainControl->setRecord($element["record"]);
       $result = $aimainControl->checkTranIn($this->dbx);
       $this->assertEquals((bool)$result, (bool)$element["result"]);
       $this->assertEquals(count($aimainControl->getErrors()), $element["count"]);
       $errors = $aimainControl->getErrors();
       if(count($errors))
        $this->assertNotEquals( strpos($errors[0], $element["err"]) , false);
     }
     
     
   }
   
   
}