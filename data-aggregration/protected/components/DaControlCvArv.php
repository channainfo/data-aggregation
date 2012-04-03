<?php
 class DaControlCvArv extends DaControlArv{
   public $code = DaConfig::CTRL_EXCEPTION_AVARV;
   
   public function check($options=array()){
     return $this->checkARV();
   }
 }