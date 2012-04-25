<?php
 class DaControlAiMain extends DaControl{   
    /**
     * Table: tblAImain
     * DateFirstVisit should not be in year 1900
     * OffYesNo = Yes --> OffTranserin <> ''
     * DateStaART <> 1900 --> ArtNumber <> '' and it should be 9 digits
    */
    
    /**
     * @throws  DaInvalidControlException
     */
    public function check($options=array()) {
       return $this->checkDateFirstVisit() &&  $this->checkTranIn() &&  $this->checkDateStartART();
    }
    /**
     * 
     * @throws DaInvalidControlException 
     */
    public function checkTranIn(){
      if(DaChecker::offYesNo($this->record["OffYesNo"])){
        if($this->record["OffTransferin"] == ""){
          $this->addError("Invalid transferin. [OffYesNo=Yes] so OffTransferin should not be empty ");
          return false ;
        }
      }
      return true;
    }
    /**
     *
     * @throws DaInvalidControlException 
     */   
    public function  checkDateFirstVisit(){
       $year = DaTool::getYear($this->record["DateFirstVisit"]);
       if($year == "1900"){
         $this->addError("Invalid [DateFirstVisit]. Year of [DateFirstVisit] should not be 1900 ");
         return false ;
       }
       return true ;
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateStartART(){
      $valid = true;
      $year = DaTool::getYear($this->record["DateStaART"]);
      if($year != "1900" ){
        if($this->record["ArtNumber"] == ""){
          $this->addError("[ArtNumber] could not be empty ");
          $valid = false;
        }
           
        else{
          $startART = trim($this->record["ArtNumber"]);
          if(strlen($startART) != 9){
            $this->addError("[ArtNumber] must be 9 character in length: [ArtNumber] = ['{$startART}']");
            $valid = false ;
          }
        }
      }
      return $valid;
    }
    
    
    
 }
