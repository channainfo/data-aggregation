<?php
 class DaConfig{
   const PAGE_SIZE = 15 ;
   const IMPORT_TABLE_NAME = "da_import_tables";
   const IMPORT_ESC_TABLE_NAME = "da_import_escs";
   const TBL_CLINIC = "tblclinic" ;
   
   const IMPORT_TABLE_TYPE_FIXED = "fixed";
   const IMPORT_TABLE_TYPE_IMPORT = "import" ;
   private static $importConfig = false;
   
   public static function pathDataStore(){
     return dirname(__FILE__)."/../../data/";
   }
   
   public static function importConfig(){
      if(self::$importConfig)
        return self::$importConfig ;
      
      $configFile = dirname(__FILE__)."/../config/importConfig.php" ;
      if(file_exists($configFile))
        self::$importConfig = require_once($configFile) ;
      else
        throw new DaInvalidFileException(" Could not find include file : {$configFile}");
      return self::$importConfig ;  
   }
   
   
   public static function pathDataDb(){
     return "data/";
   }
     
   public static function generateFile($filename){
     return  time()."-".rand(0,10000)."-".$filename;
   }
   
   public static function webRoot(){
     return dirname(__FILE__)."/../../";
   }  
 }
?>
