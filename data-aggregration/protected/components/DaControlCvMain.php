<?php
 class DaControlCvMain extends DaControl {
   /**
    * ARTNum should be 10 digits 
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check(){
     $this->checkARTNum();
   }
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function checkARTNum(){
     $art = trim($this->row["ARTNum"]);
     if( strlen($art) != 10){
       throw new DaInvalidControlException("Invalid [ARTNum]. [ARTNum] should be 10 characters in length ");
     }
   }
   
 }