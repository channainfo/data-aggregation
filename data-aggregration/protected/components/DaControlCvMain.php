<?php
 class DaControlCvMain extends DaControl {
   /**
    * ARTNum should be 10 digits 
    */
   public $code = DaConfig::CTRL_EXCEPTION_CVMAIN ;
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     $this->checkARTNum();
   }
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNum(){
     $art = trim($this->record["ARTNum"]);
     if( strlen($art) != 10){
       throw new DaInvalidControlException("Invalid [ARTNum] . [ARTNum] = ['{$art}'] should be 10 characters in length ", $this->code);
     }
   }
   
 }