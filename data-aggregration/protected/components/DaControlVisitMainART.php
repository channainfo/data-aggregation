<?php
 abstract  class DaControlVisitMainART extends DaControlVisitMain{
   public function checkARTNumber(){
     $art = trim($this->record["ARTNum"]);
     if($art == "")
       return true;
     try{
       DaChecker::artNum($this->record["ARTNum"]);
       return true ;
     }
     catch(Exception $ex){
       $this->addError($ex->getMessage());
       return false ;
     }
   }
 }