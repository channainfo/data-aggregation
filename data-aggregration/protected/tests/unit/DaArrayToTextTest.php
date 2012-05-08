<?php
 class DaArrayToTextTest extends CDbTestCase {
   public $data ;
   
   public function setUp() {
     $this->data = array(
         "elem1" => array("field1" => "value1", "field2" => "value2", "field3" => "value3"),
         "elem2" => array("field4" => "value4", "field5" => "value5", "field6" => "value6"),
         "elem3" => array("field7" => "value7", "field8" => "value8", "field9" => "value9"),
         "elem4" => array("field11" => "value11", "field12" => "value12", "field13" => "value13"),
     );
     return parent::setUp();
   }
   
   public function testPrepareContent(){
     $ini = new DaArrayToText($this->data);
     
     $content = $ini->prepareContent(array("field1" => "value1", "field2" => "value2", "field3" => "value3"));
     $this->assertEquals($content, "field1=value1\nfield2=value2\nfield3=value3\n") ;
     
     $content = $ini->prepareContent(array("elm1" => array("field1" => "value1", "field2" => "value2", "field3" => "value3")));
     $this->assertEquals($content, "[elm1]\n\tfield1=value1\n\tfield2=value2\n\tfield3=value3\n") ;
     
     $content = $ini->prepareContent(array( "elem1" => array("field1" => "value1", "field2" => "value2", "field3" => "value3"),
                                            "elem2" => array("field2" => "value2", "field3" => "value3", "field4" => "value4")
                                     ));
     $this->assertEquals($content, "[elem1]\n\tfield1=value1\n\tfield2=value2\n\tfield3=value3\n[elem2]\n\tfield2=value2\n\tfield3=value3\n\tfield4=value4\n") ;
     $content = $ini->prepareContent(array("elem1" => array("field1" => "value1", "field2" => "value2"),
                                           "elem2" => "value2",
                                           "elem3" => array(
                                                            "data1" => array("sub1" => "value1"), 
                                                            "data2" => array("sub2"=> array("value2" => "v")))
                                       ));
     $this->assertEquals($content, "[elem1]\n\tfield1=value1\n\tfield2=value2\nelem2=value2\n[elem3]\n\t[data1]\n\t\tsub1=value1\n\t[data2]\n\t\t[sub2]\n\t\t\tvalue2=v\n") ;
   }
   
 
   
   
   
   
 }