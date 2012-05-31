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
     
     /***************Prevent provide less data than it column******************/
     $left = self::numberLeftColumn($records, $table, $sitecode);
     for($t=0; $t<$left ; $t++){
       $empty = "" ;
       $command->bindParam($i++, $empty, PDO::PARAM_STR );
     }
     /*************************************************************************/
     
     if($sitecode)
       $command->bindParam($i, $sitecode, PDO::PARAM_STR );
     
     $command->execute();
   }
   
   public static function numberLeftColumn($record, $table, $sitecode){
     $cols = self::colsFromTable($table);
     
     $ncol = count($cols);
     $nfield = count($record);
     
     if($sitecode)
       $left = $ncol - $nfield - 1 ;
     else
       $left = $ncol - $nfield ;
     
     return $left ;
   }
   /**
    * Check to see if the table exist in the CDbConnection database
    * 
    * @param string $tableName
    * @param CDbConnection $dbX
    * @return boolean return true if table exist otherwise false
    */
   public static function isTableExistInMssql($tableName, $dbX){
      $sql = "select Top 1 * from $tableName " ;
      $command = $dbX->createCommand($sql);
      try{
        $command->query();
        return true ;
      }
      catch(Exception $ex){
        return false ;
      }
   }
   
 }