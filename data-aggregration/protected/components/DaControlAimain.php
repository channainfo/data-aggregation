<?php
 class DaControlAiMain extends DaControl{
    public $code = DaConfig::CTRL_EXCEPTION_AIMAIN ;
    /**
     * Table: tblAImain
     * dateFirstVisit should not be in year 1900
     * OffYesNo = Yes --> OffTranserin <> ''
     * DateStaART <> 1900 --> ArtNumber <> '' and it should be 9 digits
     */
    
    /**
     * @throws  DaInvalidControlException
     */
    public function check($option=array()) {
      $this->checkDateFirstVisit();
      $this->checkTranIn();
      $this->checkDateStartART();
    }
    

    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkTranIn(){
      if($this->row["OffYesNo"] == "Yes"){
        if($this->row["OffTranserin"] == ""){
          throw new DaInvalidControlException("Invalid transferin. [OffYesNo=Yes] so OffTranserin should not be empty ", $this->code);
        }
      }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */   
    public function  checkDateFirstVisit(){
       $year = DaTool::getYear($this->row["dateFirstVisit"]);
       if($year == "1900"){
         throw new DaInvalidControlException("Invalid [dateFirstVisit]. Year of [dateFirstVisit] should not be 1900 ", $this->code);
       }
    }
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkDateStartART(){
      $year = DaTool::getYear($this->row["DateStaART"]);
      if($year != "1900" ){
        if($this->row["ArtNumber"] == "")
          throw new DaInvalidControlException("[ArtNumber] could not be empty ", $this->code);
        else{
          $startART = trim($this->row["ArtNumber"]);
          if(strlen($startART) != 9)
            throw new DaInvalidControlException("[ArtNumber] must be 9 character in length: [ArtNumber]({$startART})", $this->code);
        }
      }
    }
    
    
    
 }
