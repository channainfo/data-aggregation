<?php
 class DaControlImportTest extends CDbTestCase {
   public function testValidGetControlInstance(){
     $elements = array(
         array("tblaimain", "DaControlAiMain"),
         array("tblcimain", "DaControlCiMain"),
         array("tblavmain", "DaControlAvMain"),
         array("tblcvmain", "DaControlCvMain"),
         array("tblart", "DaControlART"),
         array("tblcart", "DaControlART"),
         array("tblavlostdead", "DaControlAvLostDead"),
         array("tblcvlostdead", "DaControlCvLostDead"),
         array("tblavarv", "DaControlArv"),
         array("tblcvarv", "DaControlArv"),
         array("tblevarv", "DaControlArv"),
     );
     
     foreach($elements as $element ){
       $instance = DaControlImport::getControlInstance( $element[0] );
       $this->assertEquals(get_class($instance), $element[1]);
     }
   }
 
   public function testInvalidGetControlInstance(){
     $instance = DaControlImport::getControlInstance("not exist");
     $this->assertEquals($instance, null);
   }
   
 }