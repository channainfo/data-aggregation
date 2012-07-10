<?php
 class DaControlCvMain extends DaControlVisitMain {
   /**
    *
    * @param CDbConnection $dbx 
    * @return boolean if the record exist in tblcart
    */
   public function checkARTInCARTTable($dbx){
      $art = trim($this->record["ARTNum"]);
      if(empty($art))
        return true ;
      
      $clinicid = $this->record["ClinicID"];
      $sql = " SELECT  count(*) as total FROM tblcart WHERE art = ?  AND clinicid = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $art, PDO::PARAM_STR);
      $command->bindParam(2, $clinicid, PDO::PARAM_STR);
      
      $row = $command->queryRow();
      if(!$row["total"]){
        $this->addError("[ARTNum] {$art} does not exist in tblcart .");
        return false;
      }
      return true ;
   }
   /**
    *
    * @param type $options
    * @return type 
    */
   public function check($options=array()){
     return $this->checkARTNumber() && $this->checkDateVisit() && $this->checkARTInCARTTable($options["dbx"]);
   }
 }