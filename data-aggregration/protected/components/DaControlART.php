<?php
 class DaControlART extends DaControl{
  /**
   * Control ART number: 9 digits for adult and 10 digits for children
   * ART date should not be in year 1900 
   * ART number for children : start with 'p'
   */ 

   
   /**
    * @throws DaInvalidControlException 
    */
    public function check($option=array()) {
      return $this->checkARTNumber() && $this->checkARTDate();
    }
    
    public function checkARTDate(){
      $valid = true ;
      try{ DaChecker::dateVisit($this->record["ARTDate"]);
      }
      catch(Exception $ex){
        $valid = false;
        $this->addError($ex->getMessage());
      }
      return $valid ;
    }
  
    /**
     * @return boolean 
     */
    public function checkARTNumber(){
      $valid = true ;
      try{
        $valid = DaChecker::artNum($this->record["ART"]);
      }
      catch(Exception $ex){
        $valid = false ;
        $this->addError($ex->getMessage());
      }
      return $valid ;
    }
 }