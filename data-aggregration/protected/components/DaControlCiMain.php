<?php
 class DaControlCiMain extends DaControl{
   /*
    * DateVisit should not be in year 1900
    * if OffYesNo = Yes --> Officein <> ''
    * if DateARV <> 1900 --> ARVNumber <> '' and it should be 10 digits
    */
    public $code = DaConfig::CTRL_EXCEPTION_CIMAIN ;
    
    /**
     * @throws DaInvalidControlException 
     */
    public function check($option=array()){
      $this->checkDateVisit();
      $this->checkOfficein();
      $this->checkDateARV();
    }
   
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateVisit(){
       $year = DaTool::getYear($this->row["DateVisit"]);
       if($year == "1900" ){
         throw new DaInvalidControlException("Invalid [DateVisit]. Year of [DateVisit] should not be 1900", $this->code);
       }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkOfficein(){
      if($this->row["OffYesNo"] == "Yes"){
        if($this->row["Officein"] == ""){
          throw new DaInvalidControlException("Invalid [Officein]. [Officein] should not be empty when [OffYesNo]= Yes", $this->code);
        }
      }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateARV(){
      $year = DaTool::getYear($this->row["DateARV"]);
      if($year == "1900"){
        $arv = trim($this->row["ARVNumber"]);
        if( $arv == "" )
          throw new DaInvalidControlException("Invalid [ARVNumber]. [ARVNumber] should not be empty ", $this->code);
        else{
          if(strlen($arv) == 10 )
            throw new DaInvalidControlException("Invalid [ARVNumber]  should be 9 characters length", $this->code);
        }
      }
    }
    
    
    
 }
