<?php
 /**
  * @property string $code
  * @property CDbConnection $dbX
  * @property SiteConfig $siteconfig
  * @property integer $startTime
  * @property integer $endTime
  * @property array $configs
  *  
  */
 class ImportCommand extends CConsoleCommand {
    public $code = false;
    public $dbX = false;
    public $siteconfig = false ;
    public $configs = false;
    
    public $startTime ;
    public $endTime ;
    
    public function beforeAction($action, $params) {
      echo "\n String running {$action}" ;
      $this->startTime = microtime(true);
      return parent::beforeAction($action, $params);
    }
    
    public function afterAction($action, $params) {
      $this->endTime = microtime(true);
      $duration = $this->endTime -  $this->startTime ;
      echo "\n End running {$action} in : ". $duration ;
      return parent::afterAction($action, $params);
    }
    
    public function actionRemoveSite($code){
      $db = Yii::app()->db;
      $configs = $this->config();
      
      foreach($configs["tables"] as $tableName => $cols){
        if($tableName !== DaConfig::TBL_CLINIC)
          $condition = "ID = :code";
        else
          $condition = "ART = :code";
        
        $sql = " DELETE from {$tableName} WHERE {$condition} ";
        
        $command = $db->createCommand($sql);
        $this->output("Removing site: {$code} from {$tableName}");

        $command->bindParam(":code", $code, PDO::PARAM_STR);
        
        try{
          $count = $command->execute();
          $this->output("{$count} records have been removed");
        }
        catch(Exception $ex){
          $this->output("Error: {$ex->getMessage()}");
        }
      }
    }

    public function actionTruncate(){
      $this->output("Preparing ");
      $configs = $this->config();
      
      $this->startIgnoreForeignKey();

      $db = Yii::app()->db;
      foreach($configs["tables"] as $tableName => $cols){
        $this->output("truncating {$tableName}");
        $sql = "truncate {$db->quoteTableName($tableName)} " ;
        $command = $db->createCommand($sql);
        try{ 
          $command->execute();  
          $this->output(" run {$sql}");
        }
        catch(Exception $ex){
          $this->output("Error : {$ex->getMessage()}");
        }
      }
      $this->endIgnoreForeignKey();
    }

    public function actionCreate($code){
      try{
        $this->getDbX($code);
        $siteconfig = $this->getSiteConfig($code);
        $import = new ImportSiteHistory();
        $import->siteconfig_id = $siteconfig->id;
        $import->status = ImportSiteHistory::START;
        $import->save();
        $this->actionStart($code);
      }
      catch(Exception $ex){
        echo "\n  {$ex->getMessage()}" ;
      }
    }
    
    public function actionStart($code){
      $this->startIgnoreForeignKey();
      try{
        $this->getDbX($code);
        $configs = $this->config();
        $this->startImporting();
        foreach($configs["tables"] as $table => $cols){
          $this->import($table, $cols);
        }
      }
      catch(Exception $ex){
        echo "\n Error with message: {$ex->getMessage()} ";
        $this->endImporting(ImportSiteHistory::FAILED);
      }
      
      $this->endImporting(ImportSiteHistory::SUCCESS);
      $this->endIgnoreForeignKey();
    }
    
    public function output($str){
      echo "\n {$str}" ;
    }
    /**
     *
     * @param string $table
     * @param array $cols 
     */
    private function import($table, $cols){
      echo "\n Start Table: {$table} ";

      $dbx = $this->getDbX($this->code);
      $db = Yii::app()->db ;
      
      $sql = " SELECT * FROM {$table}";
      $commandX = $dbx->createCommand($sql);
      $dataReader =  $commandX->query();

      $colName  = implode(",  ", $cols);
      $colParam = implode(", ",array_map("simbolizeCol",$cols));

      $sql = "INSERT INTO {$table} \n ($colName) VALUES \n ($colParam)" ;
      $command = $db->createCommand($sql);
     
      foreach($dataReader as $row){
        foreach($row as $col => $value){
          $colSymbol = ":{$col}";
          echo "\n bining :{$colSymbol} -> {$value}";
          $command->bindParam($colSymbol, $value, PDO::PARAM_STR );
        }
        
        if($table !=DaConfig::TBL_CLINIC){
          $this->output("\n\t\t bining :ID  with value: {$this->code}");
          $command->bindParam(":ID", $this->code );
        }
        $command->execute();
      }
    }
   /**
    *
    * @param string $code
    * @return SiteConfig 
    * @throws Exception 
    */ 
   public function getSiteConfig($code){
     if($this->code == $code && $this->siteconfig){
       return $this->siteconfig;
     }
     $siteconfig = SiteConfig::model()->findByAttributes(array("code" => $code));
     if(!$siteconfig){
       throw new Exception("Invalid site code : {$this->code}");
     }
     
     $this->code = $code;
     $this->siteconfig = $siteconfig;
     return $this->siteconfig ;
     
   } 
    
    /**
     *
     * @param string $code
     * @return CDbConnection
     * @throws Exception 
     */
    public function getDbX($code){
      if($this->code == $code && $this->dbX ){
        return $this->dbX;
      }
      
      $siteconfig = $this->getSiteConfig($code);
      $dbEx = false;

      if($siteconfig->lastImport() && $siteconfig->lastImport()->inProgress()){
        throw new Exception("Site Import in progress : {$code} was f ");
      }

      $dsn = "sqlsrv:Server={$siteconfig->attributes["host"]};Database={$siteconfig->attributes["db"]}";
      $username = $siteconfig->attributes["user"];
      $password = $siteconfig->attributes["password"];
      $dbEx=new CDbConnection($dsn,$username,$password);
      if($dbEx)
        $dbEx->active=true;
      else
        throw  new Exception("Could not connect to  : {$siteconfig->attributes["host"]}");
      $this->siteconfig = $siteconfig ;  
      $this->code = $code;
      $this->dbX = $dbEx;
      return $this->dbX;
    }
    /**
     *
     * @throws Exception 
     */
    public function startImporting(){
      $siteConfig = $this->getSiteConfig($this->code);
      if($siteConfig->lastImport() && $siteConfig->lastImport()->status == ImportSiteHistory::PENDING )
        throw new Exception("Site {$this->code} import in progress");
      else if($siteConfig->lastImport()->status == ImportSiteHistory::START){
        $import = $siteConfig->lastImport();
        $import->status = ImportSiteHistory::PENDING;
        $import->save();
      }
      else if($siteConfig->lastImport()->status == ImportSiteHistory::FAILED  || $siteConfig->lastImport()->status == ImportSiteHistory::SUCCESS ){
        throw new Exception(" Site {$this->code} has run  already with status ".$siteConfig->lastImport()->getStatusText() );
      }
      else if(!$siteConfig->lastImport()){
        $import = new ImportSiteHistory();
        $import->status = ImportSiteHistory::PENDING;
        $import->siteconfig_id = $siteConfig->id;
        $import->save();
      }
    }
    /**
     *
     * @param integer $status
     * @return boolean 
     */
    public function endImporting($status){
      $siteConfig = $this->getSiteConfig($this->code);
      $import = $siteConfig->lastImport(false);
      $import->status = $status ;
      return $import->save();
    }
    
    public function config(){
      if($this->configs)
        return $this->configs;
      $this->configs = require_once dirname(__FILE__)."/../config/importConfig.php";
      return $this->configs ;
    }
    
    public function startIgnoreForeignKey(){
      $db = Yii::app()->db ;
      $command = $db->createCommand("SET FOREIGN_KEY_CHECKS = 0;");
      $command->execute();
    }
    
    public function endIgnoreForeignKey(){
      $db = Yii::app()->db ;
      $command = $db->createCommand("SET FOREIGN_KEY_CHECKS = 1;");
      $command->execute();
    }
  }
  
  

  
  if(!function_exists("simbolizeCol")){
    function simbolizeCol($name){
      return ":{$name}";
    }
  }