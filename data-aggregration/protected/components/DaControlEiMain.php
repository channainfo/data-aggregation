<?php
 /**
  * @todo check data type of OffYesNo
  */
 class DaControlEiMain extends DaControl{
   /*
    * DateVisit should not be in year 1900
    * if OffYesNo = Yes --> TransferIn <> ''
    * if DateARV <> 1900 --> ARVNumber <> '' and it should be 10 character
    */
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check($option=array()){
      return $this->checkDateVisit() && 
      $this->checkTransferIn() ;
      
    }
    
   
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateVisit(){
       $year = DaTool::getYear($this->record["DateVisit"]);
       if($year == "1900" ){
         $this->addError("Invalid [DateVisit]. Year of [DateVisit] should not be 1900");
         return false;
       }
       return true ;
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkTransferIn(){
      $offYesNo = new DaOffYesNo($this->record["OffYesNo"]);
      if($offYesNo->valid()){
        if($this->record["TransferIn"] == ""){
          $this->addError("Invalid [TransferIn]. [TransferIn] should not be empty when [OffYesNo]= {$offYesNo->value} ");
          return false;
        }
      }
      return true ;
    }
 }
