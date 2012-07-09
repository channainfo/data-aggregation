<?php
 class DaGenderErrorTest extends CTestCase {
   
   public function testErrorGender(){
     $elements = array(
        array("value" => "female", "result" => true),
        array("value" => "male", "result" => true), 
        array("value" => "Malee", "result" => true),
        array("value" => "", "result" => true),
        array("value" => "Male", "result" => false),
        array("value" => "Female", "result" => false),
        array("value" => " Male ", "result" => false),
     );
     foreach($elements as $element){
       $gender = new DaGenderError($element["value"]);
       $result = (bool)$gender->getErrorType();
       //echo "\nresult: {$result}, expected: {$element["result"]}" ;
       $this->assertEquals((bool)$result, $element["result"]);
     }
     
   }
 
 }