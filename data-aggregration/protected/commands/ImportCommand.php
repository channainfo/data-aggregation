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
      DaTool::p("\n Action {$action} running ") ;
      $this->startTime = microtime(true);
      return parent::beforeAction($action, $params);
    }
    
    public function afterAction($action, $params) {
      $this->endTime = microtime(true);
      $duration = $this->endTime -  $this->startTime ;
      DaTool::p("\n Finished running {$action}");
      
      DaTool::p("\n *************** Duration : {$duration}" );
      DaTool::p("\n *************** Memory : ". floatval(memory_get_peak_usage()/(1024*1024)));
      return parent::afterAction($action, $params);
    }
    
    public function actionTest(){
      $db = Yii::app()->db ;
      $sql = " INSERT INTO tblclinic( clinic,ClinicKh,ART,District,OD,Province  ) VALUES (:Clinic,:ClinicKh,:ART,:District,:OD,:Province) ";
    
      $command = $db->createCommand($sql);
      $clinic =" 000c";
      $art = " 0098";
      $clinicKh = "000ckh";
      $district = "0000dtrt";
      $od = "000 od" ;
      $province = "0000pr" ;
      
      $array = array("a"=>12, "C" => 20 , "Dd" => 30);
      
      print_r(array_map("lowerCaseCol", array_keys($array)));
      
      $command->bindParam(":Clinic", $clinic);
      $command->bindParam(":ART", $art);
      $command->bindParam(":ClinicKh", $art);
      $command->bindParam(":District", $art);
      $command->bindParam(":OD", $art);
      $command->bindParam(":Province", $art);
      
      $command->bindParam(":ART", $art);
           
      $command->execute();
      
      
      
    }
    
    public function actionRemoveSite($code=""){
      if($code == ""){
        $this->truncate();
        return;
      }
      if(!$this->confirm("\n Are you sure to remove all data related to site : {$code}"))
        return ;
      
      $db = Yii::app()->db;
      $this->startIgnoreForeignKey();
      $configs = $this->config();
      
      foreach($configs["tables"] as $tableName => $cols){
        if($tableName !== DaConfig::TBL_CLINIC)
          $condition = "ID = :code";
        else
          $condition = "ART = :code";
        
        $sql = " DELETE from {$tableName} WHERE {$condition} ";
        
        $command = $db->createCommand($sql);
        DaTool::p("Removing site: {$code} from {$tableName}");

        $command->bindParam(":code", $code, PDO::PARAM_STR);
        
        try{
          $count = $command->execute();
          DaTool::p("{$count} records have been removed");
        }
        catch(Exception $ex){
          DaTool::p("Error: {$ex->getMessage()}");
        }
      }
      $this->endIgnoreForeignKey();
    }

    public function actionTruncate(){
      $this->truncate();
    }

    public function actionCreate($code){
      try{
        $this->getDbX($code);
        $siteconfig = $this->getSiteConfig($code);
        $import = new ImportSiteHistory();
        $import->siteconfig_id = $siteconfig->id;
        $import->status = ImportSiteHistory::START;
        $import->save();
        $this->import($code);
      }
      catch(Exception $ex){
        echo "\n  {$ex->getMessage()}" ;
      }
    }
    
    public function actionStart($code){
      $this->import($code);
    }
    
    public function truncate(){
      
      if(!$this->confirm("\n Are you sure to truncate all the data in the tables"))
        return ;
      
      DaTool::p("Preparing ");
      $configs = $this->config();
      
      $this->startIgnoreForeignKey();

      $db = Yii::app()->db;
      foreach($configs["tables"] as $tableName => $cols){
        DaTool::p("truncating {$tableName}");
        $sql = "truncate {$db->quoteTableName($tableName)} " ;
        $command = $db->createCommand($sql);
        try{ 
          $command->execute();  
          DaTool::p(" run {$sql}");
        }
        catch(Exception $ex){
          DaTool::p("Error : {$ex->getMessage()}");
        }
      }
      $this->endIgnoreForeignKey();
    }
        
    public function import($code){
      $this->startIgnoreForeignKey();
      $status = ImportSiteHistory::SUCCESS ;
      $reason = "";
      $db = Yii::app()->db;
      
      $transaction = $db->beginTransaction();
              
      try{
        $this->getDbX($code);
        $configs = $this->config();
        $this->startImporting();
        foreach($configs["tables"] as $table => $cols){
          $this->importTable($table, $cols);
        }
        $transaction->commit();
        $this->endImporting($status, $reason);
      }
      
      
      catch(DaInvalidStatusException  $ex){
        DaTool::pException($ex);
      }
      catch(DaInvalidSiteException $ex){
        DaTool::pException($ex);
      }
      catch(Exception $ex){
        $transaction->rollback();
        DaTool::p($ex->getMessage());        
        $status = ImportSiteHistory::FAILED ;
        $reason = DaTool::p($ex, true);
        $this->endImporting($status, $reason);
      }
      $this->endIgnoreForeignKey();
    }
    /**
     *
     * @param Exception $ex
     * @param boolean $return
     * @return string 
     */
    public function outputException($ex, $return = false){
      $str = "\n {$ex->getMessage()} at line {$ex->getLine()}}  ";
      if($return )
        return $str;
      DaTool::p($str) ;
    }
    
    /**
     *
     * @param string $table
     * @param array $cols 
     */
    private function importTable($table, $cols){

      $dbx = $this->getDbX($this->code);
      $db = Yii::app()->db ;
      
      $sql = " SELECT * FROM {$table}";
      $commandX = $dbx->createCommand($sql);
      $dataReader =  $commandX->query();

      $colName  = implode(",  ", $cols);
      $colParam = implode(", ",array_map("simbolizeCol",$cols));

      $sql = "INSERT INTO {$table} ($colName) VALUES ($colParam)" ;
      $command = $db->createCommand($sql);
     
      foreach($dataReader as $row){
        DaTool::p("\n Table : {$table}");
        DaTool::p("Source cols : ".count($row)." Destination cols: ".count($cols));
        $i =0;
        //use $cols instead of key of row so we can pre downcase with downcase each records 
        foreach($row as  &$value){
          $colSymbol = ":{$cols[$i]}";
          DaTool::p( ($i+1)." - bining  {$colSymbol} -> {$value}");
          $command->bindParam($colSymbol, $value, PDO::PARAM_STR );
          $i++;
        }
        
        if($table != DaConfig::TBL_CLINIC){
          DaTool::p( ($i+1)." - bining  :id  -> {$this->code}");
          $command->bindParam(":id", $this->code );
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
       throw new DaInvalidSiteException("Invalid site code : {$code}");
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
      if($siteConfig->lastImport()){
        if($siteConfig->lastImport()->status == ImportSiteHistory::PENDING )
          throw new DaInvalidStatusException("Site {$this->code} import in progress");
        else if( $siteConfig->lastImport()->status == ImportSiteHistory::START){
          $import = $siteConfig->lastImport();
          $import->status = ImportSiteHistory::PENDING;
          $import->save();
        }
        else if( $siteConfig->lastImport()->status == ImportSiteHistory::FAILED  || $siteConfig->lastImport()->status == ImportSiteHistory::SUCCESS ){
          throw new DaInvalidStatusException(" Site {$this->code} has run already with status ". $siteConfig->lastImport()->getStatusText() );
        }
      }
      else{ //this is not always the case. since
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
    public function endImporting($status, $reason=""){
      $siteConfig = $this->getSiteConfig($this->code);
      $import = $siteConfig->lastImport(false);
      $import->status = $status ;
      $import->duration = microtime(true)- $this->startTime;
      $import->reason = $reason;
      
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
  if(!function_exists("lowerCaseCol")){
    function lowerCaseCol($name){
      return strtolower($name);
    }
  }