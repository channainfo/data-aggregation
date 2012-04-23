<?php
 class DaCheckerTest extends CDbTestCase {
   
   public function testUnder2Year(){
     $visits = array(
         array( "dob" => "2008-10-10" , "dateVisit" => "2010-10-10" , "result" => true ) ,
         array( "dob" => "2009-10-10" , "dateVisit" => "2009-09-09" , "result" => true ) ,
         array( "dob" => "2010-10-10" , "dateVisit" => "2012-09-09" , "result" => true ) ,
         array( "dob" => "2005-10-10" , "dateVisit" => "2007-12-12" , "result" => false ) ,
         array( "dob" => "2005-10-10" , "dateVisit" => "2007-10-11" , "result" => false ) ,
         
         
     );
     foreach($visits as $visit){
       $check = DaChecker::under2Year($visit["dob"], $visit["dateVisit"]);
       $this->assertEquals($check, $visit["result"]);
     }
   }
   public function testArtError(){ 
     $arts = array( array("art" => "p123456789", "error" => "" ), 
                    array("art" => "123456789" , "error" => ""), 
                    array("art" => "p1234567890", "error" => $this->errMsg("p9", "p1234567890") ), 
                    array("art" => "12345678900", "error" => $this->errMsg("9","12345678900"))); 
     
     foreach($arts as $art){
       $message =DaChecker::artError($art["art"]);
       $this->assertEquals($art["error"], $message);
     }
   }
   /**
    * @expectedException Exception 
    */
   public function testArtNum(){
     DaChecker::artNum("90998009888");  
   }
   private function errMsg($errorType, $art){
     $errors = array(
         "p9" => "Invalid [ART] number for child: [ART]= ['{$art}'] for child should have 10 characters in length",
         "9"  => "Invalid [ART] number for adult: [ART]= ['{$art}'] should have 9 characters in length");
         
      return $errors[$errorType];    
         
   }
   
   
 }