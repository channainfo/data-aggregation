<?php
 class DaDbHelper {
   public static function startIgnoringForeignKey($db){
     $command = $db->createCommand("SET FOREIGN_KEY_CHECKS = 0;");
     $command->execute();
   }
   
   public static function endIgnoringForeignKey($db){
     $command = $this->db->createCommand("SET FOREIGN_KEY_CHECKS = 1;");
     $command->execute();
   }
 }