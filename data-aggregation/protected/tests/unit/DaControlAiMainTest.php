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
                          "Sex" => "Female"
                          
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
     $elements= array(
                      array("record" => array("OffYesNo"=>"",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100005",
                                              $this->key => "000005",
                                              "Sex" => "Male"),  
                            "result" => true, "err" =>""
                             ),
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100005",
                                              "Sex" => "Male",
                                              $this->key => "000005"),
                            "result" => false, "err" =>"ArtNumber: 190100005 does not exist in tblart"
                             ),
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "1900-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100005",
                                              "Sex" => "Male",
                                              $this->key => "000005"),
                            "result" => false, "err" =>"Invalid [DateFirstVisit]"
                             ),
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2000-08-08" ,
                                              "ArtNumber" => "190100005",
                                              "Sex" => "TranSex",
                                              $this->key => "000005"),
                            "result" => false, "err" =>"[tblaimain] invalid sex TranSex"
                             ),
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "1900-08-08" ,
                                              "ArtNumber" => "190100005",
                                              "Sex" => "Male",
                                              $this->key => "000005"),
                            "result" => false, "err" =>"DateStaART should not be in year 1900"
                             ),
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100005x",
                                              "Sex" => "Male",
                                              $this->key => "000005"),
                            "result" => false, "err" =>"Invalid [ART] number"
                             ),
         );
      foreach($elements as $index => $element) {
        $aiMain = new DaControlAiMain();
        $aiMain->setRecord($element["record"]);
        $result = $aiMain->check(array("dbx" => $this->dbx));
        
        //echo "\n {$index} result:{$result}-expected:{$element["result"]}";
        $this->assertEquals((bool)$result, $element["result"]);
       
        
        
        if(!$result)
          $this->assertEquals(count($aiMain->getErrors ()),1);
        
        if(count($aiMain->errors)){
          $exist = strpos($aiMain->errors[0], $element["err"]) !==false ;
          $this->assertTrue($exist, true);
        }
      }
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
         array("art" => "1901020   ", "clinicid" => "000020   ", "result" => true ),
     );
     
     foreach($elements as $element){
       $result = $aimainControl->existARTInART($this->dbx, $element["art"], $element["clinicid"]);
       $this->assertEquals((bool)$result, (bool)$element["result"] );
     }
   }
   
   public function testExistARTInAvMain(){
     $aimainControl = new DaControlAiMain();
     $elements = array(
         array("art" => "190100005" , "clinicid" => "000005  ", "result" => false ),
         array("art" => "190100006" , "clinicid" => "000006 ", "result" => true),
         array("art" => "190100007" , "clinicid" => "000007 ", "result" => true),
         array("art" => "190100008" , "clinicid" => "000008 ", "result" => true),
         
     );
     foreach($elements as $element){
       $result = $aimainControl->existARTInAvMain($this->dbx, $element["art"], $element["clinicid"]);
       //echo "\n ARTInAvMain result: $result-expected:{$element["result"]}";
       $this->assertEquals((bool)$result, (bool)$element["result"] );
     }
   }
   
   
   public function testCheckTranIn(){
     $elements= array(
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => " Yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "190100006",
                                              $this->key => "000006"),
                            "result" => true, "err" =>"" ),
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => " yes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "19010000x",
                                              $this->key => "000006"),
                            "result" => false, "err" => "ArtNumber: 19010000x does not exist" ),
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "yes ", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "1900-08-08" ,
                                              "ArtNumber" => "19010000",
                                              $this->key => "000006"),
                            "result" => false, "err" => "DateStaART should not be in year 1900" ),
         
         
                            
         
                      array("record" => array("OffYesNo"=>"Yes",
                                              "OffTransferin" => "", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "19010000",
                                              $this->key => "000006"),
                            "result" => false, "err" => "Invalid transferin."),
         
                       
         
                      array("record" => array("OffYesNo"=>"No",
                                              "OffTransferin" => "yxxxes", 
                                              "DateFirstVisit" => "2009-09-09", 
                                              "DateStaART" => "2009-08-08" ,
                                              "ArtNumber" => "xxx",
                                              $this->key => "xxx"),
                            "result" => true, "err" => "" ),
         
     );
     
     foreach($elements as $index => $element){
       $aimainControl = new DaControlAiMain();
       $aimainControl->setRecord($element["record"]);
       $result = $aimainControl->checkTranIn($this->dbx);
       //echo "\n {$index} result:{$result}-expected:{$element["result"]}";
       $this->assertEquals((bool)$result, (bool)$element["result"]);
       if(!$result)
        $this->assertEquals(count($aimainControl->getErrors()), 1);
       
       $errors = $aimainControl->getErrors();
       if(count($errors)){
        $exist = strpos($errors[0], $element["err"]) !== false ;
        $this->assertTrue($exist);
       }
     }
   }
   
   
}