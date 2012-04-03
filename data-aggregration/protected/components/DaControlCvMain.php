<?php
 class DaControlCvMain extends DaControl {
   /**
    * ARTNum should be 10 digits 
    */
   public $code = DaConfig::CTRL_EXCEPTION_CVMAIN ;
   public $key = "Cid" ;
   public $index = 80 ;
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     return $this->checkARTNum();
   }
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNum(){
     $art = trim($this->record["ARTNum"]);
     if( strlen($art) != 10){
       $this->addError("Invalid [ARTNum] . [ARTNum] = ['{$art}'] should be 10 characters in length ");
       return false;
     }
     return true ;
   }
   
 }