<?php
 class DaControlAvMain extends DaControl {
   public $code = DaConfig::CTRL_EXCEPTION_AVMAIN ;
   public $key = "AV_ID";
   public $index = 85 ;
   /**
    * ARTNum should be 9 digits
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     return $this->checkARTNumber();
   }
     
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNumber(){
     $number = trim($this->record["ARTNum"]);
     if(strlen($number) != 9){
       $this->addError("Invalid [ARTNum] number . [ARTNum] = ['{$number}'] should be 9 characters in length");
       return false;
     }
     return true ;
   }
 }