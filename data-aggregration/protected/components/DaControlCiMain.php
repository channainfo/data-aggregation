<?php
 //@todo : OffYesNo
 class DaControlCiMain extends DaControl{
   /*
    * DateVisit should not be in year 1900
    * if OffYesNo = Yes --> OfficeIn <> ''
    * if DateARV <> 1900 --> ARVNumber <> '' and it should be 10 character
    */
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check($option=array()){
      return $this->checkDateVisit() && 
      $this->checkOfficeIn() &&
      $this->checkDateARV();
      
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
    public function checkOfficeIn(){
      if(DaChecker::offYesNo($this->record["OffYesNo"])){
        if($this->record["OfficeIn"] == ""){
          $this->addError("Invalid [OfficeIn]. [OfficeIn] should not be empty when [OffYesNo]= Yes");
          return false;
        }
      }
      return true ;
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateARV(){
      $valid = true ;
      $year = DaTool::getYear($this->record["DateARV"]);
      if($year != "1900"){
        $arv = trim($this->record["ARVNumber"]);
        if( $arv == "" ){
          $this->addError("Invalid [ARVNumber]. [ARVNumber] = ['{$arv}'] should not be empty ");
          $valid = false ;
        }
        else{
          if(strlen($arv) != 10 ){
            $this->addError("Invalid [ARVNumber]. [ARVNumber] = ['{$arv}'] should be 10 characters length");
            $valid = false;
          }
        }
      }
      return $valid ;
    }
    
    
    
 }
