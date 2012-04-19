<?php
 class DaControlImportTest extends CDbTestCase {
   public function testValidGetControlInstance(){
     $elements = array(
       "tables" => array("tblaimain", "tblcimain", "tblavmain", "tblcvmain", "tblart", "tblcart", "tblavlostdead", "tblcvlostdead", "tblavarv", "tblcvarv"  ),
       "instances" => array("DaControlAiMain", "DaControlCiMain", "DaControlAvMain", "DaControlCvMain", "DaControlART",
           "DaControlART", "DaControlAvLostDead", "DaControlCvLostDead", "DaControlArv", "DaControlArv" )  
     );
     
     foreach($elements["tables"] as $index => $table ){
       $instance = DaControlImport::getControlInstance( $table );
       $this->assertEquals(get_class($instance), $elements["instances"][$index]);
     }
   }
 
   public function testInvalidGetControlInstance(){
     $instance = DaControlImport::getControlInstance("not exist");
     $this->assertEquals($instance, null);
   }
   
 }