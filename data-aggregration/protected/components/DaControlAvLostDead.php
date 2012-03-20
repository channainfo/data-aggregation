<?php
 class DaControlAvLostDead extends DaControlLostDead {
   public $code = DaConfig::CTRL_EXCEPTION_AVLOSTDEAD;
   
   /**
    *
    * @throws DaInvalidControlException
    * @param array $option 
    */
   public function check($option=array()) {
     $this->checkLDDate();
   }
 }