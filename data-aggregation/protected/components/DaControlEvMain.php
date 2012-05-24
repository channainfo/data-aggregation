<?php
 class DaControlEvMain extends DaControlVisitMain {
   /**
    *  DateVisit 1900
    *  Age count to last visit <= 2years
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($options=array()){
     return $this->checkDateVisit() && $this->checkAge($options["dob"]) ;
   }
   
   public function checkAge($dob){
     $under2Year =  DaChecker::under2Year($dob, $this->record["DateVisit"]);
     if(!$under2Year){
       $this->addError("Patient is not under 2 years old. [Date visit] : {$this->record["DateVisit"]}, [DOB] : {$dob}");
     }
     return $under2Year ;
   }
 }