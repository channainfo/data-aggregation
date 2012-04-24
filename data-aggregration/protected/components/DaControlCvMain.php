<?php
 class DaControlCvMain extends DaControlVisitMainART {
   public function check($option=array()){
     return $this->checkARTNumber() && $this->checkDateVisit();
   }
 }