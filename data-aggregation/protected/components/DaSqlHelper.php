<?php
 class DaSqlHelper {
   
   public static function insertTableColsWithIndexParam($table, $cols, $REPLACE=true){
     $insert = $REPLACE ? "REPLACE" : "INSERT" ;
     $colName  = implode(",", $cols);
     $param = str_repeat("?,", count($cols));
     $colParam = substr($param,0, strlen($param)-1);
     
     $sql = "{$insert} INTO {$table} ($colName) VALUES ($colParam)" ;
     return $sql ;
   }
   
   public static function insertTableWithIndexParam($table, $REPLACE=true){
     return self::insertTableColsWithIndexParam($table,  self::colsFromTable($table), $REPLACE);
   }

   public static function sqlFromTableCols($table, $cols, $REPLACE=true, $skips=array()){
     
     $insert = $REPLACE ? "REPLACE" : "INSERT" ;
     $cols = array_diff($cols, $skips); 
     $arrayHelper = new DaArrayHelper($cols);
     
     $colName  = $arrayHelper->lowerCase()->join(", ");
     $colParam = $arrayHelper->symbolize(true)->join(", ");
     $sql = "{$insert} INTO {$table} ($colName) VALUES ($colParam)" ;
     return $sql ;
   }
  
   public static function colsFromTable($table){
      $configs = DaConfig::importConfig();
      $cols = null;
      if(isset($configs["tables"][$table]))
        $cols = $configs["tables"][$table];
      elseif(isset($configs["fixed"][$table]))
        $cols = $configs["fixed"][$table];
      else
        throw new Exception("Invalid table name {$table}");
      return $cols;
   }


   public static function sqlFromTable($table, $REPLACE=true, $skips = array()){
      return self::sqlFromTableCols($table, self::colsFromTable($table), $REPLACE, $skips);
   }
   
   public static function  addRecord($records, $table, $sitecode=false ){
     $sql = DaSqlHelper::insertTableWithIndexParam($table );
     $command = Yii::app()->db->createCommand($sql);
     $i = 1;
     
     foreach($records  as  &$value){
       $command->bindParam($i++, $value, PDO::PARAM_STR ); //use $cols index instead of key of row so we can pre downcase with downcase each records 
     }
     
     if($sitecode)
       $command->bindParam($i, $sitecode, PDO::PARAM_STR );
     
     $command->execute();
   }
 }