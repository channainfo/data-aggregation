<?php
 class DaControlAvMain extends DaControlVisitMain {
   /**
    *
    * @param CDbConnection $dbx
    * @return total of record found 
    */
   public function checkARTInARTTable($dbx){
      $art = trim($this->record["ARTNum"]);
      if(empty($art))
        return true;
      $clinicid = $this->record["ClinicID"];
      
      $sql = " SELECT  count(*) as total FROM tblart WHERE art = ?  AND clinicid = ? " ; 
      $command = $dbx->createCommand($sql);
      
      $command->bindParam(1, $art, PDO::PARAM_STR);
      $command->bindParam(2, $clinicid, PDO::PARAM_STR);
      
      $row = $command->queryRow();
      
      if(!$row["total"] ){
        $this->addError("[ARTNum]: {$art} does exist in table tblart");
        return false;
      }
      return true;
   }
   /**
    *
    * @param array $options
    * @return boolean true if the record is valid, false otherwise 
    */
   public function check($options=array()){
     return $this->checkARTNumber() && $this->checkDateVisit() && $this->checkARTInARTTable($options["dbx"]);
   }
 }