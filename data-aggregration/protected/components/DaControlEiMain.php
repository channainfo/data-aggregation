<?php
 /**
  * @todo check data type of OffYesNo
  */
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
       try{
         DaChecker::dateVisit($this->record["DateVisit"]);
         return true;
       }
       catch(Exception $ex){
         $this->addError($ex->getMessage());
         return false;
       }
    }
 }
