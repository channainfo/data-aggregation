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
      $yearError = new DaYearError($this->record["ARTDate"]);
      if($yearError->getErrorType() !== DaYearError::ERR_NONE){
        $this->addError("Invalid [ARTDate] : {$yearError}");
        return false;
      }
      return true;
    }
  
    /**
     * @return boolean 
     */
    public function checkARTNumber(){
      $artError = new DaARTError($this->record["ART"]);
      if($artError->getErrorType() != DaARTError::ERR_NONE){
        $this->addError("Invalid [ART] number {$artError} . ");
        return false;
      }
      return true ;
    }
 }