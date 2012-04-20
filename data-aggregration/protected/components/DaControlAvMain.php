<?php
 class DaControlAvMain extends DaControlVisitMainART {
   /**
    * ARTNum should be 9, 10 digits or empty
    * p9digits
    * Al VistiMain
    */
   
   /**
    *
    * @throws DaInvalidControlException 
    */
   public function check($option=array()){
     return $this->checkARTNumber() && $this->checkDateVisit();
   }
 }