<?php
  abstract class DaControlLostDead extends DaControl {
    /**
     * Table: tblAvLostDead, tblCvLostDead
     * LDdate should not be in year 1900
     * For a patient who has 2 status Lost and Dead. the lost status date should be before the dead date. 
     */
    public $options = array();
    public $sql = "" ;
    public $clinicId = "";
    public $errorRecords = array() ;
    public $error = false;
    public $warning = false;

    public function check($options=array()) {
      $this->warning = false ;
      $dbx = $options["dbx"];
      $clinicid = $options["clinicid"];
      $valid = $this->checkLDDate() && $this->checkLostDead($dbx, $clinicid);
      if(!$valid)
        $this->loadRecords ($dbx, $clinicid );
      return $valid;
    } 
    
    public function isWarning(){
      return $this->warning;
    }
    /**
     * @throws DaInvalidControlException 
     */
    public function checkLDDate(){
     $yearError = new DaYearError($this->record["LDdate"]);
     if($yearError->getErrorType() != DaYearError::ERR_NONE){
        $tableName = $this->tableName();
        $this->addError(" Invalid [LDDate] = {$this->record["LDdate"]} in [{$tableName}] should not be in 1900 ");
        return false;
     }
     return true ;
    }
    /**
    * Lost and dead need to check  checkLostDead
    * @param CDbConnection $db
    * @return boolean
    * @throws DaInvalidControlException 
    */
    public function checkLostDead($dbx, $clinicid){
      $valid = true ;
      $this->loadErrorLostDead($dbx, $clinicid);
      $tableName = $this->tableName();
      if($this->error){
        $status = trim($this->record['Status']) ;
        $this->addError( " Invalid {$tableName}. [Date] = '{$this->record["LDdate"]}'  , [Status] = '{$status}'" );
        $valid = false;
        $this->warning = true ;
      }
      return $valid;
    }
    
    /**
    *
    * @param CDbConnection $dbX
    * @param integer $clinic
    * @return array
    */
    public function loadErrorLostDead($dbX, $clinicId){
     if( $this->clinicId == $clinicId && !empty($clinicId)  ){
       //DaTool::p("cached error for clinicid : {$clinicId}");
       return $this->error;
     }
    
     //DaTool::p("uncached error for clinicid : {$clinicId}");
     $this->clinicId = $clinicId ;
     $tableName = $this->tableName();
     
     $sqlDeadFirst = " ( SELECT *  FROM {$tableName} as L1 WHERE L1.status = 'dead' AND clinicid='{$this->clinicId}' ) ";
     $sqlLostLast  = " ( SELECT *  FROM {$tableName} as L2 WHERE L2.status = 'lost' AND clinicid='{$this->clinicId}' ) ";
          
     $sql = " SELECT Lost.clinicid  FROM "
          . "\n {$sqlLostLast} AS Lost , {$sqlDeadFirst} AS Dead "
          . "\n WHERE Lost.lddate > Dead.lddate "
          . "\n AND Lost.clinicid = Dead.clinicid  "
          . "\n GROUP BY Lost.clinicid "   
          ;
     $this->sql = $sql ;     
     $error = $dbX->createCommand($this->sql)->queryRow();
     $this->error = !empty($error);
     return $this->error ;
   }
   
  public function recorsdError() {
    return $this->errorRecords ;
  } 
   /**
    *
    * @param CDbConnection $dbX
    * @param string $clinicId 
    */
   public function loadRecords($dbX,$clinicId){
      if($this->clinicId == $clinicId && !empty($clinicId) ){
        //DaTool::p("cached load records for clinicid: {$clinicId}");
        return $this->errorRecords ;
      }
      //DaTool::p("uncached load records for clinicid: {$clinicId}");
      $this->clinicId = $clinicId ;
      $tableName = $this->tableName() ;
      
      $sql = "SELECT * FROM {$tableName} WHERE clinicid = ? " ;
      $command = $dbX->createCommand($sql);
      $command->bindParam(1, $clinicId, PDO::PARAM_STR);
      $this->errorRecords = $command->queryAll();
      return $this->errorRecords ;
   }
   
   public function getErrQuery(){
     return $this->sql ;  
   }
   public abstract function visitIdName();
   public abstract function tableName();
    
  }
