<?php
  class ImportCommand extends CConsoleCommand {
    public $code = false;
    public $dbX = false;
    public $siteconfig = false ;
    
    public function beforeAction($action, $params) {
      echo "\n Before run" ;
      
      if(function_exists("pcntl_signal")){
        declare (ticks=1);
        pcntl_signal(SIGTERM, array(&$this, "interruption"));
        pcntl_signal(SIGINT, array(&$this, "interruption"));
      }
      return parent::beforeAction($action, $params);
    }
    
    private function interruption($signal){
      echo "\n Interrupting with {$signal}...";
    }


    public function actionIndex($code){
      try{
        $dbX = $this->getDbX($code);
      }
      catch(Exception $ex){
        echo "\n  {$ex->getMessage()}" ;
      }
      echo "\n Preparing ..." ;
      $tStart = microtime(true);
      $configs = require_once dirname(__FILE__)."/../config/importConfig.php";
     
      try{
        foreach($configs["tables"] as $table => $cols){
          $this->import($table, $cols);
        }
      }
      catch(CException $ex){
        echo "\n Failed : code: {$ex->getCode()} message: {$ex->getMessage()} ";
      }
      $tEnd = microtime(true);
      echo "\n Duration : ".($tEnd-$tStart);
    }
    /**
     *
     * @param string $table
     * @param array $cols 
     */
    private function import($table, $cols){
      echo "\n Start Table: {$table} ";
      $tStart = microtime(true);
      
      
      $dbx = $this->getDbX($this->code);
      $db = Yii::app()->db ;
      
      $sql = " SELECT * FROM {$table}";
      $commandX = $dbx->createCommand($sql);
      $dataReader =  $commandX->query();

      if($table != DaConfig::TBL_CLINIC){
        $cols[] = "ID" ; 
      } 
      
      
      $colName  = implode(",", $cols);
      $colParam = implode(",",array_map("simbolizeCol",$cols));

      $sql = "INSERT INTO {$table} ($colName) VALUES($colParam)" ;
      $command = $db->createCommand($sql);
      echo "\n {$sql}";
      $recordNumber = 1;
      foreach($dataReader as $row){
        echo "\n Running record {$recordNumber}";
        $recordNumber ++; 

        $i=0;
        foreach($row as $field){
          //echo "\n :{$cols[$i]} -> {$field}" ;
          $command->bindParam(":{$cols[$i]}", $field, PDO::PARAM_STR);
          $i++;
        }
        if($table !=DaConfig::TBL_CLINIC){
          $command->bindParam(":ID", $this->code);
        }
        
        try{
          $command->execute();
          echo "\n\t Record added  ";
        }
        catch(CException  $ex){
          echo "\n\t Not inserted record duplicate " ;
        }
      }
      $tEnd = microtime(true);
      echo "\n Finish: {$table} with : ".($tEnd-$tStart);
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
      
      $siteconfig = SiteConfig::model()->findByAttributes(array("code" => $code));
      
      if($siteconfig){
        $dsn = "sqlsrv:Server={$siteconfig->attributes["host"]};Database={$siteconfig->attributes["db"]}";
        $username = $siteconfig->attributes["user"];
        $password = $siteconfig->attributes["password"];
        $dbEx=new CDbConnection($dsn,$username,$password);
        if($dbEx)
          $dbEx->active=true;
        else
          throw  new Exception("Could not connect to  : {$siteconfig->attributes["host"]}");
      }
      else
        throw  new Exception("Invalid site code : {$code}");
      
      $this->siteconfig = $siteconfig ;  
      $this->code = $code;
      $this->dbX = $dbEx;
      return $this->dbX;
    }
  }

  
  if(!function_exists("simbolizeCol")){
    function simbolizeCol($ex){
      return ":{$ex}";
    }
  }