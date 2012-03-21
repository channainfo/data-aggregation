<?php
  /**
  * @property string $code
  * @property CDbConnection $dbX
  * @property CDbConnection $db
  * @property SiteConfig $siteconfig
  * @property array $configs
  * @property integer $start
  * @property integer $end 
  *  
  */
 class DaImporter {
   public $dx = false;
   public $dbX = false;
   public $code = false;
   public $siteconfig = false ;
   public $configs = false;
   public $startTime = false;
   public $endTime = false;
   public $method = "";
   
   public function fstart(){
   }
   public function fend(){

   }
   /**
    *
    * @param CDbConnection $db 
    */
   public function __construct($db, $code="") {
     $this->db = $db ;
     $this->code = trim($code);
   }
   /**
    *
    * @param bool $includeFixed
    * @return array 
    * @throws DaInvalidFileException
    */
   private function _getProcessTables($includeFixed=false){
     $configs = DaConfig::importConfig();
     if($includeFixed)
       return array_merge($configs["fixed"], $configs["tables"]);
     return $configs["tables"];
   }
 
   /**
    * @return type 
    */
   public function removeSite($includeFixed = false){
      $this->fstart();
      try{
        DaDbHelper::startIgnoringForeignKey($this->db);
        $tables  = $this->_getProcessTables($includeFixed);
        $command = false ;
        foreach($tables as $tableName => $cols){
          if(!$this->_isFixedTable($tableName)){
            $condition = "ID = :code";
            $sql = " DELETE from {$tableName} WHERE {$condition} ";
            $command = $this->db->createCommand($sql);
            $command->bindParam(":code", $this->code, PDO::PARAM_STR);
          }
          else{
            $command = $this->db->createCommand("TRUNCATE ". $tableName);
          }
          
          try{
            $command->execute();
            DaTool::p("removed record(s) from {$tableName} ");
          }
          catch(Exception $ex){
            DaTool::pErr("{$ex->getMessage()}");
          }
        }
      }
      catch(Exception $ex){
        DaTool::p($ex->getMessage());
      }
      DaDbHelper::endIgnoringForeignKey($this->db);
    }
    /**
     *
     * @throws Exception
     */

    public function create(){
      $start = microtime(true);
      try{
        $siteconfig = $this->_loadSiteConfig(); 
        $this->_loadDbX();
        $this->saveImportSiteHistory($siteconfig->id);
        $this->_import();
      }
      catch(DaInvalidSiteException $ex){
        DaTool::pException($ex);
      }
      catch(DaInvalidSiteDatabaseException $ex){
        DaTool::pException($ex);
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)-$start, $ex->getMessage());  
      }
      catch(Exception $ex){
        echo "\n kdkkdkdkdk dfkdskf " ;
        DaTool::pException($ex);
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)-$start, $ex->getMessage());
      }
    }
    
    public function saveImportSiteHistory($siteconfig_id, $status = ImportSiteHistory::START){
        $import = new ImportSiteHistory();
        $import->siteconfig_id = $siteconfig_id;
        $import->status = $status;
        $import->save();
    }
    
    /**
     * @throws Exception
     */
    public function start(){
      $startTime = microtime(true);
      try{
        $this->_loadSiteConfig();
        $this->_loadDbX();
        $this->checkSiteDatabase();
        $this->_import();
      }
      catch(DaInvalidSiteException $ex){
        DaTool::p($ex->getMessage());
      }
      catch(DaInvalidStatusException  $ex){
        DaTool::pErr($ex->getMessage());        
      }
      
      catch(DaInvalidSiteDatabaseException $ex) {
        DaTool::pErr($ex->getMessage());
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)- $startTime , $ex->getMessage());
      }
    }
    
    /**
     * @throw Exception 
     */
    public function truncate(){
      $this->_truncate();
    }
    
    /**
     * @throws Exception 
     */
    private function _truncate($includeFixed=false){
      DaTool::p("Preparing ");
      $tables = $this->_getProcessTables($includeFixed);
      DaDbHelper::startIgnoringForeignKey($this->db);
      foreach($tables as $tableName => $cols){
        DaTool::p("truncating {$tableName}");
        $sql = "truncate {$this->db->quoteTableName($tableName)} " ;
        $command = $this->db->createCommand($sql);
        $command->execute();  
      }
      DaDbHelper::endIgnoringForeignKey($this->db);
    }
        
    /** This function is used with multiple place so intend to catch it exception here
     * @throws Exception
     */
    
    private function _import($includeFixed = false){
      $startTime = microtime(true);
      $total = 0;       
      $transaction = false;
      DaTool::p("Preparing import");
      try{
        $this->_startImporting();
        $tables = $this->_getProcessTables($includeFixed);
        $transaction = $this->db->beginTransaction();
        
        foreach($tables as $table => $cols){
          try{
            $count = $this->_importTable($table, $cols);
            $total += $count;
            DaTool::p("{$count} record(s) have been imported to table: {$table} ");
          }
          //catch only in case clinic
          catch(DaInvalidFixedTableException $ex){
            DaTool::pErr($ex->getMessage());
          }
        }
        $transaction->commit();
        $duration = microtime(true)-$startTime ;
        $this->_endImporting(ImportSiteHistory::SUCCESS , $duration, DaTool::getMessags());
        DaTool::p("Finished importing");
      }
      
      catch(DaInvalidFileException $ex){
        DaTool::pException($ex);
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)- $startTime , $ex->getMessage());
      }
      catch(DaInvalidFixedTableException $ex){
        DaTool::pErr($ex->getMessage());
        $transaction->rollback(); 
        DaTool::p("Rolling back");
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)- $startTime , $ex->getMessage());
      }
      
      catch(DaInvalidFixedTableException $ex){
        DaTool::pErr($ex->getMessage());
        $transaction->rollback(); 
        DaTool::p("Rolling back");
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)- $startTime , $ex->getMessage());
      }
      catch(CDbException $ex){
        DaTool::pErr($ex->getMessage());
        $transaction->rollback();     
        DaTool::p("Rolling back");
        $this->_endImporting(ImportSiteHistory::FAILED, microtime(true)- $startTime , $ex->getMessage());
      }
      DaTool::p("\n Resume");
      DaTool::p(" - {$total} record(s) have been imported in total ");
    }

    /**
     *
     * @param string $table
     * @param array $cols
     * @return int 
     * @throws CDbException
     */
    private function _importTable($table, $cols){
      $dbx = $this->_loadDbX();
      $sql = " SELECT * FROM {$table}";
      $commandX = $dbx->createCommand($sql);
      $dataReader =  $commandX->query();

      $sql = DaSqlHelper::sqlFromTableCols($table, $cols);
      
      $command = $this->db->createCommand($sql);
      
      $j = 0;
      $isFixedTable = $this->_isFixedTable($table);
      $controlImport = DaControlImport::getControlInstance($table);
      foreach($dataReader as $row){        
        $i =0;
        
        if($controlImport){
          try{
              if($j==0){
                echo "\n table: {$table} \n";
                print_r($row);
                
                }
              $controlImport->setRecord($row);
              $controlImport->check();
              break;
          }
          catch(DaInvalidControlException $ex){
              DaTool::pErr($ex->getMessage());
          }
        }
        foreach($row as  &$value){
          $command->bindParam(":{$cols[$i]}", $value, PDO::PARAM_STR ); //use $cols index instead of key of row so we can pre downcase with downcase each records 
          $i++;
        }

        if(!$isFixedTable)
          $command->bindParam(":id", $this->code );

        try {
          $command->execute();
        }
        catch(CDbException $ex){
          if(!$isFixedTable)
            throw $ex;
          else
            DaTool::pErr($ex->getMessage()); // throw new DaInvalidFixedTableException($ex->getMessage());
        }
        $j++;
      }
      return $j ;
    }
   /**
    *
    * @return SiteConfig 
    * @throws Exception 
    */ 
   public function _loadSiteConfig(){
     if($this->siteconfig){
       return $this->siteconfig;
     }
     $siteconfig = SiteConfig::model()->findByAttributes(array("code" => $this->code));
     if(!$siteconfig){
       throw new DaInvalidSiteException("Could not find any site matched with code : {$this->code}");
     }
     
     $this->siteconfig = $siteconfig;
     return $this->siteconfig ;
   } 
    
    /**
     *
     * @return CDbConnection
     * @throws DaInvalidSiteException, DaInvalidSiteException 
     */
    public function _loadDbX(){
      if($this->dbX ){
        return $this->dbX;
      }
      
      $siteconfig = $this->_loadSiteConfig();
      $dbEx = false;
      
      $dsn = "sqlsrv:Server={$siteconfig->attributes["host"]};Database={$siteconfig->attributes["db"]}";
      $username = $siteconfig->attributes["user"];
      $password = $siteconfig->attributes["password"];
      
      $dbEx = new CDbConnection($dsn,$username,$password);
      try{
        $dbEx->active=true;
      }
      catch(CDbException $ex){
        throw  new DaInvalidSiteDatabaseException("Could not connect to : {$siteconfig->attributes["host"]} " . $ex->getMessage());
      }
      $this->dbX = $dbEx;
      return $this->dbX;
    }
    /**
     *
     * @throws Exception 
     */
    private function _startImporting(){
      
      $siteconfig = $this->_loadSiteConfig();
      
      if($siteconfig->lastImport()){
        if($siteconfig->lastImport()->status == ImportSiteHistory::PENDING )
          throw new DaInvalidStatusException("Site {$this->code} import in progress");
        else if($siteconfig->lastImport()->status != ImportSiteHistory::START ){
          throw new DaInvalidStatusException("Site {$this->code} import in had been finished with status: " . 
                  $siteconfig->lastImport()->getStatusText(). " On date : " . " [ {$siteconfig->lastImport()->modified_at} ]" );
        }  
      }
      $import = $siteconfig->lastImport();
      $import->status = ImportSiteHistory::PENDING;
      $import->save();
      
      DaDbHelper::startIgnoringForeignKey($this->db);
    }
    /**
     *
     * @param integer $status
     * @return boolean 
     */
    private function _endImporting($status, $duration , $reason="" ){
      $siteconfig = $this->_loadSiteConfig();
      $import = $siteconfig->lastImport(false);
      $import->status = $status ;
      $import->duration = $duration;
      $import->reason = $reason;
      $import->save();
      DaDbHelper::endIgnoringForeignKey($this->db);
    }
    /**
     *
     * @param string $table
     * @return boolean 
     */
    private function _isFixedTable($table){
      $configs = DaConfig::importConfig();
      foreach($configs["fixed"] as $tableName => $cols){
        if($tableName == $table)
          return true;
      }
      return false;
    }
    /**
     *
     * @return boolean
     * @throws DaInvalidSiteDatabaseException 
     */
    public function checkSiteDatabase(){
      $dbEx = $this->_loadDbX();
      $siteconfig = $this->_loadSiteConfig();
      $sql = "SELECT ART FROM ".  DaConfig::TBL_CLINIC ;
      $command = $dbEx->createCommand($sql);
      $row = $command->queryRow();
      
      if(!$row)
        throw new DaInvalidSiteDatabaseException("Invalid site code: {$this->code} with database: {$siteconfig->attributes["db"]} with no ART Data in ". DaConfig::TBL_CLINIC );
      
      if(trim($row["ART"]) != $this->code)  
        throw new DaInvalidSiteDatabaseException("Invalid site code: {$this->code} with ART code: {$row["ART"]} 
         \n You must make them the same values by change site code to : {$row["ART"]}
        ");
      return true;   
    }
  }