<?php
 class DaControlEiMain extends DaControl{
   /*
    * DateVisit should not be in year 1900
    */
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check($option=array()){
      return $this->checkDateVisit() ;
    }
   
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
 }
