<?php
 class DaControlART extends DaControl{
  /**
   * Control ART number: 9 digits for adult and 10 digits for children
   * ART date should not be in year 1900 
   * +++ ART number for children : start with 'p'
   */ 
   public $code = DaConfig::CTRL_EXCEPTION_ART ;
   
   /**
    * @throws DaInvalidControlException 
    */
    public function check($option=array()) {
      $this->checkARTNumber();
    }
  
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkARTNumber(){
      $art = trim($this->record["ART"]);
      if(strtolower($art[0])== 'p'){
        $code =substr($art, 1);
        if(strlen($code) != 10){
          throw new DaInvalidControlException("Invalid [ART] number: [ART]= {$art} for child should have 10 characters in length ", $this->code);
        }
      }
      if(strlen($art) != 9){
        throw new DaInvalidControlException("Invalid [ART] number: [ART]={$art} should have 9 characters in length", $this->code);
      }
      
    }
 }