<?php
  class MigrateSchmaTest extends CDbTestCase{
    public function testTableWithId(){
      $this->tablesWithoutId();
    }
    
    public function tablesWithoutId(){
      $connection = Yii::app()->db ;
      $tables = array();
      
      $sql = "SHOW TABLES LIKE 'tbl%'";
      $command=$connection->createCommand($sql);
      $dataReader = $command->query();

      foreach($dataReader as $row){
        $table = current($row) ;
        if(strpos($table, "tbl_") === false){
          if(!$this->hasIdColumn($table)){
             $tables[] = $table ;
          }
        }
      }
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