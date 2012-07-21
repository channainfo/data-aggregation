<?php
  /**
  * @property string $code
  * @property CDbConnection $dbX
  * @property CDbConnection $db
  * @property SiteConfig $siteconfig
  * @property integer $start
  * @property integer $end 
  * @property integer $rejectCount
  * @property integer $recordErrors An array of in the form  recordErrors[patienTable][] = $recordCasueError
  * @property integer $controlErrors  An array contain value of error of control $control->getErrors(), $error[] = "errorMessage"
  * @property integer $currentPatientRecord current record of patient being imported
  * @property integer $currentPatientTable current patient table being imported
  * @property integer $patientIter current number of patient being imported
  * @property integer $patientTotal total number of patient
   
  * @property CDbTransaction $transaction
  *  
  */

  class DaImportSequence {
   public $transaction = null ; 
   public $db = null ;
   public $dbX = null;
   public $siteconfig = null;
   
   public $start = null;
   public $end = null ;
   
   public $rejectCount = 0;
   public $recordErrors = array();
   public $controlErrors = array();
   
   public $currentPatientRecord ;
   public $currentPatientTable ;
   
   private $patientIter;
   private $patientTotal=array();
   
   private $patientIdTemp ;
     
   public function __destruct(){
     $this->dbX->setActive(false);
   }
   
   public function __construct($db, $code ) {
     $this->db = $db ;
     $this->_loadSiteConfig($code);
     $this->_loadDbX($code);
   }
   /**
    *
    * @return CDbConnection 
    */
   public function getDbX(){
     return $this->dbX;
   }
      
   /**
    *
    * @return SiteConfig 
    * @throws Exception 
    */ 
   protected function _loadSiteConfig($code){
     if($this->siteconfig)
       return $this->siteconfig;
     
     $siteconfig = SiteConfig::model()->findByAttributes(array("code" => $code));
     
     if(!$siteconfig)
       throw new DaInvalidSiteException("Could not find any site matched with code : {$code}");
     
     $this->siteconfig = $siteconfig;
     global $_siteconfig;
     $_siteconfig = $siteconfig ;
     return $this->siteconfig ;
   }
   /**
     *
     * @return CDbConnection
     * @throws DaInvalidSiteException, DaInvalidSiteException 
     */
   protected function _loadDbX($code){
      if($this->dbX ){
        return $this->dbX;
      }
      $siteconfig = $this->_loadSiteConfig($code);
      $this->dbX = DaDbMsSqlConnect::connect($siteconfig->attributes["host"], $siteconfig->attributes["db"],
              $siteconfig->attributes["user"], $siteconfig->attributes["password"]);
      return $this->dbX;
    }
    
    protected function isTableExistInMssql($tableName){
      return DaSqlHelper::isTableExistInMssql($tableName, $this->dbX);
    }

    protected function _startImporting(){
      $this->start = microtime(true);
      $siteconfig = $this->siteconfig;
      
      if($siteconfig->lastImport()){

        if($siteconfig->lastImport()->status == ImportSiteHistory::PENDING )
          throw new DaInvalidStatusException("Site {$siteconfig->code} import in progress");
        else if($siteconfig->lastImport()->status != ImportSiteHistory::START ){
          throw new DaInvalidStatusException("Site {$siteconfig->code} import in had been finished with status: " . 
                  $siteconfig->lastImport()->getStatusText(). " On date : " . " [ {$siteconfig->lastImport()->modified_at} ]" );
        }
        
        $import = $siteconfig->lastImport();
        $import->status = ImportSiteHistory::PENDING;
        
        $import->save();
        
        $siteconfig->status = SiteConfig::PENDING ;
        $siteconfig->save();
        
        DaDbHelper::startIgnoringForeignKey($this->db);
        
      }
      else
        throw new DaInvalidStatusException("Could not find any import");
   }
   /**
     *
     * @param integer $status
     * @return boolean 
     */
   protected function _endImporting($status, $reason="" ){
      $this->end = microtime(true);
      $siteconfig = $this->siteconfig;
      
      $duration = $this->end - $this->start ;
      
      $import = $siteconfig->lastImport(false);
      $import->status = $status ;
      $import->duration = $duration;
      $import->reason = $reason;
      $import->info = serialize($this->patientTotal);
      $import->save();
      
      //tell site status and last imported
      $siteconfig->last_imported = DaDbWrapper::now();
      $siteconfig->status = $status ;
      $siteconfig->save();
      
      
      DaDbHelper::endIgnoringForeignKey($this->db);
   }
   public function start(){
     try{
        DaTool::hp("Importing site: {$this->siteconfig->code}, {$this->siteconfig->name}");

        $this->_startImporting();
        $this->importTablesFixed();
        
        $this->importIMain("tblaimain");
        $this->importIMain("tblcimain");
        
        if($this->isTableExistInMssql("tbleimain"))
          $this->importIMain("tbleimain");
        
        $this->_endImporting(ImportSiteHistory::SUCCESS);
     }
     catch(DaInvalidStatusException $ex){
        DaTool::debug($ex->getTraceAsString(),0,0);
     }
     catch(DaInvalidDbException $ex){
       DaTool::debug($ex->getTraceAsString(),0,0);
       $this->_endImporting(ImportSiteHistory::FAILED, $ex->getMessage());
     }
     catch(Exception $ex){
       DaTool::debug($ex->getTraceAsString(),0,0);
       $this->_endImporting(ImportSiteHistory::FAILED, $ex->getMessage());
     }
   }
   /**
    *
    * @throws Exception 
    */
   public function importTablesFixed(){
     $configs = DaConfig::importConfig();
     $fixedTables = $configs["fixed"];
     
     foreach($fixedTables as $table => $cols){
        $this->_importTableFixed($table, $cols);
     }
   }
   /**
    *
    * @param string $table
    * @param array $cols
    * @throws CDbException 
    */
   protected function _importTableFixed($table, $cols){
     DaTool::pln("Importing : {$table}");
     $total = DaDbHelper::countRecord($this->dbX, $table);
     $quantUpdate = $this->getRandomRecordUpdate();
     
     $s = microtime(true);
     $sqlX = " SELECT * FROM {$table} ";
     //$commandX =  $this->dbX->createCommand($sqlX);
     $dataReaderX = $this->getRecordReader($sqlX);
     
     $insertSql = DaSqlHelper::sqlFromTableCols($table, $cols);
     $commandInsert = $this->db->createCommand($insertSql);
     $r = 1;
     foreach($dataReaderX as $records){
        $i = 0;
        foreach($records as  &$value){
          $commandInsert->bindParam(":{$cols[$i]}", $value, PDO::PARAM_STR ); //use $cols index instead of key of row so we can pre downcase with downcase each records 
          $i++;
        }
        try{
          $this->processImportHistoryUpdate($total, $r++, $quantUpdate, $table, $records);
          $commandInsert->execute();
        }
        catch(CDbException $ex){
          throw new DaInvalidDbException($ex->getMessage());
        }
     }
     $dataReaderX->close();
     $f = microtime(true);
     DaTool::p(" finished in : " . ($f-$s). " second(s)") ;
     
   }
   /**
    *
    * @param type $total total record
    * @param type $current current record
    * @param type $quantity iteration to update
    * @param type $tableName 
    */
   public function processImportHistoryUpdate($total, $current,$quantity, $tableName, $record){
     if( $current == 1 ||  ($current % $quantity == 0) ){
       $import = $this->siteconfig->lastImport();
       
       $import->total_record = $total;
       $import->current_record = $current;
       $import->importing_table = $tableName ;
       $import->setImportingRecord($record);
       $import->save();
     }
     
   }
   public function getRandomRecordUpdate(){
     return rand(5, 15);
   }
   public function beginTransaction(){
     $this->transaction = $this->db->beginTransaction();
     $this->transaction->active = true;
   }
   
   public function rollback(){
     if($this->transaction)
      $this->transaction->rollback();
      $this->transaction = null;
   }
   public function commit(){
     $this->incPatientTotal($this->currentPatientTable, "inserted");
     if($this->transaction)
      $this->transaction->commit();
      $this->transaction = null;
   }
   
   private function incPatientTotal($table, $type){
     if(isset($this->patientTotal[$type][$table]))
       $this->patientTotal[$type][$table]++;
     else
       $this->patientTotal[$type][$table] = 1;
   }
   public function getRecordReader($sqlX, $parentId=false){
     $commandX = $this->dbX->createCommand($sqlX);
     if($parentId)
      $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
      try { 
         $dataReader = $commandX->query();
         return $dataReader;
      }
      catch (Exception $ex) {
        throw new DaInvalidDbException($ex->getMessage());
      }
   }  
   
   public function importIMain($table){
      DaTool::pln("Import patient: {$table}");
      $s  = microtime(true);
      DaDbHelper::startIgnoringForeignKey($this->db);
      
      $totalRecord = DaDbHelper::countRecord($this->dbX, $table);
      $r = 1 ;
      $randomUpdate = $this->getRandomRecordUpdate();
     
      $this->currentPatientTable = $table ;
      $dataReader = $this->getRecordReader("SELECT * FROM {$table}");
      $control = DaControlImport::getControlInstance($table);
      
      $this->patientIter = 0;
      foreach($dataReader as $record){
         $this->processImportHistoryUpdate($totalRecord, $r++, $randomUpdate, $table, $record);

         $this->resetRecordError(); 
         $this->currentPatientRecord = $record ;
         
         $this->incPatientTotal($table, "total");
         
         $control->setRecord($record);
         if($control->check(array("dbx" => $this->dbX))){
            $this->beginTransaction();
            try {
                if($this->addRecord($record, $table)){
                    $patientId = $this->getTableKeyValue($table, $record);
                    $visitTable = $this->getTypeVisit($table);
                    $this->importVisitMain($patientId, $visitTable);

                    if(!$this->hasError())
                      $this->importIMainChildrenPartial($patientId, $table);

                    if(!$this->hasError())
                      $this->importTestPatient($patientId) ; 

                    if(!$this->hasError()){
                        $this->commit(); 
                    }

                    else{
                      $this->rollback();
                      $this->addRejectPatient();
                    }
                }
           }
           catch(DaInvalidDbException $ex){
             DaTool::debug($ex->getTraceAsString(),0,0);
             $this->rollback();
             throw $ex ;
           }
           catch(Exception $ex){
             DaTool::debug($ex->getTraceAsString(),0,0);
             $this->rollback();
             $this->addRejectPatient( );
           }
         }
         else{
           $this->addRecordErrors($control->getErrors(), $record, $table);
           $this->addRejectPatient();
         }
         $this->patientIter ++;
      }
      $dataReader->close();
      $f = microtime(true);
      DaTool::p(" finished in : " . ($f-$s). " second(s)") ;
      DaDbHelper::endIgnoringForeignKey($this->db);
   }
   
   public function getTotalPatientIter(){
     return $this->patientIter ;
   }
   
   public function addRecordErrors($errMessage, $record, $table){
     $this->recordErrors[$table][] = $record;
     $this->controlErrors = $errMessage ;
   }
   
   public function resetRecordError(){
     $this->recordErrors = array(); 
     $this->controlErrors = array();
   }
   
   public function getTypeVisit($table){
     if($table == "tblaimain")
       return "tblavmain";
     else if($table == "tblcimain")
       return "tblcvmain";
     else if($table == "tbleimain")
       return "tblevmain";
   }
   //===========================================================================
   public function rejectPatients($table, $offset=0, $limit=10){
     $import_site_history = $this->siteconfig->lastImport()->id ;
     
     $sql = "SELECT * FROM  da_reject_patients WHERE tablename=? AND import_site_history_id = ? limit {$offset}, {$limit} " ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $table, PDO::PARAM_STR);
     $command->bindParam(2, $import_site_history , PDO::PARAM_STR );
     $records = $command->queryAll();
     return $records ;
     
   }   
   //===========================================================================
   public function importTestPatient($parentId){
     $table = "tblpatienttest" ;
     $sqlX = DaRecordReader::getReader($table);
     $dataReader = $this->getRecordReader($sqlX, $parentId);
     foreach($dataReader as $record){
        if($this->addRecord($record, $table)){
          $id = $this->getTableKeyValue($table, $record);
          $this->importChildren($id, $table) ;
        }
     }
     $dataReader->close();
   }
   
   public function importVisitMain($patientId, $table){
     $sqlX = DaRecordReader::getReader($table);
     $dataReader = $this->getRecordReader($sqlX, $patientId);
     $control = DaControlImport::getControlInstance($table);
     $options = array("dbx"=>$this->dbX);
     foreach($dataReader as $record){
       $control->setRecord($record);
       if(get_class($control) == "DaControlEvMain")
          $options["dob"] = $this->currentPatientRecord["DOB"];
              
       if($control->check($options)){
          if($this->addRecord($record, $table)) {
            $id = $this->getTableKeyValue($table, $record);
            $this->importChildren($id, $table);
            if($this->hasError())
              break;
          }
       }
       else{
         $this->addRecordErrors($control->getErrors(),$record, $table) ;
         break;
       }
     }
     $dataReader->close();
   }
   
   public function importIMainChildrenPartial($parentId, $table){
     $parentChildren = DaRecordReader::IMainChildrenPartial($table);
     $this->importBulk($parentChildren["children"], $parentId);
   }
   
   public function importChildren($parentId, $parentTable){
     $parentChildren = DaRecordReader::getChildren($parentTable);
     $this->importBulk($parentChildren["children"], $parentId);
   }
   
   public function importBulk($children, $parentId){
      foreach($children as $childTable ){
        $this->importChild($childTable, $parentId);
        if($this->hasError())
          break;
      }
   }
   /**
    * @throws DaInvalidDbException
    * @param string $table
    * @param integer $parentId 
    */
   public function importChild($table, $parentId){
      DaTool::pln("Importing: {$table}  with parent: {$parentId}" );
      $sqlX = DaRecordReader::getReader($table);
      $dataReader = $this->getRecordReader($sqlX, $parentId);
      foreach($dataReader as $record){
        $control = DaControlImport::getControlInstance($table);
        if($control){
          $control->setRecord($record);
          $options = array();
          
          if(get_parent_class($control) == "DaControlLostDead"){
            $options["dbx"] = $this->dbX;
            $options["clinicid"] = $this->getTableKeyValue($this->currentPatientTable, $this->currentPatientRecord);      
          }
          
          if($control->check($options)){
            //this will through DaInvalidDbException
            $this->addRecord($record, $table);
          }
          else{
              // check if failed in case of lostdead
              if(get_parent_class($control) == "DaControlLostDead"){
                //if error type is waring just act like success but need to add waring to reject patient
                if($control->isWarning()){
                  $this->addRecord($record, $table);
                  $this->addRejectPatientWarning(array($table=> array($record)), $control->getErrors());
                }
                //just like normal error. reject the patient and skip other child
                else{
                   $this->addRecordErrors($control->getErrors(), $record, $table) ;
                   break; 
                }
              }
              // just normal error no warning
              else{
                $this->addRecordErrors($control->getErrors(), $record, $table) ;
                break;
              }
          }
        }
        else 
          $this->addRecord($record, $table);
              
      }
      $dataReader->close();
   }
   
   /**
    *
    * @param CDbCommand $command
    * @param array $record
    * @param string $sitecode 
    */
   public function bindAndExecParam(&$command, &$record){
      $sitecode = $this->siteconfig->code;
      $n = $n=count($record);
      for($i=0; $i<$n ; $i++){
       $command->bindParam($i+1, $record[$i], PDO::PARAM_STR ); //use $cols index instead of key of row so we can pre downcase with downcase each records 
      }

      $command->bindParam($i+1, $sitecode, PDO::PARAM_STR ); 
      $command->execute();
   }
   /**
    *
    * @param array $record record data 
    * @param string $table
    * @return boolean
    * @throws DaInvalidDbException 
    */
   public function addRecord($record, $table){
     try{
       DaSqlHelper::addRecord($record, $table, $this->siteconfig->code);
       return true ;
     }
     catch(Exception $ex){
       DaTool::debug($ex->getMessage(),0,0);
       DaTool::debug($ex->getTraceAsString(),0,0);
       $this->rollback();
       throw new DaInvalidDbException($ex->getMessage());
     }
     return false;
     
   }
   public function addRejectPatientWarning( $errorRecords, $errorMsg){
     $patientId = $this->getTableKeyValue($this->currentPatientTable, $this->currentPatientRecord);
     //only add reject only one time per patient
     if($this->patientIdTemp != $patientId ){
          $this->addPatientToTable( $this->currentPatientTable, 
                               $this->currentPatientRecord, 
                               $errorRecords, 
                               $errorMsg, 
                               RejectPatient::TYPE_WARNING);
          $this->patientIdTemp = $patientId;
     }
   }
   /**
    * 
    */
   public function addRejectPatient(){
     $this->incPatientTotal($this->currentPatientTable, "rejected");
     
     $this->addPatientToTable( $this->currentPatientTable, 
                               $this->currentPatientRecord, 
                               $this->recordErrors, 
                               $this->controlErrors, 
                               RejectPatient::TYPE_STRICT );
     $this->rejectCount++ ;
   }
   
   public function addPatientToTable($tableName, $record, $errorRecords, $errorMessage, $rejectType ){
      $sql = DaSqlHelper::sqlFromTableCols("da_reject_patients", 
         array( "record", "err_records" , "message", "tableName", "name" ,
                "import_site_history_id", "reject_type", "created_at", "modified_at" ),
         false );
     
      $command = $this->db->createCommand($sql);
      
      $now = date("Y-m-d H:i:s");
      $record = serialize($record);
      $errorMessage = serialize($errorMessage);

      $import_id = $this->siteconfig->lastImport()->id ;
      $errorRecords = serialize($errorRecords);
      
      $command->bindParam( "tableName" , $tableName , PDO::PARAM_STR);
      $command->bindParam( "err_records" , $errorRecords , PDO::PARAM_STR);
      $command->bindParam( "name" , $tableName , PDO::PARAM_STR);
      $command->bindParam( "record" , $record , PDO::PARAM_STR);
      $command->bindParam( "import_site_history_id" , $import_id, PDO::PARAM_STR  );
      $command->bindParam( "message" , $errorMessage , PDO::PARAM_STR  );
      $command->bindParam( "reject_type" , $rejectType , PDO::PARAM_INT  );
      
      $command->bindParam( "created_at" , $now , PDO::PARAM_STR);
      $command->bindParam( "modified_at" , $now , PDO::PARAM_STR);
      $command->execute();
   }
   
   
   public function totalReject(){
     return $this->rejectCount;
   }
   
   public function getTableKeyValue($table, $record){
     return DaRecordReader::getIdFromRecord($table, $record);
   }
   
   public function hasError(){
     return !empty($this->controlErrors);
   }   
 }
