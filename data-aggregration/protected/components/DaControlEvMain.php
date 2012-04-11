<?php
 class DaControlEvMain extends DaControl {
   /**
    * ARTNum should be 9 digits
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     return true ;
   }
 }