<?php
  require_once dirname(__FILE__).DIRECTORY_SEPARATOR."DaDbTestCase.php";
  
  class DaControlCiMainTest extends DaDbTestCase {
   public $valid = array( "OffYesNo"=>"Yes",
                          "OfficeIn" => "yes", 
                          "DateARV" => "2009-09-09", 
                          "ARVNumber" => "P190100010" ,
                          "DateVisit" => "2009-09-09"  
                    );
   
   public $key ;
   public function setUp() {
     $this->key = DaRecordReader::getKeyFromTable("tblcimain"); 
     $this->valid[$this->key] = "P000001" ;  
     parent::setUp();
   }
   
   public function testExistARVInCART(){
     $cimainControl = new DaControlCiMain();
     $elements = array(
         array("art" => "P190100010"  , "clinicid" => "P000001", "result" => true ),
         array("art" => "1901000060 " , "clinicid" => "P000005", "result" => false ),
         array("art" => "P030100267"  , "clinicid" => "P000009   ", "result" => true )
     );
     
     foreach($elements as $element){
       $result = $cimainControl->existARVInCART($this->dbx, $element["art"]);
       $this->assertEquals((bool)$result, (bool)$element["result"]);
     }
   }
   
   public function testExistARVInCvMain(){
     $cimainControl = new DaControlCiMain();
     $elements = array(
         array("art" => "P190100010"  , "clinicid" => "P000001", "result" => true ),
         array("art" => "P03010020x"  , "clinicid" => "P000005", "result" => false ),
         array("art" => "P030100267"  , "clinicid" => "P000009   ", "result" => true ),
         array("art" => "P190100004"  , "clinicid" => "P000010", "result" => true ),
     );
     
     foreach($elements as $element){
       $result = $cimainControl->existARVInCvMain($this->dbx, $element["art"] , $element["clinicid"]);
       //echo "\n\nexpected: {$element["result"]}-result:{$result}" ;
       $this->assertEquals((bool)$result, (bool)$element["result"]);
     }
   }
   
   public function testCheck(){
     $elements= array(
                      array( "record" => array("OffYesNo"=>"",
                                              "OfficeIn" => "yes", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P190100010" ,
                                              $this->key => "P000001",
                                              "DateVisit" => "2009-09-09",
                                              
                                        ),
                             "result" => true, "err" =>"", "count" => 0,
                             ),
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "Battambong", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P190100010" ,
                                              $this->key => "P000001",
                                              "DateVisit" => "2009-09-09",
                                              
                                        ),
                             "result" => true, "err" =>"", "count" => 0,
                             ),       
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P190100010" ,
                                              $this->key => "P000001",
                                              "DateVisit" => "2009-09-09"  
                                        ),
                             "result" => false, "err" =>"Invalid [OfficeIn].", "count" => 1,
                             ),
         
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "yes", 
                                              "DateARV" => "1900-09-09", 
                                              "ARVNumber" => "P190100010",
                                              $this->key => "P000001",
                                              "DateVisit" => "2009-09-09"  
                                        ),
                             "result" => false, "err" =>"Invalid [DateARV]", "count" => 1,
                             ),
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "yes", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P19010000002x" ,
                                              $this->key => "P000001",  
                                              "DateVisit" => "2009-09-09"  
                                        ),
                             "result" => false, "err" =>"Invalid [ARVNumber]", "count" => 1,
                             ),
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "yes", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P19010000x" ,
                                              $this->key => "P000007",  
                                              "DateVisit" => "2009-09-09"  
                                        ),
                             "result" => false, "err" =>"[tblcart] ARVNumber: P19010000x", "count" => 1,
                             ),
                      array( "record" => array("OffYesNo"=>"Yes",
                                              "OfficeIn" => "yes", 
                                              "DateARV" => "2009-09-09", 
                                              "ARVNumber" => "P190100005" ,
                                              $this->key => "P000007",
                                              "DateVisit" => "2009-09-09"  
                                        ),
                             "result" => false, "err" =>"[tblcvmain] ARVNumber: P190100005", "count" => 1,
                             )
                
         
     );
     
     foreach($elements as $element){
       $cimainControl = new DaControlCiMain();
       $cimainControl->setRecord($element["record"]);
       $result = $cimainControl->checkOfficeIn($this->dbx);
       
       //echo "\n\nexpected: {$element["result"]}-result:{$result}" ;
       $this->assertEquals((bool)$result, (bool)$element["result"]);
       $this->assertEquals(count($cimainControl->getErrors()), $element["count"]);
       
       $errors = $cimainControl->getErrors();
       if(count($errors))
        $this->assertNotEquals( strpos($errors[0], $element["err"]) , false);
     }
   }
   
   public function testCheckDateVistit(){
        $record = array("OffYesNo"=>"Yes",
                          "OfficeIn" => "yes", 
                          "DateARV" => "2009-09-09", 
                          "ARVNumber" => "P190100010" ,
                          $this->key => "P000001",
                          "DateVisit" => "1900-09-09"  
                    );
        $ciControl = new DaControlCiMain();
        $ciControl->setRecord($record);
        $result = $ciControl->checkDateVisit();
        $this->assertEquals($result, false);
        $this->assertEquals(count($ciControl->getErrors()),1);
        $errors = $ciControl->getErrors();
        $this->assertNotEquals(strpos($errors[0], "Invalid [DateVisit]:"),false);
   }
  }