<?php
 class DaImportRejectPatient {
   
    public static function addRejectPatient($table, $record, $code, $name, $message, $import_id ){
     
      $sql = DaSqlHelper::sqlFromTableCols("da_reject_patients", 
              array("tableName", "name", "record", "code", "message", "import_site_history_id", "created_at", "modified_at"),false);
     
      $command = Yii::app()->db->createCommand($sql);
      
      $now = date("Y-m-d H:i:s");
      $record = serialize($record);
      
      $command->bindParam( "tableName" , $table , PDO::PARAM_STR);
      $command->bindParam( "name" , $name , PDO::PARAM_STR);
      $command->bindParam( "record" , $record , PDO::PARAM_STR);
      $command->bindParam( "code" , $code , PDO::PARAM_STR);
      $command->bindParam( "message" , $message, PDO::PARAM_STR);
      $command->bindParam( "import_site_history_id" , $import_id , PDO::PARAM_STR  );
      $command->bindParam( "created_at" , $now , PDO::PARAM_STR);
      $command->bindParam( "modified_at" , $now , PDO::PARAM_STR);
      $command->execute();
    }
 }