<?php
class m120223_032951_add_columns_to_tbl_tables extends CDbMigration{
  
  public $_da_esc = "da_escs" ;
  public $_da_table = "da_tables";
  
  public function up(){
    $this->createTable($this->_da_esc, array(
        "id" => "pk",
        "table_name" =>   "string",
        "created_at" =>   "datetime",
        "modified_at" =>  "datetime"
    ));
    
    $this->createTable($this->_da_table, array(
        "id" => "pk",
        "table_name" =>   "string",
        "created_at" =>   "datetime",
        "modified_at" =>  "datetime"
    ));
    
    $tables = $this->tableList();
    
    $connection = Yii::app()->db;
    
    if(count($tables["constant"])){
      $sql="INSERT INTO {$this->_da_esc} (table_name, created_at, modified_at) VALUES(:name, NOW(),NOW())";
      $command=$connection->createCommand($sql);
      foreach($tables["constant"] as $table){
        $command->bindParam(":name",$table,PDO::PARAM_STR);
        $command->execute();
      }
    }
    if(count($tables["server"])){
      $sql="INSERT INTO {$this->_da_table} (table_name, created_at, modified_at) VALUES(:name, NOW(),NOW())";
      $command=$connection->createCommand($sql);
      foreach($tables["server"] as $table){
        $command->bindParam(":name",$table,PDO::PARAM_STR);
        $command->execute();
      }
    }
    
  }
  
  
  public function down(){
     $this->dropTable($this->_da_esc);
     $this->dropTable($this->_da_table);
     
  }
  
  
  public function tableList(){
      $connection = Yii::app()->db ;
      $tables = array("server" => array(), "constant" => array());
      
      $sql = "SHOW TABLES LIKE 'tbl%'";
      $command=$connection->createCommand($sql);
      $dataReader = $command->query();

      foreach($dataReader as $row){
        $table = current($row) ;
        if(strpos($table, "tbl_") === false){
          if(!$this->hasIdColumn($table)){
             $tables["constant"][] = $table ;
          }
          else{
            $tables["server"][] = $table;
          }
        }
      }
      return $tables ;
    }
    
    public function hasIdColumn($table){
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