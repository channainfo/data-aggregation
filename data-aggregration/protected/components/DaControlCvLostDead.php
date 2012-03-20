<?php
 class DaControlCvLostDead extends DaControlLostDead {
   public $code = DaConfig::CTRL_EXCEPTION_CVLOSTDEAD;
   
   /**
    *
    * @throws DaInvalidControlException
    * @param array $option 
    */
   public function check($option=array()) {
     $this->checkLDDate();
   }
 }