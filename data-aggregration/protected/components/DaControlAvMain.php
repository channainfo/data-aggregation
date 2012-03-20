<?php
 class DaControlAvMain extends DaControl {
   public $code = DaConfig::CTRL_EXCEPTION_AVMAIN ;
   /**
    * ART number should be 9 digits
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     $this->checkARTNumber();
   }
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNumber(){
     $number = trim($this->row["ART"]);
     if(strlen($number) != 9){
       throw DaInvalidControlException("Invalid [ART] number. [ART] number should be 9 characters in length", $this->code);
     }
   }
 }