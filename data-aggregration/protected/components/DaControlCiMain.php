<?php
 class DaControlCiMain extends DaControl{
   /*
    * DateVisit should not be in year 1900
    * if OffYesNo = Yes --> OfficeIn <> ''
    * if DateARV <> 1900 --> ARVNumber <> '' and it should be 10 digits
    */
    public $code = DaConfig::CTRL_EXCEPTION_CIMAIN ;
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check($option=array()){
      $this->checkDateVisit();
      $this->checkOfficeIn();
      $this->checkDateARV();
    }
   
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateVisit(){
       $year = DaTool::getYear($this->record["DateVisit"]);
       if($year == "1900" ){
         throw new DaInvalidControlException("Invalid [DateVisit]. Year of [DateVisit] should not be 1900", $this->code);
       }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkOfficeIn(){
      if($this->record["OffYesNo"] == "Yes"){
        if($this->record["OfficeIn"] == ""){
          throw new DaInvalidControlException("Invalid [OfficeIn]. [OfficeIn] should not be empty when [OffYesNo]= Yes", $this->code);
        }
      }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateARV(){
      $year = DaTool::getYear($this->record["DateARV"]);
      if($year == "1900"){
        $arv = trim($this->record["ARVNumber"]);
        if( $arv == "" )
          throw new DaInvalidControlException("Invalid [ARVNumber]. [ARVNumber] = ['{$arv}'] should not be empty ", $this->code);
        else{
          if(strlen($arv) == 10 )
            throw new DaInvalidControlException("Invalid [ARVNumber]. [ARVNumber] = ['{$arv}'] should be 9 characters length", $this->code);
        }
      }
    }
    
    
    
 }
