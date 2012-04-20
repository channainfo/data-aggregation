<?php
abstract class DaControlVisitMain extends DaControl {
   /**
    * ARTNum should be 9, 10 digits or empty
    * p9digits
    * Al VistiMain
    */
   /**
    *
    * @throws DaInvalidControlException 
    */   
   public function checkDateVisit(){
     
     try{
       DaChecker::dateVisit($this->record["DateVisit"]);
       return true ;
     }
     catch(Exception $ex){
       $this->addError($ex->getMessage());
       return false ;
     }
   }
}