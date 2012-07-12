<?php
 class DaCheckerTest extends CDbTestCase {
   public function testUnder2Year(){
     $visits = array(
         array( "dob" => "2008-10-10" , "dateVisit" => "2010-10-10" , "result" => true ) ,
         array( "dob" => "2009-10-10" , "dateVisit" => "2009-09-09" , "result" => false ) , // imposible dob > datevisit 
         array( "dob" => "2010-10-10" , "dateVisit" => "2012-09-09" , "result" => true ) ,
         array( "dob" => "2005-10-10" , "dateVisit" => "2007-10-19" , "result" => false ) ,
         array( "dob" => "2005-10-10" , "dateVisit" => "2007-10-13" , "result" => false ) ,   
     );
     foreach($visits as $visit){
       $check = DaChecker::under2Year($visit["dob"], $visit["dateVisit"]);
       $this->assertEquals($check, $visit["result"]);
     }
   }
   
   public function testIsEmpty(){
     $elements = array(
         array( "OffTransferin" => "", "result" => true),
         array( "OffTransferin" => " ", "result" => true),
         array( "OffTransferin" => "TaKeo ", "result" => false),
         array( "OffTransferin" => " Kompongcham", "result" => false)
     ); 
     
     foreach($elements as $element) {
        $result = DaChecker::isEmpty($element["OffTransferin"]);
        //echo "\n result:{$result}-expected:{$element["result"]}" ;
        $this->assertEquals($result, $element["result"]);
     }
   }
   
   public function testOffYesNo(){
     $elements = array(
         array( "value" => "yes" , "result" => true ),
         array( "value" => " yes " , "result" => true ),
         array( "value" => " Yes" , "result" => true ),
         array( "value" => "YES " , "result" => true ),
         array( "value" => "yess" , "result" => false ),
         array( "value" => "No" , "result" => false ),
     );
     
     foreach($elements as $element){
       $result = DaChecker::offYesNo($element["value"]);
       //echo "\n result: {$result}-expected:{$element["result"]}";
       $this->assertEquals($result, $element["result"]);
       
     }
     
   }
   
   private function errMsg($errorType, $art){
     $errors = array(
         "p9" => "Invalid [ART] number for child: [ART]= ['{$art}'] for child should have 10 characters in length",
         "9"  => "Invalid [ART] number for adult: [ART]= ['{$art}'] should have 9 characters in length");
      return $errors[$errorType];    
         
   }
   
   
 }