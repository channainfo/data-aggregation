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
     $yearError = new DaYearError($this->record["DateVisit"]);
     if($yearError->getErrorType() != DaYearError::ERR_NONE){
       $this->addError("[DateVisit] invalid: \"{$yearError}\" ");
       return false;
     }
     return true;
   } 
   
   public function checkARTNumber(){
     $art = trim($this->record["ARTNum"]);
     if($art == "")
       return true;
     $artError = new DaARTError($art);
     if($artError->getErrorType() != DaARTError::ERR_NONE){
       $this->addError("[ARTNum] invalid {$artError} .");
       return false;
     }
     return true ;
   }
}