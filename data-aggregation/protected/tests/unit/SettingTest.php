<?php
 class SettingTest extends CTestCase {
   public function testSave(){
     $settings = array(
                        "table1" => array("col1" =>1, "col2" =>1 , "col3"=>1 , "col4" =>1, "col5" => 1),
                        "table2" => array("a1" =>1 , "a2" => 1, "a3" => 1, "a4" =>1, "a5" =>1)
         );
     Setting::save($settings);
     $loadedSettings = DaConfig::importSetting(false);
     
     $this->assertEquals(count($loadedSettings), 2);
     
     $this->assertEquals(isset($loadedSettings["table1"]), true);
     $this->assertEquals(isset($loadedSettings["table2"]), true);
     
     $this->assertEquals( $loadedSettings["table1"][0], "col1" );
     $this->assertEquals( $loadedSettings["table1"][1], "col2" );

     $this->assertEquals( $loadedSettings["table2"][0], "a1" );
     $this->assertEquals( $loadedSettings["table2"][1], "a2");

     $this->assertEquals(count($loadedSettings["table1"]), 5);
     $this->assertEquals(count($loadedSettings["table2"]), 5);
   }
 }