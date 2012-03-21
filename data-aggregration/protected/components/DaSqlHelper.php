<?php
 class DaSqlHelper {
   public static function sqlFromTableCols($table, $cols){
     $colName  = implode(",  ", $cols);
     $colParam = implode(", ", array_map("simbolizeCol",$cols));
     $sql = "REPLACE INTO {$table} ($colName) VALUES ($colParam)" ;
     return $sql ;
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
