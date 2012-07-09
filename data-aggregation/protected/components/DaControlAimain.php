<?php
 class DaControlAiMain extends DaControl{   
    /**
     * Table: tblAImain
     * DateFirstVisit should not be in year 1900
     * OffYesNo = Yes --> OffTranserin <> ''
     * DateStaART <> 1900 --> ArtNumber <> '' and it should be 9 digits
    */
    
    /**
     *
     * @param array $options an associative array contain a key  "dbx" with value of CDbConnection object  
     * @return boolean check(array("dbx"=> $dbx));
     */
    public function check($options=array()) {
       return $this->checkDateFirstVisit() && $this->checkGender() &&  $this->checkTranIn($options["dbx"]) ;
    }
    
    public function checkGender(){
      $gender = new DaGenderError($this->record["Sex"]);
      if($gender->getErrorType() != DaGenderError::ERR_NONE){
        $this->addError("[tblaimain] invalid sex {$gender}. ");
        return false;
      }
      return true;
    }
    
    /**
     *
     * @param CDbConnection $dbx
     * @return boolean 
     */
    public function checkTranIn($dbx){
      if(DaChecker::offYesNo($this->record["OffYesNo"])){
        
        if(trim($this->record["OffTransferin"]) == ""){
          $this->addError("Invalid transferin. [OffYesNo=Yes] so OffTransferin should not be empty ");
          return false ;
        }
        
        $yearError =  new DaYearError($this->record["DateStaART"]);
        if($yearError->getErrorType() == DaYearError::ERR_1900 ){
          $this->addError("DateStaART should not be in year 1900");
          return false ;
        }
        
        $artError = new DaARTError($this->record["ArtNumber"]);
        if($artError->getErrorType() == DaARTError::ERR_ADULT){ 
          $this->addError("Invalid [ART] number for adult: [ART]= ['{$artError->getART()}'] should have 9 characters in length");
          return false;
        }
        else if($artError->getErrorType() == DaARTError::ERR_CHILD){
          $this->addError("Invalid [ART] number for child: [ART]= ['{$artError->getART()}'] for child should have 10 characters in length") ;
          return false;
        }
        else if($artError->getErrorType() == DaARTError::ERR_NONE) ;
        return $this->checkARTExistence($dbx);
      }
      return true ;
    }
   /**
    *
    * @param CDbConnection $dbx 
    */ 
   public function checkARTExistence($dbx){
      $art = $this->record["ArtNumber"] ;
      $clinicid = DaRecordReader::getIdFromRecord("tblaimain", $this->record); 
      
      if( !$this->existARTInART($dbx, $art) ){
        $this->addError("ArtNumber: ". $art. " does not exist in tblart with CLinicId: ".$clinicid);
        return false ;
      }
      
      if(!$this->existARTInAvMain($dbx, $art, $clinicid)){
        $this->addError("ArtNumber: ". $art. " does not exist in tblavmain with CLinicId: ".$clinicid);
        return false ;
      }
      
      return true ;
   } 
   
   public function existARTInART($dbx, $art){
      $sql = " SELECT  count(*) as total FROM tblart WHERE art = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $art, PDO::PARAM_STR);
      $row = $command->queryRow();
      return $row["total"];
   }
   
   public function existARTInAvMain($dbx, $art, $clinicid){
     $sql = " SELECT  count(*) as total FROM tblavmain WHERE clinicid = ? AND artnum = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $clinicid, PDO::PARAM_STR);
      $command->bindParam(2, $art, PDO::PARAM_STR);
      $row = $command->queryRow();
      return $row["total"];
   }
    /**
     *
     * @throws DaInvalidControlException 
     */   
    public function  checkDateFirstVisit(){
       $yearError = new DaYearError($this->record["DateFirstVisit"]);
       if($yearError->getErrorType() == DaYearError::ERR_1900){
         $this->addError("Invalid [DateFirstVisit]. Year of [DateFirstVisit] should not be 1900 ");
         return false ;
       }
       return true ;
    }
    
 }
