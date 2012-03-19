<?php
 class DaControlAvMain extends DaControl {
   public $code = 20 ;
   /**
    * ART number should be 9 digits
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check(){
     $this->checkARTNumber();
   }
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNumber(){
     $number = trim($this->row["ART"]);
     if(strlen($number) != 9){
       throw DaInvalidControlException("Invalid [ART] number. [ART] number should be 9 characters in length");
     }
   }
 }