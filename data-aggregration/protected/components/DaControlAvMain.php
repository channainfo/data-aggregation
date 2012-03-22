<?php
 class DaControlAvMain extends DaControl {
   public $code = DaConfig::CTRL_EXCEPTION_AVMAIN ;
   /**
    * ARTNum should be 9 digits
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
     $number = trim($this->record["ARTNum"]);
     if(strlen($number) != 9){
       throw new DaInvalidControlException("Invalid [ARTNum] number . [ARTNum] = ['{$number}'] should be 9 characters in length", $this->code);
     }
   }
 }