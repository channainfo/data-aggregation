<?php
 class DaControlART extends DaControl{
  /**
   * Control ART number: 9 digits for adult and 10 digits for children
   * ART date should not be in year 1900 
   * +++ ART number for children : start with 'p'
   */ 
   public $code = DaConfig::CTRL_EXCEPTION_ART ;
   public $key = "ART";
   public $index = 2 ;

   
   /**
    * @throws DaInvalidControlException 
    */
    public function check($option=array()) {
      return $this->checkARTNumber();
    }
  
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkARTNumber(){
      $valid = true ;
      $art = trim($this->record["ART"]);
      if(strtolower($art[0])== 'p'){
        $code =substr($art, 1);
        if(strlen($code) != 9){
          $this->addError("Invalid [ART] number for child: [ART]= ['{$art}'] for child should have 10 characters in length ");
          $valid = false ;
        }
      }
      else if(strlen($art) != 9){
        $this->addError("Invalid [ART] number for adult: [ART]= ['{$art}'] should have 9 characters in length");
        $valid = false ;
      }
      return $valid ;
    }
 }