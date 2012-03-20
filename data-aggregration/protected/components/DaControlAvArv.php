<?php
 class DaControlAvArv extends DaControlArv{
   public $code = DaConfig::CTRL_EXCEPTION_CVARV;
   
   public function check($options=array()){
     $this->checkARV();
   }
 }