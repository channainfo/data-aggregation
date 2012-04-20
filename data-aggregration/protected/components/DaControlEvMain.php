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
     return DaChecker::under2Year($dob, $this->record["DateVisit"]);
   }
 }