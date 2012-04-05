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
      $dbX = false;
      
      $dsn = "sqlsrv:Server={$siteconfig->attributes["host"]};Database={$siteconfig->attributes["db"]}";
      $username = $siteconfig->attributes["user"];
      $password = $siteconfig->attributes["password"];
      
      $dbX = new CDbConnection($dsn,$username,$password);
      try{
        $dbX->active=true;
      }
      catch(CDbException $ex){
        throw  new DaInvalidSiteDatabaseException("Could not connect to : {$siteconfig->attributes["host"]} " . $ex->getMessage());
      }
      $this->dbX = $dbX;
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
      }
      $import = $siteconfig->lastImport();
      $import->status = ImportSiteHistory::PENDING;
      //$import->save();
      DaDbHelper::startIgnoringForeignKey($this->db);
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
        $this->importAiMain();
        $this->importCiMain();
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
     DaTool::p("Import {$table}");
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
     $this->transaction->rollback();
     $this->transaction = null;
   }
   public function commit(){
     $this->transaction->commit();
     $this->transaction = null;
   }
   
   public function importIMain($table){
      DaTool::p("Import {$table}");
      DaDbHelper::startIgnoringForeignKey($this->db);
     
      $commandX= $this->dbX->createCommand("SELECT * FROM {$table}");
      $dataReader = $commandX->query();
      $control = DaControlImport::getControlInstance($table);
      $errors = array();
      $this->recordErrors = array();
      foreach($dataReader as $record){
         $control->setRecord($record);
         if($control->check()){
           $this->beginTransaction();
           
           $this->addRecord($record, $table);
           $parentId = $this->getParentKeyValue($table, $record);
           $visitTable = $this->getTypeVisit($table);
           
           $errs1 = $this->importVisitMain($parentId, $visitTable);
           $errs2 = $this->importIMainChildrenPartial($parentId, $table);
           $errs3 = $this->importTestPatient($parentId);
           
           $errors = array_merge($errs1,$errs2, $errs3);
           
           if(count($errors)){
             $this->rollback();
             
             $this->addRejectPatient($table, $record, $errors);
           }
           else
            $this->commit();
         }
         else{
           $errors = $control->getErrors();
           $this->addRejectPatient($table, $record, $errors);
         }
      }

      DaDbHelper::endIgnoringForeignKey($this->db);
      return $errors;
   }
   public function addRecordErrors($record, $table){
     $this->recordErrors[$table][] = $record;
   }
   public function getTypeVisit($table){
     if($table == "tblaimain")
       return "tblavmain";
     else if($table == "tblcimain")
       return "tblcvmain";
   }

   public function importCiMain(){
      return $this->importIMain("tblcimain") ;
   }
   public function importAiMain() {
      return $this->importIMain("tblaimain");
   }
   public function importTestPatient($parentId){
     $table = "tblpatienttest" ;
     
     $sqlX = DaRecordReader::getReader($table);
     $commandX = $this->dbX->createCommand($sqlX);
     $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
     $dataReader = $commandX->query();
     $errors =  array();
     foreach($dataReader as $record){
        $this->addRecord($record, $table);
        $id = $this->getParentKeyValue($table, $record);
        $errors = array_merge($errors, $this->importChildren($id, $table) );
     } 
     return $errors;
   }
   
   public function importVisitMain($parentId, $table){
     $sqlX = DaRecordReader::getReader($table);
     $commandX = $this->dbX->createCommand($sqlX);
     $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
     $dataReader = $commandX->query();
     $control = DaControlImport::getControlInstance($table);
     $errors = array();
     
     foreach($dataReader as $record){
       $control->setRecord($record);
       if($control->check()){
          $this->addRecord($record, $table);
          $id = $this->getParentKeyValue($table, $record);
          $temErros = $this->importChildren($id, $table);
          $errors = array_merge($errors, $temErros);
       }
       else{
         $this->addRecordErrors($record, $table) ;
         return $control->getErrors();
       }
     }
     return $errors;
   }
   
   public function importIMainChildrenPartial($parentId, $table){
     $parentChildren = DaRecordReader::IMainChildrenPartial($table);
     return $this->importBulk($parentChildren["children"], $parentId);
   }
   public function importChildren($parentId, $parentTable){
     $parentChildren = DaRecordReader::getChildren($parentTable);
     return $this->importBulk($parentChildren["children"], $parentId);
   }
   
   public function importBulk($children, $parentId){
      $errors = array();
      foreach($children as $childTable ){
        $errors = array_merge( $errors, $this->importChild($childTable, $parentId));
      }
      return $errors;
   }
   
   public function importChild($table, $parentId){
      $sqlX = DaRecordReader::getReader($table);

      $commandX = $this->dbX->createCommand($sqlX);
      $commandX->bindParam(1, $parentId, PDO::PARAM_STR);
      $dataReader = $commandX->query();
      $errors = array();
      foreach($dataReader as $record){
        $control = DaControlImport::getControlInstance($table);
        if($control){
          $control->setRecord($record);
          if($control->check(array("dbX" => $this->dbX )))
            $this->addRecord($record, $table);
          else{
            $this->addRecordErrors($record, $table) ;
            $errors = array_merge($errors, $control->getErrors()); 
          }
        }
        else
          $this->addRecord($record, $table);
      }
      return $errors;
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
   
   public function addRejectPatient($name , $record, $message){
      
      $sql = DaSqlHelper::sqlFromTableCols("da_reject_patients", 
         array("record", "err_records" , "message","tableName", "name" ,"import_site_history_id", "created_at", "modified_at" ),false);
         
             // array("tableName", "name", "record", "code", "message", "import_site_history_id", "created_at", "modified_at"),false);
     
      $command = $this->db->createCommand($sql);
      
      $now = date("Y-m-d H:i:s");
      $record = serialize($record);
      $message = serialize($message);
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
   
   public function getParentKeyValue($table, $record){
     $configs = DaConfig::importConfig();
     $key = $configs["keys"][$table];
     return $record[$key];
   }
 }