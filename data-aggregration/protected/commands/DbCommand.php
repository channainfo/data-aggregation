<?php
  //yiic <command-name> <parameters>
  class DbCommand extends CConsoleCommand{
    
     public function actionSeed(){
    
      $admin_group_id = 1;
      
      $groups = array(
          array("name" => "Administrator", "description" => "Administrator of system" , "id" => $admin_group_id ),
          array("name" => "Viewer", "description" => "TODO: clarify later", "id" => 2)
      );
      
      $users = array(
          array("name" => "Administrator", "login" => "admin", "password" => "123456" , 
              "password_repeat" => "123456","active" => 1, "group_id" => $admin_group_id ) 
      );
      
      foreach($groups as $group){
         $model = new Group();
         $model->setAttributes($group);
         $model->id = $group["id"];
         try{
            if($model->save())
              DaTool::p("'{$model->name}' has been created");
            else
              DaTool::p("'{$model->name}' could not be created");
         }
         catch (Exception $ex){
            DaTool::p("\n ".$ex->getMessage());
         }   
      }
      foreach($users as $user) {
        $model = new User();
        $model->setAttributes($user);
        try{
          if($model->save())
            DaTool::p(" '{$model->name}' has been created");
          else
            DaTool::p(" '{$model->name}' could not be created");
        }
        catch(Exception $ex){
          DaTool::p($ex->getMessage());
        }
      }
    }
    
    public function actionCleanSeed(){
      $count = User::model()->deleteAll();
      DaTool::p(" Remove '$count' users " );
      $count = Group::model()->deleteAll();
      DaTool::p(" Remove '$count' groups ");
    }
    
    public function actionImportConfig(){
      $file = dirname(__FILE__)."/../config/importConfig.php" ;
      $sql = " SELECT * FROM ". DaConfig::IMPORT_TABLE_NAME." ORDER by priority DESC";
      $db = Yii::app()->db;
      
      $command = $db->createCommand($sql);
      $dataReader = $command->query();
      $tables =  array();
      foreach($dataReader as $row) {
        $cols = unserialize($row["cols"]);
        $table = " '{$row["table_name"]}' => array( {$this->concatCols($cols)} ) ";
        $tables[] = $table ;
      }
      
      $sql = "SELECT * FROM da_drug_controls " ;
      $command = $db->createCommand($sql);
      $dataReader = $command->query();
      
      $drugConrols = array();
      foreach($dataReader as $row)
        $drugConrols[] = "'{$row["name"]}'";
      
      
      
      $tableStr = implode("\n\t\t," , $tables );
      $drugControlStr = implode("\n\t\t,", $drugConrols);
      
      $content = <<<EOT
  <?php    
  return array(
    "tables" => array($tableStr
    ),
    "drugControls" => array($drugControlStr
    )  
);      
EOT;
      DaTool::p($content) ;
      file_put_contents($file, $content);
    }
    
    public function actionGTableNames(){
      $tables = $this->tableList();

      $connection = Yii::app()->db;

      $sql="INSERT INTO ".DaConfig::IMPORT_ESC_TABLE_NAME." (table_name, created_at, modified_at) VALUES(:name, NOW(),NOW())";
      $command=$connection->createCommand();
      
      $command->truncateTable(DaConfig::IMPORT_TABLE_NAME);
      $command->truncateTable(DaConfig::IMPORT_ESC_TABLE_NAME);
      
      if(count($tables["constant"])){
        $command=$connection->createCommand($sql);
        foreach($tables["constant"] as $table){
          $command->bindParam(":name",$table,PDO::PARAM_STR);
          try{
            $command->execute();
            DaTool::p("Table : {$table} has been inserted ");
            DaTool::p($command->getText());
          }
          catch(Exception $ex){
            DaTool::p($ex->getMessage());
          }
        }
      }
      if(count($tables["server"])){
        $sql="INSERT INTO ". DaConfig::IMPORT_TABLE_NAME ." (table_name, cols, created_at, modified_at) VALUES(:name,:cols, NOW(),NOW())";
        $command=$connection->createCommand($sql);
        foreach($tables["server"] as $table => $cols){
          $cols_str = serialize($cols);
          $command->bindParam(":name",$table,PDO::PARAM_STR);
          $command->bindParam(":cols", $cols_str,PDO::PARAM_STR);
          
          try{
            $command->execute();
            DaTool::p("Table : {$table} has been inserted ");
            DaTool::p($command->getText());
          }
          catch(Exception $ex){
            DaTool::p($ex->getMessage());
          }
        }
      }
    }  
    
    public function actionPriorityImportTables($table,$priority){
      
      $tables = array();
      $priorities = array();
      
      if(preg_match("/^\[(.+)\]/i", $priority, $matches))
        $priorities = explode(",",$matches[1]);
      else{
        if( !is_int($priority) || intval($priority) < 0 ){
          DaTool::p("Priority must be greater than or equal to cero or in format of [number, number]");
          return ;
        }
        $priorities [] = $priority;
      }
      
      if(preg_match("/^\[(.+)\]/i", $table, $matches))
        $tables = explode(",",$matches[1]);
      else
        $tables [] =$table;
      
      $diff = count($tables)-count($priorities);
      if($diff >0){
        $last = count($priorities)-1;
        $last_priority = $priorities[$last];
        for($i=0; $i<$diff ; $i++){
          $priorities[] = $last_priority ;
        }
      }
      $this->prioritizeTable($tables, $priorities);
      
    }
    
    public function actionOrderTable(){
      $tables = array("tblclinic", "tblaimain" , "tblcimain" , "tblpatienttest" , "tblcvmain" , "tblavmain" ) ;
      $priorities = array(100,50,50,30,20,20);
      $this->prioritizeTable($tables, $priorities);
    }
    
    private function concatCols($cols){
      
      $fields = array();
      foreach($cols as $col){
        $field = strtolower($col["Field"]);
        $fields[] = "'{$field}'";
      }
      return implode(",", $fields);
    }
    
    public function prioritizeTable($tables, $priorities){
      $connection = Yii::app()->db;
      $sql = "UPDATE ".DaConfig::IMPORT_TABLE_NAME ." SET 	priority = :priority WHERE table_name = :table" ;
      $command = $connection->createCommand($sql) ;
      
      for($i=0; $n = count($tables), $i<$n; $i++){
        $command->bindParam(":priority", $priorities[$i], PDO::PARAM_INT );
        $command->bindParam(":table", $tables[$i], PDO::PARAM_STR );
        try{
          if($command->execute())
            DaTool::p(" Table: {$tables[$i]} has been set to priority: {$priorities[$i]} ") ;
          else
            DaTool::p(" Table: {$tables[$i]} with priority: {$priorities[$i]} is not modified ") ;
        }
        catch(CException $ex){
          DaTool::p($ex->getMessage()) ;
        }
        DaTool::p($command->getText()); 
      }
    }
    
    private function tableList(){
      $connection = Yii::app()->db ;
      $tables = array("server" => array(), "constant" => array());
      
      $sql = "SHOW TABLES LIKE 'tbl%'";
      $command=$connection->createCommand($sql);
      $dataReader = $command->query();

      $tables["server"][DaConfig::TBL_CLINIC] = $this->getColumnsFromTable(DaConfig::TBL_CLINIC);
      
      foreach($dataReader as $row){
        $table = current($row) ;
        if(strpos($table, "tbl_") === false){
          if(!$this->hasIdColumn($table))
             $tables["constant"][] = $table ;
          else
            $tables["server"][$table] = $this->getColumnsFromTable($table);
        }
      }
      return $tables ;
    }
    
    private function getColumnsFromTable($table){
      $connection = Yii::app()->db;
      $sql = "SHOW COLUMNS FROM {$table} ";
      $colCommand = $connection->createCommand($sql);
      $columns = $colCommand->queryAll();
      return $columns ;
    }
    
    private function hasIdColumn($table){
      $sql = "SHOW COLUMNS FROM {$table} ";
      $connection = Yii::app()->db ;
      $command = $connection->createCommand($sql);
      $dataReader = $command->query();
      foreach($dataReader as $row) {
        if(strtolower($row["Field"]) == "id"){
          return true;
        }
      }
      return false;
    }
  }