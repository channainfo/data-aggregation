<?php
  /**
  * @property string $code
  * @property CDbConnection $dbX
  * @property CDbConnection $db
  * @property SiteConfig $siteconfig
  * @property integer $start
  * @property integer $end 
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
   public $errors = array();
   
   public $currentPatient ;
   public $patientTable ;
   
   public function __construct($db, $code ) {
     $this->db = $db ;
     $this->_loadSiteConfig($code);
     $this->_loadDbX($code);
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
        //$import->save();
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
      //$import->save();
      DaDbHelper::endIgnoringForeignKey($this->db);
   }
   public function start(){
     try{
        $this->_startImporting();
        
        $this->importTablesFixed();
        
        $this->importIMain("tblaimain");
        //$this->importIMain("tblcimain") ;
        //$this->importIMain("tbleimain") ;
        
        $this->_endImporting(ImportSiteHistory::SUCCESS);
     }
     catch(DaInvalidStatusException $ex){
       DaTool::pErr($ex->getMessage());
     }
     catch(Exception $ex){
        DaTool::pException($ex);
        $this->_endImporting(ImportSiteHistory::FAILED, $ex->getMessage());
     }
   }
   public function importTablesFixed(){
     $configs = DaConfig::importConfig();
     $fixedTables = $configs["fixed"];
     
     DaDbHelper::startIgnoringForeignKey($this->db);
     
     foreach($fixedTables as $table => $cols){
       $this->_importTableFixed($table, $cols);
     }
     
     DaDbHelper::endIgnoringForeignKey($this->db);
   }
   protected function _importTableFixed($table, $cols){
     $sql = " SELECT * FROM {$table} ";
     $commandX =  $this->dbX->createCommand($sql);
     $dataReaderX = $commandX->query();
     
     $insertSql = DaSqlHelper::sqlFromTableCols($table, $cols);
     $commandInsert = $this->db->createCommand($insertSql);
     
     foreach($dataReaderX as $records){
        $i = 0;
        foreach($records as  &$value){
          $commandInsert->bindParam(":{$cols[$i]}", $value, PDO::PARAM_STR ); //use $cols index instead of key of row so we can pre downcase with downcase each records 
          $i++;
        }
        try{
          $commandInsert->execute();
        }
        catch(CDbException $ex){
          DaTool::pException($ex);
        }
     }
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
     $this->transaction->commit();
     $this->transaction = null;
   }
   
   public function importIMain($table){
      DaDbHelper::startIgnoringForeignKey($this->db);
     
      $this->patientTable = $table ;
      $commandX= $this->dbX->createCommand("SELECT * FROM {$table}");
      $dataReader = $commandX->query();
      $control = DaControlImport::getControlInstance($table);
      
      foreach($dataReader as $record){
        
         $this->recordErrors = array(); 
         $this->errors = array();
         $control->setRecord($record);
         if($control->check()){
            $this->beginTransaction();
            try {
                $this->addRecord($record, $table);
                $patientId = $this->getTableKeyValue($table, $record);
                $visitTable = $this->getTypeVisit($table);
                $this->currentPatient = $record ;
                $this->importVisitMain($patientId, $visitTable);

                if(!$this->hasError())
                  $this->importIMainChildrenPartial($patientId, $table);

                if(!$this->hasError())
                  $this->importTestPatient($patientId) ; 

                if(!$this->hasError())
                    $this->commit(); 

                else{
                  $this->rollback();
                  $this->addRejectPatient($record, $table );
                }
           }
           catch(Exception $ex){
             DaTool::pException($ex);
             $this->rollback();
           }
         }
         else{
           $this->errors = $control->getErrors();
           $this->addRecordErrors($record, $table);
           $this->addRejectPatient($record, $table);
         }
      }
      DaDbHelper::endIgnoringForeignKey($this->db);
   }
   public function addRecordErrors($record, $table){
     $this->recordErrors[$table][] = $record;
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
   public function rejectPatients($offset=0, $limit=10){
     $import_site_history = $this->siteconfig->lastImport()->id ;
     
     $sql = "SELECT * FROM  da_reject_patients WHERE import_site_history_id = ? limit {$offset}, {$limit} " ;
     $command = Yii::app()->db->createCommand($sql);
     $command->bindParam(1, $import_site_history , PDO::PARAM_STR );
     $records = $command->queryAll();
     return $records ;
     
   }   
   //===========================================================================
   public function importTestPatient($parentId){
     $table = "tblpatienttest" ;
     
     $sqlX = DaRecordReader::getReader($table);
     $commandX = $this->dbX->createCommand($sqlX);
     $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
     $dataReader = $commandX->query();

     foreach($dataReader as $record){
        $this->addRecord($record, $table);
        $id = $this->getTableKeyValue($table, $record);
        $this->importChildren($id, $table) ;
     } 
   }
   
   public function importVisitMain($patientId, $table){
     $sqlX = DaRecordReader::getReader($table);
     
     $commandX = $this->dbX->createCommand($sqlX);
     $commandX->bindParam(1, $patientId, PDO::PARAM_STR);
     $dataReader = $commandX->query();
     $control = DaControlImport::getControlInstance($table);
     
     foreach($dataReader as $record){
       $control->setRecord($record);
       $options = array();
       if(get_class($control) == "DaControlEvMain")
          $options["dob"] = $this->currentPatient["DOB"];
       
       if($control->check($options)){
          $this->addRecord($record, $table);
          $id = $this->getTableKeyValue($table, $record);
          $this->importChildren($id, $table);
          if($this->hasError())
            break;
       }
       else{
         $this->errors = $control->getErrors() ; 
         $this->addRecordErrors($record, $table) ;
         break;
       }
     }
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
   
   public function importChild($table, $parentId){
      //DaTool::p($table);
      $sqlX = DaRecordReader::getReader($table);
      $commandX = $this->dbX->createCommand($sqlX);
      $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
      $dataReader = $commandX->query();
      foreach($dataReader as $record){
        $control = DaControlImport::getControlInstance($table);
        if($control){
          $control->setRecord($record);
          $options = array();
          
          if(get_parent_class($control) == "DaControlLostDead"){
            $options["dbX"] = $this->dbX;
            $options["clinicid"] = $this->getTableKeyValue($this->patientTable, $this->currentPatient);
          }
          if($control->check($options))
            $this->addRecord($record, $table);
          else{
            $this->addRecordErrors($record, $table) ;
            $this->errors = $control->getErrors(); 
            break;
          }
        }
        else
          $this->addRecord($record, $table);
      }
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
   public function addRecord($record, $table){
     DaSqlHelper::addRecord($record, $table, $this->siteconfig->code);
   }
   
   public function addRejectPatient($record, $name ){
      
      $sql = DaSqlHelper::sqlFromTableCols("da_reject_patients", 
         array("record", "err_records" , "message","tableName", "name" ,"import_site_history_id", "created_at", "modified_at" ),
         false );
     
      $command = $this->db->createCommand($sql);
      
      $now = date("Y-m-d H:i:s");
      $record = serialize($record);
      $message = serialize($this->errors);

      $import_id = $this->siteconfig->lastImport()->id ;
      $errorRecords = serialize($this->recordErrors);
      
      $command->bindParam( "tableName" , $name , PDO::PARAM_STR);
      $command->bindParam( "err_records" , $errorRecords , PDO::PARAM_STR);
      $command->bindParam( "name" , $name , PDO::PARAM_STR);
      $command->bindParam( "record" , $record , PDO::PARAM_STR);
      $command->bindParam( "import_site_history_id" , $import_id, PDO::PARAM_STR  );
      $command->bindParam( "message" , $message , PDO::PARAM_STR  );
      $command->bindParam( "created_at" , $now , PDO::PARAM_STR);
      $command->bindParam( "modified_at" , $now , PDO::PARAM_STR);
      $command->execute();
      $this->rejectCount++ ;
   }
   
   public function totalReject(){
     return $this->rejectCount;
   }
   
   public function getTableKeyValue($table, $record){
     $configs = DaConfig::importConfig();
     $key = $configs["keys"][$table];
     return $record[$key];
   }
   
   public function hasError(){
     return !empty($this->errors);
   }
 }