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
      return $this->checkDateVisit() &&  $this->checkOfficeIn($option["dbx"]) ;
    }

    public function checkDateVisit(){
      $year = DaTool::getYear($this->record["DateVisit"]);
      if($year == "1900"){
        $this->addError("Invalid [DateVisit]: ". $this->record["DateVisit"]. "[DateVisit] should not be 1900" );
        return false;
      }
      return true ;
    }
    
    /**
     *
     * @throws DaInvalidControlException 
     */
    public function checkOfficeIn($dbx){
      if(DaChecker::offYesNo($this->record["OffYesNo"])){
        
        if(trim($this->record["OfficeIn"]) == ""){
          $this->addError("Invalid [OfficeIn]. [OfficeIn] should not be empty when [OffYesNo]= Yes");
          return false;
        }
        
        $yearError = new DaYearError($this->record["DateARV"]);
        if($yearError->getErrorType() == DaYearError::ERR_1900){
          $this->addError("Invalid [DateARV]. [DateARV] should not be 1900");
          return false;
        }

        $artError = new DaARTError($this->record["ARVNumber"]);
        if($artError->getErrorType() != DaARTError::ERR_NONE){ 
          $this->addError("Invalid [ARVNumber] :".$artError->getART());
          return false;
        }
        return $this->checkExistenceARV($dbx);
        
      }
      return true ;
    }
    
    public function checkExistenceARV($dbx){
      $art = $this->record["ARVNumber"] ;
      $clinicid = DaRecordReader::getIdFromRecord("tblcimain", $this->record); 
      
      if( !$this->existARVInCART($dbx, $art) ){
        $this->addError("[tblcart] ARVNumber: ". $art. " does not exist in tblcart with ClinicId: ".$clinicid);
        return false ;
      }
      
      if(!$this->existARVInCvMain($dbx, $art, $clinicid)){
        $this->addError("[tblcvmain] ARVNumber: ". $art. " does not exist in tblcvmain with ClinicId: ".$clinicid);
        return false ;
      }
      
      return true ;
    }
    
    public function existARVInCART($dbx, $art){
      $sql = " SELECT  count(*) as total FROM tblcart WHERE art = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $art, PDO::PARAM_STR);
      $row = $command->queryRow();
      return $row["total"];
   }
   
   public function existARVInCvMain($dbx, $art, $clinicid){
     $sql = " SELECT  count(*) as total FROM tblcvmain WHERE clinicid = ? AND ARTNum = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $clinicid, PDO::PARAM_STR);
      $command->bindParam(2, $art, PDO::PARAM_STR);
      $row = $command->queryRow();
      return $row["total"];
   }

    
    
    
 }
