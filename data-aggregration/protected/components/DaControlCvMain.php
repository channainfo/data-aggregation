<?php
 class DaControlCvMain extends DaControlVisitMain {
   public function check($option=array()){
     return $this->checkARTNumber() && $this->checkDateVisit();
   }
 }