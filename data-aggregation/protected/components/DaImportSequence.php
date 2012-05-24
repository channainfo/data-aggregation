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
   
   private $patientIter;
   private $patientTotal=array();
   
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
      DaDbHelper::endIgnoringForeignKey($this->db);
   }
   public function start(){
     try{
        DaTool::hp("Importing site: {$this->siteconfig->code}, {$this->siteconfig->name}");

        $this->_startImporting();
        $this->importTablesFixed();
        
        $this->importIMain("tblaimain");
        $this->importIMain("tblcimain") ;
        $this->importIMain("tbleimain") ;
        
        $this->_endImporting(ImportSiteHistory::SUCCESS);
     }
     catch(DaInvalidStatusException $ex){
       // DaTool::debug($ex->getMessage(),0,0);
     }
     catch(DaInvalidDbException $ex){
       //DaTool::debug($ex->getMessage(),0,0);
       $this->patientTotal = array(); 
       $this->_endImporting(ImportSiteHistory::FAILED, $ex->getMessage());
     }
     catch(Exception $ex){
        //DaTool::debug($ex->getMessage(),0,0);
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
     DaTool::p("Import: {$table}");
     
     $sqlX = " SELECT * FROM {$table} ";
     //$commandX =  $this->dbX->createCommand($sqlX);
     $dataReaderX = $this->getRecordReader($sqlX);
     
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
          throw new DaInvalidDbException($ex->getMessage());
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
     $this->incPatientTotal($this->patientTable, "inserted");
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
      DaTool::p("Import patient: {$table}");
      DaDbHelper::startIgnoringForeignKey($this->db);
     
      $this->patientTable = $table ;
      $dataReader = $this->getRecordReader("SELECT * FROM {$table}");
      $control = DaControlImport::getControlInstance($table);
      
      
      $this->patientIter = 1;
      foreach($dataReader as $record){
         
         $this->recordErrors = array(); 
         $this->errors = array();
         $this->currentPatient = $record ;
         
         $this->incPatientTotal($table, "total");
         
         $control->setRecord($record);
         if($control->check()){
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
                      $this->addRejectPatient($record, $table );
                    }
                }
           }
           catch(DaInvalidDbException $ex){
             DaTool::debug($ex->getMessage(),0,0);
             $this->rollback();
             throw $ex ;
           }
           catch(Exception $ex){
             DaTool::debug($ex->getMessage(),0,0);
             $this->rollback();
             $this->addRejectPatient($record, $table );
           }
         }
         else{
           $this->errors = $control->getErrors();
           $this->addRecordErrors($record, $table);
           $this->addRejectPatient($record, $table);
         }
         $this->patientIter ++;
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
   }
   
   public function importVisitMain($patientId, $table){
     $sqlX = DaRecordReader::getReader($table);
     $dataReader = $this->getRecordReader($sqlX, $patientId);
     $control = DaControlImport::getControlInstance($table);
     
     foreach($dataReader as $record){
       $control->setRecord($record);
       $options = array();
       if(get_class($control) == "DaControlEvMain")
          $options["dob"] = $this->currentPatient["DOB"];
       
       if($control->check($options)){
          if($this->addRecord($record, $table)) {
            $id = $this->getTableKeyValue($table, $record);
            $this->importChildren($id, $table);
            if($this->hasError())
              break;
          }
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
      $sqlX = DaRecordReader::getReader($table);
      $dataReader = $this->getRecordReader($sqlX, $parentId);
      foreach($dataReader as $record){
        $control = DaControlImport::getControlInstance($table);
        if($control){
          $control->setRecord($record);
          $options = array();
          
          if(get_parent_class($control) == "DaControlLostDead"){
            $options["dbX"] = $this->dbX;
            $options["clinicid"] = $this->getTableKeyValue($this->patientTable, $this->currentPatient);
          }
          if($control->check($options)){
            if(!$this->addRecord($record, $table))
                    break;
          }
          else{
            $this->addRecordErrors($record, $table) ;
            $this->errors = $control->getErrors(); 
            break;
          }
        }
        else if(!$this->addRecord($record, $table))
                break ;
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
       $this->rollback();
       throw new DaInvalidDbException($ex->getMessage());
     }
     return false;
     
   }
   
   public function addRejectPatient($record, $name ){
      $this->incPatientTotal($this->patientTable, "rejected");
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
     return DaRecordReader::getIdFromRecord($table, $record);
   }
   
   public function hasError(){
     return !empty($this->errors);
   }
   
   
 }
 
 
//========================================================================= 
/* 
function da_import_shutdown(){
  global $_siteconfig;
  $text = "unexpected";
  if($_siteconfig)
    $text = print_r($_siteconfig->attributes, 1);
  file_put_contents("shutdown.info", $text);
  echo $text ;
}
register_shutdown_function('da_import_shutdown');
*/